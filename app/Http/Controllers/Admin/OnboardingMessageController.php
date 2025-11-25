<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CountryState;
use App\Models\Category;
use App\Models\Service;
use App\Helpers\TwilioHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OnboardingMessageController extends Controller
{
    protected $twilioHelper;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->twilioHelper = new TwilioHelper();
    }

    public function index(Request $request)
    {
        // Get filter options
        $states = CountryState::where('status', 1)->orderBy('name', 'asc')->get();
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        $services = Service::where(['status' => 1, 'approve_by_admin' => 1])->orderBy('name', 'asc')->get();

        // Build query for pending users
        $query = User::where('onboarding_message_sent', false)
            ->where('status', 1)
            ->whereNotNull('phone')
            ->select('id', 'name', 'phone', 'email', 'onboarding_message_sent', 'state_id', 'is_provider');

        // Apply filters
        if ($request->has('state') && $request->state != '') {
            $query->where('state_id', $request->state);
        }

        if ($request->has('user_type') && $request->user_type != '') {
            if ($request->user_type == 'provider') {
                $query->where('is_provider', 1);
            } elseif ($request->user_type == 'client') {
                $query->where('is_provider', 0);
            }
        }

        // Filter by category - only for providers
        if ($request->has('category') && $request->category != '') {
            $categoryId = $request->category;
            $query->where('is_provider', 1)
                ->whereIn('id', function($q) use ($categoryId) {
                    $q->select('provider_id')
                      ->from('services')
                      ->where('category_id', $categoryId)
                      ->where('status', 1);
                });
        }

        // Filter by service - only for providers
        if ($request->has('service') && $request->service != '') {
            $serviceId = $request->service;
            $query->where('is_provider', 1)
                ->whereIn('id', function($q) use ($serviceId) {
                    $q->select('provider_id')
                      ->from('services')
                      ->where('id', $serviceId)
                      ->where('status', 1);
                });
        }

        $pendingUsers = $query->get();

        return view('admin.onboarding_messages', compact('pendingUsers', 'states', 'categories', 'services'));
    }

    public function sendToSelected(Request $request)
    {
        $rules = [
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|exists:users,id',
            'message_template' => 'required',
        ];
        
        $customMessages = [
            'user_ids.required' => trans('admin_validation.At least one user is required'),
            'message_template.required' => trans('admin_validation.Message template is required'),
        ];
        
        $this->validate($request, $rules, $customMessages);

        $successCount = 0;
        $failedCount = 0;
        $results = [];

        foreach ($request->user_ids as $userId) {
            $user = User::find($userId);

            if (!$user || !$user->phone) {
                $failedCount++;
                $results[] = [
                    'user' => $user ? $user->name : 'Unknown',
                    'error' => 'User not found or phone missing'
                ];
                continue;
            }

            // Generate random password
            $password = $this->generatePassword();
            
            // Prepare personalized message
            $message = $this->prepareMessage($request->message_template, $user, $password);
            
            // Format phone number
            $phone = $this->formatPhoneForTwilio($user->phone);

            // Send SMS via Twilio
            $result = $this->twilioHelper->sendMessage($phone, $message);
            
            if ($result['success']) {
                // Update user password and onboarding status
                $user->password = Hash::make($password);
                $user->onboarding_message_sent = true;
                $user->save();
                
                $successCount++;
            } else {
                $failedCount++;
                $results[] = [
                    'user' => $user->name,
                    'phone' => $phone,
                    'error' => $result['message']
                ];
            }
        }

        $notification = "Messages sent successfully to {$successCount} users. Failed: {$failedCount}";
        $notification = array(
            'messege' => $notification, 
            'alert-type' => $failedCount > 0 ? 'warning' : 'success',
            'results' => $results
        );
        
        return redirect()->back()->with($notification);
    }

    public function testConnection()
    {

        try {
            $testNumber = env('TWILIO_TEST_NUMBER');
    
            if (!$testNumber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Test number not configured'
                ]);
            }
    
            // Format the number correctly for Twilio
            $testNumber = $this->formatPhoneForTwilio($testNumber);
    
            // Send test message directly
            $result = $this->twilioHelper->sendMessage(
                $testNumber,
                'Test message from onboarding system'
            );
    
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function generatePassword($length = 8)
    {
        // Generate a secure random password
        $password = Str::random($length);
        
        // Ensure it contains at least one number and one uppercase letter
        $password = substr($password, 0, $length - 2) . rand(0, 9) . strtoupper(Str::random(1));
        
        return $password;
    }

    private function formatPhoneForTwilio($phone) {
        $phone = preg_replace('/\s+/', '', $phone);
        if (Str::startsWith($phone, '0')) {
            return '+234' . substr($phone, 1);
        }
        if (!Str::startsWith($phone, '+')) {
            return '+234' . $phone;
        }
        return $phone;
    }

    private function prepareMessage($template, $user, $password)
    {
        $message = str_replace('@{{name}}', $user->name, $template);
        $message = str_replace('@{{email}}', $user->email, $message);
        $message = str_replace('@{{phone}}', $user->phone, $message);
        $message = str_replace('@{{password}}', $password, $message);
        
        return $message;
    }
}
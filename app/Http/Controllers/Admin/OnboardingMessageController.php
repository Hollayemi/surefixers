<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
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

    public function index()
    {
        $pendingUsers = User::where('onboarding_message_sent', false)
            ->where('status', 1)
            ->select('id', 'name', 'phone', 'email', 'onboarding_message_sent')
            ->get();

        return view('admin.onboarding_messages', compact('pendingUsers'));
    }

    public function sendToAll(Request $request)
    {
        $rules = [
            'message_template' => 'required',
        ];
        
        $customMessages = [
            'message_template.required' => trans('admin_validation.Message template is required'),
        ];
        
        $this->validate($request, $rules, $customMessages);

        $users = User::where('onboarding_message_sent', false)
            ->where('status', 1)
            ->whereNotNull('phone')
            ->get();

        if ($users->isEmpty()) {
            $notification = trans('admin_validation.No users found for onboarding');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        $successCount = 0;
        $failedCount = 0;
        $results = [];

        foreach ($users as $user) {
            // Generate random password
            $password = $this->generatePassword();
            
            // Prepare personalized message
            $message = $this->prepareMessage($request->message_template, $user, $password);
            
            // Send SMS via Twilio
            $result = $this->twilioHelper->sendMessage($user->phone, $message);
            
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
                    'phone' => $user->phone,
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

    public function sendToSelected(Request $request)
    {
        $rules = [
            'phone_numbers' => 'required|array',
            'phone_numbers.*' => 'required|string',
            'message_template' => 'required',
        ];
        
        $customMessages = [
            'phone_numbers.required' => trans('admin_validation.At least one phone number is required'),
            'message_template.required' => trans('admin_validation.Message template is required'),
        ];
        
        $this->validate($request, $rules, $customMessages);

        $phoneNumbers = array_filter($request->phone_numbers);
        
        if (empty($phoneNumbers)) {
            $notification = trans('admin_validation.No valid phone numbers provided');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        $successCount = 0;
        $failedCount = 0;
        $results = [];

        foreach ($phoneNumbers as $phone) {
            // Find user by phone
            $user = User::where('phone', $phone)
                ->where('status', 1)
                ->first();

            if (!$user) {
                $failedCount++;
                $results[] = [
                    'phone' => $phone,
                    'error' => 'User not found'
                ];
                continue;
            }

            // Generate random password
            $password = $this->generatePassword();
            
            // Prepare personalized message
            $message = $this->prepareMessage($request->message_template, $user, $password);
            
            if (Str::startsWith($phone, '0')) {
                $phone = '+234' . substr($phone, 1);
            }

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

    public function getUsersByPhone(Request $request)
    {
        $phone = $request->phone;
        
        $user = User::where('phone', $phone)
            ->where('status', 1)
            ->select('id', 'name', 'phone', 'email', 'onboarding_message_sent')
            ->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ]);
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
        $phone = preg_replace('/\s+/', '', $phone); // remove spaces
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
        $message = str_replace('{{name}}', $user->name, $template);
        $message = str_replace('{{email}}', $user->email, $message);
        $message = str_replace('{{phone}}', $user->phone, $message);
        $message = str_replace('{{password}}', $password, $message);
        
        return $message;
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
            if (Str::startsWith($testNumber, '0')) {
                $testNumber = '+234' . substr($testNumber, 1);
            }
    
            // Send test message directly
            $result = $this->twilioHelper->sendMessage(
                $testNumber,
                'Let me know if you get this message sir'
            );
    
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
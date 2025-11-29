<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Rules\Captcha;
use Auth;
use App\Mail\UserRegistration;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\Setting;
use App\Models\BreadcrumbImage;
use App\Models\GoogleRecaptcha;
use App\Models\SocialLoginInformation;
use Mail;
use Str;
use Session;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest:web');
    }

    public function loginPage(){
        $breadcrumb = BreadcrumbImage::where(['id' => 11])->first();
        $recaptchaSetting = GoogleRecaptcha::first();
        $socialLogin = SocialLoginInformation::first();

        $setting = Setting::first();
        $login_page = array(
            'image' => $setting->login_image
        );
        $login_page = (object) $login_page;

        $selected_theme = Session::get('selected_theme');
        if ($selected_theme == 'theme_one'){
            $active_theme = 'layout';
        }elseif($selected_theme == 'theme_two'){
            $active_theme = 'layout2';
        }elseif($selected_theme == 'theme_three'){
            $active_theme = 'layout3';
        }else{
            $active_theme = 'layout';
        }

        return view('register')->with([
            'active_theme' => $active_theme,
            'breadcrumb' => $breadcrumb,
            'recaptchaSetting' => $recaptchaSetting,
            'socialLogin' => $socialLogin,
            'login_page' => $login_page,
        ]);
    }

    public function storeRegister(Request $request){
        $rules = [
            'name' => 'required',
            'phone' => 'required|unique:users',
            // 'is_provider' => 'required|in:0,1',
            'password' => 'required|min:4',
            'g-recaptcha-response' => new Captcha()
        ];
        
        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'phone.required' => trans('user_validation.Phone is required'),
            'phone.unique' => trans('user_validation.Phone already exist'),
            'is_provider.required' => trans('user_validation.Please select if you are a service provider or not'),
            'is_provider.in' => trans('user_validation.Invalid selection'),
            'password.required' => trans('user_validation.Password is required'),
            'password.min' => trans('user_validation.Password must be 4 characters'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->is_provider = $request->is_provider;
        $user->password = Hash::make($request->password);
        $user->verify_token = Str::random(100);
        $user->status = 1;
        $user->email_verified = 1;
        $user->save();

        $notification = trans('user_validation.Register Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        
        // Auto-login the user after registration
        Auth::guard('web')->attempt(['phone' => $request->phone, 'password' => $request->password]);
        
        return redirect()->route('dashboard')->with($notification);
    }

    // Remove userVerification method since we're not using email verification

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
            'is_provider' => ['required', 'in:0,1'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'is_provider' => $data['is_provider'],
            'password' => Hash::make($data['password']),
            'status' => 1,
            'email_verified' => 1,
        ]);
    }
}
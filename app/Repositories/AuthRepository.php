<?php

namespace App\Repositories;

use App\CPU\SmsHelper;
use App\Jobs\RegisterNotification;
use App\Models\Cart;
use App\Models\User;
use App\Models\UserPhone;
use App\Models\UserWallet;
use App\Jobs\SendLoginEmail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interface\AuthRepositoryInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\BatchEmail;
use App\Models\Admin;
use App\Models\CartDetail;

class AuthRepository implements AuthRepositoryInterface
{
    public function login($request, $guard)
    {
        $credentials = $guard!=='admin' ? $request->only('username', 'password') : $request->only('email', 'password');

        if ($guard == 'customer') {
            $credentials = $request->only('username', 'password');

            // Find user by username (email or phone number)
            $user = User::select('id', 'email', 'status')
            ->where('is_deleted', 0)
            ->where(function ($query) use ($credentials) {
                $query->where('email', $credentials['username'])
                    ->orWhereHas('phones', function ($q) use ($credentials) {
                        $q->where('phone_number', $credentials['username']);
                    });
            })->first();

            if ($user && $user->status) {

                if (filter_var($credentials['username'], FILTER_VALIDATE_EMAIL)) {
                    $loginCredentials = [
                        'email' => $credentials['username'],
                        'password' => $credentials['password']
                    ];
                } else {
                    $loginCredentials = [
                        'email' => $user->email,
                        'password' => $credentials['password']
                    ];
                }

                if (Auth::guard($guard)->attempt($loginCredentials)) {

                    $this->setCustomerCart();

                    // Dispatch the email job
                    SendLoginEmail::dispatch($user)->onQueue('medium');

                    return $guard;
                }

                return 0;
            }

            return ['status' => false, 'message' => 'Your account is not active or you deleted your account.'];
        } else {
            if (Auth::guard($guard)->attempt($credentials)) {
                return $guard;
            }

            return 0;
        }
    }

    public function setCustomerCart()
    {
        if (Auth::guard('customer')->check() && Session::has('cart_id') ) {
            $cartItem = Cart::find(Session::get('cart_id'));
            if ($cartItem) {
                $cartItem->user_id = Auth::guard('customer')->user()->id;
                $cartItem->save();
            }
        }
    }

    public function postForgotPassword($request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:email,phone',
            'email' => 'required_if:type,email|nullable|email',
            'phone' => 'required_if:type,phone|nullable|digits_between:10,15',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()]);
        }

        $mailStatus = false;
        if($request->type == 'email') {
            $user = User::where('email', $request->email)->first();
            if(!$user) {
                return response()->json(['status' => false, 'message' => 'Invalid Email Address.']);
            }

            $user->code = rand(100000, 999999);
            $user->save();

            $subject = get_settings('forget_password_subject');
            $message = get_settings('forget_password_template');
            $message = str_replace('[-CODE-]', $user->code, $message);

            if(Mail::to($user->email)->send(new BatchEmail($user, $subject, $message)))  {

                Session::put('password_reset_user_email', $user->email);
                Session::put('password_reset_code', $user->code);

                return ['status' => true, 'message' => 'We sent an email to your mailbox. Please check your email.', 'goto' => route('otp.form')];

            } else {
                return ['status' => false, 'message' => 'Something went wrong. Try again.'];
            }
        }
    }

    public function validateOtp($request)
    {
        if(!Session::has('password_reset_user_email') || !Session::has('password_reset_code')) {
            return redirect()->route('login');
        }

        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()]);
        }

        if($request->otp != Session::get('password_reset_code')) {
            return response()->json(['status' => false, 'message' => 'Invalid OTP.']);
        }

        $user = User::where('code', $request->otp)->first();
        if(!$user) {
            return response()->json(['status' => false, 'message' => 'Invalid OTP.']);
        }

        if($user->email != Session::get('password_reset_user_email')) {
            return response()->json(['status' => false, 'message' => 'Invalid OTP.']);
        }

        Session::forget('password_reset_user_email');
        Session::forget('password_reset_code');
        Session::put('password_reset_enabled', $user->id);

        return response()->json([
            'status' => true,
            'message' => 'OTP is validated. Please enter your password.',
            'goto' => route('password.reset.form')
        ]);
    }

    public function resetPassword($request)
    {
        if(!Session::has('password_reset_enabled')) {
            return redirect()->route('login');
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return ['status' => false, 'message' => $validator->errors()];
        }

        $user = User::find(Session::get('password_reset_enabled'));
        if (!$user) {
            return ['status' => false, 'message' => 'User not found'];
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return ['status' => true, 'message' => 'Password reset successfully.', 'goto' => route('login')];
    }

    public function registerUser($request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:email,phone',
            'email' => 'required_if:type,email|nullable|email',
            'phone' => 'required_if:type,phone|nullable|digits_between:10,15',
            'password' => 'required_if:type,email|string|min:8',
        ]);

        if ($validator->fails()) {
            return ['status' => false, 'alert' => 'Required Fields Missing', 'message' => $validator->errors()];
        }

        $registerMethod = 'email';
        if ($request->type == 'email') {
            $request->merge(['username' => $request->email]);
            if(User::where('email', $request->email)->exists()) {
                return ['status' => false, 'message' => ['An account with this email already exists. Reset your password if you have forgotten it.']];
            }
        }

        if($request->type == 'phone') {
            $registerMethod = 'phone';
            $request->merge(['username' => $request->phone]);
            if(UserPhone::where('phone_number', $request->phone)->exists()) {
                return ['status' => false, 'alert' => 'Duplicate Phone Number', 'message' => ['The phone number you provided is already in use. Please try with a different number']];
            }
        }

        // User Creation
        DB::beginTransaction();

        $customer = User::create([
            'currency_id' => Session::has('currency_id') ? Session::get('currency_id') : 1,
            'name' => $request->customer_name,
            'email' => $registerMethod == 'email' ? $request->email : '%_'.date('YdHis').'@mail.com',
            'password' => $registerMethod == 'email' ? Hash::make($request->password) : Hash::make($request->phone),
            'avatar' => 'pictures/user'.rand(1,10000).'.png',
            'status' => 1
        ]);

        if ($customer) {

            if($registerMethod == 'phone') {

                $code = rand(100000, 999999);

                $template = get_settings('sms_payment_status_change_template');
                $template = str_replace('[[SYSTEM_NAME]]', get_settings('system_name'), $template);
                $template = str_replace('[[OTP]]', $code, $template);
                $template = str_replace('[[TIME]]', 5, $template);

                $sms = new SmsHelper();   
                $result = $sms->sendSms($request->phone, $template);   
                if($result) {
                    UserPHone::create([
                        'user_id' => $customer->id,
                        'phone_number' => $request->phone,
                        'is_default' => 1,
                        'is_verified' => 0,
                        'code' => $code
                    ]);
                }
            }

            UserWallet::create([
                'user_id' => $customer->id,
                'amount' => 0,
                'status' => 'active',
            ]);

            // Dispatch the email job
            RegisterNotification::dispatch($customer)->onQueue('high');

            DB::commit();
            Cache::forget('userdata_daily');
            Cache::forget('userdata_weekly');
            Cache::forget('userdata_monthly');
            Cache::forget('userdata_yearly');
            Cache::forget('chart_user_count');
        } else {
            DB::rollBack();
        }

        return $customer;
    }

    public function social_login($socialUser, $provider)
    {
        $user = User::where('email', $socialUser->getEmail())->first();
        if (!$user) {
            $user = User::create([
                'currency_id' => 1,
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'provider_id' => $socialUser->getId(),
                'provider_name' => $provider,
                'avatar' => $socialUser->getAvatar(),
                'password' => Hash::make(123456),
                'email_verified_at' => now()
            ]);

            UserWallet::create([
                'user_id' => $user->id,
                'amount' => 0,
                'status' => 'active',
            ]);
        }

        return $user;
    }

    public function customer_login($request, $guard)
    {
        $cred = $request->only('email', 'password');

        if (Auth::guard($guard)->attempt($cred)) {
            return $guard;
        }

        return 0;
    }

    public function form()
    {
        return view('backend.auth.login');
    }

    public function customer_login_form()
    {
        return view('frontend.auth.login');
    }

    public function customer_forgot_password_form()
    {
        return view('frontend.auth.forget-password');
    }

    public function customer_register_form()
    {
        return view('frontend.auth.register');
    }

    public function logout($guard)
    {
        Auth::guard($guard)->logout();
    }

    public function customer_logout($guard)
    {
        Auth::guard($guard)->logout();
    }
}

<?php

namespace App\Http\Controllers\Frontend\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Repositories\Interface\AuthRepositoryInterface;
use App\Repositories\Interface\ProductRepositoryInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{

    protected $authRepository;
    protected $productRepository;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->authRepository = $authRepository;
        $this->productRepository = $productRepository;
    }

    public function redirectToGoogle(Request $request)
    {
        if($request->has('back')) {
            Session::put('back', $request->back);
        }
        if($request->has('buy')) {
            Session::put('buy', $request->buy);
        }
        return Socialite::driver('google')->redirect();
    }

    // Facebook Login
    public function redirectToFacebook(Request $request)
    {
        if($request->has('back')) {
            Session::put('back', $request->back);
        }
        if($request->has('buy')) {
            Session::put('buy', $request->buy);
        }
        return Socialite::driver('facebook')->redirect();
    }

    public function otpForm()
    {
        if(!Session::has('password_reset_user_email') || !Session::has('password_reset_code')) {
            return redirect()->route('login');
        }

        return view('frontend.auth.otp');
    }
    
    public function reset_password()
    {
        if(!Session::has('password_reset_enabled')) {
            return redirect()->route('login');
        }

        return view('frontend.auth.reset_password');
    }

    public function resetPassword(Request $request) 
    {
        return $this->authRepository->resetPassword($request);
    }

    public function postForgotPassword(Request $request) 
    {
        return $this->authRepository->postForgotPassword($request);
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();
        $this->loginOrRegisterUser($user, 'google');
    }

    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->stateless()->user();
        $this->loginOrRegisterUser($user, 'facebook');
    }

    // Common function to handle both Google and Facebook logins
    protected function loginOrRegisterUser($socialUser, $provider)
    {
        $user = $this->authRepository->social_login($socialUser, $provider);
        
        $login = Auth::guard('customer')->login($user);
        if($login) {
            $this->authRepository->setCustomerCart();
        }

        $route = URL::to('dashboard');
        if(Session::has('back')) {
            $route = URL::to('order/checkout');
            Session::forget('back');
        }

        if(Session::has('buy')) {
            $route = URL::to('buy/'. Session::get('buy'));
            Session::forget('buy');
        }

        echo '<script>window.location.href="'. $route .'"</script>';
    }

    public function validateOtp(Request $request)
    {
        if(!Session::has('password_reset_user_email') || !Session::has('password_reset_code')) {
            return redirect()->route('login');
        }
        
        return $this->authRepository->validateOtp($request);
    }

    public function index()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('dashboard');
        }

        return $this->authRepository->customer_login_form();
    }
    
    public function forgotPassword()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('dashboard');
        }

        return $this->authRepository->customer_forgot_password_form();
    }

    public function login(Request $request)
    {
        $guard = $this->authRepository->login($request, 'customer');

        if(isset($guard['status']) && $guard['status'] === false) {
            return response()->json([
                'status' => false, 
                'message' => $guard['message']
            ]);
        }

        if ($guard) {
            $request->session()->regenerate();

            $route = route('dashboard');
            if($request->has('buy') && $request->get('buy') != '') {
                $route = route('buy.now', $request->buy);
            } elseif ($request->has('back')) {
                $route = route('order.checkout');
            }

            return response()->json([
                'status' => true, 
                'goto' => $route,
                'message' => "Login successfully"
            ]);
        }

        return response()->json([
            'status' => false, 
            'message' => "The provided credentials do not match our records"
        ]);
    }

    public function logout()
    {
        // Helpers::logout('admin');
        $this->authRepository->logout('customer');  
        
        return response()->json([
            'status' => true, 
            'goto' => route('home'),
            'message' => "Logout successful"
        ]);
    }
}

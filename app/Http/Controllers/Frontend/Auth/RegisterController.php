<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\AuthRepositoryInterface;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{

    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function index()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('dashboard');
        }

        return $this->authRepository->customer_register_form();
    }

    public function register(Request $request)
    {
        $registerResponse = $this->authRepository->registerUser($request);
        if (!$registerResponse['status']) {
            return response()->json([
                'status' => false,
                'validator' => $registerResponse['message']
            ]);
        }

        // Session::put('code', rand(100000, 999999));
        // Session::put('timeout_start_time', date('Y-m-d H:i:s'));

        // return response()->json([
        //     'status' => true,
        //     'goto' => route('verify.phone'),
        //     'message' => "Registration successful. Please verify your mobile number."
        // ]);

        $guard = $this->authRepository->login($request, 'customer');
        if ($guard) {
            $request->session()->regenerate();

            $route = route('dashboard');
            if ($request->has('buy') && $request->get('buy') != '') {
                $route = route('buy.now', $request->buy);
            } elseif ($request->has('back')) {
                $route = route('order.checkout');
            }
            Notification::create([
                'user_id' => Auth::guard('customer')->id(),
                'message' => 'New Customer Registered: ' . ucwords(Auth::guard('customer')->user()->name),
                'go_to_link' => route('admin.customer.index'),
            ]);
            return response()->json([
                'status' => true,
                'goto' => $route,
                'message' => "Registration successful"
            ]);
        }
    }

    public function resent_otp()
    {
        Session::put('code', rand(100000, 999999));
        Session::put('timeout_start_time', date('Y-m-d H:i:s'));

        return redirect()->route('verify.phone');
    }
}

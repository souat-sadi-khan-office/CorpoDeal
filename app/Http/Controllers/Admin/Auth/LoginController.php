<?php

namespace App\Http\Controllers\Admin\Auth;

use App\CPU\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\AuthRepositoryInterface;

class LoginController extends Controller
{

    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function index()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        // return view('backend.auth.login');
        return $this->authRepository->form();
    }

    public function login(Request $request)
    {
        $guard = $this->authRepository->login($request, 'admin');

        if ($guard) {
            $request->session()->regenerate();
            return response()->json([
                'status' => true, 
                'goto' => route('admin.dashboard'),
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
        $this->authRepository->logout('admin');  
        
        return response()->json([
            'status' => true, 
            'goto' => route('admin.login'),
            'message' => "Logout successful"
        ]);
        // return redirect()->route('admin.login');
    }

}

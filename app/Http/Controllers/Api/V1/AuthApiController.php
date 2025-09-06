<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\BatchEmail;

class AuthApiController extends Controller
{
    /**
     * @group Authentication APIs
     * 
     * APIs for user registration, login, password reset, and logout.
     */

    /**
     * Register a new user
     *
     * @bodyParam name string required Full name of the user. Example: John Doe
     * @bodyParam email string required Unique email address. Example: john@example.com
     * @bodyParam password string required Password (minimum 8 characters). Example: password123
     * @bodyParam password_confirmation string required Must match the password. Example: password123
     *
     * @response 200 {
     *  "status": true,
     *  "token": "1|xxxxxxx",
     *  "user": { ... }
     * }
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'currency_id' => 1,
            'status' => 1,
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['status' => true, 'token' => $token, 'user' => $user]);
    }

    /**
     * Login user
     *
     * @bodyParam email string required Registered email. Example: john@example.com
     * @bodyParam password string required Password. Example: password123
     *
     * @response 200 {
     *  "status": true,
     *  "token": "1|xxxxxxx",
     *  "user": { ... }
     * }
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['status' => true, 'token' => $token, 'user' => $user]);
    }

    /**
     * Send forgot password OTP
     *
     * @bodyParam email string required Registered email to receive OTP. Example: john@example.com
     *
     * @response 200 {
     *  "status": true,
     *  "message": "Reset code sent to email."
     * }
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $code = rand(100000, 999999);
        $user = User::where('email', $request->email)->first();
        $user->code = $code;
        $user->save();

        $subject = "Password Reset Code";
        $message = "Your OTP code is: {$code}";

        Mail::to($user->email)->send(new BatchEmail($user, $subject, $message));

        Session::put('password_reset_email', $user->email);

        return response()->json(['status' => true, 'message' => 'Reset code sent to email.']);
    }

    /**
     * Reset Password
     *
     * Requires OTP sent to email.
     *
     * @bodyParam code integer required 6-digit reset code. Example: 123456
     * @bodyParam password string required New password (min 8 chars). Example: newpass123
     * @bodyParam password_confirmation string required Must match password. Example: newpass123
     *
     * @response 200 {
     *  "status": true,
     *  "message": "Password reset successfully."
     * }
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'     => 'required|numeric',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $email = Session::get('password_reset_email');
        $user = User::where('email', $email)->where('code', $request->code)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Invalid code'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->code = null;
        $user->save();

        Session::forget('password_reset_email');

        return response()->json(['status' => true, 'message' => 'Password reset successfully.']);
    }

    /**
     * Logout (Revoke current token)
     *
     * @authenticated
     *
     * @header Authorization Bearer {access_token}
     *
     * @response 200 {
     *  "status": true,
     *  "message": "Logged out successfully."
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['status' => true, 'message' => 'Logged out successfully.']);
    }

    /**
     * Account Deletion Notice
     *
     * Prevent account deletion from app.
     *
     * @authenticated
     * @header Authorization Bearer {access_token}
     *
     * @response 200 {
     *  "status": false,
     *  "message": "Account Deletion is disabled from Mobile App"
     * }
     */
    public function deleteAccount()
    {
        return response()->json([
            'status' => false,
            'message' => 'Account Deletion is disabled from Mobile App'
        ]);
    }
}

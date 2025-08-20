<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Show the email verification notice.
     */
    public function notice()
    {
        $user = Auth::guard('customer')->user();

        if (($user instanceof MustVerifyEmail && $user->hasVerifiedEmail()) || !mailChecker($user->email)) {
            return redirect()->route('dashboard');
        }
        return view('frontend.auth.verify-email');
    }

    public function phone()
    {
        return view('frontend.auth.verify-phone');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('dashboard')->with('success', 'Email successfully verified.');
    }

    /**
     * Resend the email verification link.
     */
    public function resend(Request $request)
    {
        if ($request->user('customer')->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user('customer')->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    }
}

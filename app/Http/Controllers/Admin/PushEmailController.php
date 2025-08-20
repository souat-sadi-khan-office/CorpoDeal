<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Jobs\SendBatchEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushEmailController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('send-mail') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.pushEmails.index');
    }

    public function send(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('send-mail') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'attachments' => 'nullable|file|mimes:jpeg,png,jpg,pdf,docx,zip|max:512',
        ]);

        $message = $validated['description'];
        $subject = $validated['subject'];
        $attachments = null;

        // Process the attachments if any
        if ($request->hasFile('attachments')) {
            $file = $request->file('attachments');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('attachments', $fileName, 'public');
            $attachments = $path;
        }

        // Dispatch the batch email job with a low-priority queue
        SendBatchEmail::dispatch($subject, $message, $attachments)
            ->onQueue('low'); // Use the dot for method chaining
        $activity = 'Pushed Batch emails to Users. With Subject: <strong>' . e($subject) . '</strong>.';
        Helpers::activity(null, Auth::guard('admin')->id(), null, 'system', $activity, 'default');

        // Return a response indicating the job is queued
        return response()->json(['message' => 'Emails are being sent!', 'status' => true, 'load' => true]);
    }

}

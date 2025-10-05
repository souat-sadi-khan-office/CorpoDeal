<?php 

namespace App\Http\Controllers;

use App\Models\CustomerNotification;
use Illuminate\Http\Request;

class CustomerNotificationController extends Controller
{
    /**
     * Get all notifications for a customer
     */
    public function index(Request $request)
    {
        $user = auth()->guard('user')->user();

        $notifications = CustomerNotification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id, Request $request)
    {
        $user = auth()->guard('user')->user();

        $notification = CustomerNotification::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['status' => true, 'message' => 'Notification marked as read']);
    }

    /**
     * Clear all notifications
     */
    public function clearAll(Request $request)
    {
        $user = auth()->guard('user')->user();

        CustomerNotification::where('user_id', $user->id)->delete();

        return response()->json(['status' => true, 'message' => 'All notifications cleared']);
    }
}

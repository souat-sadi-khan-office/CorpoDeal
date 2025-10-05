<?php 

namespace App\Http\Controllers\Api\V1;

use App\Models\CustomerNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerNotificationApiController extends Controller
{

    private function userId()
    {
        return auth('api')->user()->id ?? null;
    }

    /**
     * Get all notifications for a customer
     */
    public function index(Request $request)
    {
        $notifications = CustomerNotification::where('user_id', $this->userId())
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
        $notification = CustomerNotification::where('user_id', $this->userId())
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
        CustomerNotification::where('user_id', $this->userId())->delete();

        return response()->json(['status' => true, 'message' => 'All notifications cleared']);
    }
}

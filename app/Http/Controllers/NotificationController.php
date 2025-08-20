<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get all admin notifications.
     */
    public function getAdminNotifications(): JsonResponse
    {
        $query = Notification::orderBy('created_at', 'desc');

        $notifications = Notification::whereNull('admin_read_at')->count() > 10
            ? $query->whereNull('admin_read_at')->get()
            : $query->limit(10)->get();
        $notifications->transform(function ($notification) {
            $notification->message = add_line_breaks(e($notification->message),5);
            return $notification;
        });
        return response()->json($notifications);
    }

    /**
     * Mark all admin notifications as read.
     */
    public function markAllAsAdminRead(): JsonResponse
    {
        Notification::whereNull('admin_read_at')->update(['admin_read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsAdminRead($id): JsonResponse
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found.',
            ], 404);
        }

        $notification->admin_read_at =now();
        $notification->save();
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);
    }
}

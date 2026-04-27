<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // List all notifications for the authenticated resident
    public function index()
    {
        $resident = auth('resident')->user();

        $notifications = Notification::forResident($resident->resident_id)->orderByDesc('created_at')->paginate(15);

        // Mark all unread as read on page visit
        Notification::forResident($resident->resident_id)
            ->unread()
            ->update(['is_read' => 1]);

        return view('resident.notifications.index', compact('notifications'));
    }

    // Mark a single notification as read (called via AJAX or form)
    public function markRead($id)
    {
        $resident = auth('resident')->user();

        Notification::where('notification_id', $id)
            ->where(function ($q) use ($resident) {
                $q->where('resident_id', $resident->resident_id)->orWhereNull('resident_id');
            })
            ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

    // Mark all as read
    public function markAllRead()
    {
        $resident = auth('resident')->user();

        Notification::forResident($resident->resident_id)
            ->unread()
            ->update(['is_read' => 1]);

        return back()->with('success', 'All notifications marked as read.');
    }
}

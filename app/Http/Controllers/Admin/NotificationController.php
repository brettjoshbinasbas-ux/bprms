<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Premises;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // List all broadcast announcements admin has sent
    public function index()
    {
        $announcements = Notification::broadcasts()->orderByDesc('created_at')->paginate(15);

        $premises = Premises::orderBy('premises_name')->get();

        return view('admin.notifications.index', compact('announcements', 'premises'));
    }

    // Post a custom announcement OR a premises vacancy notice
    public function store(Request $request)
    {
        $request->validate([
            'announcement_type' => ['required', 'in:custom,vacancy'],
            'title' => ['required_if:announcement_type,custom', 'nullable', 'string', 'max:150'],
            'message' => ['required_if:announcement_type,custom', 'nullable', 'string'],
            'premises_id' => ['required_if:announcement_type,vacancy', 'nullable', 'exists:premises,premises_id'],
        ]);

        if ($request->announcement_type === 'vacancy') {
            $premises = Premises::with('location')->findOrFail($request->premises_id);
            NotificationService::vacancyAnnouncement($premises);
        } else {
            NotificationService::customAnnouncement($request->title, $request->message);
        }

        return back()->with('success', 'Announcement published to all residents.');
    }

    // Delete an announcement
    public function destroy($id)
    {
        Notification::where('notification_id', $id)->whereNull('resident_id')->delete();

        return back()->with('success', 'Announcement deleted.');
    }
}

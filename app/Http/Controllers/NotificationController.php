<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mark a specific notification as read and redirect to its link.
     */
    public function read(Notification $notification)
    {
        // Ensure user is authorized to read this notification
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        return redirect($notification->link ?: route('dashboard'));
    }

    /**
     * Mark all notifications of the authenticated user as read.
     */
    public function readAll()
    {
        auth()->user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}

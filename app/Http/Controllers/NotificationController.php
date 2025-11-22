<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $skip = $request->input('skip', 0);

        $newNots = Notification::where('user_id', auth()->id())
            ->orderBy('notifications.id', 'DESC')
            ->skip($skip)
            ->take(10)
            ->get();

        // Mark retrieved notifications as read
        Notification::whereIn('id', $newNots->pluck('id'))
            ->where('user_id', auth()->id())
            ->update(['is_read' => true]);

        
        return view('includes.ajax-notify-list', ['notifications' => $newNots, 'count' => $newNots->count()])->render();

        // return response()->json([
        //     'status' => 'success',
        //     'count' => $newNots->count(),
        //     'notificaciones' => $newNots
        // ]);
    }

    public function markAsRead($id)
    {
        $n = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $n->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $skip = $request->input('skip', 0);

        // Validamos si es un usuario de tipo seller usaremos Auth::guard('sellers')->check()
        if (Auth::guard('sellers')->check()) {
            $newNots = Notification::where('seller_id', Auth::guard('sellers')->id())
                ->orderBy('notifications.id', 'DESC')
                ->skip($skip)
                ->take(10)
                ->get();

                
            // Mark retrieved notifications as read
            Notification::whereIn('id', $newNots->pluck('id'))
                ->where('seller_id', Auth::guard('sellers')->id())
                ->update(['is_read' => true]);

        } else {
            $newNots = Notification::where('user_id', auth()->id())
                ->orderBy('notifications.id', 'DESC')
                ->skip($skip)
                ->take(10)
                ->get();

            
            // Mark retrieved notifications as read
            Notification::whereIn('id', $newNots->pluck('id'))
                ->where('user_id', auth()->id())
                ->update(['is_read' => true]);
        }

        
        return view('includes.ajax-notify-list', ['notifications' => $newNots, 'count' => $newNots->count()])->render();

        // return response()->json([
        //     'status' => 'success',
        //     'count' => $newNots->count(),
        //     'notificaciones' => $newNots
        // ]);
    }

    public function count(Request $request)
    {
        // Validamos si es un usuario de tipo seller usaremos Auth::guard('sellers')->check()
        if (Auth::guard('sellers')->check()) {
            $count = Notification::where('seller_id', Auth::guard('sellers')->id())
                ->where('is_read', false)
                ->count();
        } else {
            $count = Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count();
        }

        return response()->json([
            'status' => 'success',
            'count' => $count
        ]);
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

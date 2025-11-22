<?php 

namespace App\Traits;

use App\Models\Notification;

trait NotifiesUsers
{
    public function notifyUser($userId, $type, $title, $message, $data = [], $route, $expiresAt = null)
    {
        return Notification::create([
            'user_id'   => $userId,
            'type'      => $type,
            'title'     => $title,
            'message'   => $message,
            'data'      => $data,
            'route_redirect' => $route,
            'expires_at'=> $expiresAt
        ]);
    }
}

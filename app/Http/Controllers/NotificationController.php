<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function redirect(string $notification)
    {
        $item = auth()->user()->notifications()->whereKey($notification)->firstOrFail();
        $item->markAsRead();

        return redirect($item->data['url'] ?? route('dashboard'));
    }
}

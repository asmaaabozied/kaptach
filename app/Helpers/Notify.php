<?php

namespace App\Helpers;

use App\Notifications\TransferUpdates;
use Illuminate\Support\Facades\Notification;

class Notify
{
    function NotifyAdmin($admins, $data)
    {
        Notification::send($admins, new TransferUpdates($data));
    }
}
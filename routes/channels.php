<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
//live_comments/routes/channels.php
Broadcast::channel('add-channel', function () {
    return true;
});
Broadcast::channel('activity.admin.*', function () {
    return true;
});
Broadcast::channel('activity.driver.*', function () {
    return true;
}, ['guards' => ['api', 'admin']]);
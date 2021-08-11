<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('channel-staff', function () {
    return Auth::user()->user_type_id == 2;
});

Broadcast::channel('channel-feedback', function () {
    return Auth::user()->user_type_id == 2;
});

Broadcast::channel('channel-customer', function () {
    return Auth::check();
});

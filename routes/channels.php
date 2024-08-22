<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('Stories-{id}', function ($user, $id) {
    return $user->id === $id;
});

Broadcast::channel('Messages-{id}', function ($user, $id) {
    return $user->id === $id;
});

Broadcast::channel('Conversations-{id}', function ($user, $id) {
    return $user->id === $id;
});

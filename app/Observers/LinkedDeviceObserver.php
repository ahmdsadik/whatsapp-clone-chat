<?php

namespace App\Observers;

use App\Models\LinkedDevice;

class LinkedDeviceObserver
{
    public function created(LinkedDevice $linkedDevice): void
    {
        // TODO:: Broadcast
    }

    public function deleted(LinkedDevice $linkedDevice): void
    {
        // TODO:: Broadcast
    }
}

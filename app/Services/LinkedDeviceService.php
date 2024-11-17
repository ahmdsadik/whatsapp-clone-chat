<?php

namespace App\Services;

use App\DTO\LinkedDeviceDTO;
use App\Events\LinkedDevice\DeviceLinkedEvent;
use App\Events\LinkedDevice\DeviceUnlinkedEvent;
use App\Exceptions\InvalidChannelLinkException;
use App\Models\LinkedDevice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LinkedDeviceService
{
    /**
     * User's Linked Devices
     *
     * @return LinkedDevice[]|Collection
     */
    public function allLinkedDevices(): array|Collection
    {
        return auth()->user()->linkedDevices()->with(['token:id,last_used_at'])->get();
    }

    /**
     * Link a device
     *
     * @throws InvalidChannelLinkException
     */
    public function linkDevice(LinkedDeviceDTO $linkedDeviceDTO): void
    {
        DB::transaction(function () use ($linkedDeviceDTO) {
            // Generate token
            $user = auth()->user();
            $access_token = $user->createToken($linkedDeviceDTO->device_name);
            $access_token_id = $access_token->accessToken->id;
            $token = $access_token->plainTextToken;

            // Prepare data to store
            $data = [
                ...$linkedDeviceDTO->toArray(),
                'token_id' => $access_token_id,
            ];

            $user->linkedDevices()->create($data);

            broadcast(new DeviceLinkedEvent($linkedDeviceDTO->channel_name, $token));
        });
    }

    /**
     * Unlink Device
     */
    public function unlinkDevice(LinkedDevice $linkedDevice): void
    {
        DB::transaction(function () use ($linkedDevice) {
            $linkedDevice->token()->delete();
            $linkedDevice->delete();

            broadcast(new DeviceUnlinkedEvent($linkedDevice->channel_name));
        });
    }
}

<?php

namespace App\Services;

use App\Actions\BroadcastLinkedTokenAction;
use App\DTO\LinkedDeviceDTO;
use App\Exceptions\InvalidChannelLinkException;
use App\Http\Resources\LinkedDeviceResource;
use App\Models\LinkedDevice;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class LinkedDeviceService
{
    /**
     * User's Linked Devices
     *
     * @return AnonymousResourceCollection
     */
    public function allLinkedDevices(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $linked_devices = auth()->user()->linkedDevices()->with(['token:id,last_used_at'])->get();

        return LinkedDeviceResource::collection($linked_devices);
    }

    /**
     * Link a device
     *
     * @throws InvalidChannelLinkException
     */
    public function linkDevice(LinkedDeviceDTO $linkedDeviceDTO): void
    {
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

        (new BroadcastLinkedTokenAction())->execute($token);
    }

    /**
     * Unlink Device
     *
     * @param LinkedDevice $linkedDevice
     * @return void
     */
    public function unlinkDevice(LinkedDevice $linkedDevice): void
    {
        DB::transaction(function () use ($linkedDevice) {
            $linkedDevice->token()->delete();
            $linkedDevice->delete();
        });
    }
}

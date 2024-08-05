<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LinkedDevice\LinkDeviceRequest;
use App\Http\Requests\LinkedDevice\UnlinkDeviceRequest;
use App\Http\Resources\LinkedDeviceResource;
use App\Models\LinkedDevice;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LinkedDeviceController extends Controller
{
    /**
     * Retrieve user's linked devices
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $linked_devices = auth()->user()->linkedDevices()->with(['token:id,last_used_at'])->get();

            return $this->successResponse([
                'linked_devices' => LinkedDeviceResource::collection($linked_devices),
            ], 'Linked devices retrieved Successfully!');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $this->errorResponse('Error happened While trying to retrieve linked devices.');
        }

    }

    /**
     * Create a channel to link a device through it
     *
     * @return JsonResponse
     */
    public function createLinkChannel()
    {
        try {
            // Generate channel name valid for a minute
            $random_bytes = bin2hex(random_bytes(16));
            $timestamp = Carbon::now()->addMinute()->timestamp;
            $channelName = "{$random_bytes}_{$timestamp}";

            return $this->successResponse([
                'channel_name' => $channelName,
            ], 'Link Channel Generated Successfully!');

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $this->errorResponse('Error happened While creating link channel.');
        }
    }

    /**
     * Link a device
     *
     * @param LinkDeviceRequest $request
     * @return JsonResponse
     */
    public function linkDevice(LinkDeviceRequest $request)
    {
        try {
            // Check Channel name
            $timestamp = explode('_', $request->validated('channel_name'))[1] ?? '';

            if (!Carbon::now()->lessThanOrEqualTo(Carbon::createFromTimestamp($timestamp))) {
                return $this->errorResponse('Invalid Qr. try another one');
            }

            // Generate token
            $user = auth()->user();
            $access_token = $user->createToken($request->validated('device_name'));
            $access_token_id = $access_token->accessToken->id;
            $token = $access_token->plainTextToken;

            // Prepare data to store
            $data = [
                ...$request->validated(),
                'token_id' => $access_token_id,
            ];


            $user->linkedDevices()->create($data);

            // TODO:: Broadcast Token To web to login in

            return $this->successResponse(message: 'Device Linked Successfully!');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $this->errorResponse('Error happened While linking your device.');
        }
    }

    /**
     * unlink a given device
     *
     * @param UnlinkDeviceRequest $request
     * @param LinkedDevice $linkedDevice
     * @return JsonResponse
     */
    public function unlinkDevice(UnlinkDeviceRequest $request, LinkedDevice $linkedDevice)
    {
        // TODO:: Broadcast a logout Event to Linked Device

        try {
            // Delete Linked device and token
            DB::transaction(function () use ($linkedDevice) {
                $linkedDevice->token()->delete();
                $linkedDevice->delete();
            });

            return $this->successResponse(message: 'Device Unlinked Successfully!');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $this->errorResponse('Error While trying to unlink device');
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Actions\GenerateLinkChannelAction;
use App\DTO\LinkedDeviceDTO;
use App\Exceptions\InvalidChannelLinkException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LinkedDevice\LinkDeviceRequest;
use App\Http\Requests\LinkedDevice\UnlinkDeviceRequest;
use App\Http\Resources\LinkedDeviceResource;
use App\Models\LinkedDevice;
use App\Services\LinkedDeviceService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LinkedDeviceController extends Controller
{
    public function __construct(
        private readonly LinkedDeviceService $linkedDeviceService,
    )
    {
    }

    /**
     * Retrieve user's linked devices
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {

            $linkedDevices = $this->linkedDeviceService->allLinkedDevices();

            return $this->successResponse([
                'linked_devices' => LinkedDeviceResource::collection($linkedDevices),
            ], 'Linked devices retrieved Successfully!');

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
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
            $channelName = (new GenerateLinkChannelAction())->execute();

            return $this->successResponse([
                'channel_name' => $channelName,
            ], 'Link Channel Generated Successfully!');

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
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

            $this->linkedDeviceService->linkDevice(LinkedDeviceDTO::fromApiFormRequest($request));

            return $this->successResponse(message: 'Device Linked Successfully!');
        } catch (InvalidChannelLinkException $channelException) {
            return $this->errorResponse($channelException->getMessage());
        } catch (UniqueConstraintViolationException) {
            return $this->errorResponse('This channel already linked.!');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error happened while linking your device.');
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
        try {

            // Delete Linked device and token
            $this->linkedDeviceService->unlinkDevice($linkedDevice);

            return $this->successResponse(message: 'Device Unlinked Successfully!');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
            return $this->errorResponse('Error While trying to unlink device');
        }
    }
}

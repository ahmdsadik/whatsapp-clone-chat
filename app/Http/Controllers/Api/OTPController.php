<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidOTP;
use App\Http\Controllers\Controller;
use App\Http\Requests\OTP\ResendOTPRequest;
use App\Http\Requests\OTP\VerifyOTPRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\OTP\OTP;
use App\Services\OTPService;
use App\Services\SMS\SMS;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OTPController extends Controller
{
    public function __construct(
        private readonly OTPService $OTPService,
    ) {}

    public function resendOtp(ResendOTPRequest $request): JsonResponse
    {
        try {

            $this->OTPService->sendOTP($request->validated('mobile_number'));

            return $this->successResponse(message: 'OTP sent');
        } catch (\Throwable $throwable) {
            return $this->errorResponse('Error Happened while sending OTP');
        }
    }

    public function verifyOTP(VerifyOTPRequest $request): JsonResponse
    {
        try {

            [$user, $token] = $this->OTPService->verifyOTP($request->validated('mobile_number'),
                $request->validated('otp'));

            return $this->successResponse([
                'user' => UserResource::make($user),
                'token' => $token
            ], 'Valid Otp. You have been successfully logged In!');

        } catch (InvalidOTP $exception) {
            return $this->errorResponse($exception->getMessage());
        } catch (\Throwable $throwable) {
            return $this->errorResponse('Error Happened while verifying OTP');
        }
    }
}

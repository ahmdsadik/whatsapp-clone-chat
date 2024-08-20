<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OTP\ResendOTPRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\OTP\OTP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OTPController extends Controller
{
    public function resendOtp(ResendOTPRequest $request, OTP $otp)
    {

        try {

            $phone = new PhoneNumber($request->mobile_number);

            $phone_parts = explode('-', str_replace('tel:', '', $phone->formatRFC3966()));
            $phone_parts[0] = $phone_parts[0] . '-';

            $mobile_number = implode('', $phone_parts);

            $otp = $otp->generate($request->mobile_number);
            OpenApiService::sendSms($mobile_number, $otp->token);

            return $this->successResponse(message: 'OTP sent');
        } catch (\Throwable $throwable) {
            return $this->errorResponse('Error Happened');
        }
    }

    public function verifyOTP(Request $request, OTP $otp)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            //   'mobile_number' => 'required|regex:/(0)[0-9]/|not_regex:/[a-z]/|min:9',
            'fcm_token' => "required|string"
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors()->all());
        }

        try {
            $user = User::where('mobile_number', $request->mobile_number)->first();
            if (!$user) {

                return $this->errorResponse('User does not exist');
            }

            // $otp = $otp->validate($user->mobile_number, $request->otp);


            // if (!$otp->status) {
            //     return $this->errorResponse($otp->message);
            // }

            if ($user->fcm_token) {
                $user->blocks()->delete();
                $user->archivedChats()->delete();
            }

            $user->update([
                'fcm_token' => $request->fcm_token,
                'notification' => true
            ]);



            $token = $user->createToken('Laravel Password Grant Client', ['*'])->accessToken;


            return $this->successResponse([
                'user' => UserResource::make($user),
                'token' => $token
            ], 'Valid Otp. You have been successfully logged In!');
        } catch (\Throwable $throwable) {
            return $this->errorResponse('Error Happened');
        }
    }
}

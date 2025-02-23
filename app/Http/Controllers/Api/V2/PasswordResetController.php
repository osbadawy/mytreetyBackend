<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\User;
use App\Notifications\AppEmailVerificationNotification;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function forgetRequest(Request $request): JsonResponse
    {
        //Get user details
        $user = User::where('email', $request->email)->first();

        //Check if user exist
        if (!$user) return $this->returnIfNotExist();

        //Generate new verification code
        $this->generateNewVerificationCode($user);

        //Send new email for the user with the new code
        $user->notify(new AppEmailVerificationNotification());

        return response()->json(['result' => true, 'message' => translate('A code is sent')], 200);
    }

    /**
     * @return JsonResponse
     */
    public function returnIfNotExist(): JsonResponse
    {
        return response()->json(['result' => false, 'message' => translate('User is not found')], 404);
    }

    /**
     * @param $user
     * @return void
     */
    public function generateNewVerificationCode($user): void
    {
        $user->verification_code = rand(100000, 999999);
        $user->save();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmReset(Request $request): JsonResponse
    {
        //Get the user by verification code
        $user = User::where('verification_code', $request->verification_code)->first();

        //Check if user exist
        if ($user != null) {

            //Change Password
            $this->changePassword($user, $request->password);

            return response()->json(['result' => true, 'message' => translate('Your password is reset.Please login'),], 200);
        } else {
            return response()->json(['result' => false, 'message' => translate('No user is found'),], 400);
        }
    }

    /**
     * @param $user
     * @param $password
     * @return void
     */
    public function changePassword($user, $password): void
    {
        $user->verification_code = null;
        $user->password = Hash::make($password);
        $user->save();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function resendCode(Request $request): JsonResponse
    {
        //Get user details
        $user = User::where('email', $request->email)->first();

        //Check if user exist
        if (!$user) return $this->returnIfNotExist();

        //Generate new verification code
        $this->generateNewVerificationCode($user);

        //Send new email for the user with the new code
        $user->notify(new AppEmailVerificationNotification());


        return response()->json(['result' => true, 'message' => translate('A code is sent again'),], 200);
    }
}

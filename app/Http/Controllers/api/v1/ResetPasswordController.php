<?php

namespace App\Http\Controllers\api\v1;

use App\Codes;
use App\Models\User;
use Carbon\Carbon;
use App\Models\PasswordReset;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Create token password reset
     *
     * @param Request $request
     * @return JsonResponse [string] message
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => Codes::VALIDATION_FAILED,
                'message' => $validator->errors(),
            ], 400);
        }

        // Search user in the database
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json([
                'status_code' => Codes::USER_NOT_FOUND,
                'message' => 'No existen registros asociados al correo electrónico ingresado.'
            ], 400);

        // Generate password reset
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(64)
            ]
        );

        if ($user && $passwordReset)
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );

        return response()->json([
            'status_code' => Codes::SUCCESS,
            'message' => 'Password reset link sent.',
        ], 200);
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return JsonResponse [string] message
     * @throws Exception
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset)
            return response()->json([
                'status_code' => Codes::INVALID_TOKEN,
                'message' => 'This password reset token is invalid.'
            ], 400);

        if (Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'status_code' => Codes::EXPIRED_TOKEN,
                'message' => 'This password reset token is expired.'
            ], 400);
        }
        return response()->json($passwordReset);
    }

    /**
     * Reset password
     *
     * @param Request $request
     * @return JsonResponse [string] message
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|confirmed|min:6',
            'token' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => Codes::VALIDATION_FAILED,
                'message' => $validator->errors(),
            ], 400);
        }

        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if (!$passwordReset)
            return response()->json([
                'status_code' => Codes::INVALID_TOKEN,
                'message' => 'This password reset token is invalid.'
            ], 400);

        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return response()->json([
                'status_code' => Codes::USER_NOT_FOUND,
                'message' => 'No user with that e-mail address.'
            ], 401);

        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess());
        return response()->json($user); //TODO
    }

    /**
     * Change password
     *
     * @param Request $request
     * @return JsonResponse [string] message
     */
    public function change(Request $request)
    {
        $user = Auth::user();

        // Check correct password
        if (!(Hash::check($request->password, $user->password))) {
            return response()->json([
                'status_code' => Codes::INVALID_PASSWORD,
                'message' => 'Password is invalid.',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
            'new_password' => 'required|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => Codes::VALIDATION_FAILED,
                'message' => $validator->errors(),
            ], 400);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();
        return response()->json([
            'status_code' => Codes::SUCCESS,
            'message' => 'Password changed.',
        ], 200);

    }
}

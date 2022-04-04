<?php

namespace App\Http\Controllers\api\v1;

use App\Codes;
use App\Models\LinkedSocialAccount;
use App\Models\Options;
use App\Notifications\RegisterActivate;
use App\Models\User;
use App\Models\UserVerification;
use App\Services\SocialAccountsService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    /**
     * User registration
     * @param Request $request
     * @return JsonResponse
     */
    public function social(Request $request)
    {
        $providerUser = null;
        $provider = $request->input('provider');
        $accessToken = $request->input('access_token');
        switch ($provider) {
            case LinkedSocialAccount::SERVICE_FACEBOOK:
                $providerUser = Socialite::driver(LinkedSocialAccount::SERVICE_FACEBOOK)->fields([
                    'name',
                    'first_name',
                    'last_name',
                    'email'
                ]);
                break;
            case LinkedSocialAccount::SERVICE_LINKEDIN:
                $providerUser = Socialite::driver(LinkedSocialAccount::SERVICE_LINKEDIN)->scopes([
                    'first_name',
                    'last_name',
                    'email'
                ]);
                break;
            case LinkedSocialAccount::SERVICE_GOOGLE:
                $providerUser = Socialite::driver(LinkedSocialAccount::SERVICE_GOOGLE)
                    ->scopes(['profile', 'email']);
                break;
            default :
                $providerUser = null;
        }
        $providerUser = $providerUser->userFromToken($accessToken);


        if ($providerUser == null) {
            return response()->json([
                'status_code' => Codes::INVALID_TOKEN,
                'message' => 'Invalid credentials',
            ], 400);
        }


        $user = (new SocialAccountsService())->findOrCreate($providerUser, $provider);

        // Check if Automatically activate is on
        $is_auto_active = Options::firstWhere('prop_key', '=', 'automatically_activate_users');
        if (isset($is_auto_active)) {
            if ($is_auto_active->prop_value) {
                $user->fill(array('active' => true));
            }
        }

        //Check if user is active
        if (!$user->active) {
            return response()->json([
                'status_code' => Codes::USER_NOT_ACTIVE,
                'message' => 'User is not active.'], 401);
        }


        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        $token->save();

        return response()->json([
            'status_code' => Codes::SUCCESS,
            'user' => $user,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
        ], 201);
    }


    /**
     * User registration
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'country_id' => 'required|integer',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => Codes::VALIDATION_FAILED,
                'message' => $validator->errors(),
            ], 400);
        }

        // User creation
        $input = $request->all();

        // Check if Automatically activate is on
        $is_auto_active = Options::firstWhere('prop_key', '=', 'automatically_activate_users');
        if (isset($is_auto_active)) {
            if ($is_auto_active->prop_value) {
                $input['active'] = true;
            }
        }

        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        // Create confirmation token
        $userVerification = UserVerification::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => User::generateToken(),
                'expiration_date' => Carbon::now()->addHours(1)
            ]
        );

        $user->notify(new RegisterActivate($userVerification->token));

        //$token = $user->createToken('Personal Access Token')->accessToken;

        // Response with token included
        return response()->json([
            'status_code' => Codes::CREATED,
            //'token' => $token,
            'user' => $user
        ], 201);
    }

    /**
     * Perform the confirmation of the user's e-mail address.
     *
     * @param Request $request
     * @return mixed
     */
    public function confirm(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => Codes::VALIDATION_FAILED,
                'message' => $validator->errors(),
            ], 400);
        }

        // Check token
        $userVerification = UserVerification::where('token', $request->token)->first();
        if (!$userVerification)
            return response()->json([
                'status_code' => Codes::INVALID_TOKEN,
                'message' => 'This token is invalid.'
            ], 400);

        // Check User
        $user = User::where('email', $userVerification->email)->first();
        if (!$user)
            return response()->json([
                'status_code' => Codes::USER_NOT_FOUND,
                'message' => 'No user with that e-mail address.'
            ], 400);

        //Confirm user
        $user->confirmed_at = Carbon::now();
        $user->save();
        $userVerification->delete();

        // Response with token included
        return response()->json([
            'status_code' => Codes::SUCCESS,
            'user' => $user,
        ], 200);

    }


    /**
     * Login and obtain a valid token
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => Codes::VALIDATION_FAILED,
                'message' => $validator->errors(),
            ], 400);
        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status_code' => Codes::LOGIN_FAILED,
                'message' => 'Unauthorized'], 401);
        }

        $user = $request->user();
        //Check if user is active
        if (!$user->active) {
            return response()->json([
                'status_code' => Codes::USER_NOT_ACTIVE,
                'message' => 'User is not active'], 401);
        }

        //Check if user is not confirmed
        if (!isset($user->confirmed_at)) {
            return response()->json([
                'status_code' => Codes::USER_NOT_CONFIRMED,
                'message' => 'User is not confirmed'], 401);
        }


        $tokenResult = $user->createToken('Personal Access Token');
        /*$token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        */
        return response()->json([
            'status_code' => Codes::SUCCESS,
            'user' => $user,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at)
                ->toDateTimeString(),
        ], 200);
    }

    /**
     * Revoke token and finish session
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'status_code' => Codes::SUCCESS,
            'message' => 'Successfully logged out',
        ], 200);
    }

    /**
     * Revoke token and finish session
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request)
    {
        $request->user()->token()->revoke();
        $tokenResult = $request->user()->createToken('Personal Access Token');
        $token = $tokenResult->token;

        return response()->json([
            'status_code' => Codes::SUCCESS,
            'user' => $request->user(),
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at)
                ->toDateTimeString(),
        ], 200);
    }

    /**
     * Account details
     * @param Request $request
     * @return JsonResponse
     */
    public function details(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'status_code' => Codes::SUCCESS,
            'user' => $user,
        ], 200);
    }
}

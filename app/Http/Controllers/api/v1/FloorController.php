<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Passport\AccessToken;
use App\Models\Parameter;
use App\Models\User;
use App\Models\UserVerification;
use App\Codes;
use Illuminate\Http\Testing\MimeType;

use App\Mail\uploadPlan;

class FloorController extends Controller
{
    public function uploadPlan(Request $request){
        Validator::extend('is_file', 'App\Utils\CustomValidations@isFile');
        Validator::extend('max_file_size', 'App\Utils\CustomValidations@maxFileSize');
        $validator = Validator::make($request->all(), [
            'building_name' => 'required',
            'address' => 'required',
            'country' => 'required',
            'city' => 'required',
            'link' => 'required|is_file',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => Codes::VALIDATION_FAILED,
                'message' => $validator->errors(),
            ], 400);
        }

        $parametros = Parameter::first();
        $correo = $parametros['email'];
        $user = Auth::user();
        try {
            $file_data   = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->link));
            $ext        = MimeType::search(finfo_buffer(finfo_open(), $file_data, FILEINFO_MIME_TYPE));
            $image_name = 'attached_'.time().'.'.$ext;
            $file_route     = 'temp_file_email/'.$image_name;
            Storage::disk('local')->put($file_route, $file_data);
            $file_location = Storage::disk('local')->path($file_route);
            Mail::to($correo)->send(new uploadPlan($user, $file_location, $request['building_name'], $request['adress'], $request['country'], $request['city']  ));
            Storage::delete($file_location);
        
            return response()->json([
                'message' => 'Solicitud exitosa',
            ], 200);

        }catch(Exception $e){
            return response()->json([
                'message' => 'Error',
            ], 500);
        }
    }

    public function getActiveUsers(Request $request)
    {
        $activeUsers    =   User::
                            whereJsonLength('permissions', '=', 0)
                            ->orWhere('permissions', '=', null)
                            ->whereDoesntHave('roles')
                            ->whereNotNull('active')
                            ->get(['id', 'name', 'last_name']);

        return response()->json([
            'status_code' => Codes::SUCCESS,
            'activeUsers' => $activeUsers,
        ], 200);
    }

    public function createAccessToken($id)
    {
        $user = User::find($id);

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        $token->save();

        return response()->json([
            'status_code' => Codes::SUCCESS,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
        ], 200);
    }
}

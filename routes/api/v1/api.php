<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Users

Route::prefix('/user')->group(function () {
    Route::post('/social', 'api\v1\AuthController@social');
    Route::post('/login', 'api\v1\AuthController@login');
    Route::post('/register', 'api\v1\AuthController@register');
    Route::post('/confirm', 'api\v1\AuthController@confirm');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/upload-plan', 'api\v1\FloorController@uploadPlan');
        Route::get('/active-users', 'api\v1\FloorController@getActiveUsers');
        Route::post('/accesstoken', 'api\v1\FloorController@createAccessToken');
        Route::post('/details', 'api\v1\AuthController@details');
        Route::post('/logout', 'api\v1\AuthController@logout');
        Route::post('/refresh', 'api\v1\AuthController@refresh');
    });

    Route::group([
        'prefix' => 'password'
    ], function () {
        Route::post('/email', 'api\v1\ResetPasswordController@create');
        Route::get('/find/{token}', 'api\v1\ResetPasswordController@find');
        Route::post('/reset', 'api\v1\ResetPasswordController@reset');
        Route::group(['middleware' => 'auth:api'], function () {
            Route::post('/change', 'api\v1\ResetPasswordController@change');
        });
    });
});

Route::get('/countries', 'api\v1\CountryController@countries');



Route::get('/test', 'api\v1\CountryController@test');


// Anything else
Route::any('{query}',
    function () {
        return response()->json(['message' => 'Not Found'], 404);
    })
    ->where('query', '.*');

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvitationController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function(Request $request){
    return 'testing';
});

// Route::get('/userlist', function(Request $request){
//     $userList = UserController::listUsers();
//     return $userList->toJson();
// });

// Route::get('/userlist/{id}', [UserController::class, 'listUsersExcept'])->where('id', '[0-9]+');
Route::get('/userlist', [UserController::class, 'listUsersExceptCurrent']);
Route::get('userlist/uid={uid}&input={input}', [UserController::class, 'findUser'])
    ->where([
        'uid', '[0-9]+',
        'input', '[a-zA-Z0-9]+'
        ]);
Route::get('invitation/{uid}', [InvitationController::class, 'list'])->where(['uid', '[0-9]+']);
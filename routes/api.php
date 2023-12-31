<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//AuthController

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

//Is Admin
Route::group(['middleware' => ['auth:sanctum', 'isAdmin']], function(){
    Route::get('/users/all', [UserController::class, 'viewAllUsers']);
    Route::delete('/users/delete/{id}', [UserController::class, 'deleteUserById']);
    Route::get('/users/details/{id}', [UserController::class, 'usersDetailsById']);
    Route::put('/comments/update/{id}', [UserController::class, 'updateMessagesByIdAdmin']);
    Route::delete('/comments/delete/{id}', [UserController::class, 'deleteCommentByIdAdmin']);
    Route::post('/party/create', [PartyController::class, 'createParty']);
    Route::get('/party/detail/{id}', [PartyController::class, 'getPartyById']);
});


//User Controller
Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile/update', [UserController::class, 'profileUpdate']);
    Route::post('/comments/create', [UserController::class, 'createComment']);
    Route::get('/comments/view',[UserController::class, 'viewMyMessages']);
    Route::get('/comments/party/{id}',[UserController::class, 'viewMessagesPartyById']);
    Route::delete('/mycomments/delete/{id}',[UserController::class, 'deleteCommentByIdUser']);
    Route::put('/mycomments/update/{id}',[UserController::class, 'updateMessagesByIdUser']);
    Route::post('/party/join',[PartyController::class, 'joinParty']);
    Route::delete('/party/leave/{id}',[PartyController::class, 'leaveParty']);
});



//Direccion de Testeo
Route::get('/wellcome', function(){
    return view('welcome');
});

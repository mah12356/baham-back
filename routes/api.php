<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CafeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');
if (auth('api')->user()===null) {
    $middleware=['auth:host'];
}else{
    $middleware=['auth:api'];
}
Route::middleware(['auth:api'])->group(function (){
    Route::post('/host-page',[CafeController::class,'hostPage']);
    Route::post('/reserve',[UserController::class,'reserve']);
    Route::get('/user-profile',[UserController::class,'userProfile']);
    Route::post('/like-cafe',[UserController::class,'likeCafe']);
    Route::post('/comments',[UserController::class,'comments']);
    Route::post('/add-comment',[UserController::class,'addComment']);
    Route::post('/update-user',[UserController::class,'updateUser']);
});
Route::middleware(['auth:host'])->group(function (){
    Route::get('/my-comments',[CafeController::class,'myComments']);
    Route::post('/save-ticket',[CafeController::class,'saveTicket']);
    Route::get('/host-profile',[CafeController::class,'hostProfile']);
    Route::put('/edit-host',[AuthController::class,'editHost']);
//    Route::patch('/edit-host-photo',[CafeController::class,'editHostPhoto']);
    Route::patch('/answer',[CafeController::class,'answer']);
    Route::patch('/edit-ticket-date-time',[CafeController::class,'editTicketDateTime']);
    Route::delete('/delete-ticket',[CafeController::class,'deleteTicket']);
});
Route::get('get-sc',[AuthController::class,'getSc']);
Route::get('/get-game-data', [HomeController::class, 'getGameData']);
Route::post('/games',[HomeController::class,'games']);
Route::post('/register-host',[AuthController::class,'registerHost']);
Route::post('/login-host',[AuthController::class,'loginHost']);
Route::post('/register-user',[AuthController::class,'registerUser']);
Route::post('/login-user',[AuthController::class,'loginUser']);
Route::middleware($middleware)->group(function (){
    Route::get('/user',[AuthController::class,'user']);
    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('/shaba',[AuthController::class,'shaba']);
    Route::post('/save-amount',[CommonController::class,'saveAmount']);
    Route::get('/update-wallet',[CommonController::class,'updateWallet']);
});
Route::get('/cafe-profile/{id}',[CafeController::class,'cafeProfile']);
Route::get('/about-us',[HomeController::class,'aboutUs']);
Route::post('/change-password-step-one',[AuthController::class,'changePasswordStepOne']);
Route::post('/change-password-step-two',[AuthController::class,'changePasswordStepTwo']);
Route::patch('/change-password-step-three',[AuthController::class,'changePasswordStepThree']);
Route::post('/',function(Request $request){
    $file = $request->file('image');

    $filename = $file->getClientOriginalName();

    Storage::disk('s3')->putFileAs(
        'images',
        $file,
        $filename
    );
//    Screenshot from 2026-07-05 17-21-08.png
});
Route::get('/image/{file}',function($file){
    $path = "host/$file";

    return response(Storage::disk('s3')->get($path));
});

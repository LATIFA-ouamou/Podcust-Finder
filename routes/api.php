<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\UserController;
 use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\HostController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});




Route::post('register',[AuthController::class,'register']);

Route::post('login',[AuthController::class,'login']);

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');







Route::get('/podcasts', [PodcastController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {

    Route::post('/podcasts', [PodcastController::class, 'store']);
    Route::put('/podcasts/{podcast}', [PodcastController::class, 'update']);
    Route::delete('/podcasts/{podcast}', [PodcastController::class, 'destroy']);



});






 Route::middleware(['auth:sanctum'])->group(function () {
    
  
    Route::middleware('role:admin')->group(function () {


        Route::get('/users', [UserController::class, 'index']);   
        Route::post('/users', [UserController::class, 'store']);  
        Route::delete('/users/{user}', [UserController::class, 'destroy']); 
        Route::put('/users/{user}', [UserController::class, 'update']); 
    });




    Route::get('/users/{user}', [UserController::class, 'show']); 
});









 
 
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/hosts', [HostController::class, 'store']);       
    Route::put('/hosts/{host}', [HostController::class, 'update']); 
    Route::delete('/hosts/{host}', [HostController::class, 'destroy']); 
});




Route::middleware('auth:sanctum')->group(function () {

    Route::get('episodes', [EpisodeController::class, 'index']);

    Route::post('podcasts/{podcast}/episodes', [EpisodeController::class, 'store']);
Route::get('episodes/{episode}', [EpisodeController::class, 'show']);
    Route::put('episodes/{episode}', [EpisodeController::class, 'update']);

    Route::delete('episodes/{episode}', [EpisodeController::class, 'destroy']);
    Route::get('/podcasts/{podcast}/episodes', [EpisodeController::class, 'listByPodcast']);
});







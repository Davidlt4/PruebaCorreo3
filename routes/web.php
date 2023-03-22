<?php

use App\Http\Controllers\MailController;
use App\Http\Controllers\GetTokenController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|  
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::prefix('/')->group(function(){
    Route::view('home','home')->name('home');
    Route::post('/get-token',[OAuthController::class,'doGenerateToken'])->name('generate.token');
    Route::get('/get-token',[OAuthController::class, 'doSuccessToken'])->name('token.success');
    Route::post('/send',[MailController::class, 'doSendEmail'])->name('send.email');
});*/

Route::get('/', function () {
    return Redirect::route('home');
});

Route::prefix('/')->group(function(){
    Route::view('home','home')->name('home');
    // Route::post('/get-token',[OAuthController::class,'doGenerateToken'])->name('generate.token');
    Route::post('/get-token',[GetTokenController::class,'index'])->name('generate.token');
    Route::get('/get-token',[GetTokenController::class,'index'])->name('generate.token');
    // Route::get('/get-token',[OAuthController::class, 'doSuccessToken'])->name('token.success');
    Route::post('/send',[MailController::class, 'doSendEmail'])->name('send.email');
});
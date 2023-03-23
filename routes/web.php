<?php

use App\Http\Controllers\GetTokenController;
use App\Http\Controllers\CorreoController;
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

Route::get('/', function () {
    return Redirect::route('home');
});

Route::prefix('/')->group(function(){

    Route::view('home','home')->name('home');

    //para obtener token
    // Route::post('/get-token',[GetTokenController::class,'index'])->name('generate.token');

    // Route::get('/get-token',[GetTokenController::class,'index'])->name('generate.token');

    //para enviar correo

    Route::post('/enviar',[CorreoController::class,'enviarCorreo'])->name('enviar.correo');

    Route::get('/enviar',[CorreoController::class,'enviarCorreo'])->name('enviar.correo');

});
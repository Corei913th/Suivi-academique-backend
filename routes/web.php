<?php

use App\Http\Controllers\FiliereWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route web pour les filières (interface web)
Route::resource('filiere', FiliereWebController::class);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;


Route::get('/', function () {
    return view('welcome');
});



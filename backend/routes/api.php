<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/optimal-schedule', [ScheduleController::class, 'getBestSchedule']);

Route::get('/test', function () {
    return response()->json(['message' => 'Hello, World!']);
});

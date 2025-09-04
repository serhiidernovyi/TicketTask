<?php

use App\Http\Controllers\Ticket\StatsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ticket\TicketController;


Route::apiResource('tickets', TicketController::class)->middleware('throttle:60,1');
Route::post('tickets/{ticket}/classify', [TicketController::class, 'classify'])->middleware('throttle:10,1');
Route::get('stats', [StatsController::class, 'index'])->middleware('throttle:30,1');

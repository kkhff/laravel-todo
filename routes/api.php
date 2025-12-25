<?php

use App\Http\Controllers\Api\V1\TodoController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggle']);
    Route::apiResource('todos', TodoController::class)->except(['show']);
});
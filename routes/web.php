<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

    Route::get('/', function () {
        return view('todos.index');
    });
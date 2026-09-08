<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SnmpController;

Route::get('/', [SnmpController::class, 'index']);

<?php

// use Illuminate\Http\Request;

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CardController;
use App\Http\Controllers\api\ProjectController;
use App\Http\Controllers\api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/test', [UserController::class, 'test']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::post('/projects', [ProjectController::class, 'store'])->middleware('jwt.auth');
Route::post('/projects/join', [ProjectController::class, 'join'])->middleware('jwt.auth');
Route::get('/projects', [ProjectController::class, 'index'])->middleware('jwt.auth');
Route::put('/projects/{project}', [ProjectController::class, 'update'])->middleware('jwt.auth');

Route::get('/cards/statuses', [CardController::class, 'getStatuses']);
Route::post('/projects/{project}/cards', [CardController::class, 'store'])->middleware('jwt.auth');
Route::put('/projects/{project}/cards/{card}', [CardController::class, 'update'])->middleware('jwt.auth');
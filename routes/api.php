<?php

use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\RewardController;
use Illuminate\Http\Request;
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

Route::post('/reward', [RewardController::class, 'store']);

Route::post('/project', [ProjectController::class, 'store']);
Route::put('/project', [ProjectController::class, 'updateObject']);
Route::get('/project/findByStatus', [ProjectController::class, 'findByStatus']);
Route::get('/project/{project}', [ProjectController::class, 'show']);
Route::post('/project/{project}', [ProjectController::class, 'update']);
Route::delete('/project/{project}', [ProjectController::class, 'destroy']);

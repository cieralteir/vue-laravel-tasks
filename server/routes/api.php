<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Files\FileController;
use App\Http\Controllers\Api\Tasks\TaskContoller;
use App\Http\Controllers\Api\Tasks\TaskFileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::name('auth.')->controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Display authenticated user
     */
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Tasks
    Route::name('tasks.')->prefix('tasks')->controller(TaskContoller::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{task}', 'show')->name('show')->can('manage', 'task');
        Route::put('/{task}', 'update')->name('update')->can('manage', 'task');
        Route::delete('/{task}', 'destroy')->name('destroy')->can('manage', 'task');
        Route::put('/{task}/complete', 'complete')->name('complete')->can('manage', 'task');
        Route::put('/{task}/uncomplete', 'uncomplete')->name('uncomplete')->can('manage', 'task');
        Route::put('/{task}/archive', 'archive')->name('archive')->can('manage', 'task');
        Route::put('/{task}/restore', 'restore')->name('restore')->can('manage', 'task');
    });

    Route::name('tasks.files')->prefix('tasks')->controller(TaskFileController::class)->group(function () {
        Route::post('/{task}/files', 'store')->name('store')->can('manage', 'task');
    });

    Route::name('files')->prefix('files')->controller(FileController::class)->group(function () {
        Route::get('/{file}', 'show')->name('show');
        Route::delete('/{file}', 'destroy')->name('destroy');
    });
});

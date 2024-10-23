<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
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

Route::get('/', function () {
    return response()->json(['status' => 'ChienTT OK']);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'create']);
Route::post('refresh_token', [AuthController::class, 'refreshToken'])->name('admin.refresh_token');
Route::get('active-user/{token}', [AuthController::class, 'activeAccount']);

Route::middleware('auth:api_admin')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::get('profile', [AuthController::class, 'profile'])->name('admin.profile');


    Route::group(['prefix' => 'transactions'], function () {
        Route::get('', [TransactionController::class, 'index']);
        Route::post('', [TransactionController::class, 'store']);
        Route::put('{id}', [TransactionController::class, 'update']);
        Route::get('get-all', [TransactionController::class, 'getAll']);
        Route::get('options', [TransactionController::class, 'getOptions']);
        Route::get('{id}', [TransactionController::class, 'show']);
        Route::delete('{id}', [TransactionController::class, 'destroy']);
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('', [CategoryController::class, 'index']);
        Route::post('', [CategoryController::class, 'store']);
        Route::put('{id}', [CategoryController::class, 'update']);
        Route::get('get-all', [CategoryController::class, 'getAll']);
        Route::get('tree', [CategoryController::class, 'getCategoryTree']);
        Route::get('options', [CategoryController::class, 'getCategoryOptions']);
    });

    Route::group(['prefix' => 'wallets'], function () {
        Route::get('', [WalletController::class, 'index']);
        Route::post('', [WalletController::class, 'store']);
        Route::put('{id}', [WalletController::class, 'update']);
        Route::get('get-all', [WalletController::class, 'getAll']);
        Route::get('options', [WalletController::class, 'getOptions']);
    });

    Route::group(['prefix' => 'tasks'], function () {
        Route::get('', [TaskController::class, 'index']);
        Route::post('', [TaskController::class, 'store']);
        Route::put('{id}', [TaskController::class, 'update']);
        Route::get('get-all', [TaskController::class, 'getAll']);
    });

    Route::get('report/finance', [ReportController::class, 'getDashBoard']);
});

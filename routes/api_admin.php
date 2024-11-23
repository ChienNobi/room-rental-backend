<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\LocationCityController;
use App\Http\Controllers\Admin\LocationDistrictController;
use App\Http\Controllers\Admin\LocationWardController;
use App\Http\Controllers\Admin\PostController;
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
    Route::get('cities', [LocationCityController::class, 'index']);
    Route::get('districts', [LocationDistrictController::class, 'index']);
    Route::get('wards', [LocationWardController::class, 'index']);

    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::get('profile', [AuthController::class, 'profile'])->name('admin.profile');

    Route::get('posts/{id}', [PostController::class, 'show']);
    Route::get('posts', [PostController::class, 'index'])->name('posts.get');
    Route::post('posts', [PostController::class, 'store'])->name('posts.create');
    Route::put('posts/{id}', [PostController::class, 'update'])->name('post.update');
    Route::delete('posts/{id}', [PostController::class, 'destroy']);
});

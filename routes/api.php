<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FiscalYearController;
use App\Http\Controllers\TaskController;
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

Route::get('/home', function () {
    return "yes";
});

Route::post('register', [AuthController::class, 'create']);
Route::post('login', [AuthController::class, 'login']);
Route::get('user', [CompanyController::class, 'user'])->middleware('auth:sanctum');
Route::post('change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
Route::put('change-user-password/{user}', [AuthController::class, 'changeUsersPassword'])->middleware('auth:sanctum');
Route::get('all-users', [AuthController::class, 'allUsers'])->middleware('auth:sanctum');
Route::post('/companies/{company}/renew', [CompanyController::class, 'renew'])->name('companies.renew');
// Route::get('/categories', [CategoryController::class, 'index']);

Route::post('copy-events', [EventController::class, 'copyEvents'])->middleware('auth:sanctum');
Route::post('copy-my-events', [EventController::class, 'copyMyEvents'])->middleware('auth:sanctum');
Route::post('copy-selected-events', [EventController::class, 'copySelectedEvents'])->middleware('auth:sanctum');
Route::post('dublicate-events', [EventController::class, 'dublicateEvents'])->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('fiscal-years', FiscalYearController::class);
    Route::get('active-year', [FiscalYearController::class, 'activeFiscalYear']);
    Route::put('order-fiscal-years', [FiscalYearController::class, 'orderFiscalYears']);

    Route::apiResource('categories', CategoryController::class);
    Route::put('order-categories', [CategoryController::class, 'orderCategory']);
    Route::apiResource('companies', CompanyController::class);
});
// Route::apiResource('categories', CategoryController::class)->middleware('auth:sanctum');
Route::apiResource('events', EventController::class)->middleware('auth:sanctum');
Route::apiResource('tasks', TaskController::class)->middleware('auth:sanctum');
// Route::apiResource('get-tasks/{event}/', [TaskController::class, 'getTasks'])->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

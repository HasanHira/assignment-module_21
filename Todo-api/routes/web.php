<?php

use App\Http\Controllers\TodoItemController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerifyMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function(Request $request){
//     return $request->user();
// });

// Authentication API Routes
Route::post('/user-registration', [UserController::class, 'UserRegistration']);
Route::post('/user-login', [UserController::class, 'UserLogin']);
Route::post('/send-otp', [UserController::class, 'SendOTPCode']);
Route::post('/verify-otp', [UserController::class, 'VerifyOTP']);

Route::post('/reset-password', [UserController::class, 'ResetPassword'])->middleware([TokenVerifyMiddleware::class]);


Route::post('/create-item', [TodoItemController::class, 'CreateTodoItem'])->middleware([TokenVerifyMiddleware::class]);
Route::get('/todo-list', [TodoItemController::class, 'ListTodoItems'])->middleware([TokenVerifyMiddleware::class]);
Route::post('/delete-item', [TodoItemController::class, 'DeleteTodoItem'])->middleware([TokenVerifyMiddleware::class]);
Route::post('/update-item', [TodoItemController::class, 'UpdateTodoItem'])->middleware([TokenVerifyMiddleware::class]);

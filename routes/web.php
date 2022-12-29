<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    
    return view('welcome');
})->name('home');
//================================= Manage User routes ===================================
Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
Route::get('/users', [AdminController::class, 'users'])->name('users');
Route::get('/add_users_show', [AdminController::class, 'add_users_show'])->name('add_users_show');
Route::post('/add_users' , [AdminController::class, 'add_users'])->name('add_users');
Route::get('/edit_user/{id}' , [AdminController::class, 'edit_user'])->name('edit_user');
Route::post('/update_user/{id}' , [AdminController::class, 'update_user'])->name('update_user');
Route::delete('/delete_user' , [AdminController::class, 'delete_user'])->name('delete_user');

//============================User Appointment Routes =======================================
Route::get('/user_appointment', [AdminController::class, 'user_appointment'])->name('user_appointment');
Route::get('/user_appointment_show', [AdminController::class, 'user_appointment_show'])->name('user_appointment_show');
Route::post('/add_appointment' , [AdminController::class, 'add_appointment'])->name('add_appointment');
Route::get('/edit_appointment/{id}' , [AdminController::class, 'edit_appointment'])->name('edit_appointment');
Route::post('/update_appointment/{id}' , [AdminController::class, 'update_appointment'])->name('update_appointment');
Route::delete('/delete_appointment' , [AdminController::class, 'delete_appointment'])->name('delete_appointment');

//============================User Appointment Added Itself  =======================================
// Route::get('/user_appointment_itself', [AdminController::class, 'user_appointment_itself'])->name('user_appointment_itself');
// Route::get('/user_appointment_itself_show', [AdminController::class, 'user_appointment_itself_show'])->name('user_appointment_itself_show');
// Route::post('/add_appointment_itself' , [AdminController::class, 'add_appointment_itself'])->name('add_appointment_itself');
// Route::get('/edit_appointment_itself/{id}' , [AdminController::class, 'edit_appointment_itself'])->name('edit_appointment_itself');
// Route::post('/update_appointment_itself/{id}' , [AdminController::class, 'update_appointment_itself'])->name('update_appointment_itself');
// Route::delete('/delete_appointment_itself' , [AdminController::class, 'delete_appointment_itself'])->name('delete_appointment_itself');

//============================Complete Appointment Added Itself  =======================================
Route::get('/complete_appointment', [AdminController::class, 'complete_appointment'])->name('complete_appointment');
Route::get('/user_appointment_details/{id}', [AdminController::class, 'user_appointment_details'])->name('user_appointment_details');
Route::delete('/delete_complete_appointment' , [AdminController::class, 'delete_complete_appointment'])->name('delete_complete_appointment');

//========================================= Qoute Routes =============================================
Route::get('/qoute', [AdminController::class, 'qoute'])->name('qoute');
Route::get('/qoute_details/{id}', [AdminController::class, 'qoute_details'])->name('qoute_details');
Route::get('/approved_qoute', [AdminController::class, 'approved_qoute'])->name('approved_qoute');
Route::get('/approved_qoute_details/{id}', [AdminController::class, 'approved_qoute_details'])->name('approved_qoute_details');
Route::get('/status', [AdminController::class, 'status'])->name('status');



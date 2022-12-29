<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//=================================Login Route =============================================
Route::post('/login',  [ApiController::class, 'login']);

//===============================admin_appointments Routes==================================
Route::get('admin_appointments', [ApiController::class, 'admin_appointments'])->name('admin_appointments');

//===============================upload_img Routes=====================================
Route::post('upload_img', [ApiController::class, 'upload_img'])->name('upload_img');

//=============================== Schedule api ========================
Route::post('scheduled', [ApiController::class, 'scheduled'])->name('scheduled');

//=============================== Schedule List Against User api ========================
Route::get('scheduled_list', [ApiController::class, 'scheduled_list'])->name('scheduled_list');

//===============================status change api ========================
Route::post('job_status', [ApiController::class, 'job_status'])->name('job_status');

//=============================== User Added Appointment api ========================
Route::post('user_appointment_itself', [ApiController::class, 'user_appointment_itself'])->name('user_appointment_itself');

//===============================admin_appointments Routes==================================
Route::get('complete_appointments', [ApiController::class, 'complete_appointments'])->name('complete_appointments'); 

//===========================Notification Against User Routes==================================
Route::get('notification', [ApiController::class, 'notification'])->name('notification'); 

//=============================== Qoute api =================================
Route::post('qoute', [ApiController::class, 'qoute'])->name('qoute');

//===============================Edit Qoute api =================================
Route::post('edit_qoute', [ApiController::class, 'edit_qoute'])->name('edit_qoute');

//=============================== Qouted List Against User api ========================
Route::get('qoute_list', [ApiController::class, 'qoute_list'])->name('qoute_list');

//=============================== Approved List Against User api ========================
Route::get('approved_list', [ApiController::class, 'approved_list'])->name('approved_list');

//=============================== Both List Against User api ========================
Route::get('both', [ApiController::class, 'both'])->name('both');
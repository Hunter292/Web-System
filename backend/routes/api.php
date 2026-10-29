<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ClientMailController;
use App\Http\Controllers\Api\IncomeController;
use App\Http\Controllers\Api\WorkDoneController;
use App\Http\Controllers\Api\StaffController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
require __DIR__ . '/auth.php';
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/work_done',[WorkDoneController::class,'get']);
    Route::post('/work_done',[WorkDoneController::class,'post']);
    Route::patch('/work_done/{work}',[WorkDoneController::class,'patch']);
    Route::delete('/work_done/{work}',[WorkDoneController::class,'delete']);

    Route::get('/report/client',[ClientController::class,'get']);
    Route::post('/report/client',[ClientController::class,'post']);
    Route::patch('/report/client/{client}',[ClientController::class,'patch']);
    Route::delete('/report/client/{client}',[ClientController::class,'delete']);
    Route::get('/report/client_work/{client}',[ClientController::class,'get_client_work_done']);
    Route::get('/report/client_rates',[ClientController::class,'get_client_rates']);

    Route::get('/report/staff',[StaffController::class,'get_staff_stats']);
    Route::get('/report/staff/{employee}',[StaffController::class,'get_staff_work_done']);

    Route::get('/report/income',[IncomeController::class,'get']);
    Route::post('/report/income/batch',[IncomeController::class,'post_batch']);
    Route::post('/report/income/single',[IncomeController::class,'post_single']);

    Route::patch('/report/income/{invoice}',[IncomeController::class,'patch']);
    Route::delete('/report/income/{invoice}',[IncomeController::class,'delete']);

    Route::get('/client_mail/{client}',[ClientMailController::class,'get']);
    Route::post('/client_mail/{client}',[ClientMailController::class,'post']);
});
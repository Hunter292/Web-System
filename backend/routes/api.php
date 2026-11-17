<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ClientMailController;
use App\Http\Controllers\Api\IncomeController;
use App\Http\Controllers\Api\MailerController;
use App\Http\Controllers\Api\WorkDoneController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskMembersController;
use App\Http\Controllers\Api\UserController;
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

    Route::get('/register/{employee?}',[UserController::class,'get']);
    Route::post('/register',[UserController::class,'post']);
    Route::patch('/register',[UserController::class,'patch_self']);
    Route::patch('/register/{employee}',[UserController::class,'patch']);
    Route::delete('/register/{employee}',[UserController::class,'delete']);

    Route::get('/task/to',[TaskController::class,'get_to_worker']);
    Route::get('/task/of',[TaskController::class,'get_of_worker']);
    Route::get('/task/new',[TaskController::class,'get_new']);
    Route::post('/task',[TaskController::class,'post']);
    Route::patch('/task/{task}',[TaskController::class,'patch']);
    Route::delete('/task/{task}',[TaskController::class,'delete']);

    Route::get('/task/members/to',[TaskMembersController::class,'get_to_worker']);
    Route::get('/task/members/of',[TaskMembersController::class,'get_of_worker']);
    Route::post('/task/members/{task}/{employee}',[TaskMembersController::class,'post']);
    Route::patch('/task/members/{task}/{employee}',[TaskMembersController::class,'patch']);
    Route::delete('/task/members/{task}/{employee}',[TaskMembersController::class,'delete']);

    Route::get('/mailer',[MailerController::class,'get_report']);
    Route::get('/mailer/emails',[MailerController::class,'get_report_emails']);

    Route::post('/mailer',[MailerController::class,'post']);

});
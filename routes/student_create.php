<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified','check_url_access'])->group(function () { 

   
    // Route::get('/test_payment', [PaymentController::class, 'test_payment'])->name('test_payment');
    Route::post('/save_student_track_number', [PaymentController::class, 'SaveStudentTrackNumber'])->name('save_student_track_number');
    Route::post('/username_autocomplete', [EmployeeController::class, 'UsernameAutocomplete'])->name('username_autocomplete');

});
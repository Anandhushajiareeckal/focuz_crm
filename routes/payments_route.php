<?php

use App\Http\Controllers\CourseInstallmentsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OfferLetterController;
use Illuminate\Support\Facades\Route;

//check_url_access
Route::middleware(['auth', 'verified', 'check_url_access'])->group(function () {
    Route::get('/payments_approve/{status?}', [PaymentController::class, 'PaymentApproveView'])->name('payments_approve');
    Route::post('/approve_payments', [PaymentController::class, 'ApprovePayments'])->name('approve_payments');
    Route::post('/reject_payments', [PaymentController::class, 'RejectPayments'])->name('reject_payments');
    Route::get('/view_promotions', [PaymentController::class, 'ViewPromotions'])->name('view_promotions');
    Route::post('/manage_promotions', [PaymentController::class, 'ManagePromotions'])->name('manage_promotions');
    Route::post('/save_promo', [PaymentController::class, 'SavePromo'])->name('save_promo');
    Route::get('/course_installments/{message?}/{installment_id?}', [CourseInstallmentsController::class, 'courseInstallments'])->name('course_installments');
    Route::post('/invoice_print', [PaymentController::class, 'InvoicePrint'])->name('invoice_print');
    Route::any('/test_payment', [PaymentController::class, 'test_payment'])->name('test_payment');
    Route::post('/manage_course_installment', [CourseInstallmentsController::class, 'manageCourseInstallment'])->name('manage_course_installment');
    Route::post('/save_installment', [CourseInstallmentsController::class, 'saveInstallment'])->name('save_installment');
    
    
  

// // Stream/download route (returns inline PDF)
Route::get('/offer_letter/download/{id}', [OfferLetterController::class, 'download'])
    ->name('offer_letter_download');


// Route::get('/offer_letter/view', [OfferLetterController::class, 'show'])
//     ->name('offer_letter_view');


});

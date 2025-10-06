<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\OfferLetterController;

use App\Http\Controllers\LocationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\StudentController;
use App\Models\Students;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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



// Route::post('/save_user_profile', [AdminContoller::class, 'SaveUserProfile'])->name('save_user_profile');
// Route::get('/approve_user/{id}/{approve}', [AdminContoller::class, 'ApproveUser'])->name('approve_user');
// Route::any('/managewebvideo', [AdminContoller::class, 'managewebvideo'])->name('managewebvideo');

Auth::routes(['verify' => true]);

Route::get('/access_denied', function () {
    return view('permissions.permission_denied');
})->name('access_denied');

Route::middleware(['auth', 'verified', 'check_url_access'])->group(function () {
    Route::get('/clear_cache', function () {
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:cache');
        Artisan::call('queue:clear');
        Artisan::call('optimize');
        Artisan::call('optimize:clear');
        return 'Cache cleared';
    });

    Route::get('/storage_link', function () {
        Artisan::call('storage:link');
        return 123;
    });

    Route::get('/', [HomeController::class, 'index'])->name('home_page');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::any('/add_students/{step?}/{student_id?}/{course_id_param?}/{course_schedule_id?}/{installment_id?}', [StudentController::class, 'add_students'])->name('add_students');
    // Route::any('/add_students', [StudentController::class, 'add_students'])->name('add_students');
    Route::any('/view_students/{search?}', [StudentController::class, 'view_students'])->name('view_students');
    Route::get('/view_profile/{step}/{student_id}', [StudentController::class, 'view_profile'])->name('view_profile');
    Route::post('/save_personal_info', [StudentController::class, 'savePersonalInfo'])->name('save_personal_info');
    Route::post('/save_edcation_info', [StudentController::class, 'saveEdcationInfo'])->name('save_edcation_info');
    Route::post('/save_payments_info', [PaymentController::class, 'savePaymentInfo'])->name('save_payments_info');
    Route::post('/clear_page_session', [HomeController::class, 'clearPageSession'])->name('clear_page_session');
    Route::post('/upload_student_docs', [DocumentsController::class, 'uploadStudentDocs'])->name('upload_student_docs');
    Route::post('/load_view_student_filter', [StudentController::class, 'loadViewStudentFilter'])->name('load_view_student_filter');
    Route::get('/view_employees', [EmployeeController::class, 'viewEmployees'])->name('view_employees');
    Route::post('/update_emp_status', [EmployeeController::class, 'updateEmpStatus'])->name('update_emp_status');
    Route::post('/export_students_excel', [StudentController::class, 'view_students'])->name('export_students_excel');
    Route::post('/get_graph_data', [HomeController::class, 'getStudentsAdmissionData'])->name('get_graph_data');

    Route::post('/search_students', [StudentController::class, 'searchStudents'])->name('search_students');
    Route::post('/update_emp_role', [EmployeeController::class, 'updateEmpRole'])->name('update_emp_role');
    
   

    Route::get('/documents', [DocumentsController::class, 'verify'])->name('documents.verify');
Route::post('/documents/update-status', [DocumentsController::class, 'updateStatus'])->name('documents.update-status');

Route::post('/students/{id}/update-document-status', [StudentController::class, 'updateDocumentVerificationStatus'])->name('students.updateDocumentVerificationStatus');




});

Route::post('/load_cities', [LocationController::class, 'load_cities'])->name('load_cities');
Route::post('/load_university', [LocationController::class, 'load_university'])->name('load_university');
Route::post('/load_states', [LocationController::class, 'load_states'])->name('load_states');
Route::post('/load_countries', [LocationController::class, 'load_countries'])->name('load_countries');
Route::post('/load_states_and_countries', [LocationController::class, 'load_states_and_countries'])->name('load_states_and_countries');



Route::post('/load_courses', [CourseController::class, 'load_courses'])->name('load_courses');
Route::post('/load_courses_period', [CourseController::class, 'load_courses_period'])->name('load_courses_period');
Route::post('/load_courses_payments', [CourseController::class, 'loadCoursePayments'])->name('load_courses_payments');


Route::post('/load_payment_method', [PaymentController::class, 'load_payment_method'])->name('load_payment_method');
Route::post('/load_promo_codes', [PaymentController::class, 'load_promo_codes'])->name('load_promo_codes');
Route::post('/load_card_types', [PaymentController::class, 'load_card_types'])->name('load_card_types');
Route::post('/load_banks', [PaymentController::class, 'load_banks'])->name('load_banks');
Route::get('/feature_not_avail', [PermissionController::class, 'feature_not_avail'])->name('feature_not_avail');



<?php

use App\Http\Controllers\CourseController;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::any('/view_courses/{course_id?}', [CourseController::class, 'ViewCourses'])->name('view_courses');
    Route::post('/manage_courses', [CourseController::class, 'ManageCourses'])->name('manage_courses');
    Route::post('/load_streams', [CourseController::class, 'LoadStreams'])->name('load_streams');
    Route::post('/load_specialization', [CourseController::class, 'LoadSpecialization'])->name('load_specialization');
    Route::post('/save_specialization', [CourseController::class, 'SaveSpecialization'])->name('save_specialization');
    Route::post('/get_course_schedule_form', [CourseController::class, 'GetCourseScheduleForm'])->name('get_course_schedule_form');
    Route::post('/save_course_data', [CourseController::class, 'saveCourseData'])->name('save_course_data');
    Route::post('/remove_course_schedule', [CourseController::class, 'removeCourseSchedule'])->name('remove_course_schedule');
    Route::post('/get_specialization_keys', [CourseController::class, 'getSpecializationKeys'])->name('get_specialization_keys');
    Route::post('/load_student_courses', [CourseController::class, 'loadStudentCourses'])->name('load_student_courses');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Admin\TestQuestionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseAdminController;
use App\Http\Controllers\Admin\TestimonialAdminController;
use App\Http\Controllers\Admin\ContactMessageAdminController;

// ── Public Routes ──
Route::prefix('v1')->group(function () {

    // Courses
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{course}', [CourseController::class, 'show']);

    // Testimonials (عرض عام بس)
    Route::get('/testimonials', [TestimonialController::class, 'index']);

    // Contact
    Route::post('/contact', [ContactController::class, 'store']);

    // Test (اختبار تحديد المستوى)
    Route::get('/test/questions', [TestController::class, 'questions']);
    Route::post('/test/submit', [TestController::class, 'submit']);

    // Schedules
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::get('/schedules/{id}', [ScheduleController::class, 'show']);

    // Bookings
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{reference}', [BookingController::class, 'show']);
    Route::patch('/bookings/{reference}/cancel', [BookingController::class, 'cancel']);
    Route::post('/bookings/{booking}/receipt', [BookingController::class, 'uploadReceipt']);

    // ── Auth (المستخدمين العاديين + جوجل) ──
    Route::prefix('auth')->group(function () {
        Route::post('/register',          [AuthController::class, 'register']);
        Route::post('/login',             [AuthController::class, 'login']);
        Route::post('/logout',            [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('/me',                 [AuthController::class, 'me'])->middleware('auth:sanctum');
        Route::get('/google/redirect',    [AuthController::class, 'redirectToGoogle']);
        Route::get('/google/callback',    [AuthController::class, 'handleGoogleCallback']);
    });

    // Routes محمية (لازم تسجيل دخول)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
    });
});

// ── Admin Routes ──
Route::prefix('v1/admin')->group(function () {
    // متاح من غير تسجيل دخول (ده أصلاً بوابة الدخول)
    Route::post('/login', [AdminController::class, 'login']);

    // كل حاجة تحت كده لازم تسجيل دخول بـ token + يكون is_admin = true
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {

        // Dashboard (المستخدمين + كورساتهم + حجوزاتهم + نتيجة اختبارهم)
        Route::get('/dashboard/users', [DashboardController::class, 'users']);
        Route::put('/dashboard/users/{user}', [DashboardController::class, 'update']);
        Route::delete('/dashboard/users/{user}', [DashboardController::class, 'destroy']);

        // Schedules CRUD
        Route::get('/schedules', [ScheduleController::class, 'adminIndex']);
        Route::post('/schedules', [ScheduleController::class, 'store']);
        Route::put('/schedules/{id}', [ScheduleController::class, 'update']);
        Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);
        Route::get('/schedules/trashed', [ScheduleController::class, 'trashedIndex']);
        Route::post('/schedules/{id}/restore', [ScheduleController::class, 'restore']);

        // Bookings management
        Route::get('/bookings', [BookingController::class, 'adminIndex']);
        Route::patch('/bookings/{id}/confirm', [BookingController::class, 'confirm']);
        Route::patch('/bookings/{id}/cancel', [BookingController::class, 'adminCancel']);
        Route::patch('/bookings/{id}/restore', [BookingController::class, 'restore']);

        // Test Questions CRUD (إدارة أسئلة اختبار تحديد المستوى)
        Route::apiResource('test-questions', TestQuestionController::class)->except(['show']);

        // Courses CRUD (إدارة الكورسات)
        Route::apiResource('courses', CourseAdminController::class)->except(['show']);

        // Testimonials CRUD (إدارة آراء الطلاب)
        Route::get('/testimonials', [TestimonialAdminController::class, 'index']);
        Route::post('/testimonials', [TestimonialAdminController::class, 'store']);
        Route::delete('/testimonials/{testimonial}', [TestimonialAdminController::class, 'destroy']);

        // Contact Messages (إدارة رسائل التواصل)
        Route::get('/messages', [ContactMessageAdminController::class, 'index']);
        Route::patch('/messages/{message}/read', [ContactMessageAdminController::class, 'markRead']);
        Route::delete('/messages/{message}', [ContactMessageAdminController::class, 'destroy']);
    });
});

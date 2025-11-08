<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\EdutechController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;

// Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Produk Routes
Route::prefix('produk')->group(function () {
    Route::get('/consultant', [ConsultantController::class, 'index'])->name('consultant.index');
    Route::get('/consultant/{category}', [ConsultantController::class, 'show'])->name('consultant.show');
    Route::post('/consultant/inquiry', [ConsultantController::class, 'submitInquiry'])->name('consultant.inquiry');
    Route::get('/developer', [DeveloperController::class, 'index'])->name('developer.index');
    Route::get('/edutech', [EdutechController::class, 'index'])->name('edutech.index');
});

// Tentang Kami Routes
Route::prefix('tentang-kami')->group(function () {
    Route::get('/profil', [AboutController::class, 'profile'])->name('about.profile');
    Route::get('/struktur-organisasi', [AboutController::class, 'organization'])->name('about.organization');
});

// Blog Routes (Public)
Route::prefix('blog')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('blog.show');
});

// Mitra
Route::get('/mitra', [PartnerController::class, 'index'])->name('partner.index');

// Contact Us
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// ========================================
// EDUTECH SYSTEM
// ========================================

use App\Http\Controllers\Edutech\AuthController as EdutechAuthController;
use App\Http\Controllers\Edutech\LandingController as EdutechLandingController;
use App\Http\Controllers\Edutech\CourseController as EdutechCourseController;
use App\Http\Controllers\Edutech\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Edutech\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Edutech\Admin\DashboardController as EdutechAdminController;
use App\Http\Controllers\Edutech\Student\MyCourseController as StudentMyCourseController;
use App\Http\Controllers\Edutech\Student\CertificateController as StudentCertificateController;
use App\Http\Controllers\Edutech\Student\LearningController;
use App\Http\Controllers\Edutech\Instructor\CourseManagementController;
use App\Http\Controllers\Edutech\Instructor\QuizManagementController;
use App\Http\Controllers\Edutech\Student\StudentQuizController;

// ========================================
// EDUTECH AUTH ROUTES (Public - No Auth Required)
// ========================================
Route::prefix('edutech')->name('edutech.')->group(function () {
    Route::get('/login', [EdutechAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [EdutechAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [EdutechAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [EdutechAuthController::class, 'register'])->name('register.post');
    Route::post('/logout', [EdutechAuthController::class, 'logout'])->name('logout');
});

// ========================================
// EDUTECH PUBLIC ROUTES (No Auth Required)
// ========================================
Route::prefix('edutech')->name('edutech.')->group(function () {
    // Landing & Browse
    Route::get('/', [EdutechLandingController::class, 'index'])->name('landing');
    Route::get('/courses', [EdutechLandingController::class, 'courses'])->name('courses.index');
    
    // Course Detail - PENTING: Pakai CourseController bukan LandingController
    Route::get('/courses/{slug}', [EdutechCourseController::class, 'show'])->name('courses.detail');
});

// ========================================
// EDUTECH PROTECTED ROUTES (Requires Auth)
// ========================================
Route::prefix('edutech')->name('edutech.')->middleware('edutech.auth')->group(function () {
    // Enrollment - POST method, pakai slug
    Route::post('/courses/{slug}/enroll', [EdutechCourseController::class, 'enroll'])->name('courses.enroll');
    
    // Learning Page - GET method, pakai slug
    Route::get('/courses/{slug}/learn', [LearningController::class, 'show'])->name('courses.learn');

    Route::post('/learning/lesson/{lesson}/complete', [LearningController::class, 'completeLesson'])->name('learning.complete');
    Route::post('/learning/lesson/{lesson}/progress', [LearningController::class, 'updateProgress'])->name('learning.progress');
    Route::get('/learning/lesson/{lesson}/next', [LearningController::class, 'nextLesson'])->name('learning.next');
});

// ========================================
// STUDENT DASHBOARD ROUTES
// ========================================
Route::prefix('edutech/student')->name('edutech.student.')->middleware('edutech.student')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-courses', [StudentMyCourseController::class, 'index'])->name('my-courses');
    Route::get('/certificates', [StudentCertificateController::class, 'index'])->name('certificates');
    Route::get('/certificate/{id}/download', [StudentCertificateController::class, 'download'])->name('certificate.download');

    // Quiz Routes
    Route::get('/quiz/{quiz}/start', [StudentQuizController::class, 'start'])->name('quiz.start');
    Route::post('/quiz/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/{quiz}/result/{attempt}', [StudentQuizController::class, 'result'])->name('quiz.result');
    Route::get('/quiz/history', [StudentQuizController::class, 'history'])->name('quiz.history');
});

// ========================================
// INSTRUCTOR DASHBOARD ROUTES
// ========================================
Route::prefix('edutech/instructor')->name('edutech.instructor.')->middleware('edutech.instructor')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
    
    // My Courses - List View
    Route::get('/courses', [CourseManagementController::class, 'index'])->name('courses');
    
    // Create Course
    Route::get('/courses/create', [CourseManagementController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseManagementController::class, 'store'])->name('courses.store');
    
    // Edit Course
    Route::get('/courses/{id}/edit', [CourseManagementController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{id}', [CourseManagementController::class, 'update'])->name('courses.update');
    
    // Delete Course
    Route::delete('/courses/{id}', [CourseManagementController::class, 'destroy'])->name('courses.destroy');
    
    // Toggle Publish Course
    Route::post('/courses/{id}/publish', [CourseManagementController::class, 'togglePublish'])->name('courses.publish');
    
    // Module Management
    Route::post('/courses/{course}/modules', [CourseManagementController::class, 'storeModule'])->name('modules.store');
    Route::put('/courses/{course}/modules/{module}', [CourseManagementController::class, 'updateModule'])->name('modules.update');
    Route::delete('/courses/{course}/modules/{module}', [CourseManagementController::class, 'destroyModule'])->name('modules.destroy');
    
    // Lesson Management
    Route::post('/courses/{course}/modules/{module}/lessons', [CourseManagementController::class, 'storeLesson'])->name('lessons.store');
    Route::put('/courses/{course}/modules/{module}/lessons/{lesson}', [CourseManagementController::class, 'updateLesson'])->name('lessons.update');
    Route::delete('/courses/{course}/modules/{module}/lessons/{lesson}', [CourseManagementController::class, 'destroyLesson'])->name('lessons.destroy');
    
    // My Students - New Route (needs controller)
    Route::get('/students', [InstructorDashboardController::class, 'students'])->name('students');

    // Quiz Management
    Route::get('/quiz', [QuizManagementController::class, 'index'])->name('quiz.index');
    Route::get('/quiz/create', [QuizManagementController::class, 'create'])->name('quiz.create');
    Route::post('/quiz', [QuizManagementController::class, 'store'])->name('quiz.store');
    Route::get('/quiz/{id}/edit', [QuizManagementController::class, 'edit'])->name('quiz.edit');
    Route::put('/quiz/{id}', [QuizManagementController::class, 'update'])->name('quiz.update');
    Route::delete('/quiz/{id}', [QuizManagementController::class, 'destroy'])->name('quiz.destroy');
    Route::post('/quiz/{id}/toggle', [QuizManagementController::class, 'toggleActive'])->name('quiz.toggle');
    
    // Question Management
    Route::post('/quiz/{quiz}/questions', [QuizManagementController::class, 'storeQuestion'])->name('quiz.questions.store');
    Route::put('/quiz/{quiz}/questions/{question}', [QuizManagementController::class, 'updateQuestion'])->name('quiz.questions.update');
    Route::delete('/quiz/{quiz}/questions/{question}', [QuizManagementController::class, 'destroyQuestion'])->name('quiz.questions.destroy');
});

// ========================================
// ADMIN DASHBOARD ROUTES
// ========================================
Route::prefix('edutech/admin')->name('edutech.admin.')->middleware('edutech.admin')->group(function () {
    Route::get('/dashboard', [EdutechAdminController::class, 'index'])->name('dashboard');
    Route::get('/courses', [EdutechAdminController::class, 'courses'])->name('courses');
    Route::get('/courses/create', [EdutechAdminController::class, 'createCourse'])->name('courses.create');
    Route::post('/courses', [EdutechAdminController::class, 'storeCourse'])->name('courses.store');
    Route::get('/instructors', [EdutechAdminController::class, 'instructors'])->name('instructors');
    Route::post('/instructors', [EdutechAdminController::class, 'storeInstructor'])->name('instructors.store');
    Route::get('/students', [EdutechAdminController::class, 'students'])->name('students');
    Route::get('/enrollments', [EdutechAdminController::class, 'enrollments'])->name('enrollments');
    Route::post('/enrollments/{id}/approve', [EdutechAdminController::class, 'approveEnrollment'])->name('enrollments.approve');
});

// ========================================
// BLOG ADMIN SYSTEM
// ========================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/register', [AdminAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('register.post');
});

Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::get('/articles', [AdminArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [AdminArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [AdminArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [AdminArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [AdminArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [AdminArticleController::class, 'destroy'])->name('articles.destroy');
    Route::post('/articles/{article}/toggle-publish', [AdminArticleController::class, 'togglePublish'])->name('articles.toggle-publish');
});


// ========================================
// PAYMENT ROUTES (Add this to routes/web.php)
// ========================================

use App\Http\Controllers\Edutech\PaymentController;

Route::prefix('edutech')->name('edutech.')->middleware('edutech.auth')->group(function () {
    // Payment Pages
    Route::get('/payment/{enrollment}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{enrollment}/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/{enrollment}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{enrollment}/failed', [PaymentController::class, 'failed'])->name('payment.failed');
    Route::get('/payment/{enrollment}/status', [PaymentController::class, 'checkStatus'])->name('payment.status');
});

// Midtrans Webhook (No auth - untuk menerima notifikasi dari Midtrans)
Route::post('/edutech/payment/notification', [PaymentController::class, 'notification'])->name('edutech.payment.notification');
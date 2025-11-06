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
    // KIM Consultant
    Route::get('/consultant', [ConsultantController::class, 'index'])->name('consultant.index');
    Route::get('/consultant/{category}', [ConsultantController::class, 'show'])->name('consultant.show');
    Route::post('/consultant/inquiry', [ConsultantController::class, 'submitInquiry'])->name('consultant.inquiry');

    // KIM Developer
    Route::get('/developer', [DeveloperController::class, 'index'])->name('developer.index');

    // KIM Edutech - OVERVIEW PAGE (Marketing/Info)
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
use App\Http\Controllers\Edutech\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Edutech\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Edutech\Admin\DashboardController as EdutechAdminController;
use App\Http\Controllers\Edutech\Student\MyCourseController as StudentMyCourseController;
use App\Http\Controllers\Edutech\Student\CertificateController as StudentCertificateController;

// Edutech Landing (Public)
Route::prefix('edutech')->name('edutech.')->group(function () {
    Route::get('/', [EdutechLandingController::class, 'index'])->name('landing');
    Route::get('/courses', [EdutechLandingController::class, 'courses'])->name('courses.index');
    Route::get('/courses/{slug}', [EdutechLandingController::class, 'courseDetail'])->name('courses.detail');

        // Learning (Protected - must be enrolled)
    Route::get('/course/{slug}/learn', [EdutechCourseController::class, 'learn'])->name('course.learn');
    
    // Enrollment (Protected - must be logged in)
    Route::post('/course/{id}/enroll', [EdutechCourseController::class, 'enroll'])
        ->middleware('edutech.auth')
        ->name('course.enroll');
});

// Edutech Auth (Public - no middleware)
Route::prefix('edutech')->name('edutech.')->group(function () {
    Route::get('/login', [EdutechAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [EdutechAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [EdutechAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [EdutechAuthController::class, 'register'])->name('register.post');
    Route::post('/logout', [EdutechAuthController::class, 'logout'])->name('logout');
});

// Admin Routes
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

// Instructor Routes
Route::prefix('edutech/instructor')->name('edutech.instructor.')->middleware('edutech.instructor')->group(function () {
    Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/courses', [InstructorDashboardController::class, 'myCourses'])->name('courses');
    Route::get('/courses/create', [InstructorDashboardController::class, 'createCourse'])->name('courses.create');
    Route::post('/courses', [InstructorDashboardController::class, 'storeCourse'])->name('courses.store');
    Route::post('/courses/{id}/publish', [InstructorDashboardController::class, 'publishCourse'])->name('courses.publish');
    Route::get('/students', [InstructorDashboardController::class, 'myStudents'])->name('students');
});

// Student Routes  
Route::prefix('edutech/student')->name('edutech.student.')->middleware('edutech.student')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // My Courses
    Route::get('/my-courses', [StudentMyCourseController::class, 'index'])->name('my-courses');
    
    // Certificates
    Route::get('/certificates', [StudentCertificateController::class, 'index'])->name('certificates');
    Route::get('/certificate/{id}/download', [StudentCertificateController::class, 'download'])->name('certificate.download');
});

// ========================================
// BLOG ADMIN SYSTEM (Existing - Tetap Separate)
// ========================================

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Register (optional - only accessible when logged in)
    Route::get('/register', [AdminAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('register.post');
});

// Admin Routes (Protected with middleware)
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    // Articles CRUD
    Route::get('/articles', [AdminArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [AdminArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [AdminArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [AdminArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [AdminArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [AdminArticleController::class, 'destroy'])->name('articles.destroy');
    Route::post('/articles/{article}/toggle-publish', [AdminArticleController::class, 'togglePublish'])->name('articles.toggle-publish');
});

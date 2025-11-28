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
    Route::get('/developer/detail', [DeveloperController::class, 'show'])->name('developer.show');
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
use App\Http\Controllers\Edutech\ProfileController;

use App\Http\Controllers\Edutech\Admin\DashboardController as EdutechAdminController;

use App\Http\Controllers\Edutech\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Edutech\Instructor\CourseManagementController;
use App\Http\Controllers\Edutech\Instructor\QuizManagementController;
use App\Http\Controllers\Edutech\Instructor\LiveMeetingController;
use App\Http\Controllers\Edutech\Instructor\StudentManagementController;
use App\Http\Controllers\Edutech\Instructor\BatchManagementController;
use App\Http\Controllers\Edutech\Instructor\AttendanceController;

use App\Http\Controllers\Edutech\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Edutech\Student\MyCourseController as StudentMyCourseController;
use App\Http\Controllers\Edutech\Student\CertificateController as StudentCertificateController;
use App\Http\Controllers\Edutech\Student\LearningController;
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

    // Verifikasi Email Routes
    Route::get('/verification/notice', [EdutechAuthController::class, 'verificationNotice'])->name('verification.notice');
    Route::get('/verify/{token}', [EdutechAuthController::class, 'verify'])->name('verify');
    Route::post('/verification/resend', [EdutechAuthController::class, 'resendVerification'])->name('verification.resend');

    Route::get('/profile', [App\Http\Controllers\Edutech\ProfileController::class, 'index'])
        ->name('profile.index');

    Route::put('/profile', [App\Http\Controllers\Edutech\ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/password', [App\Http\Controllers\Edutech\ProfileController::class, 'updatePassword'])
        ->name('profile.password');

    Route::delete('/profile/avatar', [App\Http\Controllers\Edutech\ProfileController::class, 'deleteAvatar'])
        ->name('profile.avatar.delete');
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

    // Student Management
    Route::get('/students', [StudentManagementController::class, 'index'])->name('students');
    Route::get('/students/course/{course}', [StudentManagementController::class, 'courseStudents'])->name('students.course');
    Route::get('/students/detail/{student}', [StudentManagementController::class, 'studentDetail'])->name('students.detail');

    // Student & Attendance Management
    Route::get('/students/course/{course}', [StudentManagementController::class, 'courseStudents'])->name('students.course');
    Route::get('/students/course/{course}/attendance/{batch?}', [StudentManagementController::class, 'attendance'])->name('students.attendance');
    Route::post('/students/course/{course}/attendance/{batch}', [StudentManagementController::class, 'storeAttendance'])->name('students.attendance.store');

    // Edit Attendance
    Route::get('/students/course/{course}/attendance/{batch}/meeting/{meeting}/edit', [StudentManagementController::class, 'editAttendance'])->name('students.attendance.edit');
    Route::put('/students/course/{course}/attendance/{batch}/meeting/{meeting}', [StudentManagementController::class, 'updateAttendance'])->name('students.attendance.update');

    // Attendance Reports
    Route::get('/students/course/{course}/attendance/{batch}/report', [StudentManagementController::class, 'attendanceReport'])->name('students.attendance.report');
    Route::get('/students/course/{course}/attendance/{batch}/download/{meeting?}', [StudentManagementController::class, 'downloadAttendanceReport'])->name('students.attendance.download');


    // Batch Management
    Route::get('/courses/{course}/batches', [BatchManagementController::class, 'index'])->name('batches.index');
    Route::get('/courses/{course}/batches/create', [BatchManagementController::class, 'create'])->name('batches.create');
    Route::post('/courses/{course}/batches', [BatchManagementController::class, 'store'])->name('batches.store');
    Route::get('/courses/{course}/batches/{batch}/edit', [BatchManagementController::class, 'edit'])->name('batches.edit');
    Route::put('/courses/{course}/batches/{batch}', [BatchManagementController::class, 'update'])->name('batches.update');
    Route::delete('/courses/{course}/batches/{batch}', [BatchManagementController::class, 'destroy'])->name('batches.destroy');

    Route::post('/students/course/{course}/assign-batch', [StudentManagementController::class, 'assignBatch'])->name('students.assign-batch');


    // Quiz Management
    Route::get('/quiz', [QuizManagementController::class, 'index'])->name('quiz.index');
    Route::get('/quiz/create', [QuizManagementController::class, 'create'])->name('quiz.create');
    Route::post('/quiz', [QuizManagementController::class, 'store'])->name('quiz.store');
    Route::get('/quiz/{id}/edit', [QuizManagementController::class, 'edit'])->name('quiz.edit');
    Route::put('/quiz/{id}', [QuizManagementController::class, 'update'])->name('quiz.update');
    Route::delete('/quiz/{id}', [QuizManagementController::class, 'destroy'])->name('quiz.destroy');
    Route::post('/quiz/{id}/toggle', [QuizManagementController::class, 'toggleActive'])->name('quiz.toggle');
    Route::post('/instructor/quiz/{quiz}/sync/{target}', [QuizManagementController::class, 'syncQuestions'])->name('quiz.sync');

    // Question Management
    Route::post('/quiz/{quiz}/questions', [QuizManagementController::class, 'storeQuestion'])->name('quiz.questions.store');
    Route::put('/quiz/{quiz}/questions/{question}', [QuizManagementController::class, 'updateQuestion'])->name('quiz.questions.update');
    Route::delete('/quiz/{quiz}/questions/{question}', [QuizManagementController::class, 'destroyQuestion'])->name('quiz.questions.destroy');

    // Live Meeting
    Route::get('/live-meetings', [LiveMeetingController::class, 'index'])->name('live-meetings.index');
    Route::get('/live-meetings/create', [LiveMeetingController::class, 'create'])->name('live-meetings.create');
    Route::post('/live-meetings', [LiveMeetingController::class, 'store'])->name('live-meetings.store');
    Route::get('/live-meetings/{session}/edit', [LiveMeetingController::class, 'edit'])->name('live-meetings.edit');
    Route::put('/live-meetings/{session}', [LiveMeetingController::class, 'update'])->name('live-meetings.update');
    Route::delete('/live-meetings/{session}', [LiveMeetingController::class, 'destroy'])->name('live-meetings.destroy');
});

use App\Http\Controllers\Edutech\Admin\UsersController;
use App\Http\Controllers\Edutech\Admin\CoursesController;
use App\Http\Controllers\Edutech\Admin\EnrollmentsController;
use App\Http\Controllers\Edutech\Admin\CertificatesController;
use App\Http\Controllers\Edutech\Admin\SettingsController;

// ========================================
// ADMIN DASHBOARD ROUTES
// ========================================
Route::prefix('edutech/admin')->name('edutech.admin.')->middleware('edutech.admin')->group(function () {

    // ===== DASHBOARD =====
    Route::get('/dashboard', [EdutechAdminController::class, 'index'])->name('dashboard');

    // ===== USERS MANAGEMENT =====
    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::post('/users/{id}/promote', [UsersController::class, 'promote'])->name('users.promote');
    Route::post('/users/{id}/toggle', [UsersController::class, 'toggleStatus'])->name('users.toggle');
    Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');

    // ===== COURSES MANAGEMENT =====
    Route::get('/courses', [CoursesController::class, 'index'])->name('courses');
    Route::get('/courses/{id}', [CoursesController::class, 'show'])->name('courses.show');
    Route::get('/courses/{id}/edit', [CoursesController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{id}', [CoursesController::class, 'update'])->name('courses.update');
    Route::post('/courses/{id}/toggle', [CoursesController::class, 'togglePublish'])->name('courses.toggle');
    Route::delete('/courses/{id}', [CoursesController::class, 'destroy'])->name('courses.destroy');

    // ===== ENROLLMENTS MANAGEMENT =====
    Route::get('/enrollments', [EnrollmentsController::class, 'index'])->name('enrollments');
    Route::get('/enrollments/{id}', [EnrollmentsController::class, 'show'])->name('enrollments.show');
    Route::post('/enrollments/{id}/approve', [EnrollmentsController::class, 'approve'])->name('enrollments.approve');
    Route::post('/enrollments/{id}/reject', [EnrollmentsController::class, 'reject'])->name('enrollments.reject');
    Route::delete('/enrollments/{id}', [EnrollmentsController::class, 'destroy'])->name('enrollments.destroy');

    // ===== CERTIFICATES MANAGEMENT =====
    Route::get('/certificates', [CertificatesController::class, 'index'])->name('certificates');
    Route::get('/certificates/{id}', [CertificatesController::class, 'show'])->name('certificates.show');
    Route::post('/certificates/issue/{enrollmentId}', [CertificatesController::class, 'issue'])->name('certificates.issue');
    Route::post('/certificates/{id}/revoke', [CertificatesController::class, 'revoke'])->name('certificates.revoke');
    Route::get('/certificates/{id}/download', [CertificatesController::class, 'download'])->name('certificates.download');
    Route::get('/certificates/verify', [CertificatesController::class, 'verify'])->name('certificates.verify');

    // ===== SETTINGS =====
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    Route::post('/settings/maintenance', [SettingsController::class, 'maintenance'])->name('settings.maintenance');
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


use App\Http\Controllers\DigitalController;
use App\Http\Controllers\DigitalPaymentController;
use App\Http\Controllers\QuestionnaireController;

// ========================================
// KIM DIGITAL - PUBLIC ROUTES
// ========================================

Route::prefix('produk/digital')->name('digital.')->group(function () {

    // Landing Page
    Route::get('/', [DigitalController::class, 'index'])->name('index');

    // Product Catalog
    Route::get('/catalog', [DigitalController::class, 'catalog'])->name('catalog');

    // Product Detail
    Route::get('/product/{slug}', [DigitalController::class, 'show'])->name('show');

    // Shopping Cart
    Route::get('/cart', [DigitalController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{id}', [DigitalController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [DigitalController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/clear', [DigitalController::class, 'clearCart'])->name('cart.clear');

    // Checkout
    Route::get('/checkout', [DigitalController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [DigitalPaymentController::class, 'processCheckout'])->name('checkout.process');

    // Payment
    Route::get('/payment/{orderNumber}', [DigitalPaymentController::class, 'show'])->name('payment.show');
    Route::get('/payment/success/{orderNumber}', [DigitalPaymentController::class, 'success'])->name('payment.success');
    Route::post('/payment/notification', [DigitalPaymentController::class, 'notification'])->name('payment.notification');

    Route::post('/payment/confirm/{orderNumber}', [DigitalPaymentController::class, 'confirmPayment'])->name('payment.confirm');

    // Questionnaire
    Route::get('/questionnaire/{orderNumber}', [QuestionnaireController::class, 'show'])->name('questionnaire.show');
    Route::post('/questionnaire/submit/{responseId}', [QuestionnaireController::class, 'submit'])->name('questionnaire.submit');
    Route::get('/questionnaire/result/{responseId}', [QuestionnaireController::class, 'downloadResult'])->name('questionnaire.download');
});


use App\Http\Controllers\Admin\DigitalDashboardController as DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\QuestionnaireManagementController;
use App\Http\Controllers\Auth\AuthController;

// Admin Auth Routes (No middleware)
Route::prefix('admin/digital')->name('admin.digital.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Routes - Protected by admin middleware
Route::prefix('admin/digital')->name('admin.digital.')->middleware(['admin'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', ProductController::class);
    
    // Questionnaires
    Route::resource('questionnaires', QuestionnaireManagementController::class);
    Route::post('/questionnaires/{id}/dimensions', [QuestionnaireManagementController::class, 'addDimension'])->name('questionnaires.addDimension');
    Route::put('/dimensions/{id}', [QuestionnaireManagementController::class, 'updateDimension'])->name('questionnaires.updateDimension');
    Route::delete('/dimensions/{id}', [QuestionnaireManagementController::class, 'deleteDimension'])->name('questionnaires.deleteDimension');
    Route::post('/dimensions/{id}/ranges', [QuestionnaireManagementController::class, 'addRange'])->name('questionnaires.addRange');
    Route::delete('/ranges/{id}', [QuestionnaireManagementController::class, 'deleteRange'])->name('questionnaires.deleteRange');
    Route::post('/questionnaires/{id}/questions', [QuestionnaireManagementController::class, 'addQuestion'])->name('questionnaires.addQuestion');
    Route::delete('/questions/{id}', [QuestionnaireManagementController::class, 'deleteQuestion'])->name('questionnaires.deleteQuestion');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');



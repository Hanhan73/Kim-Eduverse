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
Route::prefix('edutech/student')->name('edutech.student.')->middleware('role:student')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-courses', [StudentMyCourseController::class, 'index'])->name('my-courses');
    Route::get('/certificates', [StudentCertificateController::class, 'index'])->name('certificates');
    Route::get('/certificate/{id}/download', [StudentCertificateController::class, 'download'])->name('certificate.download');
    // Learning Page (BARU - satu halaman untuk semua)
    Route::get('/courses/{slug}/learn', [LearningController::class, 'show'])->name('courses.learn');
    Route::post('/learning/lesson/{lesson}/complete', [LearningController::class, 'completeLesson'])->name('learning.complete');
    Route::get('/learning/lesson/{lesson}/next', [LearningController::class, 'nextLesson'])->name('learning.next');

    // Quiz Routes (Update)
    Route::post('/quiz/{quiz}/start', [StudentQuizController::class, 'start'])->name('quiz.start');
    Route::post('/quiz/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('quiz.submit');
});

// ========================================
// INSTRUCTOR DASHBOARD ROUTES
// ========================================
Route::prefix('edutech/instructor')->name('edutech.instructor.')->middleware('role:instruktor')->group(function () {
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
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/', [QuizManagementController::class, 'index'])->name('index');
        Route::get('/create', [QuizManagementController::class, 'create'])->name('create');
        Route::post('/', [QuizManagementController::class, 'store'])->name('store');
        Route::get('/{quiz}/edit', [QuizManagementController::class, 'edit'])->name('edit');
        Route::put('/{quiz}', [QuizManagementController::class, 'update'])->name('update');
        Route::delete('/{quiz}', [QuizManagementController::class, 'destroy'])->name('destroy');
        Route::post('/{quiz}/toggle', [QuizManagementController::class, 'toggleActive'])->name('toggle');

        Route::post('/{quiz}/sync/{targetType}', [QuizManagementController::class, 'syncQuestions'])->name('sync');

        // Questions
        Route::post('/{quiz}/questions', [QuizManagementController::class, 'storeQuestion'])->name('questions.store');
        Route::put('/{quiz}/questions/{question}', [QuizManagementController::class, 'updateQuestion'])->name('questions.update');
        Route::delete('/{quiz}/questions/{question}', [QuizManagementController::class, 'destroyQuestion'])->name('questions.destroy');
    });

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
// EDUTECH ADMIN DASHBOARD ROUTES
// ========================================
// <-- MODIFIED: Added 'role:super_admin' to middleware
Route::prefix('edutech/admin')->name('edutech.admin.')->middleware(['role:edutech_admin,super_admin'])->group(function () {
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

// <-- MODIFIED: Added 'role:super_admin' to middleware
Route::prefix('admin')->name('admin.')->middleware(['role:blog_admin, super_admin'])->group(function () {
    Route::get('/articles', [AdminArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [AdminArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [AdminArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [AdminArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [AdminArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [AdminArticleController::class, 'destroy'])->name('articles.destroy');
    Route::post('/articles/{article}/toggle-publish', [AdminArticleController::class, 'togglePublish'])->name('articles.toggle-publish');
});

// ========================================
// PAYMENT ROUTES
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
Route::post('/edutech/payment/notification', [PaymentController::class, 'notification'])->middleware('web-no-csrf')->name('edutech.payment.notification');

use App\Http\Controllers\DigitalController;
use App\Http\Controllers\DigitalPaymentController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\Admin\SeminarController as AdminSeminarController;

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
    Route::post('/payment/notification', [DigitalPaymentController::class, 'notification'])->middleware('web-no-csrf')->name('payment.notification');

    Route::post('/payment/confirm/{orderNumber}', [DigitalPaymentController::class, 'confirmPayment'])->name('payment.confirm');

    Route::prefix('seminar')->name('seminar.')->group(function () {
        Route::get('/{orderNumber}', [SeminarController::class, 'learn'])->name('learn');

        // Quiz
        Route::post('/{orderNumber}/quiz/{quizType}/start', [SeminarController::class, 'startQuiz'])->name('quiz.start');
        Route::post('/{orderNumber}/quiz/{quizType}/submit', [SeminarController::class, 'submitQuiz'])->name('quiz.submit');

        // Material
        Route::post('/{orderNumber}/material/viewed', [SeminarController::class, 'markMaterialViewed'])->name('material.viewed');
        Route::get('/{orderNumber}/material/download', [SeminarController::class, 'downloadMaterial'])->name('download.material');

        // Certificate
        Route::get('/certificate/{enrollmentId}/download', [SeminarController::class, 'downloadCertificate'])->name('certificate.download');
        Route::post('/{orderNumber}/save-name', [SeminarController::class, 'saveParticipantName'])->name('save-name');
    });

    // Questionnaire
    Route::get('/questionnaire/{orderNumber}', [QuestionnaireController::class, 'show'])->name('questionnaire.show');
    Route::post('/questionnaire/submit/{responseId}', [QuestionnaireController::class, 'submit'])->name('questionnaire.submit');
    Route::get('/questionnaire/result/{responseId}', [QuestionnaireController::class, 'downloadResult'])->name('questionnaire.download');

    // Download Product File (untuk ebook, template, dll)
    Route::get('/download/{orderNumber}/{productId}', [DigitalPaymentController::class, 'downloadProduct'])
        ->name('product.download');

    // Download Invoice PDF
    Route::get('/invoice/{orderNumber}', [DigitalPaymentController::class, 'downloadInvoice'])
        ->name('invoice.download');
});

use App\Http\Controllers\LandingPageController;

Route::get('/promo/{slug}', [\App\Http\Controllers\LandingPageController::class, 'show'])->name('digital.landing');

use App\Http\Controllers\Admin\DigitalDashboardController as DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\QuestionnaireManagementController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DimensionController;
use App\Http\Controllers\Admin\QuestionnaireResponseController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\LandingPageController as DigitalLandingPageController;

// Admin Auth Routes (No middleware)
Route::prefix('admin/digital')->name('admin.digital.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// <-- MODIFIED: Added 'role:super_admin' to middleware
Route::prefix('admin/digital')->name('admin.digital.')->middleware(['role:digital_admin,super_admin'])->group(function () {
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

    // Dimensions (CRUD)
    Route::resource('dimensions', DimensionController::class);

    // Score Range Management
    Route::post('/dimensions/{id}/score-ranges', [DimensionController::class, 'addScoreRange'])
        ->name('dimensions.add-range');

    Route::put('/score-ranges/{id}', [DimensionController::class, 'updateScoreRange'])
        ->name('score-ranges.update');

    Route::delete('/score-ranges/{id}', [DimensionController::class, 'deleteScoreRange'])
        ->name('score-ranges.destroy');

    // Generate default ranges
    Route::post('/dimensions/{id}/generate-ranges', [DimensionController::class, 'generateDefaultRanges'])
        ->name('dimensions.generate-ranges');

    // Migrate legacy interpretations
    Route::post('/dimensions/{id}/migrate-interpretations', [DimensionController::class, 'migrateInterpretations'])
        ->name('dimensions.migrate-interpretations');

    // Reorder ranges (AJAX)
    Route::post('/dimensions/{id}/reorder-ranges', [DimensionController::class, 'reorderScoreRanges'])
        ->name('dimensions.reorder-ranges');

    // Questions (CRUD)
    Route::resource('questions', QuestionController::class);

    // Response (CRUD)
    Route::resource('responses', QuestionnaireResponseController::class);
    Route::post('responses/{id}/resend', [QuestionnaireResponseController::class, 'resend'])
        ->name('responses.resend');
    Route::post('responses/{id}/regenerate', [QuestionnaireResponseController::class, 'regenerate'])
        ->name('responses.regenerate');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Regenerate AI Analysis untuk response tertentu
    Route::post('responses/{id}/regenerate-ai', [QuestionnaireResponseController::class, 'regenerateAI'])
        ->name('responses.regenerate-ai');

    // Bulk regenerate AI untuk multiple responses
    Route::post('responses/bulk-regenerate-ai', [QuestionnaireResponseController::class, 'bulkRegenerateAI'])
        ->name('responses.bulk-regenerate-ai');

    // Preview AI analysis (tanpa save)
    Route::post('responses/{id}/preview-ai', [QuestionnaireResponseController::class, 'previewAI'])
        ->name('responses.preview-ai');

    // Test AI configuration
    Route::post('questionnaires/{id}/test-ai', [QuestionController::class, 'testAI'])
        ->name('questionnaires.test-ai');

    // Route untuk mengelola Quiz
    Route::get('quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');

    // Route untuk mengelola Pertanyaan
    Route::post('quizzes/{quiz}/questions', [QuizController::class, 'storeQuestion'])->name('quizzes.store-question');
    Route::get('quizzes/{quiz}/questions/{question}/edit', [QuizController::class, 'editQuestion'])->name('quizzes.edit-question');
    Route::put('quizzes/{quiz}/questions/{question}', [QuizController::class, 'updateQuestion'])->name('quizzes.update-question');
    Route::delete('quizzes/{quiz}/questions/{question}', [QuizController::class, 'destroyQuestion'])->name('quizzes.destroy-question');

    // Route untuk mengatur ulang urutan pertanyaan
    Route::post('quizzes/{quiz}/reorder-questions', [QuizController::class, 'reorderQuestions'])->name('quizzes.reorder-questions');
    Route::prefix('seminars')->name('seminars.')->group(function () {
        Route::get('/', [AdminSeminarController::class, 'index'])->name('index');
        Route::get('/create', [AdminSeminarController::class, 'create'])->name('create');
        Route::post('/', [AdminSeminarController::class, 'store'])->name('store');
        Route::get('/{seminar}', [AdminSeminarController::class, 'show'])->name('show');
        Route::get('/{seminar}/edit', [AdminSeminarController::class, 'edit'])->name('edit');
        Route::put('/{seminar}', [AdminSeminarController::class, 'update'])->name('update');
        Route::delete('/{seminar}', [AdminSeminarController::class, 'destroy'])->name('destroy');

        // Toggle Actions
        Route::patch('/{seminar}/toggle-active', [AdminSeminarController::class, 'toggleActive'])->name('toggle-active');
        Route::patch('/{seminar}/toggle-featured', [AdminSeminarController::class, 'toggleFeatured'])->name('toggle-featured');

        // Enrollments
        Route::get('/{seminar}/enrollments', [AdminSeminarController::class, 'enrollments'])->name('enrollments');

        Route::post('/quizzes', [AdminSeminarController::class, 'storeQuiz'])->name('quizzes.store');
    });

    // Landing Pages Management (di dalam group admin)
    Route::prefix('landing-pages')->name('landing-pages.')->group(function () {
        Route::get('/', [DigitalLandingPageController::class, 'index'])->name('index');
        Route::get('/{product}/edit', [DigitalLandingPageController::class, 'edit'])->name('edit');
        Route::put('/{product}', [DigitalLandingPageController::class, 'update'])->name('update');
        Route::delete('/{product}', [DigitalLandingPageController::class, 'destroy'])->name('destroy');
        Route::get('/{product}/preview', [DigitalLandingPageController::class, 'preview'])->name('preview');
    });
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

use App\Http\Controllers\TestClaudeController;

Route::prefix('test-claude')->name('test.claude.')->group(function () {
    Route::get('/config', [TestClaudeController::class, 'testConfig'])->name('config');
    Route::get('/simple', [TestClaudeController::class, 'testSimpleCall'])->name('simple');
    Route::get('/analysis', [TestClaudeController::class, 'testFullAnalysis'])->name('analysis');
    Route::get('/logs', [TestClaudeController::class, 'viewLogs'])->name('logs');
});

use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\BendaharaController;
use App\Http\Controllers\Instructor\InstructorController;

/*
|--------------------------------------------------------------------------
| Admin Routes - Unified Backend
|--------------------------------------------------------------------------
*/

// <-- MODIFIED: Added middleware for Super Admin
Route::prefix('admin/super-admin')->name('admin.super-admin.')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

    // User Management
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::get('/users/create', [SuperAdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [SuperAdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [SuperAdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [SuperAdminController::class, 'deleteUser'])->name('users.destroy');

    // Revenue Management
    Route::get('/revenue', [SuperAdminController::class, 'revenue'])->name('revenue');

    // Instructor Management
    Route::get('/instructors', [SuperAdminController::class, 'instructors'])->name('instructors');
    Route::get('/instructors/{instructor}', [SuperAdminController::class, 'instructorDetail'])->name('instructors.detail');

    // Withdrawal Management
    Route::get('/withdrawals', [SuperAdminController::class, 'withdrawals'])->name('withdrawals');

    // Settings
    Route::get('/settings', [SuperAdminController::class, 'settings'])->name('settings');
});

// <-- MODIFIED: Added middleware for Bendahara & Super Admin
Route::prefix('admin/bendahara')->name('admin.bendahara.')->middleware(['auth', 'role:bendahara,super_admin'])->group(function () {
    Route::get('/dashboard', [BendaharaController::class, 'dashboard'])->name('dashboard');

    // Revenue / Pemasukan
    Route::get('/revenue', [BendaharaController::class, 'revenue'])->name('revenue');

    // Instructor Earnings
    Route::get('/instructor-earnings', [BendaharaController::class, 'instructorEarnings'])->name('instructor-earnings');
    Route::get('/instructor-earnings/{instructorId}', [BendaharaController::class, 'instructorEarningDetail'])->name('instructor-earnings.detail');

    // Withdrawal Management
    Route::get('/withdrawals', [BendaharaController::class, 'withdrawals'])->name('withdrawals');
    Route::get('/withdrawals/{withdrawal}', [BendaharaController::class, 'withdrawalDetail'])->name('withdrawals.detail');
    Route::post('/withdrawals/{withdrawal}/approve', [BendaharaController::class, 'approveWithdrawal'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [BendaharaController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
    Route::post('/withdrawals/{withdrawal}/complete', [BendaharaController::class, 'completeWithdrawal'])->name('withdrawals.complete');

    // Reports
    Route::get('/reports', [BendaharaController::class, 'reports'])->name('reports');
});

// <-- MODIFIED: Added middleware for Instructor & Super Admin
Route::prefix('instructor')->name('instructor.')->middleware(['auth', 'role:instruktor, role:super_admin'])->group(function () {
    Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('dashboard');

    // Earnings
    Route::get('/earnings', [InstructorController::class, 'earnings'])->name('earnings');

    // Withdrawals
    Route::get('/withdrawals', [InstructorController::class, 'withdrawals'])->name('withdrawals');
    Route::post('/withdrawals/request', [InstructorController::class, 'requestWithdrawal'])->name('withdrawals.request');
    Route::delete('/withdrawals/{withdrawal}/cancel', [InstructorController::class, 'cancelWithdrawal'])->name('withdrawals.cancel');

    // Bank Accounts
    Route::get('/bank-accounts', [InstructorController::class, 'bankAccounts'])->name('bank-accounts');
    Route::post('/bank-accounts', [InstructorController::class, 'storeBankAccount'])->name('bank-accounts.store');
    Route::put('/bank-accounts/{bankAccount}', [InstructorController::class, 'updateBankAccount'])->name('bank-accounts.update');
    Route::delete('/bank-accounts/{bankAccount}', [InstructorController::class, 'deleteBankAccount'])->name('bank-accounts.delete');

    // My Courses
    Route::get('/courses', [InstructorController::class, 'courses'])->name('courses');
});

// Admin Dashboard Redirector - redirect based on role
Route::get('/admin', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->isSuperAdmin()) {
        return redirect()->route('admin.super-admin.dashboard');
    }

    if ($user->isBendahara()) {
        return redirect()->route('admin.bendahara.dashboard');
    }

    if ($user->isInstruktor()) {
        return redirect()->route('instructor.dashboard');
    }

    if ($user->isAdminBlog()) {
        return redirect()->route('admin.articles.index'); // Adjusted to a valid route
    }

    // Default untuk student atau role lain
    return redirect()->route('home');
})->middleware('auth')->name('admin');

use App\Http\Controllers\Auth\UnifiedLoginController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [UnifiedLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UnifiedLoginController::class, 'login'])->name('login.post');
Route::post('/logout', [UnifiedLoginController::class, 'logout'])->name('logout');
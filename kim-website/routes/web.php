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
    
    // KIM Edutech
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

// Admin Auth Routes (Public - no middleware)
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
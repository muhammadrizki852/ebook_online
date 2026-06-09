<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ManualBookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserAvatarController;
use App\Models\Ebook;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EbookController as AdminEbookController;
use App\Http\Controllers\Admin\ManualBookController as AdminManualBookController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TransactionActivityController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Authentication & User Management ---
Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login');
        Route::get('/register', 'showRegister')->name('register');
        Route::post('/register', 'register');

        Route::prefix('two-factor-challenge')->name('two-factor.')->group(function () {
            Route::get('/', 'showTwoFactorChallenge')->name('challenge');
            Route::post('/', 'verifyTwoFactorChallenge')->name('verify');
            Route::post('/resend', 'resendTwoFactorCode')->name('resend');
        });
    });

    Route::post('/logout', 'logout')->middleware('auth')->name('logout');

    // Google OAuth
    Route::get('/auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('/auth/google/callback', 'handleGoogleCallback');
});

// --- Public Assets & Media ---
Route::get('/users/{user}/avatar', [UserAvatarController::class, 'show'])->name('users.avatar');

Route::get('/storage/{path}', function (string $path) {
    $path = ltrim(str_replace('\\', '/', $path), '/');

    if (str_contains($path, '..') || ! Storage::disk('public')->exists($path)) {
        abort(404);
    }

    return response()->file(Storage::disk('public')->path($path));
})->where('path', '.*')->name('storage.public');


// --- Public Catalog & Home ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/manual-book', [ManualBookController::class, 'show'])->name('manual-book.show');
Route::get('/manual-book/file', [ManualBookController::class, 'file'])->name('manual-book.file');
Route::get('/manual-book/download', [ManualBookController::class, 'download'])->name('manual-book.download');

Route::controller(EbookController::class)->prefix('ebooks')->name('ebooks.')->group(function () {
    Route::get('/{ebook:slug}', 'show')->name('show');
    Route::get('/{ebook:slug}/read-pdf', 'readPdf')->name('read-pdf');
    Route::get('/{ebook:slug}/download-pdf', 'downloadPdf')->name('download-pdf');
});

// --- Payments & Purchases ---
Route::controller(PurchaseController::class)->group(function () {
    Route::post('/midtrans/notification', 'midtransNotification')->name('midtrans.notification');
    Route::get('/midtrans/finish', 'midtransFinish')->name('midtrans.finish');
});

// --- Authenticated User Space ---
Route::middleware('auth')->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::controller(LibraryController::class)->prefix('library')->name('library')->group(function () {
        Route::get('/', 'index');
        Route::get('/download/{ebook}', 'download')->name('.download');
    });

    Route::controller(PurchaseController::class)->prefix('purchase')->name('purchase.')->group(function () {
        Route::get('/{ebook}', 'create')->name('create');
        Route::post('/{ebook}', 'store')->name('store');
        Route::get('/{ebook:slug}/status', 'status')->name('status');
        Route::post('/{ebook:slug}/quick', 'quickStore')->name('quick-store');
        Route::post('/{ebook:slug}/midtrans', 'createMidtransTransaction')->name('midtrans');
        Route::post('/{ebook:slug}/midtrans/complete', 'completeMidtransTransaction')->name('midtrans.complete');
        Route::get('/invoice/{purchase}', 'invoice')->name('invoice');
    });

    Route::controller(FavoriteController::class)->prefix('favorites')->name('favorites.')->group(function () {
        Route::post('/{ebook:slug}', 'store')->name('store');
        Route::delete('/{ebook:slug}', 'destroy')->name('destroy');
    });
});

// --- Admin Panel ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('ebooks', AdminEbookController::class)->except(['show']);
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/report', [PaymentController::class, 'report'])->name('payments.report');
    Route::get('/payments/report/export', [PaymentController::class, 'exportReport'])->name('payments.export');
    Route::get('/payments/report/export-excel', [PaymentController::class, 'exportReportExcel'])->name('payments.export-excel');
    Route::get('/payments/invoice/{purchase}', [PaymentController::class, 'invoice'])->name('payments.invoice');
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/users', [ReportController::class, 'users'])->name('users');
        Route::get('/users/export', [ReportController::class, 'exportUsers'])->name('users.export');
        Route::get('/users/export-excel', [ReportController::class, 'exportUsersExcel'])->name('users.export-excel');
        Route::get('/ebooks', [ReportController::class, 'ebooks'])->name('ebooks');
        Route::get('/ebooks/export', [ReportController::class, 'exportEbooks'])->name('ebooks.export');
        Route::get('/ebooks/export-excel', [ReportController::class, 'exportEbooksExcel'])->name('ebooks.export-excel');
        Route::get('/transactions', [ReportController::class, 'transactions'])->name('transactions');
        Route::get('/transactions/export', [ReportController::class, 'exportTransactions'])->name('transactions.export');
        Route::get('/transactions/export-excel', [ReportController::class, 'exportTransactionsExcel'])->name('transactions.export-excel');
    });
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/manual-book', [AdminManualBookController::class, 'index'])->name('manual-books.index');
    Route::post('/manual-book', [AdminManualBookController::class, 'store'])->name('manual-books.store');
    Route::delete('/manual-book/{manualBook}', [AdminManualBookController::class, 'destroy'])->name('manual-books.destroy');

    // Transaction Activities are generated automatically from purchases.
    Route::resource('transaction-activities', TransactionActivityController::class)->only(['index', 'show']);
});

<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MyReviewController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get("/", [HomeController::class, 'index'])->name('home');
Route::get("/book/{id}", [HomeController::class, 'detail'])->name('home.detail');
Route::post('/save-book-review', [HomeController::class, 'saveReview'])->name('book.saveReview');

Route::group(['prefix' => 'account'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('register', [AccountController::class, 'register'])->name('account.register');
        Route::post('process-register', [AccountController::class, 'processRegister'])->name('account.process-register');
        Route::get('login', [AccountController::class, 'login'])->name('account.login');
        Route::post('process-login', [AccountController::class, 'processLogin'])->name('account.process-login');
    });
    Route::group(['middleware' => 'auth'], function () {
        Route::get('profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::get('logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::post('update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');

        Route::get('/books', [BookController::class, 'index'])->name('books.list');
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books/store', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/edit/{id}', [BookController::class, 'edit'])->name('books.edit');
        Route::post('/books/update/{id}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/books/delete', [BookController::class, 'destroy'])->name('books.destroy');

        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.list');
        Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/reviews/store', [ReviewController::class, 'store'])->name('reviews.store');
        Route::get('/reviews/edit/{id}', [ReviewController::class, 'edit'])->name('reviews.edit');
        Route::post('/reviews/update/{id}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/delete', [ReviewController::class, 'destroy'])->name('reviews.destroy');

        Route::get('/my_reviews',[MyReviewController::class,'index'])->name('my_reviews.list');
        Route::delete('/my_reviews/delete', [MyReviewController::class, 'destroy'])->name('my_reviews.destroy');
    });
});

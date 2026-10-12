<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localize', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function() {
    Route::get('/', function () {
        return view('welcome');
    })
    ->name('home');

    Route::get('about', function () {
        return view('about');
    })
    ->name('about');

    Route::get('contact', function ($id) {
        //
    });

    Route::group(['prefix' => 'collections', 'as' => 'collections.'], function() {
        Route::get('{slug}', function (string $slug) {
            return view('products.collection', compact('slug'));
        })->name('category');

        Route::get('fragrance', []);
    });

    Route::group(['prefix' => 'products', 'as' => 'products.'], function() {
        Route::get('{product}', [ProductController::class, 'show'])->name('detail');
    });

    Route::get('cart', function ($id) {
        //
    });

    Route::post('checkout', [CheckoutController::class, 'index'])
        ->middleware(['auth', 'should-have-carts'])
        ->name('checkout');

    Route::view('login', 'auth.login', ['authMode' => 'login'])
        ->middleware('guest')
        ->name('login');

    Route::view('register', 'auth.login', ['authMode' => 'register'])
        ->middleware('guest')
        ->name('register');

    Route::get('account', [AccountController::class, 'index'])
        ->middleware('auth')
        ->name('account');
});

Route::post('payments/midtrans/notification', [CheckoutController::class, 'midtransNotification'])
    ->name('payments.midtrans.notification');

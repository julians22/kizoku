<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{

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


    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function() {
        Route::get('account', );
    });

    Route::get('cart', function ($id) {
        //
    });

    Route::get('checkout', function ($id) {
        //
    });

});

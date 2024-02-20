<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use \App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\AccueilController::class, 'index'])->name('accueil.index');

Route::get('/contact', function () {
    return view('contact');
})->name('contact.index');

Route::get('/boutiques', function () {
    return view('boutiques');
})->name('boutiques.index');

Route::prefix('admin')->name('admin.')->controller(AdminController::class)->group(function () {
    Route::get('/', 'index')->name('index')->middleware('auth', 'authorized');

    Route::prefix("/order")->name("order.")->group(function () {
        Route::delete("/{id}", "deleteOrder")->name("delete")->middleware('auth', 'authorized');
    });

    Route::prefix('/product')->name('product.')->group(function () {
        Route::get('/create', 'createProduct')->name('create')->middleware('auth', 'authorized');
        Route::get('/edit/{id}', 'editProduct')->name('edit')->middleware('auth', 'authorized');
        Route::post('/save', 'doSaveProduct')->name('save')->middleware('auth', 'authorized');
    });
});

Route::prefix('user')->name('user.')->controller(UserController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'doLogin')->name('login');

    Route::get('register', 'register')->name('register');
    Route::post('register', 'doRegister')->name('register');

    Route::post('logout', 'doLogout')->name('logout')->middleware('auth');

    Route::get('profile', 'profile')->name('profile')->middleware('auth');
    Route::put('profile', 'update')->name('profile')->middleware('auth');

    Route::delete('profile', 'delete')->name('profile')->middleware('auth');
});

Route::prefix('product')->name('product.')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{product}', 'show')->name('show');
    Route::delete("/{product}", "delete")->name('delete');
});

Route::prefix('cart')->name('cart.')->controller(CartController::class)->group(function () {
    Route::post('/add', 'add')->name('add')->middleware("auth");
    Route::post('/remove', 'remove')->name('remove')->middleware("auth");
    Route::post('/delete', 'delete')->name('delete')->middleware("auth");
});

Route::prefix('order')->name('order.')->controller(OrderController::class)->group(function () {
    Route::get('/payment', 'payment')->name('payment');
    Route::post('/payment', 'doPayment')->name('payment');
});

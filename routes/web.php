<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('home');
Route::post('/profile/save', [App\Http\Controllers\HomeController::class, 'save'])->name('profile.save');

CompanyController::routes();
CustomerController::routes();
ProductController::routes();
OrderController::routes();
UserController::routes();

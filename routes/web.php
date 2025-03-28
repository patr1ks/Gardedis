<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\RestaurantController;
use App\Models\User;
use App\Http\Controllers\User\UserController;
use App\Models\Category;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\AdminUserController;
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

Route::get('/', [UserController::class, 'index'])->name('user.home');
Route::get('/restaurant/{title}', [UserController::class, 'restaurant'])->name('user.restaurant');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//admin routs
Route::group(['prefix' => 'admin', 'middleware' => 'redirectAdmin'], function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function() {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::delete('/restaurants/destroy/{id}', [RestaurantController::class, 'destroy'])->name('admin.restaurants.destroy');

    Route::get('/events', [EventController::class, 'index'])->name('admin.events.index');
    Route::post('/events/store', [EventController::class, 'store'])->name('admin.events.store');
    Route::delete('/events/image/{id}', [EventController::class, 'deleteImage'])->name('admin.events.image.delete');
    Route::delete('/events/destroy/{id}', [EventController::class, 'destroy'])->name('admin.events.destroy');

    Route::get('/forms', [FormController::class, 'index'])->name('admin.forms.index');
    Route::delete('/forms/destroy/{id}', [FormController::class, 'destroy'])->name('admin.forms.destroy');

    Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
    Route::delete('/payments/destroy/{id}', [PaymentController::class, 'destroy'])->name('admin.payments.destroy');
    
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/users/store', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::delete('/users/destroy/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // restaurant routes
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('admin.restaurants.index');
    Route::post('/restaurants/store', [RestaurantController::class, 'store'])->name('admin.restaurants.store');
    Route::put('/restaurants/update/{id}', [RestaurantController::class, 'update'])->name('admin.restaurants.update');
    Route::delete('/restaurants/image/{id}', [RestaurantController::class, 'deleteImage'])->name('admin.restaurants.image.delete');
    Route::delete('/restaurants/destroy/{id}', [RestaurantController::class, 'destroy'])->name('admin.restaurants.destroy');

});



//end

require __DIR__.'/auth.php';

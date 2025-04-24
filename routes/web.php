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
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Restaurant\RestaurantAuthController;
use App\Http\Controllers\Restaurant\RestaurantOwnerController;
use App\Http\Controllers\Restaurant\MenuController;
use App\Http\Controllers\Restaurant\OwnerRestaurantController;
use App\Http\Controllers\Restaurant\OwnerLayoutController;

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
Route::get('/restaurant/{id}', [UserController::class, 'restaurant'])->name('user.restaurant');
Route::get('/events', [UserController::class, 'events'])->name('user.events');
Route::get('/event/{id}', [UserController::class, 'event'])->name('user.event');
Route::get('/restaurants', [UserController::class, 'restaurants'])->name('user.restaurants');
Route::get('/application', [UserController::class, 'application'])->name('user.application');
Route::post('/application/store', [UserController::class, 'storeApplication'])->name('user.application.storeApplication');
Route::get('/contacts', [UserController::class, 'contacts'])->name('user.contacts');
Route::post('/contacts/store', [UserController::class, 'storeContacts'])->name('user.contacts.storeContacts');



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
    Route::delete('/categories/destroy/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::get('/categories/show-data/{id}', [CategoryController::class, 'showData'])->name('admin.categories.showData');

    Route::get('/events', [EventController::class, 'index'])->name('admin.events.index');
    Route::post('/events/store', [EventController::class, 'store'])->name('admin.events.store');
    Route::delete('/events/image/{id}', [EventController::class, 'deleteImage'])->name('admin.events.image.delete');
    Route::delete('/events/destroy/{id}', [EventController::class, 'destroy'])->name('admin.events.destroy');
    Route::get('/events/show-data/{id}', [EventController::class, 'showData'])->name('admin.events.showData');

    Route::get('/forms', [FormController::class, 'index'])->name('admin.forms.index');
    Route::delete('/forms/destroy/{id}', [FormController::class, 'destroy'])->name('admin.forms.destroy');
    Route::get('/forms/show-data/{id}', [FormController::class, 'showData'])->name('admin.forms.showData');

    Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
    Route::delete('/payments/destroy/{id}', [PaymentController::class, 'destroy'])->name('admin.payments.destroy');
    Route::get('/payments/show-data/{id}', [PaymentController::class, 'showData'])->name('admin.payments.showData');
    
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/users/store', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::delete('/users/destroy/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/users/show-data/{id}', [AdminUserController::class, 'showData'])->name('admin.users.showData');

    Route::get('/contacts', [ContactController::class, 'index'])->name('admin.contacts.index');
    Route::delete('/contacts/destroy/{id}', [ContactController::class, 'destroy'])->name('admin.contacts.destroy');
    Route::get('/contacts/show-data/{id}', [ContactController::class, 'showData'])->name('admin.contacts.showData');

    // restaurant routes
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('admin.restaurants.index');
    Route::post('/restaurants/store', [RestaurantController::class, 'store'])->name('admin.restaurants.store');
    Route::put('/restaurants/update/{id}', [RestaurantController::class, 'update'])->name('admin.restaurants.update');
    Route::delete('/restaurants/image/{id}', [RestaurantController::class, 'deleteImage'])->name('admin.restaurants.image.delete');
    Route::delete('/restaurants/destroy/{id}', [RestaurantController::class, 'destroy'])->name('admin.restaurants.destroy');
    Route::get('/restaurants/show-data/{id}', [RestaurantController::class, 'showData'])->name('admin.restaurants.showData');

});
//end

// restaurant owner routes

Route::group(['prefix' => 'restaurant-owner', 'middleware' => 'redirectRestaurant'], function () {
    Route::get('/login', [RestaurantAuthController::class, 'showLoginForm'])->name('restaurantOwner.login');
    Route::post('/login', [RestaurantAuthController::class, 'login'])->name('restaurantOwner.login.post');
    Route::post('/logout', [RestaurantAuthController::class, 'logout'])->name('restaurantOwner.logout');
});

Route::middleware(['auth', 'restaurant-owner'])->prefix('restaurant-owner')->group(function() {
    Route::get('/dashboard', [RestaurantOwnerController::class, 'index'])->name('restaurantOwner.dashboard');

    Route::get('/menu', [MenuController::class, 'index'])->name('restaurantOwner.menu.index');
    Route::post('/menu/store', [MenuController::class, 'store'])->name('restaurantOwner.menu.store');
    Route::delete('/menu/destroy/{id}', [MenuController::class, 'destroy'])->name('restaurantOwner.menu.destroy');
    Route::get('/menu/show-data/{id}', [MenuController::class, 'showData'])->name('restaurantOwner.menu.showData');

    Route::get('/restaurant', [OwnerRestaurantController::class, 'index'])->name('restaurantOwner.restaurant.index');
    Route::post('/restaurant/store', [OwnerRestaurantController::class, 'store'])->name('restaurantOwner.restaurant.store');
    Route::put('/restaurant/update/{id}', [OwnerRestaurantController::class, 'update'])->name('restaurantOwner.restaurant.update');
    Route::delete('/restaurant/image/{id}', [OwnerRestaurantController::class, 'deleteImage'])->name('restaurantOwner.restaurant.image.delete');
    Route::delete('/restaurant/destroy/{id}', [OwnerRestaurantController::class, 'destroy'])->name('restaurantOwner.restaurant.destroy');
    Route::get('/restaurant/show-data/{id}', [OwnerRestaurantController::class, 'showData'])->name('restaurantOwner.restaurant.showData');

    Route::get('/layout', [OwnerLayoutController::class, 'index'])->name('restaurantOwner.layout.index');
    Route::get('/layout/{id}', [OwnerLayoutController::class, 'show'])->name('restaurantOwner.layout.show');
    Route::post('/layout/store', [OwnerLayoutController::class, 'store'])->name('restaurantOwner.layout.store');
});



// end

require __DIR__.'/auth.php';

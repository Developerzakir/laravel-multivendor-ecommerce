<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendorController;
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



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//Admin Route
Route::middleware(['auth','role:admin'])->group(function(){

    Route::controller(AdminController::class)->group(function () {
        Route::get('admin/dashboard', 'adminDashboard')->name('admin.dashboard'); 
        Route::get('admin/logout', 'adminLogout')->name('admin.logout'); 
        Route::get('admin/profile', 'adminProfile')->name('admin.profile'); 
        Route::post('/admin/profile/store', 'adminProfileStore')->name('admin.profile.store');
        Route::get('/admin/change/password', 'adminChangePassword')->name('admin.change.password');
        Route::post('/admin/update/password', 'AdminUpdatePassword')->name('admin.update.password');
    });

});

//admin login route
Route::get('admin/login', [AdminController::class, 'adminLogin']); 

//vendor login route
Route::get('/vendor/login', [VendorController::class, 'vendorLogin']);

//Vendor Route
Route::middleware(['auth','role:vendor'])->group(function(){
    Route::controller(VendorController::class)->group(function () {
        Route::get('vendor/dashboard', 'vendorDashboard')->name('vendor.dashboard');
        Route::get('/vendor/logout', 'vendorDestroy')->name('vendor.logout');
        Route::get('/vendor/profile', 'vendorProfile')->name('vendor.profile');
        Route::post('/vendor/profile/store', 'vendorProfileStore')->name('vendor.profile.store');
        Route::get('/vendor/change/password', 'vendorChangePassword')->name('vendor.change.password');
        Route::post('/vendor/update/password', 'vendorUpdatePassword')->name('vendor.update.password');
   });
});


// Frontend Route all 
Route::get('/', [FrontEndController::class,'index'])->name('front.index');

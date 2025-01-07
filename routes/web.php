<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\VendorProductController;

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



// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth'])->group(function() {

    Route::controller(UserController::class)->group(function () {
        Route::get('/dashboard', 'userDashboard')->name('dashboard');
        Route::post('/user/profile/store','userProfileStore')->name('user.profile.store');
        Route::post('/user/change/password','userChangePassword')->name('user.change.password');
        Route::get('/user/logout','userLogout')->name('user.logout');
    });
}); // Gorup Milldeware End

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

     // Brand All Route 
        Route::controller(BrandController::class)->group(function(){
            Route::get('/all/brand' , 'allBrand')->name('all.brand');
            Route::get('/add/brand' , 'addBrand')->name('add.brand');
            Route::post('/store/brand' , 'storeBrand')->name('store.brand');
            Route::get('/edit/brand/{id}' , 'editBrand')->name('edit.brand');
            Route::post('/update/brand' , 'updateBrand')->name('update.brand');
            Route::get('/delete/brand/{id}' , 'deleteBrand')->name('delete.brand');
        });

     // Category All Route 
        Route::controller(CategoryController::class)->group(function(){
            Route::get('/all/category' , 'allCategory')->name('all.category');
            Route::get('/add/category' , 'addCategory')->name('add.category');
            Route::post('/store/category' , 'storeCategory')->name('store.category');
            Route::get('/edit/category/{id}' , 'editCategory')->name('edit.category');
            Route::post('/update/category' , 'updateCategory')->name('update.category');
            Route::get('/delete/category/{id}' , 'deleteCategory')->name('delete.category');
        });

     // Sub Category All Route 
        Route::controller(SubCategoryController::class)->group(function(){
            Route::get('/all/subcategory' , 'allSubCategory')->name('all.subcategory');
            Route::get('/add/subcategory' , 'addSubcategory')->name('add.subcategory');
            Route::post('/store/subcategory' , 'storeSubcategory')->name('store.subcategory');
            Route::get('/edit/subcategory/{id}' , 'editSubCategory')->name('edit.subcategory');
            Route::post('/update/subcategory' , 'updateSubCategory')->name('update.subcategory');
            Route::get('/delete/subcategory/{id}' , 'deleteSubCategory')->name('delete.subcategory');
            Route::get('/subcategory/ajax/{category_id}' , 'getSubCategory');
        });

         // Vendor Active and Inactive All Route 
        Route::controller(AdminController::class)->group(function(){
            Route::get('/inactive/vendor' , 'inactiveVendor')->name('inactive.vendor');
            Route::get('/active/vendor' , 'activeVendor')->name('active.vendor');
            Route::get('/inactive/vendor/details/{id}' , 'inactiveVendorDetails')->name('inactive.vendor.details');
            Route::post('/active/vendor/approve' , 'inactiveVendorApprove')->name('inactive.vendor.approve'); 
            Route::get('/active/vendor/details/{id}' , 'activeVendorDetails')->name('active.vendor.details');
            Route::post('/inactive/vendor/approve' , 'activeVendorDisApprove')->name('active.vendor.disapprove');  
        });

         // Product All Route 
        Route::controller(ProductController::class)->group(function(){
            Route::get('/all/product' , 'allProduct')->name('all.product');
            Route::get('/add/product' , 'addProduct')->name('add.product');
            Route::post('/store/product' , 'storeProduct')->name('store.product');
            Route::get('/edit/product/{id}' , 'editProduct')->name('edit.product');
            Route::post('/update/product' , 'updateProduct')->name('update.product'); 
            Route::post('/update/product/thambnail' , 'updateProductThambnail')->name('update.product.thambnail'); 
            Route::post('/update/product/multiimage' , 'updateProductMultiimage')->name('update.product.multiimage');
            Route::get('/product/multiimg/delete/{id}' , 'mulitImageDelelte')->name('product.multiimg.delete');

            Route::get('/product/inactive/{id}' , 'productInactive')->name('product.inactive');
            Route::get('/product/active/{id}' , 'productActive')->name('product.active');
            Route::get('/delete/product/{id}' , 'productDelete')->name('delete.product');
        });

         // Slider All Route 
        Route::controller(SliderController::class)->group(function(){
            Route::get('/all/slider' , 'allSlider')->name('all.slider');
            Route::get('/add/slider' , 'addSlider')->name('add.slider');
            Route::post('/store/slider' , 'storeSlider')->name('store.slider');
            Route::get('/edit/slider/{id}' , 'editSlider')->name('edit.slider');
            Route::post('/update/slider' , 'updateSlider')->name('update.slider');
            Route::get('/delete/slider/{id}' , 'deleteSlider')->name('delete.slider');
        });

         // Banner All Route 
        Route::controller(BannerController::class)->group(function(){
            Route::get('/all/banner' , 'allBanner')->name('all.banner');
            Route::get('/add/banner' , 'addBanner')->name('add.banner');
            Route::post('/store/banner' , 'storeBanner')->name('store.banner');
            Route::get('/edit/banner/{id}' , 'editBanner')->name('edit.banner');
            Route::post('/update/banner' , 'updateBanner')->name('update.banner');
            Route::get('/delete/banner/{id}' , 'deleteBanner')->name('delete.banner');
        });

}); //admin middleware end



//admin login route
Route::get('admin/login', [AdminController::class, 'adminLogin'])->middleware(RedirectIfAuthenticated::class);; 

//vendor login route
Route::get('/vendor/login', [VendorController::class, 'vendorLogin'])->name('vendor.login')->middleware(RedirectIfAuthenticated::class);;


//become vendor route
Route::get('/become/vendor', [VendorController::class, 'becomeVendor'])->name('become.vendor');
Route::post('/vendor/register', [VendorController::class, 'vendorRegister'])->name('vendor.register');

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

   // Vendor  Product  Route 
    Route::controller(VendorProductController::class)->group(function(){
        Route::get('/vendor/all/product' , 'vendorAllProduct')->name('vendor.all.product');
        Route::get('/vendor/add/product' , 'vendorAddProduct')->name('vendor.add.product');
        Route::post('/vendor/store/product' , 'vendorStoreProduct')->name('vendor.store.product');
        Route::get('/vendor/edit/product/{id}' , 'vendorEditProduct')->name('vendor.edit.product');
        Route::post('/vendor/update/product' , 'vendorUpdateProduct')->name('vendor.update.product');

        Route::post('/vendor/update/product/thambnail' , 'vendorUpdateProductThabnail')->name('vendor.update.product.thambnail');
        Route::post('/vendor/update/product/multiimage' , 'vendorUpdateProductmultiImage')->name('vendor.update.product.multiimage');
        Route::get('/vendor/product/multiimg/delete/{id}' , 'vendorMultiimgDelete')->name('vendor.product.multiimg.delete');

        Route::get('/vendor/product/inactive/{id}' , 'vendorProductInactive')->name('vendor.product.inactive');
        Route::get('/vendor/product/active/{id}' , 'vendorProductActive')->name('vendor.product.active');
        Route::get('/vendor/delete/product/{id}' , 'vendorProductDelete')->name('vendor.delete.product');

        Route::get('/vendor/subcategory/ajax/{category_id}' , 'vendorGetSubCategory');
        
    });


});


// Frontend Route all 

Route::controller(IndexController::class)->group(function(){
    Route::get('/', 'index')->name('front.index');
    Route::get('/product/details/{id}/{slug}',  'ProductDetails');
    Route::get('/vendor/details/{id}', 'VendorDetails')->name('vendor.details');
    Route::get('/vendor/all', 'VendorAll')->name('vendor.all');
    Route::get('/product/category/{id}/{slug}', 'CatWiseProduct');
    Route::get('/product/subcategory/{id}/{slug}', 'SubCatWiseProduct');

    // Product View Modal With Ajax
    Route::get('/product/view/modal/{id}','ProductViewAjax');
});


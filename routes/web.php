<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\ShippingAreaController;
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

        // Coupon All Route 
        Route::controller(CouponController::class)->group(function(){
            Route::get('/all/coupon' , 'AllCoupon')->name('all.coupon');
            Route::get('/add/coupon' , 'AddCoupon')->name('add.coupon');
            Route::post('/add/coupon' , 'StoreCoupon')->name('store.coupon');
            Route::get('/edit/coupon/{id}' , 'EditCoupon')->name('edit.coupon');
            Route::post('/update/coupon' , 'UpdateCoupon')->name('update.coupon');
            Route::get('/delete/coupon/{id}' , 'DeleteCoupon')->name('delete.coupon');
        });

         // ShippingArea All Route 
        Route::controller(ShippingAreaController::class)->group(function(){

            //division
            Route::get('/all/division' , 'AllDivision')->name('all.division');
            Route::get('/add/division' , 'AddDivision')->name('add.division');
            Route::post('/store/division' , 'StoreDivision')->name('store.division');
            Route::get('/edit/division/{id}' , 'EditDivision')->name('edit.division');
            Route::post('/update/division' , 'UpdateDivision')->name('update.division');
            Route::get('/delete/division/{id}' , 'DeleteDivision')->name('delete.division');

            //district
            Route::get('/all/district' , 'AllDistrict')->name('all.district');
            Route::get('/add/district' , 'AddDistrict')->name('add.district');
            Route::post('/store/district' , 'StoreDistrict')->name('store.district');
            Route::get('/edit/district/{id}' , 'EditDistrict')->name('edit.district');
            Route::post('/update/district' , 'UpdateDistrict')->name('update.district');
            Route::get('/delete/district/{id}' , 'DeleteDistrict')->name('delete.district');

            //state
            Route::get('/all/state' , 'AllState')->name('all.state');
            Route::get('/add/state' , 'AddState')->name('add.state');
            Route::post('/store/state' , 'StoreState')->name('store.state');
            Route::get('/edit/state/{id}' , 'EditState')->name('edit.state');
            Route::post('/update/state' , 'UpdateState')->name('update.state');
            Route::get('/delete/state/{id}' , 'DeleteState')->name('delete.state');
            Route::get('/district/ajax/{division_id}' , 'GetDistrict');
           
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

Route::controller(CartController::class)->group(function(){
    /// Add to cart store data
    Route::post('/cart/data/store/{id}', 'AddToCart');
    // Get Data from mini Cart
    Route::get('/product/mini/cart', 'AddMiniCart');
    Route::get('/minicart/product/remove/{rowId}', 'RemoveMiniCart');
    /// Add to cart store data For Product Details Page 
    Route::post('/dcart/data/store/{id}', 'AddToCartDetails');

    Route::get('/mycart' , 'MyCart')->name('mycart');
    Route::get('/get-cart-product' , 'GetCartProduct');
    Route::get('/cart-remove/{rowId}' , 'CartRemove');

    Route::get('/cart-increment/{rowId}' , 'CartIncrement');
    Route::get('/cart-decrement/{rowId}' , 'CartDecrement');

    /// Frontend Coupon Option
    Route::post('/coupon-apply','CouponApply');
    Route::get('/coupon-calculation', 'CouponCalculation');
    Route::get('/coupon-remove', 'CouponRemove');

    // Checkout Page Route 
    Route::get('/checkout', 'CheckoutCreate')->name('checkout');   
    
});

/// Add to Wishlist 
Route::post('/add-to-wishlist/{product_id}', [WishlistController::class, 'AddToWishList']);

// User All Route
Route::middleware(['auth','role:user'])->group(function() {
    // Wishlist All Route 
   Route::controller(WishlistController::class)->group(function(){
       Route::get('/wishlist' , 'AllWishlist')->name('wishlist');
       Route::get('/get-wishlist-product' , 'GetWishlistProduct');
       Route::get('/wishlist-remove/{id}' , 'WishlistRemove'); 
   }); 
    // Compare All Route 
    Route::controller(CompareController::class)->group(function(){
        Route::get('/compare' , 'AllCompare')->name('compare');
        Route::get('/get-compare-product' , 'GetCompareProduct');
        Route::get('/compare-remove/{id}' , 'CompareRemove'); 
    }); 

    Route::controller(CheckoutController::class)->group(function(){
        Route::get('/district-get/ajax/{division_id}' , 'DistrictGetAjax');
        Route::get('/state-get/ajax/{district_id}' , 'StateGetAjax');
        Route::post('/checkout/store' , 'CheckoutStore')->name('checkout.store');
    }); 

     // Stripe All Route 
    Route::controller(StripeController::class)->group(function(){
        Route::post('/stripe/order' , 'StripeOrder')->name('stripe.order');
        
    
    }); 

}); // end user group middleware

/// Add to Compare 
Route::post('/add-to-compare/{product_id}', [CompareController::class, 'AddToCompare']);




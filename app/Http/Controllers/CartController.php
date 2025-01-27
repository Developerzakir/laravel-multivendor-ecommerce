<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ShipDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function AddToCart(Request $request, $id){

        if(Session::has('coupon')){
            Session::forget('coupon');
        }

        $product = Product::findOrFail($id);
        if ($product->discount_price == NULL) {
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                    'vendor_id' => $request->vendor_id,
                ],
            ]);
          return response()->json(['success' => 'Successfully Added on Your Cart' ]);
        }else{
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                    'vendor_id' => $request->vendor_id,
                ],
            ]);
         return response()->json(['success' => 'Successfully Added on Your Cart' ]);
        }
    }// End Method

    public function AddMiniCart(){

        $carts = Cart::content();
        $cartQty = Cart::count();
        $cartTotal = Cart::total();
        return response()->json(array(
            'carts' => $carts,
            'cartQty' => $cartQty,  
            'cartTotal' => $cartTotal
        ));
    }// End Method

    public function RemoveMiniCart($rowId){
        Cart::remove($rowId);
        return response()->json(['success' => 'Product Remove From Cart']);
    }// End Method

    public function AddToCartDetails(Request $request, $id){

        if(Session::has('coupon')){
            Session::forget('coupon');
        }

        $product = Product::findOrFail($id);
        if ($product->discount_price == NULL) {
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                    'vendor' => $request->vendor,
                ],
            ]);
         return response()->json(['success' => 'Successfully Added on Your Cart' ]);
        }else{
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                    'vendor' => $request->vendor,
                ],
            ]);
         return response()->json(['success' => 'Successfully Added on Your Cart' ]);
        }
    }// End Method

    public function MyCart(){
        return view('front.mycart.view_mycart');
    }// End Method

    public function GetCartProduct(){
        $carts = Cart::content();
        $cartQty = Cart::count();
        $cartTotal = Cart::total();
        return response()->json(array(
            'carts' => $carts,
            'cartQty' => $cartQty,  
            'cartTotal' => $cartTotal
        ));
    }// End Method

    public function CartRemove($rowId){
        Cart::remove($rowId);
        if(Session::has('coupon')){
            $coupon_name = Session::get('coupon')['coupon_name'];
            $coupon = Coupon::where('coupon_name',$coupon_name)->first();
           
           Session::put('coupon',[
                'coupon_name' => $coupon->coupon_name, 
                'coupon_discount' => $coupon->coupon_discount, 
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100), 
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount/100 )
            ]); 
        }
        return response()->json(['success' => 'Successfully Remove From Cart']);
    }// End Method

    public function CartIncrement($rowId){
        $row = Cart::get($rowId);
        Cart::update($rowId, $row->qty +1);

        if(Session::has('coupon')){
            $coupon_name = Session::get('coupon')['coupon_name'];
            $coupon = Coupon::where('coupon_name',$coupon_name)->first();
           
           Session::put('coupon',[
                'coupon_name' => $coupon->coupon_name, 
                'coupon_discount' => $coupon->coupon_discount, 
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100), 
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount/100 )
            ]); 
        }

        return response()->json('Increment');
    }// End Method

    public function CartDecrement($rowId){
        $row = Cart::get($rowId);
        Cart::update($rowId, $row->qty -1);

        if(Session::has('coupon')){
            $coupon_name = Session::get('coupon')['coupon_name'];
            $coupon = Coupon::where('coupon_name',$coupon_name)->first();
           
           Session::put('coupon',[
                'coupon_name' => $coupon->coupon_name, 
                'coupon_discount' => $coupon->coupon_discount, 
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100), 
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount/100 )
            ]); 
        }

        return response()->json('Decrement');
    }// End Method

    // public function CouponApply(Request $request)
    // {
    //     $coupon = Coupon::where('coupon_name',$request->coupon_name)->where('coupon_validity','>=',Carbon::now()->format('Y-m-d'))->first();
    //     if ($coupon) {
    //         Session::put('coupon',[
    //             'coupon_name' => $coupon->coupon_name, 
    //             'coupon_discount' => $coupon->coupon_discount, 
    //             'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100), 
    //             'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount/100 )
    //         ]);
    //         return response()->json(array(
    //             'validity' => true,                
    //             'success' => 'Coupon Applied Successfully'
    //         ));
    //     } else{
    //         return response()->json(['error' => 'Invalid Coupon']);
    //     }
    // }


    public function CouponApply(Request $request)
    {
        $coupon = Coupon::where('coupon_name', $request->coupon_name)
            ->where('coupon_validity', '>=', Carbon::now()->format('Y-m-d'))
            ->first();

        if ($coupon) {
            // Convert Cart::total() to a numeric value
            $cartTotal = (float) str_replace(',', '', Cart::total());

            // Calculate discount and new total
            $discountAmount = round($cartTotal * ($coupon->coupon_discount / 100), 2); // Discount amount
            $newTotal = round($cartTotal - $discountAmount, 2); // Grand total after discount

            // Store coupon details in session
            Session::put('coupon', [
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => $discountAmount,
                'total_amount' => $newTotal,
            ]);

            return response()->json([
                'validity' => true,
                'success' => 'Coupon Applied Successfully',
            ]);
        } else {
            return response()->json(['error' => 'Invalid Coupon']);
        }
    }



   

    public function CouponCalculation()
    {

        if (Session::has('coupon')) {
            
            return response()->json(array(
             'subtotal' => Cart::total(),
             'coupon_name' => session()->get('coupon')['coupon_name'],
             'coupon_discount' => session()->get('coupon')['coupon_discount'],
             'discount_amount' => session()->get('coupon')['discount_amount'],
             'total_amount' => session()->get('coupon')['total_amount'], 
            ));
        }else{
            return response()->json(array(
                'total' => Cart::total(),
            ));
        } 
    }// End Method

    public function CouponRemove()
    {
        Session::forget('coupon');
        return response()->json(['success' => 'Coupon Remove Successfully']);
    }// End Method

    public function CheckoutCreate(){
        if (Auth::check()) {
           
            if (Cart::total() > 0) { 
                $carts = Cart::content();
                $cartQty = Cart::count();
                $cartTotal = Cart::total();
                $divisions = ShipDivision::orderBy('division_name','ASC')->get();
                return view('front.checkout.checkout_view',compact('carts','cartQty','cartTotal','divisions'));
                    
                }else{
                    $notification = array(
                    'message' => 'Shopping At list One Product',
                    'alert-type' => 'error'
                );
                return redirect()->to('/')->with($notification); 
                }
        }else{
             $notification = array(
            'message' => 'You Need to Login First',
            'alert-type' => 'error'
        );
        return redirect()->route('login')->with($notification); 
        }
    }// End Method

}

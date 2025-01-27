<?php

namespace App\Http\Controllers;

use App\Mail\OrderMail;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;

use App\Models\User;
use App\Notifications\OrderComplete;
use Illuminate\Support\Facades\Notification;

class StripeController extends Controller
{
    public function StripeOrder(Request $request)
    {

        \Stripe\Stripe::setApiKey('sk_test_51PcBrpRpnUt2lzX2w36dsUpdsfDbFkDjfM68AKVhWrYssznuOM8eSP40NrAjGiQGogdxf9wJZHirXWsDNsRf1o7E00g0p0121V');
 
        // $token = $_POST['stripeToken'];
        $token = $request->input('stripeToken'); // Access the token from the request
        if (!$token) {
            return response()->json(['error' => 'Stripe token not found'], 400);
        }
        $charge = \Stripe\Charge::create([
          'amount' => 999*100,
          'currency' => 'usd',
          'description' => 'BD Mulit Vendor Shop',
          'source' => $token,
          'metadata' => ['order_id' => '6735'],
        ]);
        dd($charge);
        
    } // End Method 


    public function CashOrder(Request $request){

        $user = User::where('role','admin')->get();

        if(Session::has('coupon')){
            $total_amount = Session::get('coupon')['total_amount'];
        }else{
            // $total_amount = round(Cart::total());
            $total_amount = round((float) Cart::total());
        }
        
        $order_id = Order::insertGetId([
            'user_id' => Auth::id(),
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'state_id' => $request->state_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'adress' => $request->address,
            'post_code' => $request->post_code,
            'notes' => $request->notes,
            'payment_type' => 'Cash On Delivery',
            'payment_method' => 'Cash On Delivery',
            
            'currency' => 'Usd',
            'amount' => $total_amount,
            
            'invoice_no' => 'BDS'.mt_rand(10000000,99999999),
            'order_date' => Carbon::now()->format('d F Y'),
            'order_month' => Carbon::now()->format('F'),
            'order_year' => Carbon::now()->format('Y'), 
            'status' => 'pending',
            'created_at' => Carbon::now(),  
        ]);

          // Start Send Email
          $invoice = Order::findOrFail($order_id);
          $data = [
              'invoice_no' => $invoice->invoice_no,
              'amount' => $total_amount,
              'name' => $invoice->name,
              'email' => $invoice->email,
          ];

          Mail::to($data['email'])->send(new OrderMail($data));
          
          // End Send Email 

        $carts = Cart::content();
        foreach($carts as $cart){
            
            OrderItem::insert([
                'order_id' => $order_id,
                'product_id' => $cart->id,
                'vendor_id' => $cart->options->vendor,
                'color' => $cart->options->color,
                'size' => $cart->options->size,
                'qty' => $cart->qty,
                'price' => $cart->price,
                'created_at' =>Carbon::now(),
            ]);
        } // End Foreach
        if (Session::has('coupon')) {
           Session::forget('coupon');
        }
        Cart::destroy();
        $notification = array(
            'message' => 'Your Order Place Successfully',
            'alert-type' => 'success'
        );

        Notification::send($user, new OrderComplete($request->name));
        return redirect()->route('dashboard')->with($notification); 

    } //end method
}

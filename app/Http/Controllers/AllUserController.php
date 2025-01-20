<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllUserController extends Controller
{
    public function UserAccount()
    {
        $id = Auth::user()->id;
        $userData = User::find($id);
        return view('front.userdashboard.account_details',compact('userData'));
    } // End Method 

    public function UserChangePassword()
    {
        return view('front.userdashboard.user_change_password' );
    } // End Method 

   public function UserOrderPage()
   {
        $id = Auth::user()->id;
        $orders = Order::where('user_id',$id)->orderBy('id','DESC')->get();
         return view('front.userdashboard.user_order_page',compact('orders'));
   } // End Method 

   public function UserOrderDetails($order_id)
   {
    $order = Order::with('division','district','state','user')->where('id',$order_id)->where('user_id',Auth::id())->first();
    $orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
    
    return view('front.order.order_details',compact('order','orderItem'));
   } // End Method 

}

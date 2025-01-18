<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function PendingOrder()
    {
        $orders = Order::where('status','pending')->orderBy('id','DESC')->get();
        return view('admin.orders.pending_orders',compact('orders'));
    }  // End Method 
}

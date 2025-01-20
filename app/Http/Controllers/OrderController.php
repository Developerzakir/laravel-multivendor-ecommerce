<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function PendingOrder()
    {
        $orders = Order::where('status','pending')->orderBy('id','DESC')->get();
        return view('admin.orders.pending_orders',compact('orders'));
    }  // End Method 

    public function AdminOrderDetails($order_id)
    {

        $order = Order::with('division','district','state','user')->where('id',$order_id)->first();
        $orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
        return view('admin.orders.admin_order_details',compact('order','orderItem'));
    }// End Method 

    public function AdminConfirmedOrder()
    {
        $orders = Order::where('status','confirm')->orderBy('id','DESC')->get();
        return view('admin.orders.confirmed_orders',compact('orders'));
    } // End Method 

    public function AdminProcessingOrder()
    {
        $orders = Order::where('status','processing')->orderBy('id','DESC')->get();
        return view('admin.orders.processing_orders',compact('orders'));
     } // End Method 

    public function AdminDeliveredOrder()
    {
        $orders = Order::where('status','deliverd')->orderBy('id','DESC')->get();
        return view('admin.orders.delivered_orders',compact('orders'));
    } // End Method 

}

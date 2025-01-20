<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
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

   public function UserOrderInvoice($order_id)
   {
    $order = Order::with('division','district','state','user')->where('id',$order_id)->where('user_id',Auth::id())->first();
    $orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
    $pdf = Pdf::loadView('front.order.order_invoice', compact('order','orderItem'))->setPaper('a4')->setOption([
            'tempDir' => public_path(),
            'chroot' => public_path(),
    ]);
    return $pdf->download('invoice.pdf');
   } // End Method 


   public function ReturnOrder(Request $request,$order_id)
   {
        Order::findOrFail($order_id)->update([
            'return_date' => Carbon::now()->format('d F Y'),
            'return_reason' => $request->return_reason,
            'return_order' => 1, 
        ]);
        $notification = array(
            'message' => 'Return Request Send Successfully',
            'alert-type' => 'success'
        );
    return redirect()->route('user.order.page')->with($notification); 
   } // End Method 

   public function ReturnOrderPage()
   {
    $orders = Order::where('user_id',Auth::id())->where('return_reason','!=',NULL)->orderBy('id','DESC')->get();
    return view('front.order.return_order_view',compact('orders'));
   } // End Method 

}

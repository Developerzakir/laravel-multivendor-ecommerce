<?php

namespace App\Http\Controllers;

use App\Models\ShipState;
use App\Models\ShipDistrict;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{
    public function DistrictGetAjax($division_id)
    {
        $ship = ShipDistrict::where('division_id',$division_id)->orderBy('district_name','ASC')->get();
        return json_encode($ship);
    } // End Method 

    public function StateGetAjax($district_id)
    {
        $ship = ShipState::where('district_id',$district_id)->orderBy('state_name','ASC')->get();
        return json_encode($ship);
    }// End Method 

    public function CheckoutStore(Request $request)
    {
        $data = array();
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['post_code'] = $request->post_code;   
        $data['division_id'] = $request->division_id;
        $data['district_id'] = $request->district_id;
        $data['state_id'] = $request->state_id;
        $data['shipping_address'] = $request->shipping_address;
        $data['notes'] = $request->notes; 
        $cartTotal = Cart::total();
        if ($request->payment_option == 'stripe') {
           return view('front.payment.stripe.stripe',compact('data','cartTotal'));
        }elseif ($request->payment_option == 'card'){
            return 'Card Page';
        }else{
            return view('front.payment.cash.cash',compact('data','cartTotal'));
        }
    }// End Method 
}

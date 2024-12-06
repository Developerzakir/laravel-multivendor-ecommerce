<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function vendorDashboard()
    {
        return view('vendor.index');
    } //End vendorDashboard method

    public function VendorLogin(){
        return view('vendor.vendor_login');
    } // End VendorLogin method 

    public function vendorDestroy(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/vendor/login');
    } // End vendorDestroy mehtod 
}

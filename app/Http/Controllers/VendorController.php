<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    public function vendorDashboard()
    {
        return view('vendor.index');
    } //End vendorDashboard method

    public function VendorLogin()
    {
        return view('vendor.vendor_login');
    } // End VendorLogin method 

    public function VendorProfile()
    {
        $id = Auth::user()->id;
        $vendorData = User::find($id);
        return view('vendor.vendor_profile_view',compact('vendorData'));
    } // End Mehtod 

    public function vendorProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address; 
        $data->vendor_join = $request->vendor_join; 
        $data->vendor_short_info = $request->vendor_short_info; 
        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/vendor_images/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/vendor_images'),$filename);
            $data['photo'] = $filename;
        }
        $data->save();
        $notification = array(
            'message' => 'Vendor Profile Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // End vendorProfileStore mehtod 


    public function vendorChangePassword(){
        return view('vendor.vendor_change_password');
    } // End Mehtod 


    public function vendorUpdatePassword(Request $request)
    {
            // Validation 
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|confirmed', 
            ]);
            // Match The Old Password
            if (!Hash::check($request->old_password, auth::user()->password)) {
                return back()->with("error", "Old Password Doesn't Match!!");
            }
            // Update The new password 
            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            return back()->with("status", " Password Changed Successfully");
    } // End vendorUpdatePassword Mehtod 



    public function vendorDestroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/vendor/login');
    } // End vendorDestroy mehtod 
}

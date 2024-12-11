<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function adminDashboard()
    {
        return view('admin.index');

    } //End adminDashboard method


    public function adminLogin()
    {
        return view('admin.admin_login');

    } //End adminLogin method

    public function adminProfile()
    {
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin-profile-view', compact('adminData'));

    } //End adminProfile method

    public function adminProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address; 
        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $data['photo'] = $filename;
        }
        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End adminProfileStore Mehtod 

    public function adminChangePassword(){
        return view('admin.admin_change_password');
    } // End adminChangePassword Mehtod 

    public function adminUpdatePassword(Request $request){
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
    } // End adminUpdatePassword Mehtod 
    

    public function adminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    } //End adminLogout method

    public function inactiveVendor()
    {
        $inActiveVendor = User::where('status','inactive')->where('role','vendor')->latest()->get();
        return view('admin.vendor.inactive_vendor',compact('inActiveVendor'));
    }// End Mehtod 

    public function activeVendor()
    {
        $ActiveVendor = User::where('status','active')->where('role','vendor')->latest()->get();
        return view('admin.vendor.active_vendor',compact('ActiveVendor'));
    }// End Mehtod 

    public function inactiveVendorDetails($id)
    {
        $inactiveVendorDetails = User::findOrFail($id);
        return view('admin.vendor.inactive_vendor_details',compact('inactiveVendorDetails'));
    }// End Mehtod 

    public function inactiveVendorApprove(Request $request){
        $verdor_id = $request->id;
        $user = User::findOrFail($verdor_id)->update([
            'status' => 'active',
        ]);
        $notification = array(
            'message' => 'Vendor Active Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('active.vendor')->with($notification);
    }// End Mehtod 

    public function activeVendorDetails($id)
    {
        $activeVendorDetails = User::findOrFail($id);
        return view('admin.vendor.active_vendor_details',compact('activeVendorDetails'));

    }// End Mehtod


    public function activeVendorDisApprove(Request $request){
        $verdor_id = $request->id;
        $user = User::findOrFail($verdor_id)->update([
            'status' => 'inactive',
        ]);
        $notification = array(
            'message' => 'Vendor InActive Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('inactive.vendor')->with($notification);
    }// End Mehtod 

   
}

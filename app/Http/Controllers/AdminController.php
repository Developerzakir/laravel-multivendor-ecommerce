<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VendorApprovedNotification;


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
        $user = User::where('role','vendor')->get();
        Notification::send($user, new VendorApprovedNotification($request));
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
    } // End Mehtod 


    //get admin
   
    public function AllAdmin()
    {
        $alladminuser = User::where('role','admin')->latest()->get();
        return view('admin.all_admin',compact('alladminuser'));
    } // End Mehtod 

    public function AddAdmin()
    {
        $roles = Role::all();
        return view('admin.add_admin',compact('roles'));
    } // End Mehtod 

    // public function AdminUserStore(Request $request)
    // {
    //     $user = new User();
    //     $user->username = $request->username;
    //     $user->name = $request->name;
    //     $user->email = $request->email;
    //     $user->phone = $request->phone;
    //     $user->address = $request->address;
    //     $user->password = Hash::make($request->password);
    //     $user->role = 'admin';
    //     $user->status = 'active';
    //     $user->save();
    //     if ($request->roles) {
    //         $user->assignRole($request->roles);
    //     }
    //      $notification = array(
    //         'message' => 'New Admin User Inserted Successfully',
    //         'alert-type' => 'success'
    //     );
    //     return redirect()->route('all.admin')->with($notification);

    // } // End Mehtod 

    public function AdminUserStore(Request $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->status = 'active';
        $user->save();

        if ($request->roles) {
            // Fetch the role name based on the ID
            $role = Role::find($request->roles); // Ensure `Role` model is imported
            if ($role) {
                $user->assignRole($role->name); // Pass the role name
            } else {
                return redirect()->back()->withErrors(['roles' => 'The selected role does not exist.']);
            }
        }

        $notification = [
            'message' => 'New Admin User Inserted Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.admin')->with($notification);
    } //end method



    public function EditAdminRole($id)
    {

        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.edit_admin',compact('user','roles'));
    } // End Mehtod 


    // public function AdminUserUpdate(Request $request,$id)
    // {
    //     $user = User::findOrFail($id);
    //     $user->username = $request->username;
    //     $user->name = $request->name;
    //     $user->email = $request->email;
    //     $user->phone = $request->phone;
    //     $user->address = $request->address; 
    //     $user->role = 'admin';
    //     $user->status = 'active';
    //     $user->save();

    //     $user->roles()->detach();
    //     if ($request->roles) {
    //         $user->assignRole($request->roles);
    //     }

    //      $notification = array(
    //         'message' => 'New Admin User Updated Successfully',
    //         'alert-type' => 'success'
    //     );

    //     return redirect()->route('all.admin')->with($notification);
    // } // End Mehtod 

    public function AdminUserUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->role = 'admin';
        $user->status = 'active';
        $user->save();

        // Detach all existing roles
        $user->roles()->detach();

        if ($request->roles) {
            // Fetch the role name based on the ID
            $role = Role::find($request->roles); // Ensure `Role` model is imported
            if ($role) {
                $user->assignRole($role->name); // Assign the role using its name
            } else {
                return redirect()->back()->withErrors(['roles' => 'The selected role does not exist.']);
            }
        }

        $notification = [
            'message' => 'New Admin User Updated Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.admin')->with($notification);
    } //end method


    public function DeleteAdminRole($id)
    {
        $user = User::findOrFail($id);
        if (!is_null($user)) {
            $user->delete();
        }
         $notification = array(
            'message' => 'Admin User Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // End Mehtod 


   
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

    public function adminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    } //End adminLogout method

   
}

<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function allBanner()
    {
        $banner = Banner::latest()->get();
        return view('admin.banner.index',compact('banner'));
    } // End Method 
}

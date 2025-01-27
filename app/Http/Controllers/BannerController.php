<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class BannerController extends Controller
{
    public function allBanner()
    {
        $banner = Banner::latest()->get();
        return view('admin.banner.index',compact('banner'));
    } // End Method 

    public function addBanner()
    {
        return view('admin.banner.create');
    }// End Method 

    public function storeBanner(Request $request)
    {
        $image = $request->file('banner_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(768,450)->save('upload/banner/'.$name_gen);
        $save_url = 'upload/banner/'.$name_gen;
        Banner::insert([
            'banner_title' => $request->banner_title,
            'banner_url' => $request->banner_url,
            'banner_image' => $save_url, 
        ]);
        $notification = array(
            'message' => 'Banner Inserted Successfully',
            'alert-type' => 'info'
        );
        return redirect()->route('all.banner')->with($notification); 
    }// End Method 

    public function editBanner($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banner.edit',compact('banner'));
    }// End Method 


    public function updateBanner(Request $request)
    {
        $banner_id = $request->id;
        $old_img = $request->old_image;
        if ($request->file('banner_image')) {
        $image = $request->file('banner_image');
         $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(768,450)->save('upload/banner/'.$name_gen);
        $save_url = 'upload/banner/'.$name_gen;
        if (file_exists($old_img)) {
           unlink($old_img);
        }
        Banner::findOrFail($banner_id)->update([
            'banner_title' => $request->banner_title,
            'banner_url' => $request->banner_url,
            'banner_image' => $save_url, 
        ]);
       $notification = array(
            'message' => 'Banner Updated with image Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.banner')->with($notification); 
        } else {
            Banner::findOrFail($banner_id)->update([
            'banner_title' => $request->banner_title,
            'banner_url' => $request->banner_url, 
        ]);
       $notification = array(
            'message' => 'Banner Updated without image Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.banner')->with($notification); 
        } // end else
    }// End Method 

    public function deleteBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $img = $banner->banner_image;
        unlink($img ); 
        Banner::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Banner Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 
    }// End Method 
}

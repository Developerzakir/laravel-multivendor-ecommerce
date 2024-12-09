<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function allBrand(){
        $brands = Brand::latest()->get();
        return view('admin.brand.index',compact('brands'));
    } // End Method 

    public function addBrand()
    {
        return view('admin.brand.create');

    } // End Method 
}

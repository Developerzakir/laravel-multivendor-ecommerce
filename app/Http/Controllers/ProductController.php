<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function allProduct()
    {
        $products = Product::latest()->get();
        return view('admin.product.index',compact('products'));
    } //End method

    public function addProduct(){

        return view('admin.product.create');
    } // End Method 
}

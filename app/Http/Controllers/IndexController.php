<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\MultiImg;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function ProductDetails($id,$slug)
    {
        $product = Product::findOrFail($id);

        $color = $product->product_color;
        $product_color = explode(',', $color);
        $size = $product->product_size;
        $product_size = explode(',', $size);

        $multiImage = MultiImg::where('product_id',$id)->get();

        $cat_id = $product->category_id;
        $relatedProduct = Product::where('category_id',$cat_id)->where('id','!=',$id)->orderBy('id','DESC')->limit(4)->get();

        return view('front.product.product_details',compact('product','product_color','product_size','multiImage','relatedProduct'));
     } // End Method 
}

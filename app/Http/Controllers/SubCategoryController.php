<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function AllSubCategory(){
        $subcategories = SubCategory::latest()->get();
        return view('admin.subcategory.index',compact('subcategories'));
    } // End Method 

    public function addSubcategory()
    {
        $categories = Category::orderBy('category_name','ASC')->get();
        return view('admin.subcategory.create',compact('categories'));
    } // End Method

    public function StoreSubCategory(Request $request){ 
        SubCategory::insert([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-',$request->subcategory_name)), 
        ]);
       $notification = array(
            'message' => 'SubCategory Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.subcategory')->with($notification); 
    }// End Method 
    
}

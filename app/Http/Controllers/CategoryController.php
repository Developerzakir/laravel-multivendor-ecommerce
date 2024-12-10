<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    public function allCategory()
    {
        $categories = Category::latest()->get();
        return view('admin.category.index',compact('categories'));

    } //End method
}

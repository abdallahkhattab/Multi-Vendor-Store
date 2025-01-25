<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Models\Dashboard\Product;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //

    public function index(){
        $products = Product::with('category')->active()
        ->take(8) // Retrieve the first 8 products from the database
        ->get();
            return view('front.home',compact('products'));
    }
}

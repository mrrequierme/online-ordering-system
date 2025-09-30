<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class GuestController extends Controller
{
    public function index(){
        $products = Product::orderBy('name')
        ->get();

        return view('home',compact('products'));
    }


}

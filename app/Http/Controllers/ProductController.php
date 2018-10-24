<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * list all trace product
     */
    public function listProduct(){
        $products = Product::all();
    }

    /**
     * Display create product page
     */
    public function showCreateProduct(){
        return view('product.create');
    }

}

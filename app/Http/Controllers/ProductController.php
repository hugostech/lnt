<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

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
        if (Input::has('sku')){
            $product = Product::getProductBySku(trim(Input::get('sku')));
            dd($product);
        }
        return view('product.create');
    }

}

<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

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
        $product = false;
        if (Input::has('sku')){
            $product = Product::getProductBySku(trim(Input::get('sku')));
        }
        return view('product.create', compact('product'));
    }

    public function createProduct(Request $request){
        $messages = [
            'bottom_price.required' => 'We need to know your bottom price!',
            'sku.unique' => ' The product already existed in database!',
        ];
        $validator = Validator::make($request->all(), [
            'bottom_price'=>'required|numeric',
            'sku'=>'required|unique:products,sku',
        ],$messages);

        if ($validator->fails()) {
            return \GuzzleHttp\json_encode($validator->getMessageBag()->toArray());
        }else{
            $product = Product::create($request->all());
            return \GuzzleHttp\json_encode(['product'=>$product->id]);
        }

    }

}

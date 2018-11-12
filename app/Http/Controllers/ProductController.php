<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    /**
     * list all trace product
     */
    public function listProduct(){
        $products = Product::all();
        return view('product.list',compact('products'));
    }

    public function searchProducts(Request $request){
        $this->validate($request, [
            'key'=>'required',
        ]);
        $key = trim($request->input('key'));
        if ($key===''){
            return redirect()->route('product_list');
        }
        $products = Product::where('sku','like',"%$key%")->get();
        return view('product.list',compact('products'));
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

    public function addTrace($product_id, Request $request){
        $trace_urls = $request->input('trace');
        $product = Product::find($product_id);
        $product->trace_urls = \GuzzleHttp\json_encode($trace_urls);
        $product->save();
        return \GuzzleHttp\json_encode(['errer'=>0]);
    }

    public function editProduct($product_id){
        $product = Product::find($product_id);
        return view('product.edit',compact('product'));
    }

    public function updateProduct($product_id, Request $request){
        $messages = [
            'bottom_price.required' => 'We need to know your bottom price!',
        ];
        $validator = Validator::make($request->all(), [
            'bottom_price'=>'required|numeric',
        ],$messages);

        if ($validator->fails()) {
            return \GuzzleHttp\json_encode($validator->getMessageBag()->toArray());
        }else{
            $product = Product::find($product_id);
            $product->update($request->all());
            if (!$request->has('status')){
                $product->status = 0;
                $product->save();
            }
            return \GuzzleHttp\json_encode(['product'=>$product->id]);
        }
    }

    /**
     * receive the spider prices from competitors' website
     * @param $product_id
     * @param Request $request
     */
    public function collectPrice($product_id, Request $request){
        $product = Product::find($product_id);
        if (is_null($product)){
            return response()->json(['error'=>1,'message'=>'product dose not exist']);
        }else{
            Log::info($request->getContent());
            $result = $product->processPriceResult($request->getContent());
            Log::info($result);
            return response()->json(['error'=>0,'message'=>'ok']);
        }
    }

}

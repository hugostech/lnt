<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=[
        'bottom_price','status','sku','price','name','trace_urls'
    ];
    static function getClient(){
        return new Client([
            // Base URI is used with relative requests
            'base_uri' => config('lnt_connect.url'),
            // You can set any number of default request options.
            'timeout'  => 10.0,
        ]);
    }

    static function processResult($response){
        if ($response->getStatusCode()==200){
            return json_decode($response->getBody()->getContents(),true);
        }else{
            return false;
        }
    }

    static function getProductBySku($sku){
        $client = self::getClient();
        $response = $client->get('getProductInfo/'.$sku);
        return self::processResult($response);

    }

    static function getPrice($url,$async=false){
        $url_api = 'fetchProductInfo';
        $json = compact('url','async');
        $client = self::getClient();
        $response = $client->request('PUT', $url_api, compact('json'));
        return self::processResult($response);
    }

    static function updatePrice(){
        $url = 'updateProductPrice';
        $json = [
            'sku'=>'test',
            'price'=>123.32
        ];
        $client = self::getClient();
        $response = $client->request('PUT', $url, compact('json'));
        return self::processResult($response);
    }
}

<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    protected $fillable=[
        'bottom_price','status','sku','price','name','trace_urls'
    ];

    public function traces(){
        return $this->hasMany('App\PriceTrace','product_id','id');
    }

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

    /**
     * get product details from lnt server
     * @param $sku
     * @return bool|mixed
     */
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

    /**
     * post urls to spider to grab competitors' price,
     * job enqueue
     */
    function postSyncJob(){
        $url = 'fetchMProductInfo';
        $json = [
            "async" => true,
            "url" => route('product_price_collect', ['product_id'=>$this->id]),
            "urls" => \GuzzleHttp\json_decode($this->trace_urls,true)
        ];
        Log::info('Enqueue job for product '.$this->id);
        Log::info($json);
        $client = self::getClient();
        $response = $client->request('POST', $url, compact('json'));
        return self::processResult($response);
    }

    function processPriceResult($data_raw){
        $data = json_decode($data_raw,true);
        $bestPrice = 0;
        foreach ($data['simpleResponseMsg'] as $i=>$row){

            if ($row['status']==true){

                if ($i==0){
                    $bestPrice = $row['price'];
                }
                $bestPrice = $bestPrice>$row['price']?$row['price']:$bestPrice;
                $this->traces()->create([
                    'url'=>$row['url'],
                    'price'=>$row['price'],
                    'raw_response'=>$data_raw,
                    'gen_key'=>$row['name']
                ]);
            }
        }
        if ($bestPrice>0){
            $this->special = $bestPrice;
            return $this->save();
        }else{
            return false;
        }

    }
}

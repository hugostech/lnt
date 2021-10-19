<?php

namespace App;

use App\Jobs\FetchPriceJob;
use App\Lib\Mitro10Spider;
use App\Lib\TheToolsShedSpider;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable=[
        'bottom_price','status','sku','price','name','trace_urls', 'special'
    ];

    public function traces(){
        return $this->hasMany('App\PriceTrace','product_id','id');
    }

    static function getClient(){
        return new Client([
            // Base URI is used with relative requests
            'base_uri' => config('lnt_connect.url'),
            // You can set any number of default request options.
            'timeout'  => 20.0,
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

   function updatePrice($price){
        $url = 'updateProductPrice';
        $json = [
            'sku'=>$this->sku,
            'price'=>$price
        ];
        $client = self::getClient();
        $response = $client->request('POST', $url, compact('json'));
        return self::processResult($response);
    }

    /**
     * post urls to spider to grab competitors' price,
     * job enqueue
     */
    function postSyncJob(){
//        $url = 'fetchMProductInfo';
//        $json = [
//            "async" => true,
//            "url" => route('product_price_collect', ['product_id'=>$this->id]),
//            "urls" => \GuzzleHttp\json_decode($this->trace_urls,true)
//        ];
//        Log::info('Enqueue job for product '.$this->id);
//        Log::info($json);
//        $client = self::getClient();
//        $response = $client->request('POST', $url, compact('json'));
//        return self::processResult($response);
        $urls = \GuzzleHttp\json_decode($this->trace_urls,true);

        foreach ($urls as $url){
            FetchPriceJob::dispatch($url, $this);
        }
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
            $bestPrice = floor($bestPrice)-1;
            if ($bestPrice<($this->bottom_price*1.15*1.1)){
                return false;
                $bestPrice = $this->bottom_price*1.15*1.1;
            }
            $this->special = $bestPrice;
            $this->save();
            return $this->updatePrice($bestPrice);
        }else{
            $this->special = $this->price;
            $this->save();
            return false;
        }

    }

    public function updateLocalPrice(){
        $remoteProduct = self::getProductBySku($this->sku);
        if ($remoteProduct){
            $this->price = $remoteProduct['price'];
            $this->save();
        }
    }
}

<?php

namespace App\Lib;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Mixed_;
use PHPHtmlParser\Dom;

abstract class Spider
{
    protected $curl;
    private $url;
    protected $domParser;

    /**
     * @param $url string targeted url
     */
    public function __construct($url)
    {
        $this->curl = new Client();
        $this->domParser =  new Dom();
        $this->url = $url;
    }

    /**
     * fetch url, return html object
     * @return float product price
     */
    public function handle(){
        try {
            $content = $this->curl->get($this->url);
            return $this->analyse($content);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return false;
        }

    }

    /**
     * process html content and return array
     * @param $htmlContent Response content
     * @return mixed  product price or false
     */
    abstract function analyse(Response $htmlContent);

}

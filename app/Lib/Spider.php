<?php

namespace App\Lib;

use GuzzleHttp\Client;

abstract class Spider
{
    protected $curl;
    public function __construct()
    {
        $this->curl = new Client();
    }

    /**
     * fetch url, return html object
     * @param $url
     * @return array
     */
    public function handle($url){
        $content = $this->curl->get($url);
        return $this->analyse($content);
    }

    /**
     * process html content and return array
     * @param $htmlContent html content
     * @return array
     */
    abstract function analyse($htmlContent): array;
}

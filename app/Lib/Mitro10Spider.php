<?php

namespace App\Lib;

use GuzzleHttp\Psr7\Response;

class Mitro10Spider extends Spider
{

    /**
     * @inheritDoc
     */
    function analyse(Response $htmlContent): float
    {
        try{
            $html = $htmlContent->getBody()->getContents();
            $this->domParser->loadStr($html);
            $price = $this->domParser->find('.product-item .price-display .product--price-dollars')[0];
            return $price->innerHtml;
        }catch (\Exception $exception){
            return false;
        }
    }
}

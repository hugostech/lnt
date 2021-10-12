<?php

namespace App\Lib;

use GuzzleHttp\Psr7\Response;

class TheToolsShedSpider extends Spider
{

    /**
     * @inheritDoc
     */
    function analyse(Response $htmlContent)
    {
        try {
            $html = $htmlContent->getBody()->getContents();
            $this->domParser->loadStr($html);
            $domPrice = $this->domParser->find('#single-product-details .price .value');
            $price = $domPrice[$domPrice->count() - 1]->text;
            $price = trim(str_replace('$', '', $price));
            return is_numeric($price) ? floatval($price) : false;
        } catch (\Exception $exception) {
            return false;
        }
    }
}

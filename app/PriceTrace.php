<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceTrace extends Model
{
    protected $fillable = [
        'url', 'price', 'raw_response', 'gen_key'
    ];
}

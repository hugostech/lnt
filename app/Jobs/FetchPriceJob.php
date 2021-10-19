<?php

namespace App\Jobs;

use App\Lib\Mitro10Spider;
use App\Lib\Spider;
use App\Lib\TheToolsShedSpider;
use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FetchPriceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $url;
    private $listing;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, Product $listing)
    {
        $this->url = $url;
        $this->listing = $listing;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $spiders = [
            'thetoolshed.co.nz' => TheToolsShedSpider::class,
            'mitre10.co.nz' => Mitro10Spider::class,
            'bunnings.co.nz' => null,
            'snappy.co.nz' => null,
            'placemakers.co.nz' => null
        ];
        foreach ($spiders as $domain=>$spider){
            if(Str::contains($this->url,$domain)){
                if ($spider){
                    $spider = new $spider($this->url);
                    $price = $spider->handle();
                    if ($price){
                        $this->listing->refresh();
                        Log::info(gettype($this->listing));
                        Log::info( $this->listing->id.' '.$this->listing->special .' change to '.$price);
                        $this->listing->update(['special'=>$price]);
                        if ( $this->listing->special <= 0 || $this->listing->special > $price){
                            $this->listing->update(['special'=>$price]);
                        }
                    }
                    break;
                }

            }
        }


    }
}

<?php

namespace App\Jobs;

use App\Lib\Spider;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchPriceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $spider;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Spider $spider)
    {
        $this->spider = $spider;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $price = $this->spider->handle();
    }
}

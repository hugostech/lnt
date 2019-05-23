<?php

namespace App\Console\Commands;

use App\Product;
use Illuminate\Console\Command;

class SyncPriceFromLNT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:sync-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync LNT price to local';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $products = Product::where('status',1)->get();
        $bar = $this->output->createProgressBar(count($products));
        foreach ($products as $product){
            $product->updateLocalPrice();
            $bar->advance();
        }
        $bar->finish();
    }
}

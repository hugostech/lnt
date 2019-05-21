<?php

namespace App\Console\Commands;

use App\Product;
use Illuminate\Console\Command;

class SyncPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:push 
                            {product? : The ID of product}
                            {--force : Process the task quietly}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enqueue all activated products\' competitors urls to spider list';

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
        $id = $this->argument('product');
        if ($id){
            $this->pushJob(Product::find($id));
        }else{
            if ($this->option('force') || $this->confirm('Are you sure want to sync all product?')){
                $this->pushAllProducts();
            }
        }
        $this->line('Task finished');
    }

    private function pushAllProducts(){
        $products = Product::where('status',1)->get();
        $bar = $this->output->createProgressBar(count($products));
        foreach ($products as $product){
            $product->updateLocalPrice();
            $this->pushJob($product);
            $bar->advance();
        }
        $bar->finish();
    }

    private function pushJob(Product $product){
        $this->line(sprintf(PHP_EOL.'product id: %s, %s in progress',$product->id,$product->name));
        $res = $product->postSyncJob();
        if ($res){
            $this->info('Success');
        }else{
            $this->error('Failed');

        }
    }
}

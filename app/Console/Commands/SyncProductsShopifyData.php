<?php

namespace App\Console\Commands;

use App\Models\DataCrm;
use App\Models\ShopifyApp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncProductsShopifyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allproductsshop:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description : Products from Shopify to Data';

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
     * @return int
     */
    public function handle()
    {
        $shop = new ShopifyApp();
        $shop->updateproducts();    
       
    }
}

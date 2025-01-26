<?php

namespace App\Console\Commands;

use App\Models\DataCrm;
use App\Models\ShopifyApp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NewCustomersShopifyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allnewshopfiy:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checks shopify and adds all new contacts to datacrm';

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
        $shop->allcustomers();
    }
}

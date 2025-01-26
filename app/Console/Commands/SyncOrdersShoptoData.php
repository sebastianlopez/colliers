<?php

namespace App\Console\Commands;

use App\Models\DataCrm;
use App\Models\ShopifyApp;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncOrdersShoptoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:shoptodata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Moves Orders Paid from shop to Data';

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
        
        $yesterday = Carbon::yesterday();
        Log::info('Starts '.date('Y-m-d H:i:s'));
        $this->ordersshoptodata($yesterday);    

    }


    function ordersshoptodata($date,$link=''){

        $shop = new ShopifyApp();
        $data = new DataCrm();

      
        $ordersResult = $shop->getallOrdersPaidByDate($date,$link);
        $orders = $ordersResult['body']->orders;


        foreach($orders as $key => $order){

            $string      = str_replace('T',' ',$order->closed_at);
            $splited_date = explode(' ',$string);
    
            $order = (array) $order;
            if($order['financial_status'] == 'paid' && $order['user_id'] != ''){

                $potential_exists = $data->getPotential($order['id']);
    
                if($potential_exists == null){
                    $infouser = array();
                    if($order['user_id'] != ''){
        
                        $usershopify = $shop->getUser($order['user_id']);
                        $infouser = $data->getEmail( $usershopify['body']->user->email);
        
                    }
        
        
                    $contact = $data->searchContact($order['customer']);            
                    
                
                    if($contact == null){
        
                        $contact = $data->saveContact($order['customer']);
                        Log::info('Saved '.$contact['id']);
                        $contact_id = $contact['id'];
        
                    }else{
                        $contact_id = $contact['id'];
                        Log::info('Existed '.$contact_id);
                    }
    
    
                    $products = array();
                    foreach($order['line_items'] as $prods){
        
                        $dataprd = $data->getProductBySku($prods->sku);
        
                        if($dataprd == null){
        
                            $shopprod = $shop->getProduct($prods->product_id);
        
                            $infotrans = $shopprod['body']->product;
        
                            foreach($infotrans->variants as $variant){
        
                                if($prods->sku == $variant->sku){
        
                                    $newinfo['title'] = $variant->title;
                                    $newinfo['price'] = $variant->price; 
                                    $newinfo['quant'] = $variant->inventory_quantity;                              
                                }
        
                            } 
                            
                            $newprod = array(
                                'description'           => $infotrans->body_html,
                                'productname'           => $infotrans->title.' '.$newinfo['title'],
                                'inventory_quantity'    => $newinfo['quant'],
                                'sku'                   => $prods->sku,
                                'unit_price'            => $newinfo['price']
                            );
        
                            $dataprd  = $data->saveProduct($newprod);
                        }
        
                        $prod = array(
        
                            "productid"     => $dataprd['id'],
                            "sequence_no"   => "0",
                            "quantity"      => $prods->quantity,
                            "listprice"     => $prods->price
                        );
        
                        array_push($products,$prod);
                    }
        
                    $order['processed_at'];
        
                    $potencial = array(
        
                        'contact_id'        => $contact_id,
                        'name'              => $order['name'],
                        'order_number'      => $order['order_number'],
                        'total_price'       => $order['total_price'],
                        'created_at'        => $splited_date[0],
                        'assigned_user_id'  => $infouser['id'],
                        'createdby'         => '19x47',
                        'shopify_id'        => $order['id']
        
                    );
        
                    $bussines = $data->savePotential($potencial);
        
        
                    $quote =array(
                        "subject"           => "Cotizacion de orden ".$order['order_number'],
                        "contact_id"        =>  $contact_id,   
                        'quotestage'        => 'Accepted',
                        'assigned_user_id'  => '19x47',
                        'cf_1070'           => 'UPS',
                        'cf_1072'           => 'Prepaid',
                        'potential_id'      => $bussines['id'],
                        'LineItems'         => $products
                    );
        
                    $sacedquota = $data->saveQuotes($quote);
    
                    Log::info('Saved Order #'.$order['order_number']);
    
                }
            }
        }

        if( isset($ordersResult['link']) ){
        
            $base =  $shop->cleanlink($ordersResult['link']);          
            if($base != ''){
               return $this->ordersshoptodata($date,$base);
            }
        }

    }
}

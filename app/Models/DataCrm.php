<?php

namespace App\Models;

use App\Libraries\Salaros\Vtiger\VTWSCLib\Modules;
use App\Libraries\Salaros\Vtiger\VTWSCLib\WSClient;
use Illuminate\Support\Facades\Log;

class DataCrm
{

  
    /**
     * DataCrm constructor.
     * @throws \App\Libraries\Salaros\Vtiger\VTWSCLib\WSException
     */
    public function __construct()
    {
        $this->vt = new WSClient('https://datacrm.la/datacrm/collierscolombia/',
            'Rhiss', 'RRR8gf60uEaSMHXU');
       //$this->vt = new WSClient('https://develop.datacrm.la/mrivera/mrwhatsaoo6325c/',
         //   'Rhiss', 'RRR8gf60uEaSMHXU');
    }

    //==================================== CLIENTES ====================================//

    /**
     * Obtiene los clientes de datacrm.
     *
     * @param $search
     *
     * @return array
     * @throws \App\Libraries\Salaros\Vtiger\VTWSCLib\WSException
     * Created by <Rhiss.net>
     */
    public function getClients($search, $limit = 100, $offset = 0)
    {
        $select = [
            '*'
        ];

        $clients = $this->vt->entities->findMany('Accounts', $search, $select, $limit, $offset);

        return $clients;
    }


    /**
     * Busca un cliente en datacrm por nit.
     *
     * @param $nit
     *
     * @return array
     * Created by <Rhiss.net>
     */
    public function getClient($id)
    {
        $client = $this->vt->entities->findOne('Accounts', ['id' => $id]);

        return $client;
    }


    /**
     * Undocumented function
     *
     * @param [type] $email
     * @return void
     */
    public function getClientEmail($email){
        $client = $this->vt->entities->findOne('Accounts', ['email' => $email]);

        return $client;
    }

    /**
     * Busca el id de un cliente en datacrm por nit.
     *
     * @param $email
     *
     * @return string
     * Created by <Rhiss.net>
     */
    public function getClientID($nit)
    {
        $client = $this->vt->entities->getID('Accounts', ['siccode' => $nit]);

        return $client;
    }

    /**
     * Busca un cliente en datacrm por ID.
     *
     * @param $id
     *
     * @return array
     * Created by <Rhiss.net>
     */
    public function getClientByID($id)
    {
        $client = $this->vt->entities->findOneByID('Accounts', $id, ['id', 'accountname', 'siccode']);

        return $client;
    }


    /**
     * Busca un contacto en datacrm por ID.
     *
     * @param $id
     *
     * @return array
     * Created by <Rhiss.net>
     */
    public function getContactByID($id)
    {
        $client = $this->vt->entities->findOneByID('Contacts', $id, ['id', 'account_id']);

        return $client;
    }


    public function getContactByPhone($phone){

        $product = $this->vt->entities->findOne('Contacts',['mobile' => $phone]);
        return $product;

    }


    public function getContactByEmail($email){

        $product = $this->vt->entities->findOne('Contacts',['email' => $email]);
        return $product;

    }


    /**
     * 
     */

    public function getContacts($search, $limit = 100, $offset = 0)
    {
        $select = [
            'lastname',             //Nombre Contacto
            'cf_1198',              //Customer Type
            'email',
            'mobile',
            'id',
            'cf_1052',              //Idioma       
            'assigned_user_id',
            'email',
            'leadsource',           //Origen del prospecto
            'cf_1196',              //Customer Status
            'cf_1204',              //SP Name
            'cf_1325'               //Id Shopify

        ];

        $clients = $this->vt->entities->findMany('Contacts', $search, $select, $limit, $offset);

        return $clients;
    }



    /**
     * Undocumented function
     *
     * @param [type] $data
     * @return void
     */
    public function updateContact($data)
    {
        $contact = null;
        //$data   = (array)$data;

        try {
            $contact = $this->vt->entities->updateOne('Contacts', $data['id'], $data);
        } catch (\Exception $e) {
            $msg = ['method' => 'updateContact', 'data' => ['id' => $data['id']], 'error' => $e->getMessage()];
            Log::channel('datacrm')->error(json_encode($msg));
        }

        return $contact;
    }


    

    /**
     * Busca un usuario en datacrm por ID.
     *
     * @param $id
     *
     * @return array
     * Created by <Rhiss.net>
     */
    public function getUserByID($id)
    {
        $client = $this->vt->entities->findOneByID('Users', ['mobile' => $id], ['id', 'last_name', 'first_name', 'phone_other']);
        return $client;
    }


    /**
     * Undocumented function
     *
     * @param [type] $email
     * @return void
     */
    public function getUserEmail($email){

        $product = $this->vt->entities->findOne('Users',['email1' => $email]);
        return $product;

    }

    /**
     * Busca un usuario por email.
     *
     * @param $cc
     *
     * @return string
     * Created by <Rhiss.net>
     */
    public function getUserID($id)
    {
        $client = $this->vt->entities->findOne('Users', ['id' => $id]);

        return $client;
    }

    /**
     * Guarda un cliente en datacrm.
     *
     * @param $data
     *
     * @return array|null
     * Created by <Rhiss.net>
     */
    public function saveClient($data)
    {
        $client = null;

        try {

            $data         = (array)$data;
            $data_datacrm =
                [
                    'siccode'          => $data['nit'], //PRIMARY REQUIRED
                    'accountname'      => $data['Razon_Social_Nombre_Cliente'], //REQUIRED
                    'rating'           => $data['Estado'], //Estado cliente
                    'cf_1111'          => $data['tipo_identificacion'], //Tipo de Cliente REQUIRED
                    'cf_1149'          => 'NO APLICA', // Tipo de Empresa  REQUIRED
                    'cf_1313'          => 'NO APLICA', // Tipo de Industria  REQUIRED
                    'cf_1109'          => !empty($data['razon_comercial']) ? $data['razon_comercial'] : 'N/A', //Nombre Comercial REQUIRED
                    'phone'            => $data['Telefono_Fijo'],
                    'email1'           => $data['Correo_F_Electronica'],
                    'cf_1209'          => !empty($data['Celular_WhatsApp']) ? $data['Celular_WhatsApp'] : '1', //Celular / WhatsApp  REQUIRED
                    'cf_1331'          => $data['Forma_Pago'], //Forma de Pago
                    'bill_street'      => !empty($data['direccion_cliente']) ? $data['direccion_cliente'] : 'N/A', //REQUIRED
                    'cf_1050'          => !empty($data['Ciudad_cliente']) ? $data['Ciudad_cliente'] : 'COLOMBIA', //Ciudad del Cliente REQUIRED
                    'cf_1048'          => $data['Departamento_cliente'], //Departamento del Cliente
                    'cf_1052'          => $data['pais_cliente'], //Pa¨ªs del Cliente
                    'assigned_user_id' => '19x66', // crm1@casainglesa.co  //REQUIRED
                    'cf_1775'          => 'Sin segmento', //Segmento Cliente CI
                    'cf_1777'          => 'Otro', //Origen del Cliente  REQUIRED
                    'cf_1783'          =>  !empty($data['Zona']) ? str_replace('-',' ', $data['Zona']) : 'PENDIENTE', //Zonificaci¨®n REQUIRED
                    'cf_1443'          => 1, // Enviado al ERP
                ];

            //===== usuario asignado
            $user_id = ! empty($data['Cedula']) ? $this->getUserID($data['Cedula']) : null;
            if ( ! empty($user_id)) {
                $data_datacrm['assigned_user_id'] = $user_id;
            }
            //=====

            $client = $this->vt->entities->createOne('Accounts', $data_datacrm);
        } catch (\Exception $e) {
            $msg = ['method' => 'saveClient', 'data' => ['nit' => $data['nit']], 'error' => $e->getMessage()];
            Log::channel('datacrm')->error(json_encode($msg));
        }

        return $client;
    }

    /**
     * Actualiza un cliente en datacrm.
     *
     * @param $data
     *
     * @return array|null
     * Created by <Rhiss.net>
     */
    public function updateClient($data)
    {
        $client = null;
        $data   = (array)$data;

        try {
            $client = $this->vt->entities->updateOne('Accounts', $data['id'], $data);
        } catch (\Exception $e) {
            $msg = ['method' => 'updateClient', 'data' => ['id' => $data['id']], 'error' => $e->getMessage()];
            Log::channel('datacrm')->error(json_encode($msg));
        }

        return $client;
    }

    //==================================== PRODUCTOS // INMUBLES ====================================//

    /**
     * Obtiene los clientes de datacrm.
     *
     * @param $search
     *
     * @return array
     * @throws \App\Libraries\Salaros\Vtiger\VTWSCLib\WSException
     * Created by <Rhiss.net>
     */
    public function getProducts($search, $limit = 100, $offset = 0)
    {
        $select = [
            'id',
            'cf_1022',
            'cf_1439',
            'productname'
        ];

        $clients = $this->vt->entities->findMany('Products', $search, $select, $limit, $offset);

        return $clients;
    }

    /**
     * Busca un producto en datacrm por su descripcion y referencia.
     *
     * @param $description
     * @param $store
     *
     * @return array
     * Created by <Rhiss.net>
     */
    public function getProduct( $referencia )
    {
        $client = $this->vt->entities->findOne('Products', ['cf_1542' => $referencia]);

        return $client;
    }

    /**
     * Busca un producto en datacrm por ID.
     *
     * @param $id
     *
     * @return array
     * Created by <Rhiss.net>
     */
    public function getProductByID($id)
    {
        $client = $this->vt->entities->findOneByID('Products', $id, ['id', 'cf_1022', 'cf_1439', 'productname']);

        return $client;
    }


    /**
     * Undocumented function
     *
     * @param [type] $skuS
     * @return void
     */
    public function getProductByRef($ref){

        $product = $this->vt->entities->findOne('Products',['cf_1542' => $ref]);
        return $product;

    }

    /**
     * Guarda un producto en datacrm.
     *
     * @param $data
     *
     * @return array|null
     * Created by <Rhiss.net>
     */
    public function saveProduct($data)
    {
        $product = null;

        try {
            $data_datacrm = (array)$data;
           
            $product = $this->vt->entities->createOne('Products', $data_datacrm);
        } catch (\Exception $e) {
            $msg = [
                'method' => 'saveProduct',
                'data'   => [
                    'reference'   => (isset($data['Referencia']))? $data['Referencia']:'',
                                  ],
                'error'  => $e->getMessage()
            ];
            Log::channel('datacrm')->error(json_encode($msg));
        }

        return $product;
    }


    /**
     * Actualiza un producto en datacrm.
     *
     * @param $data
     *
     * @return array|null
     * Created by <Rhiss.net>
     */
    public function updateProduct($data)
    {
        $product = null;

        try {
            $product = $this->vt->entities->updateOne('Products', $data['id'], $data);
        } catch (\Exception $e) {
            $msg = ['method' => 'updateProduct', 'data' => ['id' => $data['id']], 'error' => $e->getMessage()];
            Log::channel('datacrm')->error(json_encode($msg));
        }

        return $product;
    }



    //==================================== COTIZACIONES ====================================//

    /**
     * Busca una cotizaci¨®n en datacrm por su ID.
     *
     * @param $id
     *
     * @return array
     * Created by <Rhiss.net>
     */
    public function getQuote($id)
    {
        $quote = $this->vt->entities->findOne('Quotes', ['id' => $id]);

        return $quote;
    }

    /**
     * Obtiene las cotizaciones de datacrm.
     *
     * @param $search
     *
     * @return array
     * @throws \App\Libraries\Salaros\Vtiger\VTWSCLib\WSException
     * Created by <Rhiss.net>
     */
    public function getQuotes($search, $limit = 100, $offset = 0)
    {
        $quotes = $this->vt->entities->findMany('Quotes', $search, ['*'], $limit, $offset);

        return $quotes;
    }

    /**
     * Actualiza un cotizaci¨®n en datacrm.
     *
     * @param $data
     *
     * @return array|null
     * Created by <Rhiss.net>
     */
    public function updateQuote($data)
    {
        $quote = null;

        try {
            $quote = $this->vt->entities->updateOne('Quotes', $data['id'], $data);
        } catch (\Exception $e) {
            $msg = ['method' => 'updateQuote', 'data' => ['id' => $data['id']], 'error' => $e->getMessage()];
            Log::channel('datacrm')->error(json_encode($msg));
        }

        return $quote;
    }

    //==================================== TAXES ====================================//

    /**
     * Obtiene los impuestos de datacrm.
     *
     * @return array
     * @throws \App\Libraries\Salaros\Vtiger\VTWSCLib\WSException
     * Created by <Rhiss.net>
     */
    public function getTaxes($limit = 100, $offset = 0)
    {
        $clients = $this->vt->entities->findMany('Tax', [], ['*'], $limit, $offset);

        return $clients;
    }

    /**
     * Actualiza el % de iva de un producto de datacrm.
     *
     * @param $data
     *
     * @return array|null
     * Created by <Rhiss.net>
     */
    public function createProductTax($data)
    {
        $tax = null;
        try {
            $tax = $this->vt->entities->createOne('ProductTaxes', $data);
        } catch (\Exception $e) {
            $msg = [
                'method' => 'createProductTax',
                'data'   => ['id' => $data['productid'], 'taxpercentage' => $data['taxpercentage']],
                'error'  => $e->getMessage()
            ];
            Log::channel('datacrm')->error(json_encode($msg));
        }

        return $tax;
    }

    /**
     * Obtiene los impuestos de un producto datacrm.
     *
     * @return array
     * @throws \App\Libraries\Salaros\Vtiger\VTWSCLib\WSException
     * Created by <Rhiss.net>
     */
    public function getProductTaxes($product_id)
    {
        $clients = $this->vt->entities->findMany('ProductTaxes',
            ['productid' => $product_id, 'taxid' => config('vars.iva')]);

        return $clients;
    }

    //==================================== OTROS ====================================//

    /**
     * Ejecuta una consulta SQL.
     *
     * @param $query
     *
     * @return array
     * Created by <Rhiss.net>
     */
    public function runQuery($query)
    {

        return $this->vt->runQuery($query);

    }

    /**
     * @param $module
     * Created by <Rhiss.net>
     */
    public function describeModule($module)
    {
        $modules = new Modules($this->vt);
        $mod     = $modules->getOne($module);
        $arr     = [];

        $stores = [];
        foreach ($mod['fields'] as $f) {
            $fd = [
                'name'      => $f['name'],
                'label'     => $f['label'],
                'mandatory' => $f['mandatory'],
                'nullable'  => $f['nullable'],
                'editable'  => $f['editable'],
                'default'   => $f['default'] ?? '',
                'type'      => [
                    'name' => $f['type']['name'],
                ]
            ];

            if (is_numeric($f['label'])) {
                $stores[$f['label']] = $f['name'];
            }

            if ($fd['type']['name'] == 'picklist') {
                $fd['type']['values'] = implode(', ', array_column($f['type']['picklistValues'], 'value'));
            }
            $arr[] = $fd;
        }


//      foreach ($stores as $k=>$s){
//            echo "'$k' => '$s',".'<br>';
//      }
//
//
//        dd('ok');


        echo "<pre>" . print_r($arr, true) . "</pre>";
        dd('ok');
    }

    /**
     * Created by <Rhiss.net>
     */
    public function getAllModules()
    {
        $all = $this->vt->modules->getAll();
        dd($all);
    }




    /**
     * Obtiene los usuarios de datacrm.
     *
     * @param $search
     *
     * @return array
     * @throws \App\Libraries\Salaros\Vtiger\VTWSCLib\WSException
     * Created by <Rhiss.net>
     */
    public function getUsers($search, $limit = 100, $offset = 0)
    {
        $select = [
            '*'
        ];

        $clients = $this->vt->entities->findMany('Users', $search, $select, $limit, $offset);

        return $clients;
    }


    /**
     * Undocumented function
     *
     * @param [type] $data
     * @return void
     */
    public function saveContact($client){

       
        $client   = (array)$client;
       
        try {
            $contact = $this->vt->entities->createOne('Contacts', $client);

        } catch (\Exception $e) {
            $msg = ['method' => 'saveContact', 'data' => ['lastname' => $client['lastname']], 'error' => $e->getMessage()];
            Log::channel('datacrm')->error(json_encode($msg));
        }

        return $contact;

    }





    /**
     * Undocumented function
     *
     * @param [type] $info
     * @return void
     */
    public function searchContactbyId($id){

        $contacts = $this->vt->entities->findOne('Contacts', ['id' => $id]);
        return $contacts;

    }



    /**
     * Undocumented function
     *
     * @param integer $limit
     * @param integer $offset
     * @return void
     */
    public function getallDocuments($limit = 100, $offset = 0){

        $documents = $this->vt->entities->findMany('Documents', [], ['*'], $limit, $offset);
        return $documents;
    }

    /**
     * Undocumented function
     *
     * @param integer $limit
     * @param integer $offset
     * @return void
     */
    public function getallPotencials($limit = 100, $offset = 0){

        $potenicials = $this->vt->entities->findMany('Potentials', [], ['*'], $limit, $offset);
        return $potenicials;
    }


    /**
     * Undocumented function
     *
     * @param integer $limit
     * @param integer $offset
     * @return void
     */
    public function getSaleStagePotencials($limit = 100, $offset = 0){

        $potenicials = $this->vt->entities->findMany('cf_1331', ['sales_stage'=>'Ejecucion'], ['*'], $limit, $offset);
        return $potenicials;
    }


    /**
     * Undocumented function
     *
     * @param [type] $flokzu_id
     * @return void
     */
    public function getPotentialbyFlokzu($flokzu_id){

        $quote = $this->vt->entities->findOne('Potentials', ['cf_1478' => $flokzu_id]);
        return $quote;
    }



    /**
     * Undocumented function
     *
     * @param [type] $potential_no
     * @return void
     */
    public function getPotentialbyNo($potential_no){

        $quote = $this->vt->entities->findOne('Potentials', ['potential_no' => $potential_no]);
        return $quote;
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function getPotencials($id)
    {
        $quote = $this->vt->entities->findOne('Potentials', ['id' => $id]);
        return $quote;
    }

    /**
     * Undocumented function
    *
    * @param [type] $info
    * @return void
    */
    public function savePotential($info){


        $potential = null;

        $potentialinfo = [

            "potentialname"         => 'Order Shopify '.$info['name'],
            "potential_no"          => $info['order_number'],
            "amount"                => $info['total_price'], 
            "closingdate"           => $info['created_at'],
            "opportunity_type"      => "",
            "leadsource"            => "",

            //shopify_id
            'cf_1331'               => $info['shopify_id'],

            //sales stage
            "sales_stage"           => "Closed Won",

            //Vendedor Por POS
            "assigned_user_id"      => $info['assigned_user_id'],
            //Proviene
            'leadsource'            => 'Website',
            "isconvertedfromlead"   => "1",
            "contact_id"            => $info['contact_id'],
           
            // Business Name
            "potentialname_pick"    => 'Products',
            'createdby'             => '19x47'

        ];


        try {

            $potential = $this->vt->entities->createOne('Potentials', $potentialinfo);

        } catch (\Exception $e) {

            $msg = ['method' => 'savePotential', 'data' => ['potential_no' => $potentialinfo['potential_no']], 'error' => $e->getMessage()];
            Log::channel('datacrm')->error(json_encode($msg));

        }

        return $potential;

    }


    /**
     * Undocumented function
     *
     * @param [type] $data
     * @return void
     */
    public function updatePotential($data)
    {
        $potential = null;
        $data   = (array)$data;
    
        try {
            $potential = $this->vt->entities->updateOne('Potentials', $data['id'], $data);
        } catch (\Exception $e) {
            $msg = ['method' => 'updatePotential', 'data' => ['id' => $data['id']], 'error' => $e->getMessage()];
            Log::channel('datacrm')->error(json_encode($msg).' '.json_encode($data));
        }

        return $potential;
    }


    /**
     * Searchs for potencials
     *
     * @param [type] $search
     * @param integer $limit
     * @param integer $offset
     * @return void
     */
    public function searchPotentials($search, $limit = 100, $offset = 0){

        $select = [ '*'];
        $potentials = $this->vt->entities->findMany('Potentials', $search, $select, $limit, $offset);

        return $potentials;

    }


    /**
     * Undocumented function
     *
     * @param [type] $shopify_id
     * @return void
     */
    public function getPotential($id)
    {
        $client = $this->vt->entities->findOne('Potentials', ['id' => $id]);

        return $client;
    }



    /**
     * Undocumented function
     *
     * @param [type] $info
     * @param [type] $products
     * @return void
     */
    public function saveQuotes($info){
    
        $quotes = null;

        try {

            $quotes = $this->vt->entities->createOne('Quotes', $info);

        } catch (\Exception $e) {

            $msg = ['method' => 'saveQuote', 'data' => ['potential_no' => $info['subject']], 'error' => $e->getMessage()];
            Log::channel('datacrm')->error(json_encode($msg));

        }

        return $quotes;

    }


    /**
     * Undocumented function
     *
     * @param [type] $relationship
     * @return void
     */
    public function relationship($relationship){

        $this->vt->entities->addRelated('Potential',$relationship);
    }


    /**
     * Undocumented function
     *
     * @param [type] $value
     * @return void
     */
    public function cleanDecimals($value){

        $info =explode('.',$value);

        if($info['1']=='000'){
            return $info['0'];
        }else
            return $value;

    }







}

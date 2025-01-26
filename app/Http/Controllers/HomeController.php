<?php

namespace App\Http\Controllers;

use App\Models\DataCrm;
use App\Models\Ejecution;
use App\Models\Flokzu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class HomeController extends Controller
{

    public function test()
    {
        $this->sendToFlokzu('13x501093');
       

        $data = new DataCrm();
        $flok = new Flokzu();

       // $info =  $data->getallPotencials();
      // $pot = $data->getPotencials('13x402263');
       // dd($pot);
       // 
        
       //$this->sendtoDataFacture2('PROPFC-1357');
      // exit();


       $response = $flok->getInstance('FT-SC-03-1093');
       $response_trans = json_decode($response, true);

        $response_convert = array();
        foreach( $response_trans['fields'] as $out){
                
            foreach($out as $key => $value )
                $response_convert[$key] = $value;
        }


        dd($response_convert);
        


        /* dd($response_convert,$this->numberconvertion($response_convert['Valor m² en venta']));
         if($response_convert['Ciudad'] != ''){
            $datacity = $flok->getValueDb($response_convert['Ciudad'],'Ciudades DANE');
            $cityinfo = json_decode($datacity,true);
            $todata['cf_1534'] = $cityinfo['Nombre Ciudad'];
         }*/
       
    
    
    }


    public function numberconvertion($number){

        $number = str_replace(".", "", $number);
        $number = str_replace(",", ".", $number);

        return $number;
    }


    /**
     * Undocumented function
     *
     * @param [type] $id_negocio
     * @return void
     */
    public function sendToFlokzu($id_negocio){

        $data = new DataCrm();
        $flok = new Flokzu();

        
        $msg = ['method' => 'MovetoFlokzu - start', 'data' => ['id' => $id_negocio]];
        Log::channel('flokzu')->error(json_encode($msg));


       // if(Ejecution::where('potential_id',$id_negocio)->count() == 0){

            try {
                $pot = $data->getPotencials($id_negocio);

               
                if($pot != null){

                 //   $created = Ejecution::insert( ['potential_id'=>$id_negocio, 'potential_no'=>$pot['potential_no']]);

                    $contact = $data->searchContactbyId($pot['contact_id']);

                    dd($contact);

                    $user       = $data->getUserID($pot['assigned_user_id']); 
                    if($pot['createdby'] == $pot['assigned_user_id']){
                        $createdby  = $user;
                    }else{
                        $createdby  = $data->getUserID($pot['createdby']);                
                    }

                    $accountname    = '' ;
                    if($pot['related_to'] != ''){
                        $company = $data->getClient($pot['related_to']);


                        if($company != null){
                            $accountname = $company['accountname'];
                            if($company['account_id'] != ''){
                                $umbrella       = $data->getClient($company['account_id']);

                                if($umbrella != null){
                                    $accountname    = $umbrella['accountname'];
                                }
                            }
                        }
                    }

                    $asignby    = $data->getUserID('19x'.$pot['asignedby_user_id']);
                    
                    
                    $createddate = explode(' ',$pot['createdtime']);

                    if($pot['cf_1496'] != ''){
                    $corretaje = explode('-',$pot['cf_1496']);
                    }else{
                        $corretaje[0]='';
                        $corretaje[1]='';
                    }

                   // $prod = $data->getProductByID($pot['potentialname']);

                  // dd($pot);

                    $flokzu = array(

                        //ID Data
                        'ID'   => $pot['potential_no'],

                        // Nombre del Negocio
                        'Nombre del negocio'  => $pot['potentialname'],    

                        //Fecha de Creacion oportunidad
                        'Fecha de creación de la oportunidad'   => $createddate[0],

                        //Fecha de ingreso a Flokzu
                        'Fecha de ingreso a Flokzu'     => date('Y-m-d'),
                        'Fecha estimada de cierre'      =>  $pot['closingdate'],

                        'Probabilidad'                  => str_replace('%', '', $pot['cf_1500']),

                        //Valor de la oportunidad
                        'Valor de la oportunidad'       => round($pot['amount'],2),


                        //Empresa
                        'Empresa'           => isset($company['accountname'])? $company['accountname'] : '',
                        'Nit'               => isset($company['siccode'])? $company['siccode'] : '',
                        'Teléfono empresa'  => isset($company['phone'])? $company['phone'] : '',
                        'Correo empresa'    => isset($company['email1'])? $company['email1'] : '',
                        'País empresa'      => isset($company['bill_country'])? $company['bill_country'] : '',

                        //Pertenece a:
                        'Pertenece a:'       => $accountname,

                        //Contacto
                        'Nombre del cliente'    => ($contact != null)? $contact['lastname'] : '',
                        'Celular'               => ($contact != null)? $contact['mobile'] : '',
                        'Correo del cliente'    => ($contact != null)? $contact['email'] : '',
                        'Cargo'                 => ($contact != null)? $contact['title'] : '',
                        'País'                  => ($contact != null)? $contact['mailingcountry'] : '',
                        'Ciudad'                => ($contact != null)? $contact['cf_1037'] : '',   

                        //Categoria Origen
                        'Categoría de origen'   => $pot['cf_1402'],
                        
                        //Origen
                        'Origen'                => $pot['leadsource'],

                        //Canal
                        'Canal'                 => $pot['cf_1069'],

                        //Línea de negocio
                        'Línea de negocio'      => $pot['sector'],

                        //Especialidad
                        'Especialidad'          => $pot['cf_1135'],

                        //Ubicación negocio
                        'Ubicación negocio'     => $pot['cf_1382'],

                        //Referido
                        'Tipo de referido'      => $pot['cf_1452'],
                        
                        //Numero referido
                        'No. Referido'          => $pot['cf_1173'],

                        //Asignado a    
                        'Asignado a'            => ($user != null)? $user['first_name'].' '.$user['last_name']: '',

                        //Asignado por
                        'Asignado por'          => ($asignby!= null)? $asignby['first_name'].' '.$asignby['last_name']:'',

                        //Descripcion
                        'Descripción del negocio'  => $pot['description'],

                        //Propuesta aprobada
                        // Tiene problemas con los documentos

                        //Tipo de inmueble
                        'Tipo de inmueble'       => $pot['cf_1420'],

                        //Codigo
                        'Código inmueble:'       => $corretaje[0],

                        //Nombre del inmueble
                        'Inmueble'              => $corretaje[1],

                        //Área terreno
                        'Área terreno'          => $data->cleanDecimals($pot['cf_1442']),

                        //Área Construida
                        'Área Construida'       => $data->cleanDecimals($pot['cf_1446']),

                        //Alcance
                        'Alcance'               => $pot['cf_1416'],

                        //Financiamiento
                        'Financiamiento'        => $pot['cf_1394'],

                        //Entidad Financiera
                        'Entidad Financiera'    => $pot['cf_1418']

                        
                    );

                    dd($flokzu);

                     $response   = $flok->newProcessInstance($flokzu,$pot['sector']); 
                    
                    
                    if($response == 'RUNTIME ERROR'){

                        $msg = ['method' => 'MovetoFlokzu', 'data' => ['potential_no' => $id_negocio, 'error' => 'RUNTIME ERROR']];
                        Log::channel('flokzu')->error(json_encode($msg));
                        exit();

                    }else{

                        $flokzu_response    = json_decode($response,true);

                        $updateData['id']       = $pot['id'];
                        $updateData['cf_1478']  = $flokzu_response['reference'];
                        $updateData['cf_1478']  = $flokzu_response['reference'];


                        $info = $data->updatePotential($updateData);
                        Log::info($info);


                        Ejecution::where('potential_id',$id_negocio)->update(['flokzu' => $flokzu_response['reference'],'to_flokzu' => date('Y-m-d')]);


                        $msg = ['method' => 'MovetoFlokzu', 'data' => ['potential_no' => $id_negocio, 'flokzu_id' => $flokzu_response['reference']]];
                        Log::channel('flokzu')->error(json_encode($msg));

                        $flokzu_response['reference'];

                         echo 'SUCCESS';
                        return true;

                    }
                    

                }   

            }
            catch (\Exception $e) {

                $msg = ['method' => 'MovetoFlokzu', 'data' => ['potential_no' => $id_negocio, 'error' => $e->getMessage()]];
                Log::channel('flokzu')->error(json_encode($msg));

            }
        //}
            
        //return 1;
        echo 'SUCCESS';
    }




    /**
     * Undocumented functionEntity
     *
     * @param Request $request
     * @return void
     */
    public function sendtoDataEjecucion(Request $request){

        try{

            $webhook = $request->json()->all();
           
            Log::info($request);
            $msg = ['method' => 'sendtoDataEjecucion', 'data' => ['State'=>'Inital Process','reference' => $webhook['Payload']['reference']]];
            Log::channel('flokzu')->error(json_encode($msg));

                 
            $data = new DataCrm();
            $flok = new Flokzu();

            $response = $flok->getInstance($webhook['Payload']['reference']);
            $response_trans = json_decode($response, true);

            $response_convert = array();
            foreach( $response_trans['fields'] as $out){
                
                foreach($out as $key => $value )
                    $response_convert[$key] = $value;
            }

            if( $response_trans['lockStatus'] == 3){
        
                $pot = $data->getPotentialbyFlokzu($webhook['Payload']['reference']);

                if($pot == null){
                    $pot = $data->getPotentialbyNo($response_convert['ID']);
                }


                if($pot == null){
                     Log::channel('flokzu')->error(['pot'=>null,'reference'=>$webhook['Payload']['reference'],'ID'=>$response_convert['ID']]);
                     exit();
                }
               
                $updatedata['id']           = $pot['id'];         
                $updatedata['cf_1490']      = date('Y-m-d');


                if($webhook['Type'] == 'task_complete'){

                    $result = $data->updatePotential($updatedata);
                    Log::channel('datacrm')->error( ['updated'=>'Potential after closed process in flokzu','id'=>$result['id']] );

                }

                echo 'SUCCESS';
                return true;
            }

        }catch (\Exception $e) {

            $msg = ['method' => 'sendtoDataEjecucion', 'data' => ['Flokzu' => $webhook['Payload']['reference'], 'error' => $e->getMessage()]];
            Log::channel('flokzu')->error(json_encode($msg));

        }

        echo 'SUCCESS';
        return true;

    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function sendtoDataFacture2(Request $request){

        $data = new DataCrm();
        $flok = new Flokzu();

        Log::channel('flokzu')->error('start Facture '.$request->process);

        try{

        
            $response = $flok->getInstance($request->process);

            $response_trans = json_decode($response, true);

            Log::channel('flokzu')->error(json_encode($response));
          
            $response_convert = array();

            if( $response_trans['lockStatus'] == 3 ){

                foreach( $response_trans['fields'] as $out){
                    
                    foreach($out as $key => $value )
                        $response_convert[$key] = $value;
                }
    
           
                if($response_convert['ID'] != ''){
                    $pot = $data->getPotentialbyNo($response_convert['ID']);

                        if($pot != null){
                        
                            $updatedata['id']           = $pot['id'];
                            $updatedata['sales_stage']  = 'Facturacion/Cierre';
                            $updatedata['cf_1492']      = $response_convert['Concepto de Facturación'];

                            if($response_convert['Centro de costo'] != ''){
                                $db_response = $flok->getValueDb($response_convert['Centro de costo']);
                                $costs       = json_decode($db_response,true);
        
                                $updatedata['cf_1450']  = $costs['Centro de Costo'];
                            }

                            $updatedata['cf_1504']  = $response_convert['Número de Factura Colliers'];
                            $updatedata['cf_1500']  = '100%';

                            $updatedata['cf_1482']      = $response_convert['Fecha de Expedición Factura Colliers'];
                            $updatedata['cf_1484']      = $response_convert['Valor de la comisión a facturar'];

                            $data->updatePotential($updatedata);
                        }


                        $contact['lastname']        = $response_convert['Contacto cartera'];
                        $contact['mobile']          = $response_convert['Teléfono cartera'];
                        $contact['email']           = $response_convert['Correo facturación electrónica'];
                        $contact['assigned_user_id']= $pot['assigned_user_id'];

                        if($contact['mobile'] != '' && $contact['lastname'] != ''){
                            
                            $contactinfo = $data->saveContact($contact);
                            $test['record_id']  = $pot['id'];
                            $test['related_id'] = $contactinfo['id'];
                            $data->relationship($test);
                        }


                        echo 'SUCCESS';
                        return true;

                        //Log::info($contact);
                }
            }


        }catch(\Exception $e) {
            $msg = ['method' => 'sendtoDataFacture', 'data' => ['Flokzu' => $request->process, 'error' => $e->getMessage()]];
            Log::channel('flokzu')->error(json_encode($msg));
        }

        echo 'SUCCESS';
        return true;


    }
    
    
    public function productComerccial(Request $request){

        $data = new DataCrm();
        $flok = new Flokzu();
        $webhook = $request->json()->all();
        $response = $flok->getInstance($webhook['Payload']['reference']);

        

     try{
      
          $response = $flok->getInstance( $webhook['Payload']['reference'] );    

            
            $response_trans = json_decode($response, true);

            $response_convert = array();
            foreach( $response_trans['fields'] as $out){
                    
                foreach($out as $key => $value )
                    $response_convert[$key] = $value;
            }

            
            if(!empty($response_convert)){

                if( $response_trans['lockStatus'] == 3){
                
                        $todata['cf_1542'] = $response_trans['reference'];     
                        $todata['cf_1123'] = $response_convert['Tipo de Inmueble']; 
                        $todata['cf_1528'] = $response_convert['Barrio/Municipio/Vereda'];
                        $todata['cf_1263'] = $response_convert['Dirección Completa'];

                        $todata['cf_1530'] = $response_convert['Corredor'];
                        if($response_convert['Ciudad'] != ''){
                            $datacity = $flok->getValueDb($response_convert['Ciudad'],'Ciudades DANE');
                            $cityinfo = json_decode($datacity,true);
                            $todata['cf_1534'] = $cityinfo['Nombre Ciudad'];
                        }

                        $todata['cf_1556'] = $response_convert['Regional'];
                        $todata['cf_1536'] = $response_convert['Estrato'];

                        $todata['cf_1127'] = $response_convert['Tipo de Transacción'];
                        $todata['cf_1554'] = $this->numberconvertion($response_convert['Valor m² en venta']);

                        $todata['cf_1552'] = $this->numberconvertion($response_convert['Valor m² en renta']);
                        $todata['cf_1572'] = $response_convert['Valor de Comercialización'];

                        $todata['cf_1578'] = $response_convert['Año de Construcción'];
                        $todata['cf_1580'] = $response_convert['Área'];

                        $todata['cf_1582'] = $response_convert['Ficha de Publicación del Inmueble'];
                        $todata['cf_1584'] = 'Corporativo';

                        $todata['cf_1601'] = $response_convert['Representación'];
                        $todata['cf_1626'] = $response_convert['Valor Administración'];

                        $todata['cf_1630'] = $response_convert['Área'];
                        $todata['cf_1628'] = $response_convert['Valor de administración m²'];

                        $todata['cf_1632'] = $response_convert['Área Mezzanine'];
                        $todata['cf_1634'] = $response_convert['Área Maniobras'];
                        
                        $todata['cf_1603'] = $response_convert['Nombre y/o Razón social del Cliente'];
                        $todata['cf_1614'] = $response_convert['Tipo de Identificación']; /// En FLokzu pueden escribir y en data elegir puede ser dificl que quede igual
                        
                        $todata['cf_1616'] = $response_convert['Documento'];
                        $todata['cf_1257'] = $response_convert['Número célular/Fijo'];

                        $todata['cf_1259'] = $response_convert['Correo Electrónico'];
                    // $todata['cf_1570'] = $response_convert['Correo Electrónico']; Direccion del cliente no es clara en flokzu

                    $todata['cf_1636'] = ($response_convert['Fotografía Aérea'] == 'false') ? 'No':'Si';
                    $todata['cf_1638'] = $response_convert['Fecha Fotografía Aérea'];

                    $todata['cf_1640'] = ($response_convert['Vídeo Profesional'] == 'false') ? 'No':'Si';
                    $todata['cf_1642'] = $response_convert['Fecha Vídeo Profesional'];

                    $todata['cf_1644'] = ($response_convert['E-Mailing'] == 'false') ? 'No':'Si';
                    $todata['cf_1646'] = $response_convert['Fecha E-Mailing'];

                    $todata['cf_1648'] = ($response_convert['Tarjeta'] == 'false') ? 'No':'Si';
                    $todata['cf_1650'] = $response_convert['Fecha Tarjeta'];

                    $todata['cf_1652'] = ($response_convert['Volante'] == 'false') ? 'No':'Si';
                    $todata['cf_1654'] = $response_convert['Fecha Volante'];

                    $todata['cf_1656'] = ($response_convert['Revista'] == 'false') ? 'No':'Si';
                    $todata['cf_1658'] = $response_convert['Fecha Revista'];

                    $todata['cf_1660'] = ($response_convert['Prensa'] == 'false') ? 'No':'Si';
                    $todata['cf_1662'] = $response_convert['Fecha Prensa'];

                    $todata['cf_1664'] = ($response_convert['Brochure'] == 'false') ? 'No':'Si';
                    $todata['cf_1666'] = $response_convert['Fecha Brochure'];

                    $todata['cf_1668'] = ($response_convert['Publicidad exterior Visual'] == 'false') ? 'No':'Si';
                    $todata['cf_1670'] = $response_convert['Fecha Publicidad exterior Visual'];

                    $todata['cf_1672'] = ($response_convert['Hablador'] == 'false') ? 'No':'Si';
                    $todata['cf_1674'] = $response_convert['Fecha Hablador'];

                    $todata['cf_1676'] = ($response_convert['Destacados Página Web'] == 'false') ? 'No':'Si';
                    $todata['cf_1678'] = $response_convert['Fecha Destacados Página Web'];

                    $todata['cf_1680'] = ($response_convert['Destacados Portales'] == 'false') ? 'No':'Si';
                    $todata['cf_1682'] = $response_convert['Fecha Destacados Portales'];

                    $todata['cf_1684'] = ($response_convert['Reels'] == 'false') ? 'No':'Si';
                    $todata['cf_1686'] = $response_convert['Fecha Reels'];

                    $todata['cf_1688'] = ($response_convert['Pauta Paga'] == 'false') ? 'No':'Si';
                    $todata['cf_1690'] = $response_convert['Fecha Pauta Paga'];

                    $todata['cf_1692'] = ($response_convert['Stories'] == 'false') ? 'No':'Si';
                    $todata['cf_1694'] = $response_convert['Fecha Reels'];

                    $todata['cf_1696'] = ($response_convert['Landing Page'] == 'false') ? 'No':'Si';
                    $todata['cf_1698'] = $response_convert['Fecha Landing Page'];

                    $todata['cf_1700'] = ($response_convert['Pieza LinkedIn'] == 'false') ? 'No':'Si';
                    $todata['cf_1702'] = $response_convert['Fecha Pieza LinkedIn'];

                    $todata['productname'] = ($response_convert['Nombre del Inmueble'] != '')?  $response_convert['Nombre del Inmueble'] : $response_convert['Nombre del inmueble para agregar a la base'];                   
                    $user = $data->getUserEmail($response_convert['Correo Eléctronico Consultor']); //A Quien se le asigna si no existe en el CRM

                    if($user != null)
                        $todata['assigned_user_id'] = $user['id'];

                    $todata['discontinued']     = true;


                    $info = $data->getProductByRef($response_trans['reference']);

            

                    if($info == null){
                       $data->saveProduct($todata);
                       echo 'saved';
                    }else{
                        $todata['id'] = $info['id'];
                        $data->updateProduct($todata);
                        echo 'updated';
                    }
                }
            }

       

        }catch(\Exception $e) {
            $msg = ['method' => 'loadProduct', 'data' => ['Flokzu' => $webhook['Payload']['reference'], 'error' => $e->getMessage()]];
            Log::channel('flokzu')->error(json_encode($msg));
        }

        echo 'SUCCESS';
        return true;
    }



    /**
     * Gets 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function productNonComercial(/*Request $request*/){

        $data = new DataCrm();
        $flok = new Flokzu();

    
        //$webhook = $request->json()->all();
        $response = $flok->getInstance('CON-1830','Residencial');

       
        try{

            $response_trans = json_decode($response, true);

            $response_convert = array();
            foreach( $response_trans['fields'] as $out){
                    
                foreach($out as $key => $value )
                    $response_convert[$key] = $value;
            }

            
           // dd($response_convert);


            if(!empty($response_convert)){

              //  if( $response_trans['lockStatus'] == 3){

                    $todata['cf_1542'] = $response_trans['reference']; 

                    $todata['cf_1263'] = $response_convert['Dirección Completa Inmueble'];
                    $todata['cf_1123'] = $response_convert['Tipo de Inmueble'];
                    $todata['cf_1528'] = $response_convert['Barrio'];
                    $todata['cf_1530'] = $response_convert['Zona'];
                    $todata['cf_1536'] = $response_convert['Estrato'];

                    $todata['cf_1630'] = $response_convert['Área Privada'];

                  


                    if($response_convert['Ciudad'] != ''){
                        $datacity = $flok->getValueDb($response_convert['Ciudad'],'Ciudades DANE','Residencial');
                        $cityinfo = json_decode($datacity,true);
                        $todata['cf_1534'] = $cityinfo['Nombre Ciudad'];
                    }

                    $todata['cf_1578'] = $response_convert['Año de Construcción'];
                    $todata['cf_1127'] = ($response_convert['Tipo de Gestión Comercial'] == 'Renta')? 'Renta':'Venta';

                    if($response_convert['Tipo de Gestión Comercial'] == 'Renta')
                        $todata['cf_1572'] = $todata['unit_price'] = $this->numberconvertion($response_convert['TOTAL']);
                    else
                        $todata['cf_1572'] = $todata['unit_price'] = $this->numberconvertion($response_convert['Valor']);
                        

                    $todata['cf_1574'] = $response_trans['reference'].' '.$response_convert['Tipo de Inmueble'];
                    
                    $todata['cf_1580'] = $response_convert['Área Construida'];
                    $todata['cf_1582'] = $response_convert['Ficha de Publicación del Inmueble'];
                    $todata['cf_1758'] = $response_convert['Código DOMUS'];
                    $todata['cf_1584'] = 'Residencial';
                    $todata['cf_1601'] = ($response_convert['Tipo de Acuerdo Comercial'] == 'No Exclusiva')? 'No Exclusiva':'Exclusiva';

                    $todata['cf_1626'] = $response_convert['Valor de la Administración'];

                    $todata['productname'] = $response_trans['reference'].' '.$response_convert['Tipo de Inmueble'];

                  

                    if($response_convert['Datos Propietario'][0]['Nombre'] != ''){

                            $todata['cf_1603'] = $response_convert['Datos Propietario'][0]['Nombre'];
                            $todata['cf_1259'] = $response_convert['Correo Electrónico'];
                            $todata['cf_1614'] = 'CC';
                            $todata['cf_1616'] = $response_convert['Datos Propietario'][0]['C.C.'];
                            $todata['cf_1257'] = $response_convert['Datos Propietario'][0]['Celular'];
                            $todata['cf_1570'] = $response_convert['Datos Propietario'][0]['Dirección Residencia'];

                    }else{
                        

                            $todata['cf_1603'] = $response_convert['Datos Propietario 1'][0]['Nombre'];
                            $todata['cf_1259'] = $response_convert['Correo Electrónico Propietario 1'];
                            $todata['cf_1614'] = 'CC';
                            $todata['cf_1616'] = $response_convert['Datos Propietario 1'][0]['C.C.'];
                            $todata['cf_1257'] = $response_convert['Datos Propietario 1'][0]['Celular'];
                            $todata['cf_1570'] = $response_convert['Datos Propietario 1'][0]['Dirección Residencia'];


                    }
                
                  
                    $user = $data->getUserEmail($response_convert['Correo Electrónico Captador(a)']); //A Quien se le asigna si no existe en el CRM
                    
                    if($user != null){
                        $todata['assigned_user_id'] = $user['id'];
                    }

                    $todata['discontinued'] = true;


                    $info = $data->getProductByRef($response_trans['reference']);



                    if($info == null){
                        $data->saveProduct($todata);
                        echo 'new';
                    }else{
                        $todata['id'] = $info['id'];
                        $data->updateProduct($todata);
                        echo 'updated';
                    }
               // }
            }
            
                   
        }catch(\Exception $e) {
            $msg = ['method' => 'loadProductNon', 'data' => ['Flokzu' => 'CON-1830 ', 'error' => $e->getMessage()]];
            Log::channel('flokzu')->error(json_encode($msg));
        }

        echo 'SUCCESS';
        return true;
    }

    
   
}

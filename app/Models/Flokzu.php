<?php

namespace App\Models;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;


class Flokzu extends Model
{


    private $apiKey;
    private $apiKeyUser;
    private $XUsername;
    private $process_code;
    private $invoice_process;
    private $apiKey_caseres;

    public function __construct()
    {
        $this->apiKey           = '5df78a47915b71525f0a138071a158aa0b5b2a77c252a41b';
        $this->apiKeyUser       = '5e3abf8b326f3fed153af79f7e3f27f3c22ca8267bb34696';

        $this->XUsername        = 'sebastian@rhiss.net';

        $this->process_code     = 'EJC';  
        $this->invoice_process  = 'PROPFC';  

        $this->apiKey_caseres   = '239da19dd90cab6aadc4c0a5d880654280e41c7a6cb1dc13';

    }


    /**
     * Creates a new Process instance in flokzu returns json with id 
     *
     * @param [type] $flokzu
     * @return void
     */
    public function newProcessInstance($flokzu,$sector=''){

        $ch = curl_init();

       /* $api = $this->apiKey;
        if($sector == 'Residencial'){
            $api = $this->apiKey_caseres;
        }*/


      /*  curl_setopt($ch, CURLOPT_URL, "https://app.flokzu.com/flokzuopenapi/api/5df78a47915b71525f0a138071a158aa0b5b2a77c252a41b/instance?processCode=EJC");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($flokzu,true) );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "X-Api-Key:     5df78a47915b71525f0a138071a158aa0b5b2a77c252a41b",
            "X-Username:    ".$this->XUsername,
            "X-ForceUser:   true"
        ));*/

        $info = array(
            "processId" => "EJC",
            'data'      => $flokzu
        );

      //  dd(json_encode($info,JSON_UNESCAPED_UNICODE));

        $headers = [
            "Content-Type"  => "application/json",
            "X-Api-Key"     => "5df78a47915b71525f0a138071a158aa0b5b2a77c252a41b",
            "X-Username"    => $this->XUsername,
            "X-ForceUser"   => true
        ];

        /*try {

            $url = "https://app.flokzu.com/flokzuopenapi/api/5df78a47915b71525f0a138071a158aa0b5b2a77c252a41b/instance?processCode=EJC";
            $url = 'https://app.flokzu.com/flokzuopenapi/api/v2/process/instance';

            $client = new Client([
                'headers' => $headers
            ]); 

            dd( json_encode($info,true));

            $response = $client->request('POST', $url,[
                'body' => json_encode($info,true)
            ]);

            dd( $response, $response->getBody()->getContents() );


        } catch(Exception $e) {
            dd($e->getCode(), $e->getMessage());
            trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);

               
        
        } */

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://app.flokzu.com/flokzuopenapi/api/v2/process/instance");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($info,true));

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "X-Api-Key: 5df78a47915b71525f0a138071a158aa0b5b2a77c252a41b",
        "X-Username: sebastian@rhiss.net",
        "X-ForceUser: true"
        ));

        $response = curl_exec($ch);

        dd($response);
        curl_close($ch);

        var_dump($response);


       // $response = curl_exec($ch);
      //  curl_close($ch);

       // return $response;

    }


    


    /**
     * Updates info in process
     *
     * @param array  $flokzu
     * @param string $instance_id
     * @return void
     */
    public function updateProcessInstance($flokzu,$instance_id,$sector=''){

        $ch = curl_init();

        $api = $this->apiKey;
        if($sector == 'Residencial'){
            $api = $this->apiKey_caseres;
        }

        curl_setopt($ch, CURLOPT_URL, "https://app.flokzu.com/flokzuopenapi/api/".$api."/instance?identifier=".$instance_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($flokzu) );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "X-Api-Key: ".$api,
            "X-Username: ".$this->XUsername
        ));

        $response = curl_exec($ch);
        curl_close($ch);


        return $response;

    }


    /**
     * return Instance Info
     *
     * @param string $instance_id
     * @return void
     */
    public function getInstance($instance_id,$sector=''){

        $instance_id = trim($instance_id);

        $api = $this->apiKey;
        if($sector == 'Residencial'){
            $api = $this->apiKey_caseres;
        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://app.flokzu.com/flokzuopenapi/api/".$api."/instance?identifier=".$instance_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "X-Api-Key: ".$api,
            "X-Username: ".$this->XUsername
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }


    /**
     * Undocumented function
     *
     * @param [type] $instance_id
     * @return void
     */
    public function getInstanceInvoice($instance_id){

        $instance_id = trim($instance_id);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://app.flokzu.com/flokzuopenapi/api/".$this->apiKey."/instance?identifier=".$instance_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "X-Api-Key: ".$this->apiKey,
            "X-Username: ".$this->XUsername
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }



    



    /**
     * Undocumented function
     *
     * @param [type] $flokzu
     * @return void
     */
    public function uploadFile($flokzu){


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://app.flokzu.com/flokzuopenapi/api/".$this->apiKey."/instances/file?reference=REFERENCE&attachmentName=ATTACHMENTNAME&fieldLabel=FIELDNAME");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($flokzu) );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "X-Api-Key: ".$this->apiKey,
        "X-Username: ".$this->XUsername,
        "X-ForceUser: true"
        ));

    }



    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function getValueDb($id,$db='Centros de Costos',$sector=''){

        $api = $this->apiKey;
        if($sector == 'Residencial'){
            $api = $this->apiKey_caseres;
        }

        try {

            $url = "https://app.flokzu.com/flokzuopenapi/api/".$api."/database/".trim($db)."?paramName=Id&paramValue=".trim($id);

            $client = new Client(); 
            $response = $client->request('GET', $url);

            return $response->getBody()->getContents() ;


        } catch(Exception $e) {

            trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);
        
        } 
    }


}

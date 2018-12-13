<?php
namespace App\Dependencies;
use Exception;
class CurlRequest{
    protected $curl;
    public function __construct($url)
    {
        $this->curl=curl_init($url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }
    public function set_post_fields($fields,$isJson=false)
    {
        if($isJson){
            $fields=json_encode($fields);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($fields)
            ));
            
        }

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fields);
    }
    public function set_custom_options($options)
    {
        curl_setopt_array($this->curl,$options);
    }
    public function execute()
    {
        if($this->curl){
            $result = curl_exec($this->curl);
            
            $err = curl_error($this->curl);
            curl_close($this->curl);
            if($err){
                return Exception("An Error Occured (".$err.")");
            }
            return $result;
        }
    }
    public function execute_and_parse_json()
    {
        $result=$this->execute();
        return json_decode($result);
    }

}
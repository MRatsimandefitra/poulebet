<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Api\CommonBundle\Utils;
use Symfony\Component\DependencyInjection\Container;
/**
 * Description of Http
 *
 * @author miora.manitra
 */
class Http {
    
    private $container;
    private $ch;
    private $headers;
    
    function __construct(Container $container)
    {
        $this->container = $container;
        $this->ch = curl_init();
        $this->headers = array('Content-type: application/json');
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        $this->doPost();
    }
    public function setUrl($url){
        curl_setopt($this->ch, CURLOPT_URL,$url);
    }
    public function doPost(){
        curl_setopt($this->ch, CURLOPT_POST,           1 );
        curl_setopt($this->ch, CURLOPT_HTTPGET,           0);
    }
    public function doGet(){
        curl_setopt($this->ch, CURLOPT_HTTPGET,           1 );
        curl_setopt($this->ch, CURLOPT_POST,           0 );
    }
    public function setHeaders($header){
        array_push($this->headers, $header);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->headers);
    }
    public function setRawPostData($data){
        curl_setopt($this->ch, CURLOPT_POSTFIELDS,     $data ); 
    }
    
    public function execute(){
        $result = curl_exec ($this->ch);
        if (curl_errno($this->ch))
        {
            echo 'GCM error: ' . curl_error($this->ch);
        }
        return $result;
    }
}

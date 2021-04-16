<?php

namespace WooKapsula;

Class Request {

	public function __construct($route){
		
		$this->api_url = "https://ev.kapsula.com.br/api/v1/" . $route;
		
	}

	private $api_url;

	public function post($data){

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->api_url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data );
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}

	public function get(){

	}
}
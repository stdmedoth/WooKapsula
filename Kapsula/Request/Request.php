<?php

namespace Kapsula;

Class Request {

	public function __construct($route){

		$this->api_url = "https://ev.kapsula.com.br/api/v1/" . $route;

		$this->token = __KAPSULA_TOKEN__;

		$this->headers = array(
			'Authorization: '.'Bearer '. $this->token
		);
	}

	private $api_url;

	private $token;

	private $headers;

	public function post($data){

		$this->headers[] = 'Content-Type:application/json';

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $this->api_url);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_FAILONERROR, FALSE);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data );
		$result = curl_exec($curl);
		curl_close($curl);

		return $result;

	}

	public function get( $id = null ){

		$url = $this->api_url;
		if($id){
			$url = $this->api_url . '/' . $id;
		}
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($curl, CURLOPT_FAILONERROR, FALSE);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);


		$result = curl_exec($curl);
		$info = json_decode(json_encode(curl_getinfo($curl)));

		curl_close($curl);

		if(!$result || $info->http_code != 200 ){
			return $info;
		}

		$objects = json_decode($result);
		return $objects;
	}

	public function put($id, $data){

		if(!$id){
			return null;
		}
		$data_json = json_encode($data);
		$url = $this->api_url . '/' . $id;
		$this->headers[] = 'Content-Type:application/json';
		$this->headers[] = 'Content-Length: ' . strlen($data_json);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		//curl_setopt($curl, CURLOPT_PUT, TRUE);
		//curl_setopt($curl, CURLOPT_POSTFIELDS, $data );

		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_FAILONERROR, FALSE);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_json);

		$result = curl_exec($curl);
		curl_close($curl);

		return $result;

	}
}

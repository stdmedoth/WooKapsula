<?php

namespace Kapsula;

Class Request {

	public function __construct($route){
		
		$this->api_url = "https://ev.kapsula.com.br/api/v1/" . $route;

		$this->token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI2IiwianRpIjoiZjllNDYyYWM4MjlkYTRkMDQzZDU0NDNlNjgzZTE1YTdiNjU5ZTZkMjBiOTUzMWFlMmU3M2VjNmE5MGNmN2ZkNjk2MTQyNGRiNWM4NTYyZjkiLCJpYXQiOjE2MTg1MjcwODksIm5iZiI6MTYxODUyNzA4OSwiZXhwIjoxNjUwMDYzMDg5LCJzdWIiOiI5NzciLCJzY29wZXMiOltdfQ.V7Vp-bCX9EdjA-tIte9zlvGyeJt3gZgonYzwq3Gt0xYWFRABVFwSZOf3qv14e1bQp-2q_9iOJZHVwospIE6ciVjLVMKPsHjCnAZ7WzZXnQqR3NhC6yvDg1Ho0g9f8i0j2fy5s7ZEDV12Bs_PAd2FfxH4BBPtySxnXLuH4tZmLqpHtveEYpYV5ex2gmpUc--zino6oWqNjktyGNSGsLBOt8_j7UbJuoMktE8RiASasddOYzg-zJSPJwueU3M1vtrMwegx4MAE9mT-Dj9GPZFWhOA4d3q4stgc-s5TFTv30p6nWjP8Aw5q3K6cdzcCn0ktLi40thSPrxB8ra-mFJXPAKcRqHNP6RV9QrKlZenlmwSzdbAF86_lg0EkU9S6Y9y79QcRI3ebQiz2vNSsqgmhZ7lSeWnCYayZJXCLYv65kLNgqmmpSi18zKo_GzublfR0BgR_otKaHDEOLsJYpsbjCckCh7YMOiwMvaMFw0uvmiWUWw1TSkF2BZMij9pj4tj9_18KK2tzSB5TQYhx-LIsarCmciZ7lIW1kKwpYHyC08sNlnpKekluzmekxp7534K2wN0o5q5LJq--R6Xpk_SrvqFEZzZcTYhkQ4_ICTzgwrICwQIHLvMl3A-EEl02TEkzJZsFkeK4ghRcP31QEMt5AUH2dwzdgicm8gyFKw4JPRA";

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

		if(!$result || $info->http_code[0] == 3){
			return $info;
		}

		$objects = json_decode($result);
		return $objects;
	}
}
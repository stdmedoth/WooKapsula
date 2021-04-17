<?php

namespace Kapsula;

use Kapsula\Request;

Class Element {

	public function __construct($route){
		$this->route = $route;
		$this->request = new Request($route);
	}

	public $request;
	public $route;
	public $objects;

	public function post(){
		$response = $this->request->post($this->to_json());
		return json_decode($response);		
	}

	public function get( $id = null ){
		$obj = substr($this->route, 0, -1);
		if($id){
			$this->objects = $this->request->get( $id )->{"$obj"};
			foreach ($this->objects as $key => $value) {
				$this->{$key} = $value;
			}
		}else{
			$this->objects = $this->request->get();
		}
		return $this->objects;			
	}

	public function put( $data ){
		if( !$data ){
			return null;
		}
		$payload = json_encode($data);
		return $this->request->put($this->id, $payload);			
	}


   	public function to_json(){
        return json_encode(get_object_vars($this));    
    }



}    

<?php

namespace Kapsula;

use Kapsula\Request;

Class Element {

	public function __construct($route){
		$this->request = new Request($route);
	}

	public $request;
	public $objects;

	public function post(){
		$response = $this->request->post($this->to_json());
		return json_decode($response);		
	}

	public function get( $id = null ){
		if($id){
			$this->objects = $this->request->get( $id );
			foreach ($this->objects as $key => $value) {
				$this->{$key} = $value;
			}
		}
		else{
			$this->objects = $this->request->get();
		}
		return $this->objects;			
	}

   	public function to_json(){
        return json_encode(get_object_vars($this));    
    }

}    

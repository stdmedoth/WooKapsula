<?php

namespace WooKapsula;

use WooKapsula\Request;

Class Element {

	public function __construct($route){
		$this->request = new Request($route);
	}

	public $request;

	public function post(){
		return $this->request->post($this->to_json());		
	}

   	public function to_json(){
        return json_encode(get_object_vars($this));    
    }

}    

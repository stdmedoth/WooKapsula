<?php

namespace Kapsula;

Class Produto extends Element{

	public function __construct( $id = null ){
		parent::__construct('produtos');
		$this->data_obj = 'produto';
		if($id){
			$this->id;
			$this->get($id);
		}
	}

	public $id;
  	public $nome;
  	public $status;
		public $quantidade;

}

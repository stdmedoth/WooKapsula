<?php

namespace Kapsula;


Class Pacote extends Element{

	public function __construct( $id = null ){
		parent::__construct('pacotes');
		$this->data_obj = 'produtos';
		$this->status = 1;
		if($id){
			$this->id;
			$this->get($id);
		}
	}

  	public $id;
  	public $nome;
  	public $status;
  	public $produtos;

}
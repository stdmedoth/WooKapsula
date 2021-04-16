<?php

namespace WooKapsula;

Class Pacote extends Element{

	public function __construct(){
		parent::__construct('pacotes');
	}

  	public $id;
  	public $nome;
  	public $status;
  	public $produtos;

}
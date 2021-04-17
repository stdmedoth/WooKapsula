<?php 

namespace WooKapsula;
use WC_Product;
use Kapsula\Produto;

class WCK_Product extends WC_Product implements WCK_Integration{

	public function __construct($arg){
		parent::__construct($arg);
	} 

	public function Wc_to_Kapsula(){
		
		$produto = new Produto();
		$produto->nome = $this->get_name();
		$produto->status = 1;

		return $produto;
	}

	public function populate_from_Kapsula($Produto){
		$this->get_name($Produto->nome);
	}

}
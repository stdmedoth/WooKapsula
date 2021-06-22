<?php

namespace WooKapsula;
use WC_Product;
use Kapsula\Produto;

class WCK_Product extends WC_Product implements WCK_Integration{

	public function __construct($arg = []){
		parent::__construct($arg);
	}

	public function Wc_to_Kapsula(){

		$produto = new Produto();
		$produto->nome = $this->get_name();
		$produto->status = 1;

		return $produto;
	}

	public function populate_from_Kapsula($Produto){
		$this->set_name($Produto->nome);
		$this->set_price(0);
		$this->save();
		update_post_meta($this->get_id(),'id_kapsula', $Produto->id);
		return $this;
	}

}

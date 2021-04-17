<?php 

namespace WooKapsula;
use WC_Order_Item_Product;
use Kapsula\Produto;
use WooKapsula\WCK_Product;

class WCK_Order_Item_Product extends WC_Order_Item_Product implements WCK_Integration{

	public function __construct($item){

		foreach (get_object_vars($item) as $key => $value) {
            $this->$key = $value;
        }
		
	}

	public function Wc_to_Kapsula(){
		
		$produto = new Produto();
		$produto->nome = $this->get_name();
		$produto->status = $this->get_name();
		
		return $produto;
	}

	public function populate_from_Kapsula($Pacote){
		
	}
}
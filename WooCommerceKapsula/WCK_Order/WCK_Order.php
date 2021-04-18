<?php 

namespace WooKapsula;
use Kapsula\Pedido;
use Kapsula\Pacote;
use WooKapsula\WC_Order_Item_Product;
use WC_Order;

class WCK_Order extends WC_Order implements WCK_Integration{
	
	public function __construct($arg){
		parent::__construct($arg);
	} 

	/*returns Kapsula\Pedido*/
	public function Wc_to_Kapsula(){
		
		global $wookapsula_errors;

		$pedido = new Pedido();
		
		$customer_id = $this->get_customer_id();
		$pedido->cliente_id = get_user_meta($customer_id, 'id_kapsula')[0];
		if(!$pedido->cliente_id){
				
			if(!$customer_id){
				$wookapsula_errors->add(  'message', 'Cliente do pedido não possui login' );
				return NULL;
			}

			$woocliente = new WCK_Customer($customer_id);
			//var_dump($woocliente);
			//die();
			$ClienteKapsula = $woocliente->Wc_to_Kapsula();
			//var_dump($ClienteKapsula);
			//die();

			$return = $ClienteKapsula->post();
			
			if( $return->code == 200 ){
				add_user_meta($customer_id,  'id_kapsula', $return->cliente);
			}else{	
				$wookapsula_errors->add(  'message', 'Erro ao integrar cliente do pedido' );
				return NULL;
			}
			
		}

		$pacote = $this->get_Kapsula_pacote();
		$pedido->pacote_id = $pacote->id;
		if(!$pacote->id){
			$wookapsula_errors->add(  'message', 'O pacote não foi inserido' );
		}
		$pedido->tipo_frete = 0;
		$pedido->valor_venda = 0;
		$pedido->valor = 0;

		return $pedido;
	}


	public function set_enviado($flag){
		update_post_meta($this->ID,'_kapsula_sended', $flag);
	}

	public function get_enviado(){
		return  get_post_meta($this->ID, '_kapsula_sended');
	}

	public function get_Kapsula_pacote(){
		
		$pacote = new Pacote();
		$items = [];
		foreach ($this->get_items() as $key => $value) {
			$item = new WCK_Order_Item_Product($value);
			if(count($items))
				$pacote->nome .= ' + ';

			$product = new WCK_Product($item->get_product_id());
			$pacote->nome .= $product->get_name();
			$pacote->id = $product->get_meta('kapsula_package');
			
			$items[] = $product->Wc_to_Kapsula();
		}
		$pacote->produtos = $items;
		return $pacote;
	}

	/*returns WC_Order*/
	public function populate_from_Kapsula($pedido){
	
		$this->customer_id = $pedido->cliente_id;
		
		//$pacote = new Pacote($pedido->pacote_id);
		//$order = new WC_Order(15);
		//$this->items = $pacote->to_items();
		//var_dump($order->get_items());
		//die();
	}
}




<?php 

namespace WooKapsula;
use Kapsula\Pedido;
use Kapsula\Pacote;
use WC_Order;

class WCK_Order extends WC_Order implements WCK_Integration{
	
	public function __construct($arg){
		parent::__construct($arg);
	} 

	/*returns Kapsula\Pedido*/
	public function Wc_to_Kapsula(){
	
		$pedido = new Pedido();
		
		$customer_id = $this->get_customer_id();
			
		$pedido->cliente_id = get_user_meta($customer_id, 'id_kapsula');
		if(!$pedido->cliente_id){
			
			if(!$customer_id){
				wkp_add_notice('Cliente do pedido não possui login', 'error');	
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
				
				wkp_add_notice('Erro ao integrar cliente do pedido', 'error');	
			}
			
		}

		$pedido->pacote_id = 1;
		$pedido->tipo_frete = 0;
		$pedido->valor_venda = 0;
		$pedido->valor = 0;

		return $pedido;
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




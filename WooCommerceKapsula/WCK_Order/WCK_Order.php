<?php

namespace WooKapsula;
use Kapsula\Pedido;
use Kapsula\Produto;
use WooKapsula\WC_Order_Item_Product;
use WC_Order;

class WCK_Order extends WC_Order implements WCK_Integration{

	public function __construct($arg){
		parent::__construct($arg);
	}

	/*returns Kapsula\Pedido*/
	public function Wc_to_Kapsula(){

		global $wookapsula_errors;
		$logger = new Logger();
		$pedido = new Pedido();

		$customer_id = $this->get_customer_id();
		$cliente_id_meta = get_user_meta($customer_id, 'id_kapsula');
		if($cliente_id_meta){
			$pedido->cliente_id = $cliente_id_meta[0];
		}

		if(!$pedido->cliente_id){
			if(!$customer_id){
				$wookapsula_errors->add(  'message', 'Cliente do pedido não possui login' );
				$logger->add_log('Cliente do pedido não possui login');
				return NULL;
			}

			$woocliente = new WCK_Customer($customer_id);
			$ClienteKapsula = $woocliente->Wc_to_Kapsula();

			$return = $ClienteKapsula->post();
			if(!$return){
				$wookapsula_errors->add(  'message', 'Erro ao integrar cliente do pedido' );
				$logger->add_log('Erro ao integrar cliente do pedido');
				return NULL;
			}
			if( $return->code == 200 ){
				add_user_meta($customer_id,  'id_kapsula', $return->cliente);
				$pedido->cliente_id = $return->cliente;
			}else{
				if(isset($return->erros)){
					foreach ($return->erros as $key => $value) {
						foreach ($value as $key2 => $erro) {
							$wookapsula_errors->add(  'message',  $key . ' : ' . $erro );
						}
					}
				}else{
					$wookapsula_errors->add(  'message',  $return->message );
				}
				return NULL;
			}
		}
		$itens = $this->get_Kapsula_itens();
		if(!$itens)
			return NULL;

		$pedido->itens = $itens;
		//var_dump($pedido);
		//die();
		$method_id = @array_shift($this->get_items( 'shipping' ))['method_id'];
		$method_id = 'correios-sedex';
		switch ($method_id) {
			case 'correios-pac':
				$pedido->tipo_frete = 0;
				break;
			case 'correios-sedex':
				$pedido->tipo_frete = 1;
				break;
			case 'free_shipping':
				$pedido->tipo_frete = 0;
				break;
			default:
				$logger->add_log(['Tipo de frete não existente para Kapsula', $method_id]);
				$wookapsula_errors->add(  'message', 'Tipo de frete não existenten para Kapsula' );
				$pedido->tipo_frete = 0;
				return NULL;
		}

		$pedido->referencia_externa = 'WC_'.$this->get_id();
		$pedido->valor_venda = $this->get_subtotal()*100;
		$pedido->valor = 0;

		return $pedido;
	}


	public function set_enviado($flag, $id_kapsula){
		update_post_meta($this->get_id(),'_kapsula_sended', $flag);
		update_post_meta($this->get_id(),'_kapsula_id', $id_kapsula);
	}

	public function get_enviado(){
		return get_post_meta($this->get_id(), '_kapsula_sended');
	}

	public function get_Kapsula_itens(){

		global $wookapsula_errors;
		$logger = new Logger();
		$itens = [];
		foreach ($this->get_items() as $key => $value) {
			$item = (Object)[];
			$wc_item = new WCK_Order_Item_Product($value);
			$product = new WCK_Product($wc_item->get_product_id());
			$item->id = $product->get_meta('id_kapsula');
			$item->produto = $item->id;
			if(!$item->id || !$item->id[0]){
				$logger->add_log(['Produto ' . $product->get_name() . ' Não integrado com a Kapsula']);
				$wookapsula_errors->add(  'message', 'Produto ' . $product->get_name() . 'Não integrado com a Kapsula' );
				return NULL;
			}
			$item->quantidade = $wc_item-> get_quantity();

			$itens[] = $item;
		}

		return $itens;
	}

	/*returns WC_Order*/
	public function populate_from_Kapsula($pedido){

		$this->customer_id = $pedido->cliente_id;

		//$pacote = new Pacote($pedido->pacote_id);
		//$order = new WC_Order(15);
		//$this->items = $pacote->to_items();
	}
}

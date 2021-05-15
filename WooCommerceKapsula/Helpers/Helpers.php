<?php

namespace WooKapsula;

use WooKapsula\WCK_Order;
use Kapsula\Pedido;

Class Helpers {
	
	public function admin_notice__error() {
		$errors = $this->get_errors();
		foreach ($errors as $message) {
			?>
			<div class="notice notice-error is-dismissible">
	        	<p><?php echo $message; ?></p>
	    	</div>
	    	<?php
		}
		var_dump($errors);
		die();
		
	}

	public function get_errors(){
		global $wookapsula_errors;
		$errors_messages = $wookapsula_errors->get_error_messages();
		$errors = [];
		foreach ($errors_messages as $value) {
			$errors[] = ['code' => null, 'message' => $value];
		}
		return $errors;
	}

	public function send_order_to_kapsula( $order_id ){
		global $wookapsula_errors;

		$order = new WCK_Order($order_id);
		$flag_enviado = $order->get_enviado();
		if($flag_enviado && $flag_enviado[0] == 1){
			$wookapsula_errors->add(  'message', 'Pedido já enviado para Kapsula' );	
			return 0;
		}
		$pedido = $order->Wc_to_Kapsula();
		if(!$pedido){	
			return 0;
		}
		$response = $pedido->post();
		if(!$response){
			return 0;
		}
		if($response->code != 200){
			
			if(isset($response->erros)){
				foreach ($response->erros as $key => $value) {
					foreach ($value as $key2 => $erro) {
						$wookapsula_errors->add(  'message',  $key . ' : ' . $erro );	
					}
				}
			}else{
				$wookapsula_errors->add(  'message', $response->message );	
			}
			return 0;
				
		}
		
		$order->set_enviado(1, $response->pedido);
		return 1;
	}
}
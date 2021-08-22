<?php

namespace WooKapsula;

use WooKapsula\WCK_Order;
use WooKapsula\WCK_Product;
use Kapsula\Pedido;
use Kapsula\Cliente;
use Kapsula\Produto;

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
	}

	public function limpar_integracao(){
		global $wpdb;
		global $wookapsula_errors;
		try{
			$data = $wpdb->get_results("DELETE FROM " . $wpdb->prefix."usermeta WHERE meta_key = 'id_kapsula'", ARRAY_A); //clientes
			$data = $wpdb->get_results("DELETE FROM " . $wpdb->prefix."postmeta WHERE meta_key = 'id_kapsula'", ARRAY_A); //produtos

			$data = $wpdb->get_results("DELETE FROM " . $wpdb->prefix."postmeta WHERE meta_key = '_kapsula_id'", ARRAY_A); //pedidos
			$data = $wpdb->get_results("DELETE FROM " . $wpdb->prefix."postmeta WHERE meta_key = '_kapsula_sended'", ARRAY_A); //pedidos
			return 'ok';
		}catch(Exception $e){
			$wookapsula_errors->add(  'message', $e->getMessage() );
			return NULL;
		}
	}

	public function integrate_customers_from_kapsula(){
		global $wpdb;
		global $wookapsula_errors;
		$cli_qnt = 0;
		$clientes = new Cliente();

		$current_page = 0;
		$last_page = 1;

		do{
			$response = $clientes->get();
			if($response){
				$current_page = $response->current_page;
				$last_page = $response->last_page;

				foreach ($response->data as $cliente) {
					$data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix."usermeta WHERE meta_key = 'id_kapsula' and meta_value = " . $cliente->id, ARRAY_A);
					$exists_email = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix."users WHERE user_email like '" . $cliente->email . "'", ARRAY_A);
					if(!$data && !$exists_email){
						$wc_cli = new WCK_Customer([]);
						if(!$wc_cli->populate_from_Kapsula($cliente)){
							$wookapsula_errors->add(  'message', 'cliente ' . $cliente->id .' : '. $cliente->email . 'Não importado' );
						}else{
							$cli_qnt++;
						}
					}
				}
			}else{
				$wookapsula_errors->add(  'message', 'não foi possível receber retorno dos clientes' );
				return NULL;
			}
		}
		while($current_page < $last_page);

		return $cli_qnt;
	}

	public function integrate_products_from_kapsula(){
		global $wpdb;
		$produtos = new Produto();
		$response = $produtos->get();
		if($response){
			foreach ($response->data as $produto) {
	        $data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts p inner join ".$wpdb->prefix."postmeta m on m.post_id = p.id where post_type = 'product' and meta_key = 'id_kapsula' and meta_value = ". $produto->id, ARRAY_A);
	        if(!$data){
	          $wc_prod = new WCK_Product([]);
	          $wc_prod->populate_from_Kapsula($produto);
	        }
	    }
			return 'ok';
		}
		return 'não foi possível receber retorno dos produtos';
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
		$response = $pedido->shopping_cart();
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

		$pedido = new Pedido();

		if(is_array($pedido->id)){
			foreach ($pedido->id as $id) {
				$order->set_enviado(1, $id);
			}
		}else{
			$order->set_enviado(1, $pedido->id);
		}
		$order->set_enviado(1, $pedido->id);

		if(get_option('wookapsula_envia_faturado')!=0){
			$status = 3;
			try{
				$response = $pedido->put([
		 			"status" => intval($status)
				]);
				if(!$response){
					$wookapsula_errors->add(  'message', 'Não foi possível mudar status do pedido' );
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
				}

			}catch(Exception $e){
				$wookapsula_errors->add(  'message', $e->getMessage() );
			}
		}

		return 1;
	}
}

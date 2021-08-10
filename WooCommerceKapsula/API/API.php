<?php
namespace WooKapsula;
use Kapsula\Pedido;
use WooKapsula\WCK_Order;
use WP_REST_Server;

class API{

	public function init(){
		$this->register_routes();
	}

	public function api_permission_called(){

		return true;
	}

	public function register_routes() {
	    // register_rest_route() handles more arguments but we are going to stick to the basics for now.
	    register_rest_route( 'kapsula/v1', 'send/pedido/', array(
	        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
	        'methods'  => WP_REST_Server::READABLE,
	        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
	        'callback' => [$this, 'send_pedido'],
	        // Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
	        'permission_callback' => [$this, 'api_permission_called'],
	    ) );

	    register_rest_route( 'kapsula/v1', 'send/produto/', array(
	        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
	        'methods'  => WP_REST_Server::READABLE,
	        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
	        'callback' => [$this, 'send_produto'],
	        // Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
	        'permission_callback' => [$this, 'api_permission_called'],
	    ) );

	    register_rest_route( 'kapsula/v1', 'send/pacotes/', array(
	        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
	        'methods'  => WP_REST_Server::READABLE,
	        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
	        'callback' => [$this, 'send_pacote'],
	        // Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
	        'permission_callback' => [$this, 'api_permission_called'],
	    ) );

	    register_rest_route( 'kapsula/v1', 'send/clientes/', array(
	        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
	        'methods'  => WP_REST_Server::READABLE,
	        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
	        'callback' => [$this, 'send_cliente'],
	        // Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
	        'permission_callback' => [$this, 'api_permission_called'],
	    ) );

	    register_rest_route( 'kapsula/v1', 'update_pedido_status/', array(
	        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
	        'methods'  => WP_REST_Server::READABLE,
	        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
	        'callback' => [$this, 'update_pedido_status'],
	        // Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
	        'permission_callback' => [$this, 'api_permission_called'],
	    ) );

			register_rest_route( 'kapsula/v1', 'integra/produtos/', array(
					// By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
					'methods'  => WP_REST_Server::READABLE,
					// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
					'callback' => [$this, 'integra_produtos'],
					// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
					'permission_callback' => [$this, 'api_permission_called'],
			) );

			register_rest_route( 'kapsula/v1', 'integra/clientes/', array(
					// By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
					'methods'  => WP_REST_Server::READABLE,
					// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
					'callback' => [$this, 'integra_clientes'],
					// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
					'permission_callback' => [$this, 'api_permission_called'],
			) );

			register_rest_route( 'kapsula/v1', 'integra/limpar/', array(
					// By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
					'methods'  => WP_REST_Server::READABLE,
					// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
					'callback' => [$this, 'limpar_integracao'],
					// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
					'permission_callback' => [$this, 'api_permission_called'],
			) );

			register_rest_route( 'kapsula/v1', 'webhook/pedido-entregue/', array(
					// By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
					'methods'  => 'POST',
					// Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
					'callback' => [$this, 'webhook_pedido_entregue'],
					// Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
					'permission_callback' => [$this, 'api_permission_called'],
			) );

	}

	public function wp_error_to_response(){
		global $wookapsula_errors;
		$errors = [];
		$errors_messages = $wookapsula_errors->get_error_messages();
		foreach ($errors_messages as $value) {
			$errors[] = ['code' => null, 'message' => $value];
		}
		return $errors;
	}

	public function update_pedido_status( $request ){
		global $wookapsula_errors;
		$id = $request->get_param('id');
		if(!$id){
			$wookapsula_errors->add(  'message', 'Pedido sem o ID inserir id' );
			return rest_ensure_response( $this->wp_error_to_response() );
		}

		$post_meta = get_post_meta($id, '_kapsula_id');
		if(!$post_meta){
			$wookapsula_errors->add(  'message', 'Pedido sem integracao Kapsula' );
			return rest_ensure_response( $this->wp_error_to_response() );
		}
		$id_kapsula = $post_meta[0];
		if(!$id_kapsula){
			$wookapsula_errors->add(  'message', 'Pedido sem integracao Kapsula' );
			return rest_ensure_response( $this->wp_error_to_response() );
		}

		$status = $request->get_param('status');

		if(!$status){
			$wookapsula_errors->add(  'message', 'Pedido sem o Status, inserir status' );
			return rest_ensure_response( $this->wp_error_to_response() );
		}

		$pedido = new Pedido();
		$pedido->id = $id_kapsula;
		try{
			$response = $pedido->put([
	 			"status" => intval($status)
			]);
			if(!$response){
				$wookapsula_errors->add(  'message', 'Não foi possível mudar status do pedido' );
				return rest_ensure_response( $this->wp_error_to_response() );
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
				return rest_ensure_response( $this->wp_error_to_response() );
			}

			$retorno['code'] = $response->code;
			$retorno['message'] = $response->message;
			return rest_ensure_response(  $retorno  );

		}catch(Exception $e){
			return rest_ensure_response( $e->getMessage() );
		}
	}

	public function send_pedido( $request ){

		global $wookapsula_errors;
		$logger = new Logger();

		$id = $request->get_param('id');
		if(!$id){
			$wookapsula_errors->add(  'message', 'Pedido sem o ID inserir id' );
			$logger->add_log(['Pedido sem o ID inserir id na API de envio de pedido']);
			return rest_ensure_response( $this->wp_error_to_response() );
		}
		$order = new WCK_Order($id);
		$flag_enviado = $order->get_enviado();
		if($flag_enviado && $flag_enviado[0] == 1){
			$logger->add_log(['Pedido já enviado para Kapsula', "pedido $id"]);
			$wookapsula_errors->add(  'message', 'Pedido já enviado para Kapsula' );
			return rest_ensure_response( $this->wp_error_to_response() );
		}
		$pedido = $order->Wc_to_Kapsula();
		if(!$pedido){
			return rest_ensure_response( $this->wp_error_to_response() );
		}
		try{
			$response = $pedido->shopping_cart();
			if(!$response){
				return rest_ensure_response( $this->wp_error_to_response() );
			}
			if($response->code != 200){

				if(isset($response->erros)){
					$logger->add_log(["Erros abaixo no envio de pedido"]);
					foreach ($response->erros as $key => $value) {
						foreach ($value as $key2 => $erro) {
							$logger->add_log([ $key, $erro ]);
							$wookapsula_errors->add(  'message',  $key . ' : ' . $erro );
						}
					}
				}else{
					$wookapsula_errors->add(  'message', $response->message );
				}
				return rest_ensure_response( $this->wp_error_to_response() );
			}
			$pedido->id = $response->pedidos;
		}catch(Exception $e){
			$logger->add_log([ "Erro no envio de pedido", $e->getMessage() ]);
			return rest_ensure_response( $e->getMessage() );
		}

		$retorno['code'] = $response->code;
		$retorno['message'] = $response->message;
		if(is_array($pedido->id)){
			foreach ($pedido->id as $id) {
				$order->set_enviado(1, $id);
			}
		}else{
			$order->set_enviado(1, $pedido->id);
		}



		if(get_option('wookapsula_envia_faturado')!=0){
			try{
				$response = $pedido->put([
		 			"status" => 3
				]);
				if(!$response){
					$wookapsula_errors->add(  'message', 'Não foi possível mudar status do pedido' );
					return rest_ensure_response( $this->wp_error_to_response() );
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
					return rest_ensure_response( $this->wp_error_to_response() );

				}

			}catch(Exception $e){
				return rest_ensure_response( $e->getMessage() );
			}
		}

		$logger->add_log([ "envio de pedido" ]);
		return rest_ensure_response(  $retorno  );
	}

	public function send_produto( $request ){
		$logger = new Logger();

		$id = $request->get_param('id');
		if(!$id){
			$logger->add_log('necessário inserir id para envio de produto pela API');
			return rest_ensure_response( ['code'=>null, 'message' => 'necessário inserir id'] );
		}
		$product = new WCK_Product($id);
		$produto = $product->Wc_to_Kapsula();
		$response = $produto->post();
		if(!$response){
			return rest_ensure_response( 'erro no retorno' );
		}

		$logger->add_log([ "envio de produto" ]);
		return rest_ensure_response( $response  );
	}

	public function send_cliente( $request ){
		$logger = new Logger();

		$id = $request->get_param('id');
		if(!$id){
			$logger->add_log('necessário inserir id para envio de cliente pela API');
			return rest_ensure_response( ['code'=>null, 'message' => 'necessário inserir id'] );
		}
		$customer = new WCK_Customer($id);
		$cliente = $customer->Wc_to_Kapsula();
		$response = $cliente->post();
		if(!$response){
			return rest_ensure_response( 'erro no retorno' );
		}
		$logger->add_log([ "envio de cliente" ]);
		return rest_ensure_response( $response  );
	}

	public function integra_produtos($request){
		$helper = new Helpers();
		$result = $helper->integrate_products_from_kapsula();
		if($result == 'ok'){
			return rest_ensure_response( ['code'=>200, 'message' => 'Produtos integrados com sucesso'] );
		}
		return rest_ensure_response( ['code'=>null, 'message' => $result] );
	}

	public function integra_clientes($request){
		$helper = new Helpers();
		$result = $helper->integrate_customers_from_kapsula();
		if($result && $result > 0){
			return rest_ensure_response( ['code'=>200, 'message' => 'Clientes integrados com sucesso : ' . $result . ' Clientes'] );
		}
		return rest_ensure_response( $this->wp_error_to_response() );
	}

	public function limpar_integracao($request){
		$helper = new Helpers();
		$result = $helper->limpar_integracao();
		if($result && $result == 'ok'){
			return rest_ensure_response( ['code'=>200, 'message' => 'Integração removida com sucesso'] );
		}
		return rest_ensure_response( $this->wp_error_to_response() );
	}

	public function webhook_pedido_entregue($request){

			$body = $request->get_body();

			$json = json_decode($body);
			if(!$json){
				return rest_ensure_response( ['code'=>500, 'message' => 'Não foi possível interpretar json'] );
			}

			global $wpdb;
			$id_kapsula = $json->dados->pedido_id;
			$data = $wpdb->get_results("SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = '_kapsula_id' and meta_value = '" . $id_kapsula . "'", ARRAY_A);
			if(!$data || !count($data)){
				return rest_ensure_response( ['code'=>400, 'message' => 'Pedido ' . $json->dados->pedido_id . ' não vinculado na WooKapsula'] );
			}

			$order = wc_get_order($data[0]['post_id']);
			if(!$order){
				return rest_ensure_response( ['code'=>400, 'message' => 'Pedido ' . $json->dados->pedido_id .' não existe mais no WooCommerce'] );
			}

			if(!$order->update_status('completed', 'Pedido alterado pelo retorno via WebHook Kapsula', true)){
				return rest_ensure_response( ['code'=>500, 'message' => 'Status do Pedido ' . $json->dados->pedido_id . ' (' . $referencia_externa . ') não pode ser atualizado'] );
			}

			return rest_ensure_response( ['code'=>200, 'message' => 'Pedido ' . $json->dados->pedido_id .' atualizado como concluído'] );
	}


}

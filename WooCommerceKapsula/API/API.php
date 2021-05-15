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
		$id_kapsula = get_post_meta($id, '_kapsula_id')[0];
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

		$id = $request->get_param('id');
		if(!$id){
			$wookapsula_errors->add(  'message', 'Pedido sem o ID inserir id' );	
			return rest_ensure_response( $this->wp_error_to_response() );	
		}
		$order = new WCK_Order($id);
		$flag_enviado = $order->get_enviado();
		if($flag_enviado && $flag_enviado[0] == 1){
			$wookapsula_errors->add(  'message', 'Pedido já enviado para Kapsula' );	
			return rest_ensure_response( $this->wp_error_to_response() );	
		}
		$pedido = $order->Wc_to_Kapsula();
		if(!$pedido){	
			return rest_ensure_response( $this->wp_error_to_response() );
		}
		try{
			$response = $pedido->post();
			if(!$response){
				return rest_ensure_response( $this->wp_error_to_response() );	
			}
			$pedido->id = $response->pedido;
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
		
		$retorno['code'] = $response->code;
		$retorno['message'] = $response->message;
		$order->set_enviado(1, $pedido->id);
		
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
		
		return rest_ensure_response(  $retorno  );	
	}

	public function send_produto( $request ){
		$id = $request->get_param('id');
		if(!$id){
			return rest_ensure_response( ['code'=>null, 'message' => 'necessário inserir id'] );	
		}
		$product = new WCK_Product($id);
		$produto = $product->Wc_to_Kapsula();
		$response = $produto->post();
		if(!$response){
			return rest_ensure_response( 'erro no retorno' );	
		}

		return rest_ensure_response( $response  );
	}

	public function send_cliente( $request ){
		$id = $request->get_param('id');
		if(!$id){
			return rest_ensure_response( ['code'=>null, 'message' => 'necessário inserir id'] );	
		}
		$customer = new WCK_Customer($id);
		$cliente = $customer->Wc_to_Kapsula();
		$response = $cliente->post();
		if(!$response){
			return rest_ensure_response( 'erro no retorno' );	
		}

		return rest_ensure_response( $response  );
	}
	
}
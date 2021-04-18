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

	public function send_pedido( $request ){
		global $wookapsula_errors;

		$id = $request->get_param('id');
		if(!$id){
			$wookapsula_errors->add(  'message', 'Pedido sem o ID inserir id' );	
			return rest_ensure_response( $this->wp_error_to_response() );	
		}
		$order = new WCK_Order($id);
		$flag_enviado = $order->get_enviado()[0];
		if($flag_enviado == 1){
			$wookapsula_errors->add(  'message', 'Pedido já enviado' );	
			return rest_ensure_response( $this->wp_error_to_response() );	
		}
		$pedido = $order->Wc_to_Kapsula();
		if(!$pedido){	
			return rest_ensure_response( $this->wp_error_to_response() );
		}
		$response = $pedido->post();
		if(!$response){
			return rest_ensure_response( $this->wp_error_to_response() );	
		}
		if($response->code != 200){
			if($response->erros){
				foreach ($response->erros as $value) {
					$wookapsula_errors->add(  'message', $value );	
				}				
			}else{
				$wookapsula_errors->add(  'message', $response->message );	
			}

		}else{
			$order->set_enviado(1);
		}
		return rest_ensure_response( $response->message );	
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
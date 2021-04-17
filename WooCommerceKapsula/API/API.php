<?php

namespace WooKapsula;
use Kapsula\Pedido;
use WooKapsula\WCK_Order;
use WP_REST_Server;

class API{

	public function add_pedido( $request ){

		$id = $request->get_param('id');
		$order = new WCK_Order($id);
		$pedido = $order->Wc_to_Kapsula();
		$response = $pedido->post();
		if(!$response){

			return rest_ensure_response( 'erro no retorno' );	
		}

		return rest_ensure_response( $response  );
	}

	public function add_produto( $request ){

	}

	public function add_cliente( $request ){

	}

	public function register_routes() {
	    // register_rest_route() handles more arguments but we are going to stick to the basics for now.
	    register_rest_route( 'kapsula/v1', 'pedido/add/', array(
	        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
	        'methods'  => WP_REST_Server::READABLE,
	        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
	        'callback' => [$this, 'add_pedido'],
	        // Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
	        //'permission_callback' => [$this, ''],
	    ) );

	    register_rest_route( 'kapsula/v1', 'produto/add/', array(
	        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
	        'methods'  => WP_REST_Server::READABLE,
	        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
	        'callback' => [$this, 'add_pedido'],
	        // Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
	        //'permission_callback' => [$this, ''],
	    ) );

	    register_rest_route( 'kapsula/v1', 'pacotes/add/', array(
	        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
	        'methods'  => WP_REST_Server::READABLE,
	        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
	        'callback' => [$this, 'add_pedido'],
	        // Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
	        //'permission_callback' => [$this, ''],
	    ) );

	    register_rest_route( 'kapsula/v1', 'clientes/add/', array(
	        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
	        'methods'  => WP_REST_Server::READABLE,
	        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
	        'callback' => [$this, 'add_pedido'],
	        // Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
	        //'permission_callback' => [$this, ''],
	    ) );
	}
	
}
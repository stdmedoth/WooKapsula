<?php
/*
	Plugin Name: WooKapsula
	Description: Integração KapSula com WooCommerce
	Author: Incipe Desenvolvimento
	Version: 0.1
*/

require('functions.php');
use Kapsula\Pedido;
use Kapsula\Pacote;
use Kapsula\Cliente;
use WooKapsula\WCK_Order;
use WooKapsula\WCK_Customer;
use WooKapsula\WCK_Order_Item_Product;
use WooKapsula\CustomField;
use WooKapsula\API;

Class WooKapsulaPlugin{
	
	public function __construct (){
		add_action( 'init', array($this, 'init'), 10, 1 ); 
	}

	public function init(){

		require('Autoloader.php');
		if ( !class_exists( 'WooCommerce' ) ) {
			add_action('admin_notices', 'wordpress_inativo' );
			return ;
		}

		//$order = new WCK_Order(14);
		//var_dump($order->get_Kapsula_pacote());
		////$pedido = $order->Wc_to_Kapsula();
		//die();

		//$order = new WC_Order(14);
		//var_dump($order->get_items());
		//die();
		//$woocli = new WCK_Customer(null);
		//$woocli->from_Kapsula_id(2213139);		
		//var_dump($woocli);
		//die();
		//$clientes = $cliente->get()->data;
		
		//var_dump($clientes);
		//die();
		//foreach ($clientes as $key) {
		//	$woocli = new WCK_Customer(null);
		//	$woocli->populate_from_Kapsula($key);		
		//}
		
		
		
		//var_dump($woocli);
		//$obj = new WCK_Order(null);
		//$obj->populate_from_Kapsula($pedido);
		//var_dump($pedido);
		//die();

		$this->wkp_load_plugin_actions();
		$this->wkp_load_plugin_filters ();
	}

	public function wkp_load_plugin_actions(){

		add_action( 'wp_enqueue_scripts', [$this, 'wkp_registrar_arquivos'] );	
		add_action( 'admin_enqueue_scripts', [$this, 'wkp_registrar_arquivos'] );
		add_action( 'woocommerce_order_status_changed', [$this,'wkp_register_order_status_changed'], 10, 3);

		$custom_field = new CustomField();
		add_action('woocommerce_product_options_general_product_data', [$custom_field, 'woocommerce_product_custom_fields']);
		add_action('woocommerce_admin_process_product_object', [$custom_field, 'woocommerce_product_custom_fields_save']);		

		add_action( 'woocommerce_admin_order_data_after_order_details', [$custom_field, 'send_to_kapsula_button'] );

		$api = new API();
		add_action( 'rest_api_init', [$api, 'register_routes'] );
		    
	}

	public function wkp_load_plugin_filters (){

		//$custom_field = new CustomField();
		
	}

	public function wkp_register_order_status_changed( $this_get_id, $this_status_transition_from, $this_status_transition_to  ){
		//var_dump($this_get_id);
		$order = new WCK_Order( $this_get_id );

		$pedido = new Pedido();

	}

	public function wkp_registrar_arquivos(){
		
		wp_enqueue_script( 'WooKapsulaJs', plugins_url('js/WooKapsula.js', __FILE__), array( 'jquery' ) );

		wp_enqueue_style('jquery');

		wp_enqueue_style( 'WooKapsulaCss', plugins_url('css/WooKapsula.css', __FILE__) );
	}

}

$woo_kapsula_plugin = new WooKapsulaPlugin();


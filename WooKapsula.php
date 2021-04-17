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
use WooKapsula\WCK_Order;

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

		$obj = new WCK_Order(15);
		$pedido = $obj->Wc_to_Kapsula();
		if($pedido){
			echo json_encode($pedido->get(4058975)) ;
			die();
		}
		$this->wkp_load_plugin_actions();
	}

	public function wkp_load_plugin_actions(){

		add_action( 'wp_enqueue_scripts', [$this, 'wkp_registrar_arquivos'] );	
		add_action( 'admin_enqueue_scripts', [$this, 'wkp_registrar_arquivos'] );
		add_action( 'woocommerce_order_status_changed', [$this,'wkp_register_order_status_changed'], 10, 3);
		
	}

	public function wkp_register_order_status_changed( $this_get_id, $this_status_transition_from, $this_status_transition_to  ){
		//var_dump($this_get_id);
		$order = new WCK_Order( $this_get_id );

		$pedido = new Pedido();

	}

	public function wkp_registrar_arquivos(){
		wp_enqueue_script('jquery');
		
		wp_register_script( 'WooKapsulaJs', plugins_url('js/WooKapsula.js', __FILE__));
		wp_enqueue_script( 'WooKapsulaJs' );

		wp_register_style( 'WooKapsulaCss', plugins_url('css/WooKapsula.css', __FILE__));
		wp_enqueue_style( 'WooKapsulaCss' );
	}

}

$woo_kapsula_plugin = new WooKapsulaPlugin();


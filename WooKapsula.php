<?php
/*
	Plugin Name: WooKapsula
	Description: Integração KapSula com WooCommerce
	Author: Incipe Desenvolvimento
	Version: 0.1
*/
use WooKapsula\Cliente;
use WooKapsula\Pacote;
use WooKapsula\Pedido;

if ( !class_exists( 'WooCommerce' ) ) {
	add_action('admin_notices', 'wordpress_inativo');
}

function wkp_registrar_arquivos(){
	wp_enqueue_script('jquery');
	
	wp_register_script( 'WooKapsulaJs', plugins_url('js/WooKapsula.js', __FILE__));
	wp_enqueue_script( 'WooKapsulaJs' );

	wp_register_style( 'WooKapsulaCss', plugins_url('css/WooKapsula.css', __FILE__));
	wp_enqueue_style( 'WooKapsulaCss' );

	require('LoadWooKapsula.php');

	$pedido = new WooKapsula\Pedido();
	//echo $pedido->to_json();
	echo $pedido->post();
	die();

}

function init_wookapsula(){
	add_action( 'admin_enqueue_scripts', 'wkp_registrar_arquivos' );	
	add_action( 'admin_enqueue_scripts', 'wkp_registrar_arquivos' );	
}

add_action( 'init', 'init_wookapsula', 10, 1 ); 





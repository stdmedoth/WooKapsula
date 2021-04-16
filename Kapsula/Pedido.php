<?php 

namespace WooKapsula;

use WooKapsula\Cliente;

Class Pedido extends Element{

  public function __construct(){
  	parent::__construct('pedidos');
  	//$this->cliente = new Cliente();
  }

 	public $cliente;
 	public $pacote_id;
 	public $tipo_frete;
 	public $valor_venda; // Opcional: em centavos se informado (ex 372.00 * 100 = 37200)
 	public $notafiscal;  // Opcional
	public $chave;
	public $numero;
	public $valor;
	public $imposto;
	public $link_pdf; //link_do_pdf",
	public $link_xml; //link_do_xml" // Opcional

}


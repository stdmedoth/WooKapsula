<?php

namespace Kapsula;
use Kapsula\Cliente;
use Kapsula\Request;

Class Pedido extends Element{

	public function __construct($id = null){
		parent::__construct('pedidos');
		$this->data_obj = 'pedidos';
		if($id){
            $this->id = $id;
            $this->get($this->id);
        }
	}

	public function shopping_cart(){
		$request = new Request('pedidos/shopping-cart');
		$response = $request->post($this->to_json());
		return json_decode($response);

	}

	public $id;
 	public $cliente_id;
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
	public $itens;

}

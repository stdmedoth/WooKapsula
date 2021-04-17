<?php 

namespace WooKapsula;
use Kapsula\Cliente;
use WC_Customer;
use Extra_Checkout_Fields_For_Brazil_Formatting;

class WCK_Customer extends WC_Customer implements WCK_Integration{

	public function __construct($arg){
		parent::__construct($arg);
	} 

	public function Wc_to_Kapsula(){
	
		$cliente = new Cliente();

		$cliente->cpf = $this->get_meta('billing_cpf');
		$cliente->data_nascimento = $this->get_meta('billing_birthdate');
		$cliente->sexo = $this->get_meta('billing_sex');
		
		$cliente->nome = $this->get_first_name() . ' ' . $this->get_last_name();
		$cliente->email = $this->get_email();
		$cliente->email = $this->get_email();
		$cliente->email = $this->get_email();
		
		$cliente->endereco = $this->get_billing_address_1();
		$cliente->telefone = $this->get_billing_phone();
		$cliente->numero = $this->get_billing_phone();
		$cliente->bairro = $this->get_billing_phone();
		$cliente->cidade = $this->get_billing_city();
		$cliente->estado = $this->get_billing_state();
		$cliente->pais = $this->get_billing_country();
		$cliente->cep = $this->get_billing_postcode();
		
		return $cliente;
	}

	
	public function Kapsula_to_Wc(){

	}
	
}
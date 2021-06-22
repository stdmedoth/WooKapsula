<?php

namespace WooKapsula;
use Kapsula\Cliente;
use WP_Query;
use WP_Error;
use WC_Customer;
use Extra_Checkout_Fields_For_Brazil_Formatting;
use WC_Data_Exception;

class WCK_Customer extends WC_Customer implements WCK_Integration{

	public function __construct($arg){
		parent::__construct($arg);
	}

	public function from_Kapsula_id($cliente_id){
		$usuario = get_users([
			'meta_key' => 'id_kapsula',
			'meta_value' => $cliente_id
		]);

		$kcliente = new Cliente($cliente_id);
		$this->populate_from_Kapsula($kcliente);
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


	public function populate_from_Kapsula($cliente){

		global $wpdb;
		global $wookapsula_errors;
		try{

			$full_name = explode(' ', $cliente->nome, 2);
			if(count($full_name) > 1){
				$this->set_first_name($full_name[0]);
				$this->set_last_name($full_name[1]);
			}else{
				$this->set_first_name($cliente->nome);
			}
			$this->set_email($cliente->email);
			$this->set_billing_address_1($cliente->endereco);
			$this->set_billing_phone($cliente->telefone);
			$this->set_billing_phone($cliente->numero);

			$this->set_billing_phone($cliente->bairro);
			$this->set_billing_city($cliente->cidade);
			$this->set_billing_state($cliente->estado);
			$this->set_billing_country($cliente->pais);
			$this->set_billing_postcode($cliente->cep);

			$this->save();

			$usuario = get_users([
				'meta_key' => 'id_kapsula',
				'meta_value' => $cliente->id
			]);
			if($usuario){
				update_user_meta($this->get_id(), 'id_kapsula', $cliente->id);
				update_user_meta($this->get_id(), 'billing_cpf', $cliente->cpf);
				update_user_meta($this->get_id(), 'billing_birthdate', $cliente->data_nascimento);
				update_user_meta($this->get_id(), 'billing_sex', $cliente->sexo);
			}else{
				add_user_meta($this->get_id(), 'id_kapsula', $cliente->id);
				add_user_meta($this->get_id(), 'billing_cpf', $cliente->cpf);
				add_user_meta($this->get_id(), 'billing_birthdate', $cliente->data_nascimento);
				add_user_meta($this->get_id(), 'billing_sex', $cliente->sexo);
			}

		}catch(WC_Data_Exception $e){

			$wookapsula_errors->add(  'message', $cliente->nome . ' ' . $e->getMessage() );
			return NULL;
		}

		return $this;
	}

}

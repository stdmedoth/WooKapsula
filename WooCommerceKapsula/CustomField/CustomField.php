<?php

namespace WooKapsula;

class CustomField{

	public function admin_enqueue_bootstrap(){
		add_action( 'admin_enqueue_scripts', [$this, 'registrar_bootstrap'] );
	}

	// Display admin product custom setting field(s)
	public function woocommerce_product_custom_fields() {
	    global $product_object;

	    echo '<div class=" product_custom_field ">';

	    // Custom Product Text Field
	    woocommerce_wp_text_input( array(
	        'id'          => 'id_kapsula',
	        'label'       => __('Cód Produto Kapsula:', 'woocommerce'),
	        'placeholder' => '',
	        'value' => $product_object->get_meta('id_kapsula'),
	        'desc_tip'    => 'true' // <== Not needed as you don't use a description
	    ) );

	    echo '</div>';
	}

	public function loading_modal(){
		?>
			<div id="KapsulaModal" class="modal">
			  <p id="KapsulaMessages"></p>
			  <a href="#" rel="modal:close">Close</a>
			</div>
		<?php
	}

	// Save admin product custom setting field(s) values
	public function woocommerce_product_custom_fields_save( $product ) {
	    if ( isset($_POST['id_kapsula']) ) {
	        $product->update_meta_data( 'id_kapsula', sanitize_text_field( $_POST['id_kapsula'] ) );
	    }

	}


	public function customer_meta_fields( $fields ) {

		// Billing fields.
		$new_fields['billing']['title'] = 'Dados Kapsula';
		$new_fields['billing']['fields']['id_kapsula'] = array(
				'label'       => 'Id Kapsula',
				'description' => 'Id do cliente na plataforma Kapsula');
		$new_fields['billing']['fields']['id_kapsula'] = array(
				'label'       => 'Id Kapsula',
				'description' => 'Id do cliente na plataforma Kapsula');
		$new_fields['billing']['fields']['billing_first_name'] = array(
			'label' => 'Primeiro Nome',
			'description' => 'Insira o primeiro nome');
		$new_fields['billing']['fields']['billing_last_name'] = array(
			'label' => 'Sobrenome',
			'description' => 'Insira o sobrenome');
		$new_fields['billing']['fields']['billing_cpf'] = array(
			'label' => 'CPF',
			'description' => 'Insira o CPF');
		$new_fields['billing']['fields']['billing_rg'] = array(
			'label' => 'RG',
			'description' => 'Insira o RG');
		$new_fields['billing']['fields']['billing_sex'] = array(
			'label' => 'Sexo',
			'description' => 'Insira o Sexo (M/F)');
		$new_fields['billing']['fields']['billing_birthdate'] = array(
			'label' => 'Data Nascimento',
			'description' => 'Insira a data (DD/MM/YYYY)');
		$new_fields['billing']['fields']['billing_address_1'] = array(
			'label' => 'Endereço de venda',
			'description' => 'Insira o logradouro');
		$new_fields['billing']['fields']['billing_city'] = array(
			'label' => 'Cidade de Venda',
			'description' => 'Insira a cidade');
		$new_fields['billing']['fields']['billing_state'] = array(
			'label' => 'Estado',
			'description' => 'Insira a UF (XX)');
		$new_fields['billing']['fields']['billing_postcode'] = array(
			'label' => 'CEP',
			'description' => 'Insira o CEP (XXXXXXXX)');
		$new_fields['billing']['fields']['billing_country'] = array(
			'label' => 'Pais',
			'description' => 'Insira o Pais' );
		$new_fields['billing']['fields']['billing_email'] = array(
			'label' => 'Email',
			'description' => 'Insira o Email de venda');
		$new_fields['billing']['fields']['billing_phone'] = array(
			'label' => 'Telefone',
			'description' => 'Insira o Telefone');

		$new_fields = apply_filters( 'wcbcf_customer_meta_fields', $new_fields );

		return $new_fields;
	}

	public function wookapsula_order_container($order){
		?>
		<div id="wookapsula_order_container" class="form-field form-field-wide wc-customer">
		        <h4><?php _e( 'Kapsula' ); ?></h4>
		        <div class="">
		        	<div>
		        		<a class="button button-primary" href="javascript:void(0)" id="button_send_to_kapsula" data-order="<?= $order->get_id() ?>">Enviar para a Kapsula</a>
		        	</div>

		        	<div>
		        		<a class="button button-secondary" href="javascript:void(0)" id="button_update_kapsula_pedido_status" data-order="<?= $order->get_id() ?>">Atualizar status</a>
				        <select class="select"  id="select_kapsula_pedido_status">
				        	<option value="3">Faturado</option>
				        	<option value="6">Estornado</option>
				        	<option value="9">Cancelado</option>
				        </select>
				    </div>
				</div>
		</div>
		<?php
	}

}

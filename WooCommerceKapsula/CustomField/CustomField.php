<?php

namespace WooKapsula;

class CustomField{
	// Display admin product custom setting field(s)
	public function woocommerce_product_custom_fields() {
	    global $product_object;

	    echo '<div class=" product_custom_field ">';

	    // Custom Product Text Field
	    woocommerce_wp_text_input( array( 
	        'id'          => 'kapsula_package',
	        'label'       => __('Cód Pacote Kapsula:', 'woocommerce'),
	        'placeholder' => '',
	        'value' => $product_object->get_meta('kapsula_package'),
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
	    if ( isset($_POST['kapsula_package']) ) {
	        $product->update_meta_data( 'kapsula_package', sanitize_text_field( $_POST['kapsula_package'] ) );
	    }
	}	


	public function customer_meta_fields( $fields ) {

		// Billing fields.
		$new_fields['billing']['title'] = 'Id Kapsula';
		$new_fields['billing']['fields']['id_kapsula'] = array(
				'label'       => 'Id Kapsula',
				'description' => 'Id do cliente na plataforma Kapsula');

		$new_fields = apply_filters( 'wcbcf_customer_meta_fields', $new_fields );

		return $new_fields;
	}

	public function wookapsula_order_container($order){
		$template = new Templates();
		$template->popup_modal();
		?>
		<div id="wookapsula_order_container" class="form-field form-field-wide wc-customer">
		        <h4><?php _e( 'Kapsula' ); ?></h4>
		        <div class="">
		        	<div>
		        		<a class="btn btn-primary" href="javascript:void(0)" id="button_send_to_kapsula" data-order="<?= $order->get_id() ?>">Enviar para a Kapsula</a>
		        	</div>
		        	
		        	<div>
		        		<a class="btn btn-secondary" href="javascript:void(0)" id="button_update_kapsula_pedido_status" data-order="<?= $order->get_id() ?>">Atualizar status</a>
				        <select class="select"  id="select_kapsula_pedido_status">
				        	<option value="3">FATURADO</option>
				        	<option value="6">ESTORNADO</option>
				        	<option value="9">CANCELADO</option>
				        </select>
				    </div>
				</div>
		</div>
		<?php
	}

}
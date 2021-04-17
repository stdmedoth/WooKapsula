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

	// Save admin product custom setting field(s) values
	public function woocommerce_product_custom_fields_save( $product ) {
	    if ( isset($_POST['kapsula_package']) ) {
	        $product->update_meta_data( 'kapsula_package', sanitize_text_field( $_POST['kapsula_package'] ) );
	    }
	}	

	function add_custom_meta_box() {  
    	add_meta_box(  
	        'custom_meta_box', // $id  
	        'Custom Meta Box', // $title   
	        'show_custom_meta_box', // $callback  
	        'post', // $page  
	        'normal', // $context  
	        'high'); // $priority  
	}  

	function send_to_kapsula_button( $order ){  
		?>
		    <div class="order_data_column">
		        <h4><?php _e( 'Kapsula' ); ?></h4>
		        <a class="button" href="javascript:void(0)" id="button_send_to_kapsula" data-order="<?= $order->get_id() ?>">Enviar para a Kapsula</a>
		    </div>
		<?php
	}

}
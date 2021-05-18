<?php 

namespace WooKapsula;
use WP_List_Table;

Class Order_List_Table extends WP_List_Table{
	
	public function prepare_items(){

		$this->process_bulk_action();
		$columns = $this->get_columns();
    	$hidden = $this->get_hidden_columns();
    	$sortable = $this->get_sortable_columns();

    	$perPage = 15;
    	$data = $this->table_data();
    	$currentPage = $this->get_pagenum();
    	$totalItems = sizeof($data)-1;

    	$this->set_pagination_args( array(
        	'total_items' => $totalItems,
    	    'per_page'    => $perPage
	    ) );

    	$data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
    	$this->_column_headers = array($columns, $hidden, $sortable);
    	$this->items = $data;

	} 

	public function get_columns(){
		return array(
			'cb' => '<input type="checkbox" />',
			'order_id' => 'ID. Pedido WooCommerce',
			'pedido_id' => 'ID. Pedido Kapsula',
		);
	}

	public function get_sortable_columns(){
		return array(
		'order_id' => array('order_id',true),
		'pedido_id' => array('pedido_id', true)
		);
	}


	public function get_hidden_columns(){
		return array();
	}

	function get_bulk_actions(){
    	$actions = array();
	    $actions['delete'] = __( 'Delete' );

    	return $actions;
	}

	function process_bulk_action() {
	    global $wpdb;
	    //Detect when a bulk action is being triggered...
	    if( 'delete' === $this->current_action() ) {
	      	foreach ($_POST as $key => $value) {
	        	if($key === 'order_id'){
	          		foreach ($value as $key2 => $value2) {
	            		delete_post_meta($value[0], '_kapsula_id');
	            		delete_post_meta($value[0], '_kapsula_sended');
	        		}
	    		}
	    	}
	    }
	}

	function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="order_id[]" value="%s" />', $item['order_id']
        );
    }


	public function column_default( $item, $column_name ){
		$actions = array(
        	'delete' => sprintf("<a href='?page=wookapsula&post=product&action=delete&id=%s'>%s</a> ", $item['order_id'], __('Delete')),
      	);
		switch($column_name){
			case 'order_id':
				//return sprintf('%s %s', $item[ $column_name ], $this->row_actions($actions));
			case 'pedido_id':
			    return $item[ $column_name ];

			default:
				return print_r( $item, true ) ;
		}
	}

	private function table_data(){
		global $wpdb;

		$data = $wpdb->get_results("SELECT post_id, meta_value FROM ".$wpdb->prefix."postmeta WHERE meta_key = '_kapsula_id'", ARRAY_A);
		
		$orders = array_map(function($n){
			
			return [
				'order_id' =>  $n['post_id'],
				'pedido_id' =>  $n['meta_value']
			];

		}, $data);

		return $orders;
	}
}
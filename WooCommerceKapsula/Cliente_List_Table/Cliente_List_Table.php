<?php 

namespace WooKapsula;
use WP_List_Table;

Class Cliente_List_Table extends WP_List_Table{
	
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
			'user_id' => 'ID. Usuário',
			'first_name' => 'Nome Usuário',
			'id_kapsula' => 'Código Kapsula'
		);
	}

	public function get_sortable_columns(){
		return array(
		'user_id' => array('user_id',true),
		'first_name' => array('first_name', true),
		'id_kapsula' => array('id_kapsula', true),
		);
	}


	public function get_hidden_columns(){
		return array();
	}

	public function column_default( $item, $column_name ){
		$actions = array(
        	'delete' => sprintf("<a href='?page=wookapsula&post=customer&action=delete&id=%s'>%s</a> ", $item['user_id'], __('Delete')),
      	);
		switch($column_name){
			case 'user_id':
				//return sprintf('%s %s', $item[ $column_name ], $this->row_actions($actions));
			case 'first_name':
			case 'id_kapsula':
				return $item[ $column_name ];

			default:
				return print_r( $item, true ) ;
		}
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
	        	if($key === 'user_id'){
	          		foreach ($value as $key2 => $value2) {
	            		delete_user_meta($value[0], 'id_kapsula');
	        		}
	    		}
	    	}
	    }
	}

	function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="user_id[]" value="%s" />', $item['user_id']
        );
    }

	private function table_data(){
		global $wpdb;

		$data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'id_kapsula'", ARRAY_A);
		
		$clientes_id = array_map(function($n){
			return $n['user_id'];
		}, $data);

		if(!$clientes_id || !count($clientes_id))
			return [];

		$clientes_nomes = $wpdb->get_results("SELECT user_id,  meta_value FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'first_name' and user_id IN (" . implode($clientes_id, ',') . ")", ARRAY_A);

		$clientes_sobrenomes = $wpdb->get_results("SELECT user_id,  meta_value FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'last_name' and user_id IN (" . implode($clientes_id, ',') . ")", ARRAY_A);

		$cliente_idkapsula = $wpdb->get_results("SELECT user_id,  meta_value FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'id_kapsula' and user_id IN (" . implode($clientes_id, ',') . ")", ARRAY_A);
		
		$clientes = [];
		foreach ($clientes_id as $key => $id) {
			$clientes[$key] = [];
			$clientes[$key]['user_id'] = $id;
			$clientes[$key]['first_name'] = $clientes_nomes[$key]['meta_value'] . ' ' .  $clientes_sobrenomes[$key]['meta_value'];
			$clientes[$key]['id_kapsula'] = $cliente_idkapsula[$key]['meta_value'];
		}

		return $clientes;
	}
}
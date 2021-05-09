<?php 

namespace WooKapsula;
use WP_List_Table;

Class Cliente_List_Table extends WP_List_Table{
	
	public function prepare_items(){
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
		switch($column_name){
			case 'user_id':
			case 'first_name':
			case 'id_kapsula':
				return $item[ $column_name ];

			default:
				return print_r( $item, true ) ;
		}
	}

	private function table_data(){
		global $wpdb;

		$data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'id_kapsula'", ARRAY_A);
		
		$clientes_id = array_map(function($n){
			return $n['user_id'];
		}, $data);

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
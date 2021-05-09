<?php 

namespace WooKapsula;
use WP_List_Table;

Class Produto_List_Table extends WP_List_Table{
	
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
			'post_id' => 'ID. Produto',
			'post_title' => 'Nome Produto',
			'meta_value' => 'Código Kapsula'
		);
	}

	public function get_sortable_columns(){
		return array(
		'post_id' => array('user_id',true),
		'post_title' => array('first_name', true),
		'meta_value' => array('id_kapsula', true),
		);
	}


	public function get_hidden_columns(){
		return array();
	}

	public function column_default( $item, $column_name ){
		switch($column_name){
			case 'post_id':
			case 'post_title':
			case 'meta_value':
				return $item[ $column_name ];

			default:
				return print_r( $item, true ) ;
		}
	}

	private function table_data(){
		global $wpdb;

		$data = $wpdb->get_results("SELECT post_id, post_title, meta_value FROM kap_posts p inner join kap_postmeta m on m.post_id = p.id where post_type = 'product' and meta_key = 'kapsula_package'", ARRAY_A);
		

		return $data;
	}
}
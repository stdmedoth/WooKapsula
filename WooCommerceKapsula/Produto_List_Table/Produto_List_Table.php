<?php 

namespace WooKapsula;
use WP_List_Table;

Class Produto_List_Table extends WP_List_Table{
	
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
			'post_id' => 'ID. Produto',
			'post_title' => 'Nome Produto',
			'meta_value' => 'Pacote Kapsula'
		);
	}

	public function get_sortable_columns(){
		return array(
		'post_id' => array('user_id',true),
		'post_title' => array('post_title', true),
		'meta_value' => array('meta_value', true),
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
	        	if($key === 'post_id'){
	          		foreach ($value as $key2 => $value2) {
	            		delete_post_meta($value[0], 'kapsula_package');
	        		}
	    		}
	    	}
	    }
	}

	function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="post_id[]" value="%s" />', $item['post_id']
        );
    }


	public function column_default( $item, $column_name ){
		$actions = array(
        	'delete' => sprintf("<a href='?page=wookapsula&post=product&action=delete&id=%s'>%s</a> ", $item['post_id'], __('Delete')),
      	);
		switch($column_name){
			case 'post_id':
				//return sprintf('%s %s', $item[ $column_name ], $this->row_actions($actions));
			case 'post_title':
			case 'meta_value':
			    return $item[ $column_name ];

			default:
				return print_r( $item, true ) ;
		}
	}

	private function table_data(){
		global $wpdb;

		$data = $wpdb->get_results("SELECT post_id, post_title, meta_value FROM ".$wpdb->prefix."posts p inner join ".$wpdb->prefix."postmeta m on m.post_id = p.id where post_type = 'product' and meta_key = 'kapsula_package'", ARRAY_A);
		

		return $data;
	}
}
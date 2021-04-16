<?php

function wordpress_inativo(){
	wkp_add_notice('O WooKapsula depende do WooCommerce para funcionar', 'error');	
}

function wkp_add_notice($text, $status){
	echo "<div class='notice notice-$status '>
         <p>$text</p>
    </div>";
 }

 ?>
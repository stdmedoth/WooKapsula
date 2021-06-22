<?php

namespace WooKapsula;

class Logger {

	public function add_log( $entry, $file = 'Kapsula_Logger' ) { 

		$mode = 'a';
		// Get WordPress uploads directory.
		$upload_dir = wp_upload_dir();
		$upload_dir = $upload_dir['basedir'];

		// If the entry is array, json_encode.
		if ( is_array( $entry ) ) { 
		  $entry = json_encode( $entry ); 
		}

		// Write the log file.
		$file  = $upload_dir . '/' . $file . '.log';
		$file  = fopen( $file, $mode );
		$bytes = fwrite( $file, current_time( 'mysql' ) . "::" . $entry . "\n" ); 
		fclose( $file ); 
		return $bytes;
	}

	public function get_log($filename = 'Kapsula_Logger') {
		
		$upload_dir = wp_upload_dir();
		$upload_dir = $upload_dir['basedir'];
		$mode = 'r';
		$filename  = $upload_dir . '/' . $filename . '.log';
		$file  = @fopen( $filename, $mode );
		if($file){
			$content = file_get_contents($filename);
		}else{
			$content = 'Não foi possível abrir arquivo de logs';
		}

		return $content;
	}

	public function clean_log($file = 'Kapsula_Logger'){
		$upload_dir = wp_upload_dir();
		$upload_dir = $upload_dir['basedir'];
		$mode = 'w';
		$file  = $upload_dir . '/' . $file . '.log';
		$file  = fopen( $file, $mode );
		if(!$file)
			return 0;
		fclose( $file ); 
		return 1;
	}

}
<?php

function pitchpro_file_loader( $files = array() ){
	foreach($files as $file ){
		if( is_readable( PITCHPRO_PATH . $file . '.php' ) ){
			include_once PITCHPRO_PATH . $file . '.php' ;
		}
	}
}

function pitchpro_autoloader($class) {
    pitchpro_file_loader(['app/' . str_replace( 'pitchpro_', '', strtolower($class)) ]);
}

spl_autoload_register('pitchpro_autoloader');

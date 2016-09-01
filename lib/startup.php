<?php

add_action( 'plugins_loaded', 'pitchpro_plugin_loaded' );
add_action( 'load-plugins.php', 'pitchpro_custom_activation_message_init' );
register_activation_hook( __FILE__, 'pitchpro_activate' );
register_deactivation_hook( __FILE__, 'pitchpro_deactivate' );

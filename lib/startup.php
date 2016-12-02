<?php

// this filter wrecks havoc on the custom wp_mail method
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
add_action('phpmailer_init','pitchpro_wp_mail_set_text_body');
function pitchpro_wp_mail_set_text_body($phpmailer) {
     if (empty($phpmailer->AltBody)) {$phpmailer->AltBody = strip_tags($phpmailer->Body);}
}

add_action( 'plugins_loaded', 'pitchpro_plugin_loaded' );
add_action( 'load-plugins.php', 'pitchpro_custom_activation_message_init' );
register_activation_hook( __FILE__, 'pitchpro_activate' );
register_deactivation_hook( __FILE__, 'pitchpro_deactivate' );

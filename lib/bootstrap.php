<?php

function pitchpro_plugin_loaded() {
    // we assume class_exists( 'WPPluginFramework' ) is true
	if ( apply_filters( 'load_pitchpro/pre_check', PitchPro_App::prerequisites() ) ) {
		// when plugin is activated let's load the instance to get the ball rolling
		add_action( 'init', array( 'PitchPro_App', 'instance' ), -100, 0 );
		add_action( 'init', array( 'PitchPro_Organization', 'instance' ), -10, 0 );
		add_action( 'init', array( 'PitchPro_Campaign', 'instance' ), -10, 0 );
		add_action( 'init', array( 'PitchPro_Pitch', 'instance' ), -10, 0 );
        add_action( 'tf_create_options', 'pitchpro_init_option_framework' );
	} else {
		// let the user know prerequisites weren't met
		add_action( 'admin_head', array( 'PitchPro_App', 'fail_notices' ), 0, 0 );
	}
}

function pitchpro_get_template_part( $slug, $name = '' ){
    $name = (string) $name;
	$template = PITCHPRO_PATH . "template/theme/{$slug}.php";

    if ( '' !== $name )
        $template = PITCHPRO_PATH . "template/theme/{$slug}-{$name}.php";

	load_template( $template, true );
}

function pitchpro_init_option_framework() {
    $pitchpro_option = TitanFramework::getInstance( 'pitchpro' );
}
function pitchpro_activate(){
	update_option( 'pitchpro_activation_status', 'activated' );
	pitchpro_rewrite_rules( true );
}
function pitchpro_deactivate(){
	delete_option( 'pitchpro_activation_status' );
	pitchpro_rewrite_rules( true );
}
function pitchpro_custom_activation_message_init(){
	if( 'activated' == get_option( 'pitchpro_activation_status' ) ){
		add_filter( 'gettext', 'pitchpro_custom_activation_message', 99, 3 );
		update_option( 'pitchpro_activation_status', 'active' );
	}
}
function pitchpro_rewrite_rules( $force_flush = false ) {
	if ( !empty($_GET['page']) && $_GET['page'] == 'pitchpro_dashboard' && 'flushed' != get_option('pitchpro_rewrites_status') ) {
        flush_rewrite_rules();
        update_option('pitchpro_flush_rewrites', 'flushed');
    }
}

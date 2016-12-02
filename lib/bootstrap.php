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
		add_action( 'wp_enqueue_scripts', 'pitchpro_enqueue_scripts');
		add_filter( 'gform_after_submission', 'pitchpro_gform_after_submission', 10, 2 );
	} else {
		// let the user know prerequisites weren't met
		add_action( 'admin_head', array( 'PitchPro_App', 'fail_notices' ), 0, 0 );
	}
}

function pitchpro_enqueue_scripts(){
	wp_enqueue_script( 'pitchpro', plugins_url( 'pitchpro.js', __FILE__ ), array('jquery'), date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'pitchpro.js' )) );
	wp_localize_script( 'pitchpro', 'pitchpro', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));
	wp_enqueue_script( 'pitchpro' );
}

function pitchpro_get_template_part( $slug, $name = '', $once = true ){
    $name = (string) $name;
	$template = PITCHPRO_PATH . "template/theme/{$slug}.php";

    if ( '' !== $name )
        $template = PITCHPRO_PATH . "template/theme/{$slug}-{$name}.php";

	load_template( $template, $once );
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
function pitchpro_gform_after_submission( $entry, $form ){
	if( $form['title'] == 'Opt Out' ){
		global $wpdb;
		foreach($form['fields'] as $field ){
			if( $field['label'] == 'Opt Out Email' ){
				$opt_out_table = $wpdb->prefix . 'pitch_targets';
				$sendto = $entry[ $field['id'] ];
				$opt_out_ID = $wpdb->get_row( "SELECT ID FROM {$opt_out_table} WHERE target = '{$sendto}' LIMIT 1" );
				if( empty($opt_out_ID) ){
					$wpdb->insert($opt_out_table,array(
		                'target' => $sendto,
						'optout' => 1,
		                'last_updated' => date("Y-m-d H:i:s")
		            ),array('%s','%s'));
				} else {
					$wpdb->update($opt_out_table,array(
		                'optout' => 1,
		                'last_updated' => date("Y-m-d H:i:s")
		            ), array(
						'ID' => $opt_out_ID->ID
					), array('%s','%s'),
					array( '%d' ));
				}
				$wpdb->print_error();
			}
		}
	}
	return $form;
}

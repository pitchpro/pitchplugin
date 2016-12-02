<?php

function pitchpro_ajax_response_model( $r, $echo = true ){
    $default_response = array(
        'data' => null,
        'message' => '',
        'success' => false
    );
    $r = json_encode( wp_parse_args( $r, $default_response ) );
    if( $echo ){
        echo $r;
    } else {
        return $r;
    }
}

add_action( 'wp_ajax_pitchpro_pitch_mark_paid', 'pitchpro_pitch_mark_paid' );
function pitchpro_pitch_mark_paid(){
    $pitch_guid = $_REQUEST['guid'];
    print_r($_REQUEST['status']);
    $pitch = PitchPro_Pitch::retrieve($pitch_guid);
    $r = update_post_meta($pitch->ID,'payment_status',$_REQUEST['status']) ? array(
        'success' => true,
        'message' => 'Successfully changed payment status.',
        'data' => $_REQUEST['status']
    ) : array(
        'message' => 'Failed to change the status.'
    );

    pitchpro_ajax_response_model( $r );
    die;
}

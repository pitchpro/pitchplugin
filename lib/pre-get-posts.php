<?php

function pitchpro_pre_get_posts( $query ) {
    // filter pitches by associated campaign
    if( !is_admin() && $query->query['post_type'] == PitchPro_Pitch::POSTTYPE && !empty( $_REQUEST['campaign'] ) ){
        $query->set( 'meta_key', 'associated_campaign' );
        $query->set( 'meta_value', PitchPro_Campaign::get_campaign_id_by_guid( $_REQUEST['campaign'] ) );
    }

    // ensure global admin query include expired pitches
    if( is_admin() && empty($query->query['post_status']) && $query->query['post_type'] == PitchPro_Pitch::POSTTYPE ){
        $query->set( 'post_status', array( 'publish', 'draft', 'expire', 'sent', 'claim', 'accept', 'decline' ) );
    }

    // ensure global non-admin query include expired pitches
    if( !is_admin() && is_user_logged_in() && $query->query['post_type'] == PitchPro_Pitch::POSTTYPE ){
        $query->set( 'post_status', array( 'publish', 'draft', 'expire', 'sent', 'claim', 'accept', 'decline' ) );
    }

    // ensure global non-admin non-logged in query include expired pitches
    if( !is_admin() && !is_user_logged_in() && $query->query['post_type'] == PitchPro_Pitch::POSTTYPE ){
        $query->set( 'post_status', array( 'publish', 'expire', 'sent', 'claim', 'accept', 'decline' ) );
    }
}
add_action( 'pre_get_posts', 'pitchpro_pre_get_posts' );

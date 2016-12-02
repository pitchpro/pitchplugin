<?php

function pitchpro_pre_get_posts( $query ) {
    // ensure global admin query include expired pitches
    if( is_admin() &&
        empty($query->query['post_status']) &&
        $query->query['post_type'] == PitchPro_Pitch::POSTTYPE
    ){
        // add conditional to determine post_status hasn't been sent through
        $post_status = $query->get('post_status');
        if( empty($post_status) )
            $query->set( 'post_status', array( 'publish', 'draft', 'expire', 'sent', 'claim', 'accept', 'decline' ) );
    }

    // ensure global non-admin query include expired pitches
    if( !is_admin() &&
        is_user_logged_in() &&
        !empty($query->query['is_stats_query']) &&
        !$query->query['is_stats_query'] &&
        $query->query['post_type'] ==
        PitchPro_Pitch::POSTTYPE
    ){
        // add conditional to determine post_status hasn't been sent through
        $post_status = $query->get('post_status');
        if( empty($post_status) )
            $query->set( 'post_status', array( 'publish', 'draft', 'expire', 'sent', 'claim', 'accept', 'decline' ) );
    }

    // ensure global non-admin non-logged in query include expired pitches
    if( !is_admin() &&
        !is_user_logged_in() &&
        $query->query['post_type'] == PitchPro_Pitch::POSTTYPE
    ){
        // add conditional to determine post_status hasn't been sent through
        $post_status = $query->get('post_status');
        if( empty($post_status) )
            $query->set( 'post_status', array( 'publish', 'expire', 'sent', 'claim', 'accept', 'decline' ) );
    }


    /****** FILTERS *******/
    // filter pitches by associated campaign
    if( !is_admin() &&
        $query->query['post_type'] == PitchPro_Pitch::POSTTYPE &&
        !empty( $_REQUEST['campaign'] )
    ){
        $meta_query = $query->get('meta_query');
        $meta_query[] = array(
                    'key'=>'associated_campaign',
                    'value'=>PitchPro_Campaign::get_campaign_id_by_guid( $_REQUEST['campaign'] ),
                    'compare'=>'=',
                );
        $query->set('meta_query',$meta_query);
    }

    // filter pitches by payment status
    if( !is_admin() &&
        $query->query['post_type'] == PitchPro_Pitch::POSTTYPE &&
        !empty( $_REQUEST['payment_status'] )
    ){
        $meta_query = $query->get('meta_query');
        $meta_query[] = array(
                    'key'=>'payment_status',
                    'value'=>$_REQUEST['payment_status'],
                    'compare'=>'=',
                );
        $query->set('meta_query',$meta_query);
    }

    // filter pitches by Accepted status
    if( !is_admin() &&
        $query->query['post_type'] == PitchPro_Pitch::POSTTYPE &&
        !empty( $_REQUEST['status'] ) &&
        in_array( $_REQUEST['status'], array( 'publish', 'sent', 'claim', 'accept', 'decline', 'expire' ) )
    ){
        // add conditional to determine post_status hasn't been sent through?
        // $post_status = $query->get('post_status');
        $post_status = $_REQUEST['status'];
        $query->set('post_status',$post_status);
    }
}
add_action( 'pre_get_posts', 'pitchpro_pre_get_posts' );

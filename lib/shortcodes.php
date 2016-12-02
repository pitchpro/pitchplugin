<?php

add_shortcode( 'pitchpro', 'pitchpro_shortcode' );
add_shortcode( 'pitchpro_campaign', 'pitchpro_campaign_shortcode' );
add_shortcode( 'pitchpro_pitch', 'pitchpro_pitch_shortcode' );
add_shortcode( 'pitchpro_field', 'pitchpro_field_shortcode' );

function pitchpro_shortcode($atts){

    $properties = shortcode_atts( array(
        'id' => null,
        'type' => 'default',
        'limit' => 3
    ), $atts, 'pitchpro' );

    ob_start();

    switch( $properties['type'] ){
        case 'pitch-stats':
            global $post;
            $filter_url = get_permalink( get_page_by_path('pitches', OBJECT, PitchPro_App::POSTTYPE ) );
            $pitch_stats = PitchPro_Stats::pitch_full_stats( get_the_ID() );
            //?status=&payment_status=paid&campaign=79700064-72e7-4c25-95c3-2bc02736065e
            $stat_list = array(
                'sent' => array( 'label' => 'Sent', 'link' => $filter_url . '?status=sent&campaign=' . $post->post_name ),
                'opened' => array( 'label' => 'Opened', 'link' => null ),
                'viewed' => array( 'label' => 'Viewed', 'link' => null ),
                'accept' => array( 'label' => 'Accepted', 'link' => $filter_url . '?status=accept&campaign=' . $post->post_name ),
                'decline' => array( 'label' => 'Declined', 'link' => $filter_url . '?status=decline&campaign=' . $post->post_name ),
                'expire' => array( 'label' => 'Expired', 'link' => $filter_url . '?status=expire&campaign=' . $post->post_name ),
                'paid' => array( 'label' => 'Paid', 'link' => $filter_url . '?payment_status=paid&campaign=' . $post->post_name ),
                'total' => array( 'label' => 'Total', 'link' => $filter_url . '?campaign=' . $post->post_name )
            );
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/pitch-stats-block.php';
            break;
        case 'email_stylesheet':
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/email-stylesheet.php';
            break;
        case 'pitch-email':
            global $post;
            $post = $pitch;
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/email-richtext.php';
            break;
        case 'send-pitch-button':
            $shortcode_args = array(
                'post_type' => PitchPro_Campaign::POSTTYPE
            );
            global $post;
            $pitch_id = empty($_REQUEST['pitchit']) ? $post->ID : $_REQUEST['pitchit'];
            if( !empty($properties['id'])){
                $pitch_id = $properties['id'];
            }
            $pitch_guid = PitchPro_Pitch::get_the_guid( $pitch_id );
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/button-send-pitch.php';
            break;
        case 'edit-pitch-button':
            $shortcode_args = array(
                'post_type' => PitchPro_Campaign::POSTTYPE
            );
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/button-edit-pitch.php';
            break;
        case 'preview-pitch':
            global $post;
            $post = $pitch;
            $pitch_id = empty($_REQUEST['pitchit']) ? $post->ID : $_REQUEST['pitchit'];
            if( !empty($properties['id'])){
                $pitch_id = $properties['id'];
            }
            $pitch_guid = PitchPro_Pitch::get_the_guid( $pitch_id );
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/pitch-preview.php';
            break;
        case 'send-pitch':
            $shortcode_args = array(
                'post_type' => PitchPro_Campaign::POSTTYPE
            );
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/action-send-pitch.php';
            break;
        case 'my-campaigns':
            $shortcode_args = array(
                'post_type' => PitchPro_Campaign::POSTTYPE,
                'posts_per_page' => $properties['limit']
            );
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/list-my-campaigns.php';
            break;
        case 'my-pitches':
            $shortcode_args = array(
                'post_type' => PitchPro_Pitch::POSTTYPE,
                'post_status' => array('publish','draft','expire', 'sent'),
                'posts_per_page' => $properties['limit']
            );
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/list-my-pitches.php';
            break;
        default:
            $shortcode_args = array();
            $shortcode_template = PITCHPRO_PATH . 'template/shortcodes/default.php';
            break;
    }

    if( !empty( $shortcode_args ) ){
        global $wp_query;
        $shortcode_wp_query = new WP_Query($shortcode_args);
        $wp_query = $shortcode_wp_query;
    }
    include $shortcode_template;
    wp_reset_query();

    $html = ob_get_clean();

    return $html;
}

function pitchpro_campaign_shortcode( $atts ){
    $properties = shortcode_atts( array(
        'id' => null,
        "type" => 'my-campaigns',
        "limit" => 3
    ), $atts, 'pitchpro_campaign' );
    return pitchpro_shortcode($properties);
}

function pitchpro_pitch_shortcode( $atts ){
    $properties = shortcode_atts( array(
        'id' => null,
        "type" => 'my-pitches',
        "limit" => 3
    ), $atts, 'pitchpro_pitch' );
    return pitchpro_shortcode($properties);
}

function pitchpro_field_shortcode( $atts ){
    $properties = shortcode_atts( array(
        'id' => null,
        'pitch_id' => null
    ), $atts, 'pitchpro_field' );

    ob_start();
    if( !empty($properties['pitch_id']) ){
        $pitch_id = $properties['pitch_id'];
    } elseif (!empty($_REQUEST['guid'])) {
        $pitch = PitchPro_Pitch::retrieve($_REQUEST['guid']);
        $pitch_id = $pitch->ID;
    } elseif (!empty($_REQUEST['pitchit'])) {
        $pitch_id = $_REQUEST['pitchit'];
    } else {
        global $post;
        $pitch_id = $post->ID;
    }


    switch( $properties['id'] ){
        case 'org_logo':
            $campaign_id = get_field( 'associated_campaign', $pitch_id );
            $organization_id = get_field( 'associated_org', $campaign_id );
            $logo = get_field( 'org_logo', $organization_id );
            $organization = get_post( $organization_id );
            $more_info_url = get_field( 'more_info_url', $organization_id );
            printf( '<a href="%s" target="_blank"><img src="%s" alt="%s" /></a>', $more_info_url, $logo['sizes']['medium_large'], $organization->post_title );
            break;
        case 'pitch_subject':
            $subject = get_field('email_subject',$pitch_id);
            $campaign_id = get_field( 'associated_campaign', $pitch_id );
            if( empty($subject) ){
                $subject = get_field('email_subject',$campaign_id);
            }
            echo $subject;
            break;
        case 'url_pitch':
            echo get_permalink( $pitch_id );
            break;
        case 'pitch_text':
            if( empty($pitch) ){
                $pitch = get_post( $pitch_id );
            }
            $campaign = PitchPro_Campaign::retrieve( get_field( 'associated_campaign', $pitch_id ), true );
            echo $campaign->post_content . '<br /><br />' . $pitch->post_content;
            break;
        case 'pitch_preview':
            $campaign = PitchPro_Campaign::retrieve( get_field( 'associated_campaign', $pitch_id ), true );
            echo wp_trim_words($campaign->post_content, 15);
            break;
        case 'incentive_amount':
            echo '$' . money_format('%i', get_field( 'payout_amount', $pitch_id));
            break;
        case 'pitch_expire':
            printf( 'This offer expires %s',
                date('F jS, Y', strtotime( get_field( 'pitch_expires', $pitch_id ) ) )
            );
            break;
        case 'send_to':
            the_field( 'send_to', $pitch_id );
            break;
        default: break;
    }
    $html = ob_get_clean();

    return $html;
}

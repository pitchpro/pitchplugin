<?php

if( empty($_REQUEST['guid']) ) :

    ?><b>You are missing the secret key to send email.</b><?php

else:
    global $wpdb, $post;
$guid = $_REQUEST['guid'];
$pitch = PitchPro_Pitch::retrieve( $guid );
$post = $pitch;
$campaign_id = get_field('associated_campaign',$pitch->ID);
$organization_id = get_field( 'associated_org', $campaign_id );
$org_reply_to = get_field( 'org_reply_to', $organization_id );
$sendto = get_field('send_to', $pitch->ID);
$subject = get_field('email_subject',$pitch->ID);
$opt_out_table = $wpdb->prefix . 'pitch_targets';
$opt_out_query = $wpdb->get_row( "SELECT optout FROM {$opt_out_table} WHERE target = '{$sendto}' LIMIT 1" );
$opt_out = !empty($opt_out_query->optout) && $opt_out_query->optout == 1 ? true : false;
if( $opt_out ){
    ?>The email was not sent because <b><?php echo $sendto; ?></b> has chosen to opt out from all communication from Pitch Pro.<?php
} else {
    if( empty($subject) ){
        $subject = get_field('email_subject',$campaign_id);
    }

    ob_start();
    // print_r($pitch);
    include_once PITCHPRO_PATH . 'template/shortcodes/email-richtext.php' ;
    $richtext_email = ob_get_clean();
    ob_start();
    // print_r($pitch);
    include_once PITCHPRO_PATH . 'template/shortcodes/email-plaintext.php' ;
    $plaintext_email = ob_get_clean();
    $message['text/html'] = do_shortcode($richtext_email);
    $message['text/plain'] = do_shortcode($plaintext_email);
    wp_reset_query();
    if( !empty($org_reply_to) ){
        $headers[]   = 'Reply-To: ' . $org_reply_to;
    }
    $headers[] = 'Content-Type: text/html; charset=UTF-8' . "\r\n";
    // $headers[] = 'From: Pitch Pro ' . "\r\n";
    $attachments = array();
    $mailer_result = wp_mail($sendto,$subject,$message,$headers, $attachments);

    if($mailer_result) :
        if( empty($opt_out_query) ){
            $wpdb->insert($opt_out_table,array(
                'target' => $sendto,
                'last_updated' => date("Y-m-d H:i:s")
            ),array('%s','%s'));
        }
        PitchPro_Pitch::mark_as_sent($pitch->ID);
        ?>Email sent successfully<?php
    else :
        ?>Error sending the email<?php
        print_r($mailer_result);
    endif;
}

// if guid exists
endif;

wp_reset_query();

<?php

if( empty($_REQUEST['guid']) ) :

    ?><b>You are missing the secret key to send email.</b><?php

else:
    global $wpdb;
$guid = $_REQUEST['guid'];
$pitch = PitchPro_Pitch::retrieve( $guid );
$campaign = get_field('associated_campaign',$pitch->ID);
$sendto = get_field('send_to', $pitch->ID);
$subject = get_field('email_subject',$pitch->ID);
$opt_out_table = $wpdb->prefix . 'pitch_targets';
$opt_out_query = $wpdb->get_row( "SELECT optout FROM {$opt_out_table} WHERE target = '{$sendto}' LIMIT 1" );
$opt_out = !empty($opt_out_query->optout) && $opt_out_query->optout == 1 ? true : false;
if( $opt_out ){
    ?>The email was not sent because <b><?php echo $sendto; ?></b> has chosen to opt out from all communication from Pitch Pro.<?php
} else {
    if( empty($subject) ){
        $subject = get_field('email_subject',$campaign);
    }
    global $post;
    $post = $pitch;
    ob_start();
    include_once PITCHPRO_PATH . 'template/shortcodes/email.php' ;
    $message_template = ob_get_clean();
    $message = do_shortcode($message_template);
    wp_reset_query();
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

<?php

if( empty($_REQUEST['guid']) ) : ?>

Something went wrong

<?php else:
$guid = $_REQUEST['guid'];
$pitch = PitchPro_Pitch::retrieve( $guid );
$campaign = get_field('associated_campaign',$pitch->ID);

// print_r($pitch);
$sendto = get_field('send_to', $pitch->ID);
$subject = get_field('email_subject',$pitch->ID);
if( empty($subject) )
    $subject = get_field('email_subject',$campaign->ID);
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

PitchPro_Pitch::mark_as_sent($pitch->ID);

$mailer_result = wp_mail($sendto,$subject,$message,$headers, $attachments);

if($mailer_result) :
    ?>Email sent successfully<?php
else :
    ?>Error sending the email<?php
    print_r($mailer_result);
endif;

// if guid exists
endif;

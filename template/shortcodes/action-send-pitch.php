<?php

if( empty($_REQUEST['guid']) ) : ?>

Something went wrong

<?php else:
$guid = $_REQUEST['guid'];
$pitch = PitchPro_Pitch::retrieve( $guid );

// print_r($pitch);
$sendto = get_field('send_to', $pitch->ID);
$subject = 'Custom subject line';
$message = $pitch->post_content . '<br /><br /><a href="' . get_permalink( $pitch->ID ) . '">View Pitch</a>';
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

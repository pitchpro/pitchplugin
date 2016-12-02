<?php

 global $wpdb;
 $sendto = get_field('send_to', $pitch_id);
 $subject = get_field('email_subject', $pitch_id);
 $campaign = get_field('associated_campaign', $pitch_id);
 $opt_out_table = $wpdb->prefix . 'pitch_targets';
 $opt_out_query = $wpdb->get_row( "SELECT optout FROM {$opt_out_table} WHERE target = '{$sendto}' LIMIT 1" );
 $opt_out = !empty($opt_out_query->optout) && $opt_out_query->optout == 1 ? true : false;

 if( empty($subject) ){
     $subject = get_field('email_subject',$campaign);
 }
?>
	<section class="entry">
        <?php if( $opt_out ) : ?><div class="pitch-preview-warning">The email will not be sent because <b><?php echo $sendto; ?></b> has chosen to opt out from all communication from Pitch Pro.</div><?php endif; ?>
        <div class="pitch-preview-to">To: <b><?php echo $sendto; ?></b></div>
        <div class="pitch-preview-subject">Subject: <b><?php echo $subject; ?></b></div>
        <div class="pitch-preview">
            <?php

            ob_start();
            include_once PITCHPRO_PATH . 'template/shortcodes/email-richtext.php' ;
            $message_template = ob_get_clean();
            echo do_shortcode($message_template);

            ?>
        </div>
	    <?php

            echo do_shortcode( '[pitchpro type="send-pitch-button" id="'.$pitch_id.'"]' );
            // echo do_shortcode( '[pitchpro type="edit-pitch-button" id="'.$pitch_id.'"]' );

	    ?>
	</section><!-- /.entry -->
	<div class="fix"></div>

<?php

 global $woo_options;

 $campaign = PitchPro_Campaign::retrieve( get_field( 'associated_campaign', get_the_ID() ), true );
 woo_post_before();
?>
<article <?php post_class(); ?>>
<?php
	woo_post_inside_before();
?>
	<section class="entry">
        <p><b>Welcome <?php the_field('send_to'); ?>,</b></p>
        <p><?php echo $campaign->post_title; ?> would like to make you an offer!</p>



	    <?php


            $pitch_expires = get_field('pitch_expires');
            if( $pitch_expires ) {
                ?><p>This offer expires <?php echo date('F jS, Y', strtotime( $pitch_expires )); ?>!</p><?php
            }



                    $campaign_content = apply_filters('the_content', $campaign->post_content);

                    echo '<div>' . $campaign_content . '</div>';

	    	if ( ! is_singular() ) {
	    		the_excerpt();
	    	} else {
	    		the_content( __( 'Continue Reading &rarr;', 'woothemes' ) );
	    	}


            $campaign_files = get_field('campaign_files', $campaign->ID);

            ?><div>
                <h3>Additional files for your review:</h3>
                <ul><?php
            foreach($campaign_files as $file){
                $file_title = empty($file['title']) ? basename($file['file']) : $file['title'];
                ?><li><a href="<?php echo $file['file']; ?>" target="_new"><?php echo $file_title; ?></a></a><?php
            }
            ?></ul></div>
            <p>Thank you for Reviewing this Pitch. As a way of thanking you, please tell us how to pay you the <b><?php echo '$' . money_format('%i', get_field( 'payout_amount', get_the_ID())); ?></b> that we promised to pay you!</p>
            <?php

            echo do_shortcode('[gravityform id="4" title="false" description="false" ajax="true"]');

	    ?>

        <p><a href="http://pitchpro.co/app/opt-out/?opt_out_email=<?php the_field('send_to'); ?>">Opt out of future solicitations.</a></p>
	</section><!-- /.entry -->
	<div class="fix"></div>
<?php
	woo_post_inside_after();
?>
</article><!-- /.post -->
<?php
	woo_post_after();

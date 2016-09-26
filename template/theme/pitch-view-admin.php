<?php

 global $woo_options;

 woo_post_before();
?>
<article <?php post_class(); ?>>
<?php
	woo_post_inside_before();
?>
	<section class="entry">
	    <?php

            the_title();

	    	if ( ! is_singular() ) {
	    		the_excerpt();
	    	} else {
	    		the_content( __( 'Continue Reading &rarr;', 'woothemes' ) );
	    	}

            echo 'Incentive: $' . money_format('%i', get_field( 'payout_amount', get_the_ID()));
            echo do_shortcode( '[pitchpro type="send-pitch-button" id="'.get_the_ID().'"]' );
            echo do_shortcode( '[pitchpro type="edit-pitch-button" id="'.get_the_ID().'"]' );

	    ?>
	</section><!-- /.entry -->
	<div class="fix"></div>
<?php
	woo_post_inside_after();
?>
</article><!-- /.post -->
<?php
	woo_post_after();

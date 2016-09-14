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

            echo 'Bounty: $' . money_format('%i', get_field( 'payout_amount', get_the_ID()));

            echo do_shortcode('[gravityform id="4" title="false" description="false" ajax="true"]');

	    ?>
	</section><!-- /.entry -->
	<div class="fix"></div>
<?php
	woo_post_inside_after();
?>
</article><!-- /.post -->
<?php
	woo_post_after();

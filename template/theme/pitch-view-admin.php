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
	    ?>

        <div class="ubtn-ctn-center">
                <a class="ubtn-link ult-adjust-bottom-margin ubtn-left ubtn-large"
                href="?edit=true" target=""><button class=
                "ubtn ult-adjust-bottom-margin ult-responsive ubtn-large ubtn-no-hover-bg none ubtn-sep-icon ubtn-sep-icon-at-left ubtn-left tooltip-57d8b32f31ce0"
                data-bg="#81d742" data-border-color="" data-border-hover="" data-hover=
                "" data-hover-bg="" data-responsive-json-new=
                "{&quot;font-size&quot;:&quot;&quot;,&quot;line-height&quot;:&quot;&quot;}"
                data-shadow="" data-shadow-click="none" data-shadow-hover=""
                data-shd-shadow="" data-ultimate-target="#ubtn-3191" id="ubtn-3191"
                style=
                "font-weight:normal;border:none;background: #81d742;color: #ffffff;"
                type="button"><span class="ubtn-data ubtn-icon"><i class=
                "Defaults-sign-in" style=
                "font-size:32px;color:#ffffff;"></i></span><span class="ubtn-hover"
                style="background-color:"></span><span class="ubtn-data ubtn-text">Edit Pitch</span></button></a>
            </div>


	</section><!-- /.entry -->
	<div class="fix"></div>
<?php
	woo_post_inside_after();
?>
</article><!-- /.post -->
<?php
	woo_post_after();

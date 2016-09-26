<?php

 global $woo_options, $post;

 woo_post_before();
?>
<article <?php post_class(); ?>>
<?php
	woo_post_inside_before();
?>
	<section class="entry">
	    <?php

        echo do_shortcode('[gravityform id="2" title="false" description="false" update]');

	    ?>
	</section><!-- /.entry -->
	<div class="fix"></div>
<?php
	woo_post_inside_after();
?>
</article><!-- /.post -->
<?php
	woo_post_after();

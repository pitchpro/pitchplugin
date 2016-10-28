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

        echo do_shortcode('[userpro template="login" login_redirect="/app/dashboard/" login_button_secondary="" ]');

	    ?>
	</section><!-- /.entry -->
	<div class="fix"></div>
<?php
	woo_post_inside_after();
?>
</article><!-- /.post -->
<?php
	woo_post_after();

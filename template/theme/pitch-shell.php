<?php
global $post;
get_header();
?>

    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full">

    	<div id="main-sidebar-container">

            <!-- #main Starts -->
            <?php woo_main_before(); ?>
            <section id="main">
<?php
	woo_loop_before();

	if (have_posts()) { $count = 0;
        while (have_posts()) { the_post(); $count++;
            if( !is_user_logged_in() && get_post_status() == 'expire'){
                pitchpro_get_template_part( 'pitch', 'expired' );
            } else if( is_user_logged_in() ){
				if(!empty($_REQUEST['edit'])){
					pitchpro_get_template_part( 'pitch', 'edit' );
				} else {
					pitchpro_get_template_part( 'pitch', 'view-admin' );
				}
            } else if( get_post_status() == 'claimed' ) {
				pitchpro_get_template_part( 'pitch', 'claimed' );
			} else {
                pitchpro_get_template_part( 'pitch', 'view' );
            }
        }
    }

	woo_loop_after();
?>
            </section><!-- /#main -->
            <?php woo_main_after(); ?>

            <?php get_sidebar(); ?>

		</div><!-- /#main-sidebar-container -->

		<?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<?php get_footer(); ?>

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
            if( is_user_logged_in() ){
                pitchpro_get_template_part( 'campaign', 'edit' );
            } else {
                pitchpro_get_template_part( 'app', 'login' );
            }
        }
    }

	woo_loop_after();
?>
            </section><!-- /#main -->
            <?php woo_main_after(); ?>

            <?php if( is_user_logged_in() ){ get_sidebar('pitchpro-edit-campaign'); } ?>

		</div><!-- /#main-sidebar-container -->

		<?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<?php get_footer(); ?>

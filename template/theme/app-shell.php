<?php
/**
 * Page Template
 *
 * This template is the default page template. It is used to display content when someone is viewing a
 * singular view of a page ('page' post_type) unless another page template overrules this one.
 * @link http://codex.wordpress.org/Pages
 *
 * @package WooFramework
 * @subpackage Template
 */

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

    if( !is_valid_guid( get_last_path_segment($_SERVER['REQUEST_URI']) ) ){
        pitchpro_get_template_part( 'pitch', 'list' );
    } else {
        if (have_posts()) { $count = 0;
            while (have_posts()) { the_post(); $count++;
                if( !is_user_logged_in() && get_post_status() == 'expire'){
                    pitchpro_get_template_part( 'pitch', 'expired' );
                } else if( is_user_logged_in() ){
                    pitchpro_get_template_part( 'pitch', 'edit' );
                } else {
                    pitchpro_get_template_part( 'pitch', 'view' );
                }
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

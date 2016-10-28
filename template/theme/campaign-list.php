<?php

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

		$user_has_multi_org = count(PitchPro_Organization::get_my_organization()) > 1;

?>
<section class="entry">
<table>
    <tr>
		<?php /* if( $user_has_multi_org ) : ?>
			<th>Organization</th>
		<?php endif; */ ?>
        <th>Campaign</th>
        <th>Satus</th>
        <th>Pitches</th>
        <th>&nbsp;</th>
    </tr>
<?php
        while (have_posts()) : the_post(); $count++;

            $associated_org = get_field( "associated_org", get_the_ID() );

             global $woo_options;
            ?>
            <tr <?php post_class(); ?>>
					<?php /* if( $user_has_multi_org ) : ?>
						<td>
							<?php if( $associated_org ) : ?>
							<a href="<?php echo get_permalink( $associated_org ); ?>"><?php echo get_the_title( $associated_org ); ?></a>
							<?php endif; ?>
						</td>
					<?php endif; */ ?>
                    <td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
                    <td><?php echo PitchPro_Campaign::$campaign_status[ get_post_status() ]; ?></td>
                    <td><a href="/app/pitches/?campaign=<?php echo $post->post_name; ?>"><?php echo PitchPro_Pitch::get_count_associated_to_campaign( get_the_ID() ); ?></a></td>
                    <td><a href="<?php echo get_permalink( get_page_by_path('pitch-create', OBJECT, PitchPro_App::POSTTYPE ) ); ?>?c=<?php echo get_the_ID(); ?>">Create Pitch</a></td>
        </tr>
            <?php

        endwhile;
        ?>

    </table></section>
        <?php
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

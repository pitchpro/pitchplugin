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

?>
<section class="entry">
<div>
	<p>Filter by:</p>
	<p>
	Status:
		<select>
			<option>Select status</option>
		</select>
	Payment:
		<select>
			<option>Select payment</option>
		</select>
	Campaign:
		<select>
			<option>Select campaign</option>
		</select>
	<button>Filter</button>
</p>
</div>
<table>
    <tr>
        <th>Date</th>
        <th>Target</th>
        <th>Campaign</th>
        <th>Satus</th>
        <th>Payment</th>
        <th>Incentive</th>
    </tr>
<?php
        while (have_posts()) : the_post(); $count++;

            $associated_campaign = get_field( "associated_campaign", get_the_ID() );


             global $woo_options;
            ?>
            <tr <?php post_class(); ?>>
                    <td>created, edited, or sent?</td>
                    <td><a href="<?php the_permalink(); ?>"><?php the_field( 'send_to', get_the_ID()); ?></a></td>
                    <td><a href="<?php echo get_permalink( $associated_campaign ); ?>"><?php echo get_the_title( $associated_campaign ); ?></a></td>
                    <td><?php echo PitchPro_Pitch::$pitch_status[ get_post_status() ]; ?></td>
                    <td><?php the_field( 'payment_status', get_the_ID()); ?></td>
                    <td>$<?php echo money_format('%i', get_field( 'payout_amount', get_the_ID())); ?></td>
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

<?php

get_header();
global $pitchpro_payment_status_current;
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
<form id="filter_pitches" method="get">
	<p>Filter by:</p>
	<p>
	Status:
		<select name="status">
			<option>Select status</option>
			<?php foreach (PitchPro_Pitch::$pitch_status as $status => $label ) : ?>
			<option value="<?php echo $status; ?>" <?php selected( @$_REQUEST['status'], $status ); ?>><?php echo $label; ?></option>
			<?php endforeach; ?>
		</select>
	Payment:
		<?php

		$pitchpro_payment_status_current = @$_REQUEST['payment'];
		pitchpro_get_template_part( 'select', 'pitch-status', false );

		?>
	Campaign:
		<select name="campaign">
			<option>Select campaign</option>
			<?php foreach (PitchPro_Campaign::get_all_campaigns() as $campaign ) : ?>
			<option value="<?php echo $campaign->post_name; ?>" <?php selected( @$_REQUEST['campaign'], $campaign->post_name ); ?>><?php echo $campaign->post_title; ?></option>
			<?php endforeach; ?>
		</select>
	<button type="submit" value="Filter" form="filter_pitches">Filter</button>
</p>
</form>
<table>
    <tr>
        <th>Pitched To</th>
        <th>Campaign</th>
        <th>Satus</th>
        <th>Payment</th>
        <th>Incentive</th>
		<th class="sortable" data-sort-type="created-on">Created On</th>
    </tr>
<?php
        while (have_posts()) : the_post(); $count++;

            $associated_campaign = get_field( "associated_campaign", get_the_ID() );


             global $woo_options;
            ?>
            <tr <?php post_class(); ?>>
                    <td><a href="<?php the_permalink(); ?>"><?php the_field( 'send_to', get_the_ID()); ?></a></td>
                    <td><a href="<?php echo get_permalink( $associated_campaign ); ?>"><?php echo get_the_title( $associated_campaign ); ?></a></td>
                    <td><?php echo PitchPro_Pitch::$pitch_status[ get_post_status() ]; ?></td>
                    <td>
						<?php

						$pitchpro_payment_status_current = get_field( 'payment_status', get_the_ID());
						pitchpro_get_template_part( 'select', 'pitch-status', false );

						?>
					</td>
                    <td>$<?php echo money_format('%i', get_field( 'payout_amount', get_the_ID())); ?></td>
                    <td><?php echo get_the_date('m-d-Y'); ?></td>
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

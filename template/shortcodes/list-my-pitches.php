<?php

// print_r($properties);
// print_r($shortcode_wp_query->posts);


if ( have_posts() ):
    ?><table><tr><th>Status</th><th>Target</th><th>Campaign</th></tr><?php
    while ( have_posts() ) :
        global $post;
        the_post();

        $associated_campaign = get_field( "associated_campaign", get_the_ID() );

        ?><tr>
            <td><?php echo get_post_status(); ?></td>
            <td><a href="<?php the_permalink(); ?>"><?php the_field( 'send_to', get_the_ID()); ?></a></td>
            <td><a href="<?php echo get_the_permalink( $associated_campaign ); ?>"><?php echo get_the_title( $associated_campaign ); ?></a></td>
        </tr><?php

    endwhile;
    ?></table><?php
endif;

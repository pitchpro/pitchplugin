<?php

if ( have_posts() ):
    ?><table><tr><th>Status</th><th>Name</th><th>Pitches</tr></tr><?php
    while ( have_posts() ) :
        global $post;
        the_post();

        $pitch_count = PitchPro_Pitch::get_count_associated_to_campaign( get_the_ID() );

        ?><tr>
            <td><?php echo PitchPro_Campaign::$campaign_status[ get_post_status() ]; ?></td>
            <td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
            <td><?php if( $pitch_count > 0 ) : ?><a href="<?php echo get_permalink( get_page_by_path('pitches', OBJECT, PitchPro_App::POSTTYPE ) ); ?>?campaign=<?php echo $post->post_name; ?>"><?php endif; echo $pitch_count; if( $pitch_count > 0 ) : ?></a><?php endif; ?></td>
        </tr><?php

    endwhile;
    ?></table><?php
endif;

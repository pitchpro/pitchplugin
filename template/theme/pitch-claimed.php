<?php global $post; ?>
This offer has already been claimed. If you believe this to be in error, please contact <a href="mailto:<?php echo get_the_author_meta('email',$post->post_author); ?>"><?php echo get_the_author_meta('display_name',$post->post_author); ?></a>.

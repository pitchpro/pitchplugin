<?php

// woothemes`missing the __before_loop and __after_loop hooks on themes
add_action( 'woo_loop_before', '__before_loop' );
if(!function_exists('__before_loop')){
	function __before_loop(){
		do_action( '__before_loop' );
	}
}

add_action( 'woo_loop_after', '__after_loop' );
if(!function_exists('__after_loop')){
	function __after_loop(){
		do_action( '__after_loop' );
	}
}


add_action( 'woo_header_after','pitchpro_app_nav', 0 );
function pitchpro_app_nav(){
	if( in_array( get_post_type(), array(PitchPro_App::POSTTYPE, PitchPro_Organization::POSTTYPE, PitchPro_Campaign::POSTTYPE, PitchPro_Pitch::POSTTYPE)) ){
		// if to kill menue section completely
		// remove_action( 'woo_header_after','woo_nav', 10 );
		// add_action( 'woo_header_after','pitchpro_app_woo_nav', 10 );

		// if to modify just the menue
		remove_action( 'woo_nav_inside','woo_nav_primary', 10 );
		remove_action( 'woo_post_after', 'woo_postnav', 10 );
		add_action( 'woo_nav_inside','pitchpro_woo_nav_primary', 10 );
	}
}

function pitchpro_woo_nav_primary() {

	if ( is_user_logged_in() && function_exists( 'has_nav_menu' ) && has_nav_menu( 'pitchpro-app' ) ) {
		echo '<h3>' . woo_get_menu_name( 'pitchpro-app' ) . '</h3>';
		wp_nav_menu( array( 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'main-nav', 'menu_class' => 'nav fl', 'theme_location' => 'pitchpro-app' ) );
	}

} // End pitchpro_woo_nav_primary()

function woo_author_box () {
	global $post;
	$author_id=$post->post_author;

    if( in_array( $post->post_type, array( PitchPro_App::POSTTYPE, PitchPro_Organization::POSTTYPE, PitchPro_Campaign::POSTTYPE, PitchPro_Pitch::POSTTYPE )))
        return;

	// Adjust the arrow, if is_rtl().
	$arrow = '&rarr;';
	if ( is_rtl() ) $arrow = '&larr;';
?>
<aside id="post-author">
	<div class="profile-image"><?php echo get_avatar( $author_id, '80' ); ?></div>
	<div class="profile-content">
		<h4><?php printf( esc_attr__( 'About %s', 'woothemes' ), get_the_author_meta( 'display_name', $author_id ) ); ?></h4>
		<?php echo get_the_author_meta( 'description', $author_id ); ?>
		<?php if ( is_singular() ) { ?>
		<div class="profile-link">
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) ); ?>">
				<?php printf( __( 'View all posts by %s %s', 'woothemes' ), get_the_author_meta( 'display_name', $author_id ), '<span class="meta-nav">' . $arrow . '</span>' ); ?>
			</a>
		</div><!--#profile-link-->
		<?php } ?>
	</div>
	<div class="fix"></div>
</aside>
<?php
} // End woo_author_box

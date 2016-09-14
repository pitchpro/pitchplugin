<?php

if( !class_exists('PitchPro_Pitch') ){
    class PitchPro_Pitch{

        const LABEL = 'Pitch';
        const POSTTYPE = 'pitchpro_pitch';
        const URL_PREFIX = 'app/pitch/';

        private static $_this;
        public $path;
        protected $postTypeArgs = array(
			'public'          => true,
			'rewrite'         => array( 'slug' => self::URL_PREFIX, 'with_front' => false ),
			'menu_position'   => 3,
			'supports'        => array(
				'title',
				'editor',
				'excerpt',
				'author',
				'thumbnail',
				'custom-fields',
				'comments',
			),
            'labels'          => array(
                'name'               => 'PitchPro Pitch',
				'singular_name'      => 'Pitch',
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New Pitch',
				'edit_item'          => 'Edit Pitch',
				'new_item'           => 'New Pitch',
				'view_item'          => 'View Pitch',
				'search_items'       => 'Search Pitch',
				'not_found'          => 'Not found',
				'not_found_in_trash' => 'Not found in Trash'
            ),
            'exclude_from_search' => true,
            'menu_position' => null,
            'show_in_menu'    => false,
            'supports'        => array('title','editor', 'page-attributes'),
			'map_meta_cap'    => true,
			'has_archive'     => true,
		);

		function __construct() {
			$this->path = trailingslashit( dirname( __FILE__ ) );
			add_action( 'init', array( $this, 'init' ), 0 );
		}

        function init(){
            add_action( 'init', array( $this, 'register_post_types' ), 1 );
            add_action('admin_menu', array( $this, 'custom_menue' ), 10 );
            add_filter( 'wp_unique_post_slug', array( $this, 'unique_guid_post_name' ), 10, 4 );
            add_action( 'gform_after_submission', array( $this, 'gform_after_submission' ), 20, 2 );
            add_filter( 'template_include', array( $this, 'template_include' ), 99 );
            add_action( 'template_redirect', array( $this, 'template_redirect' ), 20 );
            add_action( 'admin_footer-post.php', array( $this, 'custom_post_status_list' ) );
            add_filter( 'display_post_states', array( $this, 'custom_display_archive_state' ) );
        }

        public function register_post_types(){
            register_post_type( self::POSTTYPE, apply_filters( 'pitchpro/pitch_post_types', $this->postTypeArgs ) );
            add_rewrite_rule( self::URL_PREFIX . '(.+?)(?:/([0-9]+))?/?$', 'index.php?' . self::POSTTYPE . '=$matches[1]&page=$matches[2]', 'top' );
            register_post_status( 'sent', array(
                'label'                     => 'Sent',
                'public'                    => false,
                'exclude_from_search'       => true,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Sent <span class="count">(%s)</span>', 'Sent <span class="count">(%s)</span>' ),
            ) );
            register_post_status( 'claimed', array(
                'label'                     => 'Claimed',
                'public'                    => false,
                'exclude_from_search'       => true,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Claimed <span class="count">(%s)</span>', 'Claimed <span class="count">(%s)</span>' ),
            ) );
        }

        public function custom_menue(){
            add_submenu_page('edit.php?post_type=' . PitchPro_App::POSTTYPE, 'Pitch', 'Pitch', 'manage_options', 'edit.php?post_type=' . SELF::POSTTYPE);
        }

        public function custom_post_status_list(){
             global $post;
             $complete = '';
             $label = '';
             if($post->post_type == self::POSTTYPE){
                  if($post->post_status == 'expire'){
                       $expired_complete = ' selected="selected"';
                       $expired_label = '<span id="post-status-display"> Expired</span>';
                  }
                  if($post->post_status == 'sent'){
                       $sent_complete = ' selected="selected"';
                       $sent_label = '<span id="post-status-display"> Sent</span>';
                  }
                  if($post->post_status == 'claimed'){
                       $claimed_complete = ' selected="selected"';
                       $claimed_label = '<span id="post-status-display"> Claimed</span>';
                  }
                  ?>
                  <script>
                  jQuery(document).ready(function($){
                       $("select#post_status").append('<option value="expire" <?php echo $expired_complete; ?>>Expired</option><option value="expire" <?php echo $expired_complete; ?>>Sent</option><option value="expire" <?php echo $claimed_complete; ?>>Claimed</option>');
                       $(".misc-pub-section label").append('<?php echo $expired_label . $sent_label . $claimed_label; ?>');
                  });
                  </script>
                  <?php
             }
        }

        public function custom_display_archive_state( $states ) {
             global $post;
             $arg = get_query_var( 'post_status' );
             if($arg != 'expire'){
                  if($post->post_status == 'expire'){
                       return array('Expired');
                  }
             }
             if($arg != 'sent'){
                  if($post->post_status == 'sent'){
                       return array('Sent');
                  }
             }
             if($arg != 'claimed'){
                  if($post->post_status == 'claimed'){
                       return array('Claimed');
                  }
             }
            return $states;
        }

        public function template_include( $template ){
            global $post;
            $pitches = get_page_by_path('pitches', OBJECT, PitchPro_App::POSTTYPE );
            if ( get_post_type() == self::POSTTYPE ) {
                $template = PITCHPRO_PATH . 'template/theme/pitch-shell.php';
            } else if( $pitches->ID == $post->ID ){
                $template = PITCHPRO_PATH . 'template/theme/pitch-list.php';
            }
            return $template;
        }

        public function template_redirect(){
            global $post;
            $pitches = get_page_by_path('pitches', OBJECT, PitchPro_App::POSTTYPE );
            if( $pitches->ID == $post->ID ){
                add_action( '__before_loop', array( $this, 'set_the_query' ) );
                add_action( '__after_loop', array( $this, 'set_the_query' ), 100 );
            }
        }

        public function set_the_query(){
            global $wp_query, $wp_the_query;
            switch ( current_filter() ) {
            	case '__before_loop':
            		//replace the current query by a custom query
        		    //Note : the initial query is stored in another global named $wp_the_query
        		    $wp_query = new WP_Query( array(
                        'post_type' => self::POSTTYPE,
                        'post_status' => array('publish','sent','claimed','expire','draft','pending')
        		    ));
            	break;
            	default:
            		//back to the initial WP query stored in $wp_the_query
            		$wp_query = $wp_the_query;
            	break;
            }
        }


        public static function get_the_guid( $post_id = null ){
            $wp_query = new WP_Query(array(
                'ID' => $post_id,
                'post_type' => self::POSTTYPE,
                'post_status' => array('publish','sent','claimed','expire','draft','pending')
            ));
            return $wp_query->post->post_name;
        }

        public static function mark_as_sent( $post_id = null ){
            $wp_query = new WP_Query(array(
                'ID' => $post_id,
                'post_type' => self::POSTTYPE,
                'post_status' => array('publish','sent','claimed','expire','draft','pending')
            ));
            add_post_meta( $post_id, 'sent_on', current_time('mysql') );
            return wp_update_post( array(
                'ID' => $post_id,
                'post_status' => 'sent'
            ), true );
        }

        public static function retrieve( $guid = null ){
            $wp_query = new WP_Query(array(
                'post_name' => $guid,
                'post_type' => self::POSTTYPE,
                'post_status' => array('publish','sent','claimed','expire','draft','pending')
            ));
            return $wp_query->post;
        }

        public static function get_count_associated_to_campaign( $campaign_id = null ){
            $count = 0;
            if( !is_null( $campaign_id ) ){
                $query_pitches = new WP_Query(array(
                    'post_type' => self::POSTTYPE,
                    'meta_key' => 'associated_campaign',
                    'meta_value' => $campaign_id
                ));
                $count = $query_pitches->post_count;
            }
            return $count;
        }

        public function unique_guid_post_name( $slug, $post_ID, $post_status, $post_type ) {
            if ( self::POSTTYPE == $post_type ) {
                $post = get_post($post_ID);
                if ( empty($post->post_name) || $slug != $post->post_name ) {
                    $slug = create_unique_GUIDv4();
                }
            }
            return $slug;
        }

        public function gform_after_submission( $entry, $form ){
            $post = get_post( $entry['post_id'] );
            if( self::POSTTYPE == $post->post_type && ( empty($post->post_name) || !is_valid_guid($post->post_name) ) ){
                $post->post_name = create_unique_GUIDv4();
                wp_update_post( $post );
            }
        }

        public function query_pitches(){
            $wp_query = new WP_Query(array(
                'post_type' => self::POSTTYPE,
                'post_status' => array('publish','sent','claimed','expire','draft','pending')
            ));
            return $wp_query;
        }

		/**
		 * Static Singleton Factory Method
		 *
		 * @return static $_this instance
		 * @readlink http://eamann.com/tech/the-case-for-singletons/
		 */
		public static function instance() {
			if ( !isset( self::$_this ) ) {
				$className = __CLASS__;
				self::$_this = new $className;
			}
			return self::$_this;
		}

    }
}

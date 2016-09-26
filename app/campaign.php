<?php

if( !class_exists('PitchPro_Campaign') ){
    class PitchPro_Campaign{
        const POSTTYPE = 'pitchpro_campaign';
        const LABEL = 'Organization';
        const URL_PREFIX = 'app/campaign/';

        private static $_this;
        public $path;
        static $campaign_status = array(
            'publish' => 'Active',
            'expire' => 'Expired'
        );
        protected $postTypeArgs = array(
			'public'          => true,
			'rewrite'         => array( 'slug' => self::URL_PREFIX, 'with_front' => false ),
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
                'name'               => 'PitchPro Campaign',
				'singular_name'      => 'Campaign',
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New Campaign',
				'edit_item'          => 'Edit Campaign',
				'new_item'           => 'New Campaign',
				'view_item'          => 'View Campaign',
				'search_items'       => 'Search Campaign',
				'not_found'          => 'Not found',
				'not_found_in_trash' => 'Not found in Trash'
            ),
            'exclude_from_search' => true,
            'supports'        => array('title','editor', 'page-attributes'),
			'map_meta_cap'    => true,
			'has_archive'     => true,
            'menu_position' => null,
            'show_in_menu'    => false,
		);
		function __construct() {
			$this->path = trailingslashit( dirname( __FILE__ ) );
			add_action( 'init', array( $this, 'init' ), 0 );
		}

        function init(){
            add_action( 'init', array( $this, 'register_post_types' ), 1 );
            add_action( 'widgets_init', array( $this, 'register_sidebar' ) );
            add_action( 'admin_menu', array( $this, 'custom_menue' ), 10 );
            add_filter( 'wp_unique_post_slug', array( $this, 'unique_guid_post_name' ), 10, 4 );
            add_filter( 'template_include', array( $this, 'template_include' ), 99 );
            add_action( 'template_redirect', array( $this, 'template_redirect' ), 20 );
            add_action( 'gform_after_submission', array( $this, 'gform_after_submission' ), 20, 2 );
            add_filter( 'gform_pre_render', array( $this, 'gforms_populate_posts' ) );
            add_filter( 'gform_pre_validation', array( $this, 'gforms_populate_posts' ) );
            add_filter( 'gform_pre_submission_filter', array( $this, 'gforms_populate_posts' ) );
            add_filter( 'gform_admin_pre_render', array( $this, 'gforms_populate_posts' ) );
        }

        public function register_post_types(){
            register_post_type( self::POSTTYPE, apply_filters( 'pitchpro/campaign_post_types', $this->postTypeArgs ) );
            add_rewrite_rule( self::URL_PREFIX . '(.+?)(?:/([0-9]+))?/?$', 'index.php?' . self::POSTTYPE . '=$matches[1]&page=$matches[2]', 'top' );
        }

        public function register_sidebar(){
            register_sidebar( array(
            	'name'          => 'Edit Campaign',
            	'id'            => 'pitchpro-edit-campaign',
            	'description'   => '',
                    'class'         => '',
            	'before_widget' => '<li id="%1$s" class="widget %2$s">',
            	'after_widget'  => '</li>',
            	'before_title'  => '<h2 class="widgettitle">',
            	'after_title'   => '</h2>' ));
        }

        public function custom_menue(){
            add_submenu_page('edit.php?post_type=' . PitchPro_App::POSTTYPE, 'Campaign', 'Campaign', 'manage_options', 'edit.php?post_type=' . SELF::POSTTYPE);
        }

        public function gform_after_submission( $entry, $form ){

            $post = get_post( $entry['post_id'] );
            if( $form['title'] == 'Campaign' ){
                $campaign_files = null;
                foreach( $form['fields'] as $field){
                    if( $field->inputName == 'campaign_files' ){
                        $campaign_files = $entry[ $field->id ];
                    }
                }

                if( !empty($campaign_files) ){
                    $campaign_files = json_decode($campaign_files);
                    echo count($campaign_files);
                    print_r($campaign_files);
                    foreach( $campaign_files as $key => $file ){

                        update_post_meta( $post->ID, 'campaign_files_' . $key . '_title', '', true);
                        update_post_meta( $post->ID, '_campaign_files_' . $key . '_title', 'field_57de2d13e23f2', true);
                        update_post_meta( $post->ID, 'campaign_files_' . $key . '_file', $file, true);
                        update_post_meta( $post->ID, '_campaign_files_' . $key . '_file', 'field_57de23dcdbfa0', true);
                    }
                    update_post_meta( $post->ID, 'campaign_files', count($campaign_files), true);
                    update_post_meta( $post->ID, '_campaign_files', 'field_57bdb41e20a49', true);
                }
            }
            if( self::POSTTYPE == $post->post_type && ( empty($post->post_name) || !is_valid_guid($post->post_name) ) ){
                $post->post_name = create_unique_GUIDv4();
                wp_update_post( $post );
            }
        }

        public function unique_guid_post_name( $slug, $post_id, $post_status, $post_type ) {
            if ( self::POSTTYPE == $post_type ) {
                $post = get_post($post_id);
                if ( empty($post->post_name) || $slug != $post->post_name ) {
                    $slug = create_unique_GUIDv4();
                }
            }
            return $slug;
        }

        public function get_campaign_id_by_guid( $guid = null ){
            $campaign_id = null;
            if( !is_null( $guid ) ){
                $query_campaign = new WP_Query(array(
                    'post_type' => self::POSTTYPE,
                    'name' => $guid
                ));
                $campaign_id = $query_campaign->post_count > 0 ? $query_campaign->posts[0]->ID : null;
            }
            return $campaign_id;
        }

        public static function retrieve( $guid = null, $use_id = false ){
            $args = array(
                'post_type' => self::POSTTYPE,
                'post_status' => array('publish','draft','pending','expire')
            );
            if( $use_id ){
                $args['ID'] = $guid;
            } else {
                $args['name'] = $guid;
            }
            $wp_query = new WP_Query($args);
            return $wp_query->post;
        }

        public function get_all_campaigns( $published = true ){
            $query = new WP_Query(array(
                'post_type' => self::POSTTYPE
            ));
            $campaigns = $query->posts;
            return $campaigns;
        }

        public function template_include( $template ){
            global $post;
            $pitches = get_page_by_path('campaigns', OBJECT, PitchPro_App::POSTTYPE );
            if ( get_post_type() == self::POSTTYPE ) {
                $template = PITCHPRO_PATH . 'template/theme/campaign-shell.php';
            } else if( $pitches->ID == $post->ID ){
                $template = PITCHPRO_PATH . 'template/theme/campaign-list.php';
            }
            return $template;
        }

        public function template_redirect(){
            global $post;
            $pitches = get_page_by_path('campaigns', OBJECT, PitchPro_App::POSTTYPE );
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
                        'post_status' => array('publish','expire','draft','pending')
        		    ));
            	break;
            	default:
            		//back to the initial WP query stored in $wp_the_query
            		$wp_query = $wp_the_query;
            	break;
            }
        }

        public function gforms_populate_posts( $form ){
            // if ( $form['title'] != "Pitch" ) return $form;

            foreach ( $form['fields'] as &$field ) {

                if ( $field->type != 'post_custom_field' || strpos( $field->cssClass, 'gravity-campaign-list' ) === false ) {
                    continue;
                }

                $posts = self::get_all_campaigns();
                $choices = array();

                foreach ( $posts as $post ) {
                    $choices[] = array( 'text' => $post->post_title, 'value' => $post->ID );
                }

                $field->placeholder = 'Select a campaign';
                $field->choices = $choices;

            }

            return $form;
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

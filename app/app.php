<?php

if( !class_exists('PitchPro_App') ){
    class PitchPro_App{
        private static $_this;
        public $path;
        const URL_PREFIX = 'app';
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
                'name'               => 'PitchPro App',
				'singular_name'      => 'PitchPro',
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New PitchPro',
				'edit_item'          => 'Edit PitchPro Page',
				'new_item'           => 'New PitchPro',
				'view_item'          => 'View PitchPro',
				'search_items'       => 'Search PitchPro',
				'not_found'          => 'Not found',
				'not_found_in_trash' => 'Not found in Trash'
            ),
            'exclude_from_search' => true,
            'hierarchical'    => true,
            'supports'        => array('title','editor', 'page-attributes'),
			'map_meta_cap'    => true,
			'has_archive'     => true,
            // 'capabilities' => array(
            //     'create_posts' => false
            // )
		);
		const MIN_WP_VERSION = '4.5';
        const POSTTYPE = 'pitchpro_app';
		function __construct() {
			$this->path = trailingslashit( dirname( __FILE__ ) );
			add_action( 'init', array( $this, 'init' ), 0 );
		}

        function init(){
            add_action( 'init', array( $this, 'register_post_types' ), 1 );
        }

        public function register_post_types(){
            register_post_type( self::POSTTYPE, apply_filters( 'pitchpro/app_post_types', $this->postTypeArgs ) );
            register_post_status( 'expire', array(
                'label'                     => 'Expired',
                'public'                    => false,
                'exclude_from_search'       => true,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>' ),
            ) );
        }

		/**
		 * Check the minimum WP version
		 *
		 * @static
		 * @return bool Whether the test passed
		 */
		public static function prerequisites() {;
			$pass = TRUE;
			$pass = $pass && version_compare( get_bloginfo( 'version' ), self::MIN_WP_VERSION, '>=' );
			return $pass;
		}
		/**
		 * Display fail notices
		 *
		 * @static
		 * @return void
		 */
		public static function fail_notices() {
			printf( '<div class="error"><p>%s</p></div>',
				sprintf( __( 'Pitch Pro requires WordPress v%s or higher.', 'pitchpro' ),
					self::MIN_WP_VERSION
				) );
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

<?php

if( !class_exists('PitchPro_Organization') ){
    class PitchPro_Organization{

        const LABEL = 'Organization';
        const POSTTYPE = 'pitchpro_org';
        const URL_PREFIX = 'app/organization/';

        private static $_this;
        public $path;
        protected $rewrite_rules = array();
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
                'name'               => 'PitchPro Org',
				'singular_name'      => self::LABEL,
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New Organization',
				'edit_item'          => 'Edit Organization',
				'new_item'           => 'New Organization',
				'view_item'          => 'View Organization',
				'search_items'       => 'Search Organization',
				'not_found'          => 'Not found',
				'not_found_in_trash' => 'Not found in Trash'
            ),
            'exclude_from_search' => true,
            'hierarchical'    => true,
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
            add_filter( 'gform_pre_render', array( $this, 'gforms_populate_posts' ) );
            add_filter( 'gform_pre_validation', array( $this, 'gforms_populate_posts' ) );
            add_filter( 'gform_pre_submission_filter', array( $this, 'gforms_populate_posts' ) );
            add_filter( 'gform_admin_pre_render', array( $this, 'gforms_populate_posts' ) );
        }

        public function register_post_types(){
            register_post_type( self::POSTTYPE, apply_filters( 'pitchpro/org_post_types', $this->postTypeArgs ) );
            add_rewrite_rule( self::URL_PREFIX . '(.+?)(?:/([0-9]+))?/?$', 'index.php?' . self::POSTTYPE . '=$matches[1]&page=$matches[2]', 'top' );

        }

        public function custom_menue(){
            add_submenu_page('edit.php?post_type=' . PitchPro_App::POSTTYPE, self::LABEL, self::LABEL, 'manage_options', 'edit.php?post_type=' . SELF::POSTTYPE);
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

        public function get_my_organization(){
            $org = new WP_Query(array(
                'post_type' => self::POSTTYPE,
                'posts_per_page'	=> -1,
                'meta_query'  	=> array(
                    array(
                        'key'           => 'associated_users',
                        'value'         => '"'.get_current_user_id().'"',//quotes to make sure user ID 23 does not match user ID 123, 230 etc
                        'compare'       => 'LIKE'
                    )
                )
            ));
            $org_list = $org->posts;
            return $org_list;
        }

        // TODO refactor to be more effecient
        // public function get_organization_by_campaign_id( $campaign_id ){
        //     $org = new WP_Query(array(
        //         'post_type' => PitchPro_Campaign::POSTTYPE,
        //         'posts_per_page'	=> 1,
        //         'meta_query'  	=> array(
        //             array(
        //                 'key'           => 'associated_users',
        //                 'value'         => '"'.get_current_user_id().'"',//quotes to make sure user ID 23 does not match user ID 123, 230 etc
        //                 'compare'       => 'LIKE'
        //             )
        //         )
        //     ));
        //     return $org->post;
        // }

        public function gforms_populate_posts( $form ){
            // if ( $form['title'] != "Campaign" ) return $form;

            foreach ( $form['fields'] as &$field ) {

                if ( $field->type != 'post_custom_field' || strpos( $field->cssClass, 'gravity-orgnization-list' ) === false ) {
                    continue;
                }

                $posts = self::get_my_organization();
                $choices = array();

                foreach ( $posts as $post ) {
                    $choices[] = array( 'text' => $post->post_title, 'value' => $post->ID );
                }

                $field->placeholder = 'Select an organization';

                // hide the field if it's only 1 option
                if( count($choices) == 1 ){
                    $choices[0]['isSelected'] = true;
                    $field->cssClass .= ' gf_invisible';
                }
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

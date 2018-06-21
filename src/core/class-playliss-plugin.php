<?php


if( !class_exists('Playliss_Plugin') ) {

/**
 * Plugin core module.
 *
 * BlaBlahBlah...
 */
class Playliss_Plugin
{
	private static $instance = null;
	const CPT_NAME = 'playliss';

	/**
	 * [__construct description]
	 */
	private function __construct()
	{

		/*Registers the post type with 'init' Hook*/
		add_action( 'init', array(__CLASS__, 'register_post_type') );

		add_action( 'init', array(__CLASS__, 'register_taxonomy_genre') );
		
		add_filter( 'template_include', array(__CLASS__, 'include_templates') );

		add_action('admin_menu', array(__CLASS__, 'add_settings_page') );

		/*loads laguage files - Localization*/
		// add_action('plugin_loaded', array(__CLASS__, 'localization_mrthod') );


		$Playlist_Settings = new Playliss_Settings();

		/*Flush rewrite upon activation and deactivation*/
		register_activation_hook( Playliss_PLUGIN_FILE, array(__CLASS__, 'activated'));
		register_deactivation_hook( Playliss_PLUGIN_FILE, array(__CLASS__, 'deactivated'));
	}

	/**
	 * [init description]
	 */
	public static function init()
	{
		if( self::$instance !== null ) return;
		self::$instance = new self();

		return self::$instance;
	}

	/**
	 * [activated description]
	 * @return [type] [description]
	 */
	public static function activated()
	{
		// trigger our function that registers the custom post type
	    self::register_post_type();
	 
	    // clear the permalinks after the post type has been registered
	    flush_rewrite_rules();
	}

	/**
	 * [deactivated description]
	 * @return [type] [description]
	 */
	public static function deactivated()
	{
	    // unregister the post type, so the rules are no longer in memory
	    unregister_post_type( 'playliss' );
	    // clear the permalinks to remove our post type's rules from the database
	    flush_rewrite_rules();
	}
	
	/**
	 * [register_post_type description]
	 * @return [type] [description]
	 */
	public static function register_post_type()
	{
		register_post_type('playliss',
            array(
                'labels'      => array(
                   'name'          => __('Playlists'),
                   'singular_name' => __('Playlist'),
                ),
                'description' => 'My Media Playlists',
                'supports' => array(
	                'title',
	                'editor',
	                'author',
	                'thumbnail',
	                'comments',
	                'revisions',
	                'custom-fields'
	            ),
                'public' => true,
                'rewrite' => array( 'slug' => 'playlist' ),
                'menu_position' => 10,
                'menu_icon' => 'dashicons-playlist-audio',
                'show_in_rest' => true // makes Gutenberg available for the post type
           )
	    );
	}

	/**
	 * [register_taxonomy_genre description]
	 * @return [type] [description]
	 */
	public static function register_taxonomy_genre()
	{
	    $labels = array(
	        'name'              => __('Genres'),
			'singular_name'     => __('Genre'),
			'search_items'      => __('Search genres'),
			'popular_items'     => __('Popular genres'),
			'all_items'         => __('All genres'),
			'parent_item'       => null,
			'parent_item_colon' => null,
			'edit_item'         => __('Edit genre'),
			'view_item'         => __('View genre'),
			'update_item'       => __('Update genre'),
			'add_new_item'      => __('Add New genre'),
			'new_item_name'     => __('New genre Name'),
			'separate_items_with_commas'    => __('Separate genres with commas'),
			'add_or_remove_items'     		=> __('Add or remove genres'),
			'choose_from_most_used'     	=> __('Choose from the most used genres'),
			'not_found'     	=> __('No genres found'),
			'no_terms'     		=> __('No genres'),
			'menu_name'         => __('Genres')
		);

		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'description'		=> __('Genre of tracks inside the playlist'),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array('slug' => 'genres'),
			'show_in_rest'		=> true
		);

		register_taxonomy( 'genre',
			self::CPT_NAME,
			$args
		);
	}

	/**
	 * [include_templates description]
	 * @param  [type] $template [description]
	 * @return [type]           [description]
	 */
	public static function include_templates($template)
	{
		$post_id = get_the_ID();

		if( is_single() && get_post_type( $post_id ) == self::CPT_NAME ) {
			// locate and check if 'single-playliss.php' template exist in active theme foldrer to load it
			// if not, load template in plugin folder
			if( '' == locate_template('single-' . self::CPT_NAME . '.php') ) {
				return PLAYLISS_DIR_SRC . 'templates/single.php';
				
			}

		}

		return $template;
	}

	/**
	 * [add_settings_page description]
	 */
	public static function add_settings_page()
	{
		add_submenu_page(
		    'edit.php?post_type=playliss',
		    'Playliss Settings',
		    'Settings',
		    'manage_options',
		    'playliss-settings',
	    	array( __CLASS__, 'settings_page_html' )
		);
	}

	/**
	 * [settings_page_html description]
	 * @return [type] [description]
	 */
	public static function settings_page_html()
	{
		if( !current_user_can( 'manage_options' ) ) {
	        return;
		}
	    
	    ?>
	    <div class="wrap">
	        <h1><?= esc_html( get_admin_page_title() ); ?></h1>
	        <form action="options.php" method="post">
	            <?php
	            settings_fields( 'playliss' );
	            do_settings_sections( 'playliss-settings' );
	            submit_button( 'Save Settings' );
	            ?>
	        </form>
	    </div>
	    <?php
	}

}

}

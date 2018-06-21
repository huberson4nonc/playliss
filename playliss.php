<?php 
/*
Plugin Name: Playliss
Plugin URI:   https://pwoghub.pw/plugins/playliss/
Description:  Playliss provides audio playlist custom post type capability,
	that allow creation of media playlist.
Version:      0.0.1
Author:       Huberson D.
Author URI:   https:pwoghub.pw/huberson
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  playliss
Domain Path:  /languages
*/


defined( 'ABSPATH' ) || exit;

if( !defined('PLAYLISS_VERSION') ) { define('PLAYLISS_VERSION', '0.0.1' ); }
if( !defined('Playliss_PLUGIN_FILE') ) { define('Playliss_PLUGIN_FILE',  __FILE__  ); }
if( !defined('PLAYLISS_DIR_PATH') ) { define('PLAYLISS_DIR_PATH', dirname( __FILE__ ).'/' ); }
if( !defined('PLAYLISS_DIR_LIB') ) { define('PLAYLISS_DIR_LIB', dirname( __FILE__ ).'/lib/' ); }
if( !defined('PLAYLISS_DIR_SRC') ) { define('PLAYLISS_DIR_SRC', dirname( __FILE__ ).'/src/' ); }
if( !defined('PLAYLISS_ASSETS_URL') ) { define('PLAYLISS_ASSETS_URL', plugin_dir_url( __FILE__ ).'src/assets/' ); }
if( !defined('PLAYLISS_DIR_URL') ) { define('PLAYLISS_DIR_URL', plugin_dir_url( __FILE__ ) ); }

require PLAYLISS_DIR_PATH.'bootstrap.php';


/*...*/
Playliss_Plugin::init();

/**
 * Checks if Gutenberg active before init block editor.
 * @return boolean [description]
 */
function playliss_is_gutenberg_active()
{
	if ( is_plugin_active( 'gutenberg/gutenberg.php' ) ) {
		Playliss_Editor::init();
   	
	}
}
add_action( 'admin_init', 'playliss_is_gutenberg_active' );

/*...*/
Playliss_Metaboxes::init();

<?php


if( !class_exists('Playliss_Editor') ) {

/**
 * Playlist editor block module.
 *
 * BlaBlahBlah...
 */
class Playliss_Editor
{
	private static $editor = null;

	/**
	 * [__construct description]
	 */
	private function __construct()
	{

		add_action( 'init', array(__CLASS__, 'playliss_editor_block_enqueue' ) );
	}

	/**
	 * Module init.
	 * 
	 * @return [type] [description]
	 */
	public static function init()
	{

		if( self::$editor !==null ) return;
		self::$editor = new self();

		return self::$editor;

	}


	/**
	 * Enqueue editor block scripts.
	 * 
	 * @return [type] [description]
	 */
	public static function playliss_editor_block_enqueue()
	{

		/*Playlist editor block scripts.*/
		wp_register_script(
			'playliss-editor-block-script',
			PLAYLISS_DIR_URL.'src/editor/playliss-block/block.js',
			array( 'wp-blocks', 'wp-element', 'wp-i18n' ) // Dependencies
		);

		/*Editor styles.*/
		wp_register_style(
			'playliss-editor-block-style',
			PLAYLISS_DIR_URL.'src/editor/playliss-block/editor.css',
			array( 'wp-edit-blocks' ),
			filemtime( PLAYLISS_DIR_SRC.'editor/playliss-block/editor.css')
		);
		/*Global block styles - apply both in edit mode and front-end */
		wp_register_style(
			'playliss-block-style',
			PLAYLISS_DIR_URL.'src/editor/playliss-block/style.css',
			array( 'wp-edit-blocks' ),
			filemtime( PLAYLISS_DIR_SRC.'editor/playliss-block/style.css')
		);
		// wp_enqueue_style(
			// 'playliss-block-fontawesome',
			// PLAYLISS_ASSETS_URL.'css/font-awesome.css' // Icon fonts
		// );

		// registers the block with WP, using namespacing
		// specifies the editor script to be used in the Gutenberg interface
		register_block_type( 'playliss/block', array(
			'editor_script' => 'playliss-editor-block-script',
			'editor_style' => 'playliss-editor-block-style',
			'style' => 'playliss-block-style',
		) );

	}

}

}

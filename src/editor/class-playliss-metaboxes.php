<?php


if(!class_exists('Playliss_Metaboxes')) {

/**
 * Handles playlist metaboxes.
 *
 * BlaBlahBlah...
 */
class Playliss_Metaboxes
{
	private static $instance = null;

	/**
	 * [__construct description]
	 */
	private function __construct()
	{

		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'save_metabox' ) );
	}

	/**
	 * [init description]
	 */
	public static function init()
	{
		if( self::$instance !==null ) return;
		self::$instance = new self();

		return self::$instance;
	}

	/**
	 * [add_metabox description]
	 * @return [type] [description]
	 */
	public function add_metabox()
	{
	    add_meta_box(
	        'playliss_info',
	        __( 'Playlist Info', 'playliss' ),
	        array( $this, 'render_metabox_html' ),
	        'playliss',
	        'advanced'
	    );

	}

	/**
	 * [register_post_type description]
	 * @return [type] [description]
	 */
	public function render_metabox_html( $post )
	{
		// Add nonce for security and authentication.
		wp_nonce_field( 'playliss_nonce_action', 'playliss_nonce' );

		// Retrieve any existing value from the database.
		$playliss_artist = get_post_meta( $post->ID, '_playliss_artist', true );
		$playliss_album = get_post_meta( $post->ID, '_playliss_album', true );
		$playliss_release_date = get_post_meta( $post->ID, '_playliss_release_date', true );

		// Set default values.
		if( empty( $playliss_artist ) ) $playliss_artist = '';
		if( empty( $playliss_album ) ) $playliss_album = '';
		if( empty( $playliss_release_date ) ) $playliss_release_date = '';
	?>
			<div class="playliss-fields">
				<!-- <fieldset class="playliss-field" >
					<legend>Genre</legend>
					<ul>
						<li> <label for="blues"> <input id="blues" type="checkbox" name="colors[]" value="blues"> Blues </label> </li>
						<li> <label for="classic"> <input id="classic" type="checkbox" name="colors[]" value="classic"> Classic </label> </li>
						<li> <label for="jazz"> <input id="jazz" type="checkbox" name="colors[]" value="jazz"> Jazz </label> </li>
						<li> <label for="raggae"> <input id="raggae" type="checkbox" name="colors[]" value="raggae"> Raggae </label> </li>
					</ul>
				</fieldset> -->

				<label class="playliss-field label" for="playliss_artist">Artist(s)
					<input type="text" name="playliss_artist" id="playliss_artist" placeholder="Louis Armstrong, Frank Sinatra, Ray Charles, ..." value="<?php echo esc_attr__(  $playliss_artist, 'playliss' ) ?>" >
				</label>
				<label class="playliss-field label" for="playliss_album">Album(s)
					<input type="text" name="playliss_album" id="playliss_album" placeholder="Rebel, confrontation, Revolution, ..." value="<?php echo esc_attr__(  $playliss_album, 'playliss' ) ?>" >
				</label>
				<label class="playliss-field label" for="playliss_release_date"> Release date
					<input id="playliss_release_date" name="playliss_release_date" type="date" value="<?php echo esc_attr(  $playliss_release_date, 'playliss' ) ?>" >
				</label>
			</div>			
	<?php
	}

	/**
	 * [save_metabox description]
	 * @param  [type] $post_id [description]
	 * @return [type]          [description]
	 */
	public function save_metabox( $post_id )
	{
		$nonce_name   = !empty( $_POST['playliss_nonce'] ) ? $_POST['playliss_nonce'] : '';
		$nonce_action = 'playliss_nonce_action';

		// Validate nonce
		if ( !empty( $nonce_name ) && !wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// Check user's capabilities
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if autosave and revision.
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) )
			return;


		$playliss_artist_new = isset( $_POST[ 'playliss_artist' ] ) ? sanitize_text_field( $_POST[ 'playliss_artist' ] ) : '';
		$playliss_album_new = isset( $_POST[ 'playliss_album' ] ) ? sanitize_text_field( $_POST[ 'playliss_album' ] ) : '';
		$playliss_release_date_new = isset( $_POST[ 'playliss_release_date' ] ) ? sanitize_text_field( $_POST[ 'playliss_release_date' ] ) : '';


		update_post_meta( $post_id, '_playliss_artist', $playliss_artist_new );
		update_post_meta( $post_id, '_playliss_album', $playliss_album_new );
		update_post_meta( $post_id, '_playliss_release_date', $playliss_release_date_new );
		
	}
}

}

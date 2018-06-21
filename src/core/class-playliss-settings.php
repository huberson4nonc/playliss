<?php


if(!class_exists('Playliss_Settings')) {

/**
 * Playlist settings module.
 *
 * BlaBlahBlah...
 */
class Playliss_Settings
{

	/**
	 * [__construct description]
	 */
	public function __construct()
	{

		add_action( 'admin_init', array( $this, 'settings_init' ) );		
	}
	 
	/**
	 * [settings_init description]
	 * @return [type] [description]
	 */
	public function settings_init()
	{
		// register playliss options group
		register_setting( 'playliss', 'playliss_settings' );

		add_settings_section(
			'playliss_display_preferences_section',
			__( 'Display preferences', 'playliss' ),
			array( $this, 'display_preferences_section_html' ),
			'playliss-settings'
		);

		add_settings_field(
			'playliss_player_skin_field', // as of WP 4.6 this value is used only internally. use $args' label_for to populate the id inside the callback
			__( 'Select player skin', 'playliss' ),
			array( $this, 'player_skin_field_html' ),
			'playliss-settings',
			'playliss_display_preferences_section',
			[
				'label_for' => 'player_skin_field',
				'class' => 'playliss_player_skin',
				'custom_data' => 'skin-id_custom',
			]
		);
	}
	 

	/**
	 * Displays preferences section.
	 * 
	 * section callbacks can accept an $args parameter, which is an array. 
	 * $args have the following keys defined: title, id, callback. 
	 * the values are defined at the add_settings_section() function.
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function display_preferences_section_html( $args ) {
		?>
	 	<p id="<?php echo esc_attr( $args['id'] ); ?>">
	 		<?php esc_html_e( 'Playlist and player display preferences', 'playliss' ); ?>
 		</p>
		<?php
	}
	 
	/**
	 * Displays Player skin options.
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function player_skin_field_html( $args ) {
		// get the value of the setting we've registered with register_setting()
		$options = get_option( 'playliss_settings' );
		// output the fields
	?>

	<?php
	}
	
}

}

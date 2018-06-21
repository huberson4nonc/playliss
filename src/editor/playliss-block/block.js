/*-------------------------------------------------------------------------
| Playlist editor block.
|--------------------------------------------------------------------------
|BLAHBLAH...
|
-------------------------------------------------------------------------*/
( function(blocks, i18n, element) {

	/**@dev - Register the block type 
	* string 	Block name
	* object 	Block configuration object
	*/
	blocks.registerBlockType( 'playliss/playlist', {
		title: 'Playliss',
		description: i18n.__('Playlist Editor block', 'playliss'),
		category: 'common',
		icon: 'playlist-audio',
		// keywords: [ __( 'read' ) ],
		// useOnce: true, // Use the block just once per post
		attributes: {
			title: {
				type: 'array',
				source: 'children',
				selector: 'h2',
			},
			mediaID: {
				type: 'number',
			},
			mediaURL: {
				type: 'string',
				source: 'attribute',
				selector: 'audio',
				attribute: 'src',
			},
			ingredients: {
				type: 'array',
				source: 'children',
				selector: '.ingredients',
			},
			instructions: {
				type: 'array',
				source: 'children',
				selector: '.steps',
			},
		},
		edit: function(blockProperties)/**@dev - describes the structure of the block in editor context. what the editor renders when the block is used*/
		{
			var focusedEditable = blockProperties.focus ? blockProperties.focus.editable || 'title' : null;
			var attributes = blockProperties.attributes;

			var onSelectImage = function( media ) {
				return blockProperties.setAttributes( {
					mediaURL: media.url,
					mediaID: media.id,
				} );
			};

			return (
				// Title
				element.createElement( 'div', 
					{ className: blockProperties.className },
					element.createElement( blocks.RichText, { 
						tagName: 'h2',
						inline: true,
						placeholder: i18n.__( 'Add your playlist title…' ),
						value: attributes.title,
						onChange: function( value ) {
							blockProperties.setAttributes( { title: value } );
						},
						focus: focusedEditable === 'title' ? focus : null,
						onFocus: function( focus ) {
							blockProperties.setFocus( _.extend( {}, focus, { editable: 'title' } ) );
						},
					} ),
					// Track - media
					element.createElement( 'div', { className: 'recipe-image' },
						element.createElement( wp.editor.MediaUpload, {
							onSelect: onSelectImage,
							type: 'audio',
							value: attributes.mediaID,
							render: function( obj ) {
								return element.createElement( wp.components.Button, {
										className: attributes.mediaID ? 'single-track' : 'button button-large',
										onClick: obj.open
									},
									! attributes.mediaID ? i18n.__( 'Add new track' ) : element.createElement( 'audio', { src: attributes.mediaURL, controls: 'controls' } )
								);
							}
						} )
					),
					// non-needed - just helpers
					element.createElement( 'h3', {}, i18n.__( 'Ingredients' ) ),
					element.createElement( wp.editor.RichText, {
						tagName: 'ul',
						multiline: 'li',
						placeholder: i18n.__( 'Write a list of ingredients…' ),
						value: attributes.ingredients,
						onChange: function( value ) {
							blockProperties.setAttributes( { ingredients: value } );
						},
						focus: focusedEditable === 'ingredients' ? focus : null,
						onFocus: function( focus ) {
							blockProperties.setFocus( _.extend( {}, focus, { editable: 'ingredients' } ) );
						},
						className: 'ingredients',
					} ),
					element.createElement( 'h3', {}, i18n.__( 'Instructions' ) ),
					element.createElement( wp.editor.RichText, {
						tagName: 'div',
						inline: false,
						placeholder: i18n.__( 'Write instructions…' ),
						value: attributes.instructions,
						onChange: function( value ) {
							blockProperties.setAttributes( { instructions: value } );
						},
						focus: focusedEditable === 'instructions' ? focus : null,
						onFocus: function( focus ) {
							blockProperties.setFocus( _.extend( {}, focus, { editable: 'instructions' } ) );
						},
					} ),
				)
			);
		},
		save: function(blockProperties)/**@dev - */
		{
			var attributes = blockProperties.attributes;

			return (
				element.createElement( 'div', { className: blockProperties.className },
					element.createElement( 'h2', {}, attributes.title ),
					attributes.mediaURL &&
						element.createElement( 'div', { className: 'recipe-image' },
							element.createElement( 'audio', { src: attributes.mediaURL, controls: 'controls' } ),
						),
					element.createElement( 'h3', {}, i18n.__( 'Ingredients' ) ),
					element.createElement( 'ul', { className: 'ingredients' }, attributes.ingredients ),
					element.createElement( 'h3', {}, i18n.__( 'Instructions' ) ),
					element.createElement( 'div', { className: 'steps' }, attributes.instructions ),
				)
			);
		}
	} );
} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element,
	window._,
);
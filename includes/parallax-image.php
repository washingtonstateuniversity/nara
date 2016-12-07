<?php

class NARA_Parallax_Image {

	/**
	 * Setup hooks.
	 */
	public function __construct() {
		add_shortcode( 'parallax_image', array( $this, 'parallax_image_display' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'parallax_image_enqueue_scripts' ) );
		add_action( 'register_shortcode_ui', array( $this, 'parallax_image_shortcode_ui' ) );
	}

	/**
	 * Display an image with data attributes to determine parallax behavior.
	 *
	 * @param $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function parallax_image_display( $atts ) {
		$defaults = array(
			'attachment' => '',
			'size' => '',
			'position' => 'absolute',
			'animate_from' => '',
			'speed' => '',
			'offset' => '0',
			'z_index' => '0',
			'classes' => false,
		);

		$atts = shortcode_atts( $defaults, $atts );

		// Bail if any attributes are empty.
		if ( in_array( '', $atts, true ) ) {
			return '';
		}

		$wrapper_classes = 'parallax-image-wrapper ' . esc_html( $atts['position'] );
		$wrapper_style = 'z-index: ' . esc_html( $atts['z_index'] ) . ';';
		$image_data = wp_get_attachment_image_src( esc_html( $atts['attachment'] ), esc_html( $atts['size'] ) );

		if ( $atts['classes'] ) {
			$wrapper_classes .= ' ' . esc_html( $atts['classes'] );
		}

		if ( 'relative' === $atts['position'] ) {
			$wrapper_style .= ' height: ' . $image_data[2] . 'px;';
		}

		ob_start();

		?>
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>"
			 aria-hidden="true"
			 style="<?php echo esc_attr( $wrapper_style ); ?>">
			<image src="<?php echo esc_url( $image_data[0] ); ?>"
				 width="<?php echo esc_attr( $image_data[1] ); ?>"
				 height="<?php echo esc_attr( $image_data[2] ); ?>"
				 data-speed="<?php echo esc_attr( $atts['speed'] ); ?>"
				 data-animate-from="<?php echo esc_attr( $atts['animate_from'] ); ?>"
				 data-offset="<?php echo esc_attr( $atts['offset'] ); ?>"
				 class="parallax-image">
		</div>
		<?php

		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * Enqueue scripts used on the front end.
	 */
	public function parallax_image_enqueue_scripts() {
		$post = get_post();

		if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'parallax_image' ) ) {
			wp_enqueue_script( 'nara-home-parallax', get_stylesheet_directory_uri() . '/js/home-parallax.min.js', array( 'jquery' ), nara_theme_version(), true );
		}
	}

	/**
	 * Shortcode UI setup for the `parallax_image` shortcode.
	 */
	public function parallax_image_shortcode_ui() {
		$fields = array(
			array(
				'label' => 'Attachment',
				'attr' => 'attachment',
				'type' => 'attachment',
				'libraryType' => array( 'image' ),
				'addButton' => 'Select Image',
				'frameTitle' => 'Select Image',
			),
			array(
				'label' => 'Image size',
				'attr' => 'size',
				'type' => 'select',
				'value' => 'large',
				'options' => array(
					array(
						'value' => 'thumbnail',
						'label' => 'Thumbnail',
					),
					array(
						'value' => 'medium',
						'label' => 'Medium',
					),
					array(
						'value' => 'large',
						'label' => 'Large',
					),
					array(
						'value' => 'full',
						'label' => 'Full',
					),
				),
			),
			array(
				'label' => 'Position',
				'description' => 'Whether the image should be included in the content flow.',
				'attr' => 'position',
				'type' => 'select',
				'value' => 'absolute',
				'options' => array(
					array(
						'value' => 'absolute',
						'label' => 'Absolute',
					),
					array(
						'value' => 'relative',
						'label' => 'Relative',
					),
				),
			),
			array(
				'label' => 'Animate From',
				'description' => 'The direction the image will animate from.',
				'attr' => 'animate_from',
				'type' => 'select',
				'value' => 'left',
				'options' => array(
					array(
						'value' => 'left',
						'label' => 'Left',
					),
					array(
						'value' => 'right',
						'label' => 'Right',
					),
				),
			),
			array(
				'label' => 'Speed',
				'description' => 'The speed at which to animate the image (typically a decimal value).',
				'attr' => 'speed',
				'type' => 'text',
				'encode' => true,
			),
			array(
				'label' => 'Offset',
				'description' => 'Pixel value to offset the image by (use a negative number to offset to the left).',
				'attr' => 'offset',
				'type' => 'text',
				'encode' => true,

			),
			array(
				'label' => 'Z-Index',
				'description' => 'Layering order if multiple images are being used in the same area.',
				'attr' => 'z_index',
				'type' => 'text',
				'encode' => true,
			),
			array(
				'label' => 'Class',
				'attr' => 'classes',
				'type' => 'text',
				'encode' => true,
				'placeholder' => 'Space delimited class names to add to the image wrapper if additional styling is needed.',
			),
		);

		$shortcode_ui_args = array(
			'label' => 'Parallax Image',
			'listItemImage' => 'dashicons-format-image',
			'post_type' => array( 'post', 'page' ),
			'attrs' => $fields,
		);

		shortcode_ui_register_for_shortcode( 'parallax_image', $shortcode_ui_args );
	}
}
new NARA_Parallax_Image();

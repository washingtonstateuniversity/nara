<?php

class WSU_Home_YouTube_Embed {
	public function __construct() {
		add_shortcode( 'nara_youtube', array( $this, 'display_nara_youtube' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Display the custom iframe used for YouTube embeds on feature pages.
	 *
	 * [nara_youtube src="https://www.youtube.com/embed/OmN5coh0heM?modestbranding=1;showinfo=0;controls=0;rel=0" width="560" height="315"]
	 * @param $atts
	 *
	 * @return string
	 */
	public function display_nara_youtube( $atts ) {
		$defaults = array(
			'video_id' => '',
			'thumbnail' => false,
		);
		$atts = shortcode_atts( $defaults, $atts );

		//$content = '<iframe ' . $width . ' ' . $height . ' src="' . $url . '" frameborder="0" allowfullscreen></iframe>';
		/**
		 * [wsu_feature_youtube video_id="OmN5coh0heM" width="560" height="315"]
		 */
		ob_start();
		?>
		<div class="nara-youtube-wrapper"<?php if ( $atts['thumbnail'] ) { ?> style="background-image: url(<?php echo esc_url( $atts['thumbnail'] ); ?>);"<?php } ?>>
			<div class="inline-youtube-video"
				 id="youtube-video-<?php echo esc_attr( $atts['video_id'] ); ?>"
				 data-video-id="<?php echo esc_attr( $atts['video_id'] ); ?>"
				 data-video-width="1280"
				 data-video-height="720"></div>
			<div class="nara-youtube-close stop-<?php echo esc_attr( $atts['video_id'] ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000">
					<path d="M896.6 26L500 422.6 103.4 26C82 4.7 47.4 4.7 26 26c-21.4 21.4-21.4 56 0 77.4L422.6 500 26 896.6c-21.4 21.4-21.4 56 0 77.4 21.4 21.4 56 21.4 77.4 0L500 577.4 896.6 974c21.4 21.4 56 21.4 77.4 0 21.4-21.4 21.4-56 0-77.4L577.4 500 974 103.4c21.4-21.4 21.4-56 0-77.4C952.6 4.7 918 4.7 896.6 26L896.6 26z"/></path>
				</svg>
			</div>
		</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * Enqueue scripts used on the front end.
	 */
	public function enqueue_scripts() {
		$post = get_post();

		if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'nara_youtube' ) ) {
			wp_enqueue_script( 'nara-youtube', get_stylesheet_directory_uri() . '/js/youtube-embed.min.js', array( 'jquery' ), nara_theme_version(), true );
		}
	}
}
new WSU_Home_YouTube_Embed();

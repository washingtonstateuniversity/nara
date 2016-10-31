<?php

add_filter( 'wsuwp_uc_people_to_add_to_content', 'nara_modify_uc_object_people_content', 10, 1 );
/**
 * Stops the display of associated people at the bottom of Team (Project) pages
 * by default.
 *
 * @param $people
 *
 * @return bool
 */
function nara_modify_uc_object_people_content( $people ) {
	if ( is_singular( wsuwp_uc_get_object_type_slug( 'project' ) ) ) {
		return false;
	}

	return $people;
}

add_shortcode( 'nara_timeline', 'nara_display_timeline_shortcode' );
/**
 * Embed the NARA timeline for nararenewables.org/timeline/
 *
 * @return string
 */
function nara_display_timeline_shortcode() {
	ob_start();

	?>
	<iframe src="https://cdn.knightlab.com/libs/timeline3/latest/embed/index.html?source=1gZ7X2jsD-_hSbZjrfFAwr9Pm1OtCW084LaUhkGz4X04&amp;font=Default&amp;lang=en&amp;initial_zoom=2&amp;height=650" mce_src="http://cdn.knightlab.com/libs/timeline3/latest/embed/index.html?source=1gZ7X2jsD-_hSbZjrfFAwr9Pm1OtCW084LaUhkGz4X04&amp;font=Default&amp;lang=en&amp;initial_zoom=2&amp;height=650" width="100%" height="650" frameborder="0"></iframe>
	<?php
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_action( 'wp_enqueue_scripts', 'nara_enqueue_scripts');
/**
 * Enqueue custom scripting in child theme.
 */
function nara_enqueue_scripts() {
	wp_enqueue_script( 'nara-custom', get_stylesheet_directory_uri() . '/js/fontfamily.js', array( 'jquery' ), spine_get_script_version(), true );

}

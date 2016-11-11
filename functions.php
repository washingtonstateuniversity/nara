<?php

// Include the YouTube Embed plugin.
include_once( __DIR__ . '/includes/youtube-embed.php' );

add_filter( 'spine_child_theme_version', 'nara_theme_version' );
/**
 * Provides a child theme version to use when breaking the cache on
 * enqueued styles and scripts.
 *
 * @return string
 */
function nara_theme_version() {
	return '0.0.17';
}

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
add_action( 'wp_enqueue_scripts', 'nara_enqueue_scripts' );
/**
 * Enqueue custom scripting in child theme.
 */
function nara_enqueue_scripts() {
	wp_enqueue_script( 'nara-custom', get_stylesheet_directory_uri() . '/js/fontfamily.js', array( 'jquery' ), spine_get_script_version(), true );

}

add_action( 'init', 'nara_remove_spine_wp_enqueue_scripts' );
/**
 * Removes the default Spine scripting so that we can use our custom version.
 */
function nara_remove_spine_wp_enqueue_scripts() {
	remove_action( 'wp_enqueue_scripts', 'spine_wp_enqueue_scripts', 20 );
}

add_action( 'wp_enqueue_scripts', 'nara_spine_wp_enqueue_scripts', 20 );
/**
 * Enqueue scripts and styles required for front end pageviews.
 */
function nara_spine_wp_enqueue_scripts() {

	$spine_version = spine_get_option( 'spine_version' );
	// This may be an unnecessary check, but we don't want to screw this up.
	if ( 'develop' !== $spine_version && 0 === absint( $spine_version ) ) {
		$spine_version = 1;
	}

	// Much relies on the main stylesheet provided by the WSU Spine.
	wp_enqueue_style( 'wsu-spine', 'https://repo.wsu.edu/spine/' . $spine_version . '/spine.min.css', array(), spine_get_script_version() );
	wp_enqueue_style( 'spine-theme', get_template_directory_uri() . '/style.css', array( 'wsu-spine' ), spine_get_script_version() );
	wp_enqueue_style( 'spine-theme-child', get_stylesheet_directory_uri() . '/style.css', array( 'wsu-spine' ), spine_get_child_version() );
	wp_enqueue_style( 'spine-theme-print', get_template_directory_uri() . '/css/print.css', array(), spine_get_script_version(), 'print' );

	// All theme styles have been output at this time. Plugins and other themes should print styles here, before blocking
	// Javascript resources are output.
	do_action( 'spine_enqueue_styles' );

	$google_font_css_url = '//fonts.googleapis.com/css?family=';
	$count = 0;
	$spine_open_sans = spine_get_open_sans_options();

	// Build the URL used to pull additional Open Sans font weights and styles from Google.
	if ( ! empty( $spine_open_sans ) ) {
		$build_open_sans_css = '';
		foreach ( $spine_open_sans as $font_option ) {
			if ( 0 === $count ) {
				$build_open_sans_css = 'Open+Sans%3A' . $font_option;
			} else {
				$build_open_sans_css .= '%2C' . $font_option;
			}

			$count++;
		}

		if ( 0 !== $count ) {
			$google_font_css_url .= $build_open_sans_css;
		} else {
			$google_font_css_url = '';
		}
	} else {
		$google_font_css_url = '';
	}

	$spine_open_sans_condensed = spine_get_open_sans_condensed_options();

	$condensed_count = 0;
	if ( ! empty( $spine_open_sans_condensed ) ) {
		if ( 0 !== $count ) {
			$build_open_sans_cond_css = '|Open+Sans+Condensed%3A';
		} else {
			$build_open_sans_cond_css = 'Open+Sans+Condensed%3A';
		}

		foreach ( $spine_open_sans_condensed as $font_option ) {
			if ( 0 === $condensed_count ) {
				$build_open_sans_cond_css .= $font_option;
			} else {
				$build_open_sans_cond_css .= '%2C' . $font_option;
			}

			$count++;
			$condensed_count++;
		}

		$google_font_css_url .= $build_open_sans_cond_css;
	}

	// Only enqueue a custom Google Fonts URL if extra options have been selected for Open Sans.
	if ( '' !== $google_font_css_url ) {
		$google_font_css_url .= '&subset=latin,latin-ext';

		// Deregister the default Open Sans URL provided by WordPress core and instead provide our own.
		wp_deregister_style( 'open-sans' );
		wp_enqueue_style( 'open-sans', $google_font_css_url, array(), false );
	}

	// WordPress core provides much of jQuery UI, but not in a nice enough package to enqueue all at once.
	// For this reason, we'll pull the entire package from the Google CDN.
	wp_enqueue_script( 'wsu-jquery-ui-full', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js', array( 'jquery' ) );

	// Much relies on the main Javascript provided by the WSU Spine.
	wp_enqueue_script( 'wsu-spine', get_stylesheet_directory_uri() . '/js/spine.min.js', array( 'wsu-jquery-ui-full' ), spine_get_child_version(), false );

	// Override default options in the WSU Spine.
	$twitter_text = ( is_front_page() ) ? get_option( 'blogname' ) : trim( wp_title( '', false ) );
	$spineoptions = array(
		'social' => array(
			'share_text' => esc_js( spine_get_title() ),
			'twitter_text' => esc_js( $twitter_text ),
			'twitter_handle' => 'wsupullman',
		),
	);
	// If a Twitter account has been added in the Customizer, use that for the via handle.
	$spine_social_options = spine_social_options();
	if ( isset( $spine_social_options['twitter'] ) ) {
		$twitter_array = array_filter( explode( '/', $spine_social_options['twitter'] ) );
		$twitter_handle = array_pop( $twitter_array );
		$spineoptions['social']['twitter_handle'] = esc_js( $twitter_handle );
	}
	wp_localize_script( 'wsu-spine', 'spineoptions', $spineoptions );

	// Enqueue jQuery Cycle2 and Genericons when a page builder template is used.
	if ( is_page_template( 'template-builder.php' ) ) {
		$has_builder_banner = get_post_meta( get_the_ID(), '_has_builder_banner', true );

		if ( $has_builder_banner ) {
			// Enqueue the compilation of jQuery Cycle2 scripts required for the slider
			wp_enqueue_script( 'wsu-cycle', get_template_directory_uri() . '/js/cycle2/jquery.cycle2.min.js', array( 'jquery' ), spine_get_script_version(), true );
			wp_enqueue_style( 'genericons', get_template_directory_uri() . '/styles/genericons/genericons.css', array(), spine_get_script_version() );
		}
	}

	// Enqueue scripting for the entire parent theme.
	wp_enqueue_script( 'wsu-spine-theme-js', get_template_directory_uri() . '/js/spine-theme.js', array( 'jquery' ), spine_get_script_version(), true );

	// Dequeue TablePress stylesheet.
	wp_dequeue_style( 'tablepress-default' );

	// Enqueue script for animating lists on posts with content containing `animate`.
	$post = get_post();
	if ( isset( $post->post_content ) && strpos( $post->post_content, 'animate' ) !== false ) {
		wp_enqueue_script( 'nara-animated-list', get_stylesheet_directory_uri() . '/js/animated-list.min.js', array( 'jquery' ), spine_get_child_version() );
	}
}

add_filter( 'nav_menu_link_attributes', 'nara_nav_menu_link_attributes', 20, 3 );
/**
 * Alters the anchor HREF on section label pages to output as # when building
 * a site navigation.
 *
 * @param array   $atts
 * @param object  $item
 * @param array   $args
 *
 * @return mixed
 */
function nara_nav_menu_link_attributes( $atts, $item, $args ) {
	if ( 'site' !== $args->menu ) {
		return $atts;
	}

	if ( 'page' === $item->object && 'post_type' === $item->type ) {
		$slug = get_page_template_slug( $item->object_id );

		if ( 'templates/section-label.php' === $slug ) {
			$atts['href'] = '#';
		}
	}

	return $atts;
}
add_filter( 'nav_menu_css_class', 'nara_nav_menu_css_class', 20, 3 );
/**
 * Assign a class of `.non-anchor` to any menu items that are custom links
 * with an href of #.
 *
 * @param $classes
 * @param $item
 * @param $args
 *
 * @return array
 */
function nara_nav_menu_css_class( $classes, $item, $args ) {
	if ( 'site' !== $args->menu ) {
		return $classes;
	}

	if ( 'page' === $item->object && 'post_type' === $item->type ) {
		$slug = get_page_template_slug( $item->object_id );

		if ( 'templates/section-label.php' === $slug ) {
			$classes[] = 'non-anchor';
		}
	}

	if ( 'custom' === $item->object && 'custom' === $item->type && '#' === $item->url ) {
		$classes[] = 'non-anchor';
	}

	return $classes;
}

add_filter( 'nav_menu_item_id', 'nara_nav_menu_id', 20 );
/**
 * Strips menu item IDs as navigation is built.
 *
 * @param string $id
 *
 * @return bool
 */
function nara_nav_menu_id( $id ) {
	return false;
}

add_filter( 'wp_nav_menu_items', 'nara_add_search_form_to_global_top_menu', 10, 2 );
/**
 * Filters the nav items attached to the global navigation and appends a
 * search form.
 *
 * @param $items
 * @param $args
 *
 * @return string
 */
function nara_add_search_form_to_global_top_menu( $items, $args ) {
	if ( 'global-top-menu' !== $args->theme_location ) {
		return $items;
	}

	return $items . '<li class="search">' . get_search_form( false ) . '</li>';
}

add_filter( 'get_the_excerpt', 'nara_trim_excerpt', 5 );
/**
 * Provide a custom trimmed excerpt.
 *
 * @param string $text The raw excerpt.
 *
 * @return string The modified excerpt.
 */
function nara_trim_excerpt( $text ) {
	$raw_excerpt = $text;
	if ( '' === $text ) {
		//Retrieve the post content.
		$text = get_the_content( '' );
		//Delete all shortcode tags from the content.
		//$text = strip_shortcodes( $text );
		//$allowed_tags = '<p>,<a>,<em>,<strong>,<img>,<h2>,<h3>,<h4>,<h5>,<blockquote>';
		//$text = strip_tags( $text, $allowed_tags );
		$text = apply_filters( 'the_content', $text );
		if ( ! has_filter( 'the_content', 'wpautop' ) ) {
			$text = wpautop( $text );
		}
		$text = str_replace( ']]>', ']]&gt;', $text );
		$excerpt_word_count = 80;
		$excerpt_length = apply_filters( 'excerpt_length', $excerpt_word_count );
		$excerpt_end = ' <a href="' . get_permalink() . '" class="more">...more</a>';
		$excerpt_more = apply_filters( 'excerpt_more', ' ' . $excerpt_end );
		$words = preg_split( "/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY );
		if ( count( $words ) > $excerpt_length ) {
			array_pop( $words );
			$text = implode( ' ', $words );
			$text = $text . $excerpt_more;
		} else {
			$text = implode( ' ', $words );
		}
		$text = force_balance_tags( $text );
	}
	return apply_filters( 'wp_trim_excerpt', $text, $raw_excerpt );
}

add_filter( 'spine_get_campus_home_url', 'nara_spine_signature_url' );
/**
 * Filter the Spine signature URL.
 */
function nara_spine_signature_url() {
	return get_site_url();
}

add_filter( 'spine_get_campus_data', 'nara_spine_signature_text' );
/**
 * Filter the Spine signature link text.
 */
function nara_spine_signature_text() {
	return 'Northwest Advanced Renewables Alliance';
}

add_action( 'pre_get_posts', 'entity_other' );
/**
 * Query the `wsuwp_uc_entity` post type for the "Other Grants" category archive.
 */
function entity_other( $query ) {
	if ( is_category( 'other-grants' ) && $query->is_main_query() ) {
		$query->set( 'post_type', array(
			'wsuwp_uc_entity'
		) );
	}
}

add_filter( 'wsuwp_uc_people_sort_items', 'nara_uc_people_sort', 10, 1 );
/*
 * Sort people results before displaying.
 */
function nara_uc_people_sort( $people ) {
	return array_reverse( $people );
}

add_filter( 'wsuwp_uc_people_item_html', 'nara_uc_people_html', 10, 2 );
/**
 * Provide a custom HTML template for use with syndicated people.
 *
 * @param string   $html   The HTML to output for an individual person.
 * @param stdClass $person Object representing a person received from University Center Objects.
 *
 * @return string The HTML to output for a person.
 */
function nara_uc_people_html( $html, $person ) {
	ob_start();
	?>
	<div class="uco-syndicate-person-container">
		<div class="uco-syndicate-person-photo">
			<?php if ( has_post_thumbnail( $person->id ) ) { ?>
			<a href="<?php echo esc_url( $person->link ); ?>">
				<?php echo get_the_post_thumbnail( $person->id, 'thumbnail' ); ?>
			</a>
			<?php } ?>
		</div>
		<div class="uco-syndicate-person-name">
			<a href="<?php echo esc_url( $person->link ); ?>">
				<?php echo esc_html( $person->title->rendered ); ?>
			</a>
		</div>
	</div>
	<?php
	$html = ob_get_contents();
	ob_end_clean();

	return $html;
}

add_filter( 'wsuwp_uc_people_to_add_to_content', 'nara_uc_content_people' );
/**
 * Reverse the array of people appended to UCO post content so they appear in alpha order by last name.
 *
 * @param array $people The people associated with the UCO post.
 *
 * @return array The reversed array of people.
 */
function nara_uc_content_people( $people ) {
	if ( is_array( $people ) ) {
		$people = array_reverse( $people );
	}

	return $people;
}

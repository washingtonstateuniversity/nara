<nav role="navigation">
	<div id="nara-logo">
		<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/logo.png' ); ?>" ></a>
	</div>
	<?php
	$spine_site_args = array(
		'theme_location'  => 'site',
		'menu'            => 'site',
		'container'       => false,
		'container_class' => false,
		'container_id'    => false,
		'menu_class'      => 'main-menu',
		'menu_id'         => null,
		'items_wrap'      => '<ul>%3$s</ul>',
		'depth'           => 3,
	);
	wp_nav_menu( $spine_site_args ); ?>
</nav>

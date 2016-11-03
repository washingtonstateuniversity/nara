<footer role="footer">
	<div class="support">
	  <p>NARA is led by Washington State University and supported by the Agriculture and Food Research Initiative Competitive Grant no. 2011-68005-30416 from the USDA National Institute of Food and Agriculture.</p>
	  <img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/wsu-cougar-head.png" alt="Washington State University Cougar head" height="50" width="56"> <img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/usda-logo.png" alt="USDA" height="50" width="68">
	</div>
	<div class="commons">
		<header>Creative commons</header>
		<p><a href="/creative-commons/">Our work is offered under Creative Commons licenses. All work on the site falls under the Attribution-NonCommercial license, unless otherwise noted</a></p>
	</div>
	<div class="quick-links">
		<header>Quick links</header>
		<nav>
	<?php
		$spine_site_args = array(
			'theme_location'  => 'site',
			'menu'            => 'quick-links',
			'container'       => false,
			'container_class' => false,
			'container_id'    => false,
			'menu_class'      => 'quick-links',
			'menu_id'         => null,
			'items_wrap'      => '<ul>%3$s</ul>',
			'depth'           => 1,
		);
		wp_nav_menu( $spine_site_args );
	?>
		</nav>
	</div>
</footer>

<section class="row single h1-header gutter pad-top">
	<div class="column one">
		<h1><?php post_type_archive_title(); ?></h1>
	</div>
</section>
<section class="row single gutter pad-top pad-bottom">
	<div class="column one">
			<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'articles/post', get_post_type() ); ?>
			<?php endwhile; // end of the loop. ?>
	</div><!--/column-->
</section>

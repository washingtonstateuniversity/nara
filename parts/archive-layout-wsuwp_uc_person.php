<section class="row single gutter hello">
	<div class="column one">
		<h1>Members</h1>
			<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'articles/post', get_post_type() ); ?>
			<?php endwhile; // end of the loop. ?>
	</div><!--/column-->
</section>

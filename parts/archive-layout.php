<section class="row single h1-header gutter pad-top">
	<div class="column one">
		<h1><?php single_cat_title(''); ?></h1>
	</div>
</section>
<section class="row side-right gutter pad-bottom">

	<div class="column one">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'articles/post', get_post_type() ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!--/column-->

	<div class="column two">

		<?php get_sidebar(); ?>

	</div><!--/column two-->

</section>

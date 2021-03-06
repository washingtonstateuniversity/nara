<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="article-header">
		<hgroup>
			<?php if ( is_single() ) : ?>
				<h1 class="article-title"><?php the_title(); ?></h1>			<?php else : ?>
			<?php endif; ?>
		</hgroup>
	</header>
	<div class="flex-body">
	<figure class="logo">
		<?php the_post_thumbnail( 'medium' ); ?>
	</figure>
	<?php if ( ! is_singular() ) : ?>
		<div class="article-summary">
			<h2 class="article-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php
			// If a manual excerpt is available, display this. Otherwise, only the most basic information is needed.
			the_excerpt();
			?>
		</div><!-- .article-summary -->
	<?php else : ?>
		<div class="article-body">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'spine' ), 'after' => '</div>' ) ); ?>
		</div>
	<?php endif; ?>

</article>

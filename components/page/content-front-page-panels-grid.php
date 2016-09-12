<?php
/**
 * Template part for displaying a single post within a grid on the front page.
 *
 * @package Twenty Seventeen
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >

	<div class="panel-content">
		<?php if ( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" rel="bookmark" class="featured-image"><?php the_post_thumbnail(); ?></a>
		<?php endif; ?>
		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
		</header><!-- .entry-header -->
	</div><!-- .panel-content -->

</article><!-- #post-## -->

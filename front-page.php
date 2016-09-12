<?php
/**
 * The front page template file.
 *
 * Used regardless of the front page setting.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Twenty Seventeen
 */

if ( 'posts' === get_option( 'show_on_front' ) ) :

	get_template_part( 'index' );

else :

// Access global variable directly to set content_width
if ( isset( $GLOBALS['content_width'] ) ) {
	$GLOBALS['content_width'] = 1120;
}

get_header(); ?>


<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php // Show the selected frontpage content
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				echo '<div class="wrap">';
				get_template_part( 'components/page/content', 'front-page' );
				echo '</div>';
			endwhile;
		else : // I'm not sure it's possible to have no posts when this page is shown, but WTH
			echo '<div class="wrap">';
			get_template_part( 'components/post/content', 'none' );
			echo '</div>';
		endif; 

		// Show custom front page content.
		$walker = new Front_Page_Content_Walker();
		wp_nav_menu( array(
			'depth' => 0,
			'walker' => $walker,
			'theme_location' => 'front-page',
			'items_wrap' => '<div id="%1$s" class="%2$s">%3$s</div>',
		));
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer();

endif; ?>

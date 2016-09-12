<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Twenty Seventeen
 */

if ( ! function_exists( 'twentyseventeen_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function twentyseventeen_posted_on() {

	/* translators: used between list items, there is a space after the comma */
	$separate_meta = __( ', ', 'twentyseventeen' );

	// Let's get a nicely formatted string for the published date
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		get_the_date( 'c' ),
		get_the_date(),
		get_the_modified_date( 'c' ),
		get_the_modified_date()
	);

	// Wrap that in a link, and preface it with 'Posted on'
	$posted_on = sprintf(
		_x( 'Posted on %s', 'post date', 'twentyseventeen' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	// Get the author name; wrap it in a link
	$byline = sprintf(
		_x( 'by %s', 'post author', 'twentyseventeen' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a></span>'
	);

	// Check to make sure we have more than one category before writing to page.
	$categories_list = get_the_category_list( $separate_meta );
	if ( $categories_list && twentyseventeen_categorized_blog() ) {
		$categories = sprintf( _x( 'in %1$s', 'prefaces list of categories assigned to the post', 'twentyseventeen' ), $categories_list ); // WPCS: XSS OK.
	}

	// Finally, let's write all of this to the page
	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span><span class="cat-links"> ' . $categories . '</span>'; // WPCS: XSS OK.
}
endif;


if ( ! function_exists( 'twentyseventeen_edit_post_link' ) ) :
/**
 * Prints the post's edit link
 */
function twentyseventeen_edit_post_link() {
	// Display 'edit' link
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			__( 'Edit %s', 'twentyseventeen' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;


if ( ! function_exists( 'twentyseventeen_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function twentyseventeen_entry_footer() {

	/* translators: used between list items, there is a space after the comma */
	$separate_meta = __( ', ', 'twentyseventeen' );

	// Display Tags for posts
	if ( 'post' === get_post_type() ) {
		$tags_list = get_the_tag_list( '', $separate_meta );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'twentyseventeen' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	// Display link to comments
	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'twentyseventeen' ), __( '1 Comment', 'twentyseventeen' ), __( '% Comments', 'twentyseventeen' ) );
		echo '</span>';
	}

	twentyseventeen_edit_post_link();
}
endif;


/**
 * Returns an accessibility-friendly link to edit a post or page.
 * This also gives us a little context about what exactly we're editing
 * (post or page?) so that users understand a bit more where they are in terms
 * of the template hierarchy and their content. Helpful when/if the single-page
 * layout with multiple posts/pages shown gets confusing.
 */
function twentyseventeen_edit_link( $id ) {
	if ( is_page() ) :
		$type = __( 'Page', 'twentyseventeen' );
	elseif ( get_post( $id ) ) :
		$type = __( 'Post', 'twentyseventeen' );
	endif;
	$link = edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			__( 'Edit %1$s %2$s', 'twentyseventeen' ),
			$type,
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
	return $link;
}


/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function twentyseventeen_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'twentyseventeen_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'twentyseventeen_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so twentyseventeen_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so twentyseventeen_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in twentyseventeen_categorized_blog.
 */
function twentyseventeen_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'twentyseventeen_categories' );
}
add_action( 'edit_category', 'twentyseventeen_category_transient_flusher' );
add_action( 'save_post',     'twentyseventeen_category_transient_flusher' );


/**
 * Display custom front page featured content.
 *
 * @todo consider including a generic filterable (for the template parts) version of this walker in core to facilitate similar behavior in other themes.
 *
 * @since Twenty Seventeen 1.0
 * @uses Walker_Nav_Menu
 */
class Front_Page_Content_Walker extends Walker_Nav_Menu {
	
	// Unused when depth is 0.
	// @todo docs
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= '<div class="front-content-container">';
	}

	// Unused when depth is 0.
	// @todo docs
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= '</div>';
	}

	/**
	 * Start the element output.
	 *
	 * @see Nav_Menu_Walker::start_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	 public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		if ( 'custom' === $item->type ) {
			// Custom links can't be displayed in this menu.
			// @todo way to prevent custom links from being added here in core.
			return;
		}
		
		/** This filter is documented in wp-includes/nav-menu.php */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		if ( 'taxonomy' === $item->type ) {
			// Show recent posts in the taxonomy.
			// @todo design this for Twenty Seventeen, including number of posts and content desired, and CSS.
			$term_id = $item->object_id;
			$taxonomy = $item->object;

			$term = get_term( $term_id, $taxonomy );

			if ( $term ) {
				// Query for posts in this term. Supports any taxonomy and post type.
				$posts = get_posts( array(
					'numberposts'      => 5, // @todo design for Twenty Seventeen
					'suppress_filters' => false,
					'post_type'        => 'any',
					'tax_query'        => array(
						array(
							'field'    => 'term_id',
							'taxonomy' => $taxonomy,
							'terms'    => $term->term_id,
						),
					),
				) );

				if ( $posts ) {
					global $post; 
					ob_start(); // @todo consider moving this to a template part.
					?>

					<section id="<?php echo $id; ?>" class="post-grid-container term-<?php echo $term->slug; ?> taxonomy-<?php echo $taxonomy; ?>">
						<header class="<?php echo $term->slug; ?> title">
							<h2 class="section-title"><a href="<?php echo get_term_link( $term, $taxonomy ); ?>"><?php echo $title; ?></a></h2>
						</header>
						<div class="post-grid-content">
						<?php foreach( $posts as $post ) {
							setup_postdata( $post ); // Allows the_* functions to work without passing an ID.
							get_template_part( 'components/page/content', 'front-page-panels-grid' ); // @todo design the different template part used here, for a grid view
																									 // This will probably show the featured image with the title and link them.
						} ?>
						</div>
					</section>

					<?php
					$output .= ob_get_clean();
				}

			}
		} elseif ( 'post_type' === $item->type ) {

			// Show the posts object.
			// @todo design this for Twenty Seventeen. Note that the menu item fields (description, etc.) are available in addition to the post's fields.
			$post_id = $item->object_id;
			$post_type = $item->object;

			global $post; 
			$post = get_post( $post_id );

			if ( $post ) {
				setup_postdata( $post ); // Allows the_* functions to work without passing an ID.
				ob_start();

				get_template_part( 'components/page/content', 'front-page-panels' );
				$output .= ob_get_clean();
			}

		}
	}

	/**
	 * Ends the element output.
	 *
	 * @see Walker_Nav_Menu::end_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Page data object. Not used.
	 * @param int    $depth  Depth of page. Not Used.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		//$output .= '</div>';
	}
}


<?php
/**
 * Guaranteed Flex 2 — theme setup.
 *
 * Principle: the theme owns presentation (tokens, components, layouts, block styles,
 * patterns, template positions); Gutenberg owns content. No page copy lives here.
 */
defined( 'ABSPATH' ) || exit;

define( 'GSS_THEME_URI', get_template_directory_uri() );
define( 'GSS_THEME_DIR', get_template_directory() );

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-logo', [ 'height' => 52, 'width' => 240, 'flex-height' => true, 'flex-width' => true ] );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
	add_theme_support( 'editor-styles' );
	add_editor_style( [ 'assets/critical.css', 'style.css', 'assets/editor.css' ] );
	remove_theme_support( 'core-block-patterns' );
	register_nav_menus( [
		'primary' => __( 'Primary Menu', 'guaranteed-flex' ),
		'footer'  => __( 'Footer Menu', 'guaranteed-flex' ),
	] );
} );

/* -------------------------------------------------------------------------
 * CSS delivery
 * 1) assets/critical.css (~9 KB, above-the-fold primitives) is inlined once.
 * 2) style.css (everything else, cacheable) is enqueued normally; the
 *    Seooptimierung Toolkit "load non-blocking" option may make it async.
 * Both files are generated from theme/src/gss.css — no rule exists twice.
 * ---------------------------------------------------------------------- */
add_action( 'wp_head', function () {
	$file = GSS_THEME_DIR . '/assets/critical.css';
	if ( is_readable( $file ) ) {
		echo '<style id="gss-critical">' . trim( file_get_contents( $file ) ) . "</style>\n"; // phpcs:ignore
	}
}, 2 );

add_action( 'wp_enqueue_scripts', function () {
	$ver = (string) filemtime( GSS_THEME_DIR . '/style.css' ); // cache-bust on every rebuild
	wp_enqueue_style( 'gss-inter', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', [], null );
	wp_enqueue_style( 'guaranteed-flex', get_stylesheet_uri(), [], $ver );

	// The design system styles these blocks itself; drop the duplicate core CSS.
	foreach ( [ 'group', 'heading', 'paragraph', 'list', 'buttons', 'button', 'details', 'table', 'columns', 'column', 'separator' ] as $block ) {
		wp_dequeue_style( 'wp-block-' . $block );
		wp_deregister_style( 'wp-block-' . $block );
	}
	wp_dequeue_style( 'classic-theme-styles' );
}, 20 );

// The theme owns critical CSS. Drop any third-party "critical CSS" snapshot
// (Seooptimierung Toolkit setting) so stale copies of old styles never load.
add_action( 'wp_print_styles', function () {
	wp_dequeue_style( 'seooptimierung-critical-css' );
}, 1 );

add_filter( 'should_load_separate_core_block_assets', '__return_true' );

// Cache-busting that survives "remove version query strings" optimisers and the
// Hostinger CDN: append the file modification time as ?v= at the very end.
add_filter( 'style_loader_src', function ( $src, $handle ) {
	if ( 'guaranteed-flex' === $handle ) {
		$src = add_query_arg( 'v', filemtime( GSS_THEME_DIR . '/style.css' ), remove_query_arg( 'ver', $src ) );
	}
	return $src;
}, PHP_INT_MAX, 2 );

add_filter( 'wp_resource_hints', function ( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = [ 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' ];
	}
	return $urls;
}, 10, 2 );

/* -------------------------------------------------------------------------
 * Template positions (widget areas). Generic positions only — never
 * page-specific content slots. Empty areas print nothing.
 * ---------------------------------------------------------------------- */
add_action( 'widgets_init', function () {
	$areas = [
		'header-top'     => __( 'Above Header', 'guaranteed-flex' ),
		'header-action'  => __( 'Header Actions', 'guaranteed-flex' ),
		'announcement'   => __( 'Announcement Bar (below header)', 'guaranteed-flex' ),
		'before-content' => __( 'Before Page Content', 'guaranteed-flex' ),
		'after-content'  => __( 'After Page Content', 'guaranteed-flex' ),
		'sidebar'        => __( 'Content Sidebar (classic pages only)', 'guaranteed-flex' ),
		'footer-1'       => __( 'Footer Column 1', 'guaranteed-flex' ),
		'footer-2'       => __( 'Footer Column 2', 'guaranteed-flex' ),
		'footer-3'       => __( 'Footer Column 3', 'guaranteed-flex' ),
		'footer-4'       => __( 'Footer Column 4', 'guaranteed-flex' ),
		'footer-bottom'  => __( 'Footer Bottom', 'guaranteed-flex' ),
	];
	foreach ( $areas as $id => $name ) {
		register_sidebar( [
			'id'            => $id,
			'name'          => $name,
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		] );
	}
} );

function gf_area( $id ) {
	if ( is_active_sidebar( $id ) ) {
		dynamic_sidebar( $id );
	}
}
function gf_band( $id, $classes = '' ) {
	if ( ! is_active_sidebar( $id ) ) {
		return;
	}
	echo '<section class="layout-band ' . esc_attr( $classes ) . '"><div class="container">';
	gf_area( $id );
	echo '</div></section>';
}
function gf_has_any( $ids ) {
	foreach ( $ids as $id ) {
		if ( is_active_sidebar( $id ) ) {
			return true;
		}
	}
	return false;
}

/* -------------------------------------------------------------------------
 * Page canvas. A page whose content provides its own <h1> is rendered edge
 * to edge (.gss-page) and the template does NOT print the title as a second
 * H1. Pages without an H1 in content get the classic layout with the title
 * as their single H1. This replaces the old CSS-only hiding of duplicates.
 * ---------------------------------------------------------------------- */
function gss_content_has_h1( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return false;
	}
	return (bool) preg_match( '/<h1[\s>]/i', $post->post_content ) || false !== strpos( $post->post_content, '"level":1' );
}

add_filter( 'body_class', function ( $classes ) {
	$classes[] = 'gf-theme';
	if ( is_singular() && gss_content_has_h1() ) {
		$classes[] = 'has-gss-canvas';
	}
	return $classes;
} );

/* Design-system masterpage stays out of search engines (also kept private). */
add_filter( 'wp_robots', function ( $robots ) {
	if ( is_page( 'design-system' ) ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}
	return $robots;
} );

/* -------------------------------------------------------------------------
 * Block styles + pattern category (patterns live in /patterns).
 * ---------------------------------------------------------------------- */
add_action( 'init', function () {
	register_block_pattern_category( 'gss', [ 'label' => __( 'Guaranteed SEO design system', 'guaranteed-flex' ) ] );
	$styles = [
		'core/group'   => [ 'gss-card' => 'Card', 'gss-card-soft' => 'Soft card', 'gss-section-soft' => 'Soft section' ],
		'core/button'  => [ 'navy' => 'Navy' ],
		'core/list'    => [ 'gss-checks' => 'Checklist', 'gss-ticks' => 'Inline ticks' ],
		'core/details' => [ 'gss-faq' => 'FAQ item' ],
		'core/table'   => [ 'gss-table' => 'Design-system table' ],
	];
	foreach ( $styles as $block => $set ) {
		foreach ( $set as $name => $label ) {
			register_block_style( $block, [ 'name' => $name, 'label' => $label ] );
		}
	}
} );

/* -------------------------------------------------------------------------
 * Front page directory: teaser cards of published child-less pages. Shows
 * page titles/excerpts only (content comes from WordPress, not PHP).
 * Hide via Customizer theme_mod gf_hide_page_directory or per page with
 * custom field _gf_hide_from_home.
 * ---------------------------------------------------------------------- */
function gf_page_directory() {
	if ( get_theme_mod( 'gf_hide_page_directory', false ) ) {
		return;
	}
	$home_id = (int) get_option( 'page_on_front' );
	$pages   = get_pages( [ 'sort_column' => 'menu_order,post_title', 'post_status' => 'publish' ] );
	$items   = array_values( array_filter( (array) $pages, static function ( $page ) use ( $home_id ) {
		return (int) $page->ID !== $home_id && ! get_post_meta( $page->ID, '_gf_hide_from_home', true );
	} ) );
	if ( ! $items ) {
		return;
	}
	$heading = apply_filters( 'gf_page_directory_heading', __( 'Explore our services and resources', 'guaranteed-flex' ) );
	echo '<section class="gss-section page-directory" aria-label="' . esc_attr( $heading ) . '"><div class="gss-wrap">';
	echo '<h2 class="gss-h2 gss-head">' . esc_html( $heading ) . '</h2><div class="teaser-grid">';
	foreach ( $items as $page ) {
		$excerpt = has_excerpt( $page ) ? get_the_excerpt( $page ) : '';
		echo '<article class="teaser-card"><h3><a href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html( get_the_title( $page ) ) . '</a></h3>';
		if ( $excerpt ) {
			echo '<p>' . esc_html( wp_trim_words( wp_strip_all_tags( $excerpt ), 25 ) ) . '</p>';
		}
		echo '</article>';
	}
	echo '</div></div></section>';
}

/* -------------------------------------------------------------------------
 * Routing fix: the "SEO Case Studies" post type (guaranteed-seo-core plugin)
 * registered an archive at /case-studies/ that hijacked the Case Studies
 * page. Keep single case URLs, drop the archive so the page renders.
 * (Better long-term: change has_archive in the plugin itself.)
 * ---------------------------------------------------------------------- */
add_filter( 'register_post_type_args', function ( $args, $post_type ) {
	if ( 'gss_case' === $post_type ) {
		$args['has_archive'] = false;
	}
	return $args;
}, 20, 2 );

<?php
/**
 * Static front page: same canvas as page.php, followed by the optional
 * automatic page directory. Falls back to index.php for a posts front page.
 */
if ( 'page' !== get_option( 'show_on_front' ) ) {
	require get_template_directory() . '/index.php';
	return;
}
get_header(); ?>
<main id="main" class="site-main gss-page">
	<?php gf_band( 'before-content' ); ?>
	<?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
	<?php gf_band( 'after-content' ); ?>
	<?php gf_page_directory(); ?>
</main>
<?php get_footer();

<?php
/**
 * Pages. Content with its own H1 renders on the full-width design-system
 * canvas (no theme title, no sidebar). Otherwise the title is the single H1.
 */
get_header();
while ( have_posts() ) :
	the_post();
	if ( gss_content_has_h1() ) : ?>
<main id="main" class="site-main gss-page">
	<?php gf_band( 'before-content' ); ?>
	<?php the_content(); ?>
	<?php gf_band( 'after-content' ); ?>
</main>
	<?php else : ?>
<main id="main" class="site-main">
	<?php gf_band( 'before-content' ); ?>
	<div class="container content-area<?php echo is_active_sidebar( 'sidebar' ) ? ' has-sidebar' : ''; ?>">
		<div class="primary">
			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<header class="article-header"><h1><?php the_title(); ?></h1></header>
				<div class="entry-content"><?php the_content(); wp_link_pages(); ?></div>
			</article>
			<?php if ( comments_open() || get_comments_number() ) { comments_template(); } ?>
		</div>
		<?php if ( is_active_sidebar( 'sidebar' ) ) : ?><aside class="site-sidebar"><?php gf_area( 'sidebar' ); ?></aside><?php endif; ?>
	</div>
	<?php gf_band( 'after-content' ); ?>
</main>
	<?php endif;
endwhile;
get_footer();

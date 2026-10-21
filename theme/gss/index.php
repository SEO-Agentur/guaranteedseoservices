<?php get_header(); ?>
<main id="main" class="site-main"><div class="container content-area">
<?php if ( is_singular() ) : while ( have_posts() ) : the_post(); ?>
	<article <?php post_class(); ?>><header class="article-header"><h1><?php the_title(); ?></h1><div class="article-meta"><?php echo esc_html( get_the_date() ); ?></div></header><div class="entry-content"><?php the_content(); wp_link_pages(); ?></div></article>
	<?php if ( comments_open() || get_comments_number() ) { comments_template(); } endwhile; ?>
<?php else : ?>
	<header class="article-header"><h1><?php echo esc_html( is_home() ? get_bloginfo( 'name' ) : wp_strip_all_tags( get_the_archive_title() ) ); ?></h1></header>
	<div class="posts-grid"><?php while ( have_posts() ) : the_post(); ?><article <?php post_class( 'gss-card' ); ?>><h2 class="gss-h3"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html( get_the_excerpt() ); ?></p></article><?php endwhile; ?></div>
	<div class="pagination"><?php the_posts_pagination(); ?></div>
<?php endif; ?>
</div></main>
<?php get_footer();

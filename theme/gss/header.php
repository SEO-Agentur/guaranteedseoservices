<?php defined( 'ABSPATH' ) || exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'guaranteed-flex' ); ?></a>
<?php gf_band( 'header-top', 'is-compact' ); ?>
<header class="site-header">
	<div class="container header-inner">
		<div class="site-branding"><?php if ( has_custom_logo() ) { the_custom_logo(); } else { ?><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a><?php } ?></div>
		<?php if ( has_nav_menu( 'primary' ) ) : ?>
		<nav class="header-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'guaranteed-flex' ); ?>"><?php wp_nav_menu( [ 'theme_location' => 'primary', 'container' => false, 'menu_id' => 'primary-menu', 'fallback_cb' => false, 'depth' => 2 ] ); ?></nav>
		<details class="mobile-navigation"><summary><?php esc_html_e( 'Menu', 'guaranteed-flex' ); ?></summary><nav aria-label="<?php esc_attr_e( 'Mobile navigation', 'guaranteed-flex' ); ?>"><?php wp_nav_menu( [ 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => false, 'depth' => 2 ] ); ?></nav></details>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'header-action' ) ) : ?><div class="header-actions"><?php gf_area( 'header-action' ); ?></div><?php endif; ?>
	</div>
</header>
<?php gf_band( 'announcement', 'is-compact' ); ?>

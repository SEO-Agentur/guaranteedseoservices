<?php defined( 'ABSPATH' ) || exit; ?>
<footer class="site-footer">
<?php if ( gf_has_any( [ 'footer-1', 'footer-2', 'footer-3', 'footer-4' ] ) || has_nav_menu( 'footer' ) ) : ?>
	<div class="container footer-inner">
		<?php if ( gf_has_any( [ 'footer-1', 'footer-2', 'footer-3', 'footer-4' ] ) ) : ?>
		<div class="footer-columns"><?php foreach ( [ 'footer-1', 'footer-2', 'footer-3', 'footer-4' ] as $area ) { if ( is_active_sidebar( $area ) ) { echo '<div>'; gf_area( $area ); echo '</div>'; } } ?></div>
		<?php endif; ?>
		<?php if ( has_nav_menu( 'footer' ) ) : ?><nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer navigation', 'guaranteed-flex' ); ?>"><?php wp_nav_menu( [ 'theme_location' => 'footer', 'container' => false, 'fallback_cb' => false, 'depth' => 1 ] ); ?></nav><?php endif; ?>
	</div>
<?php endif; ?>
<?php if ( is_active_sidebar( 'footer-bottom' ) ) : ?><div class="footer-bottom"><div class="container"><?php gf_area( 'footer-bottom' ); ?></div></div><?php endif; ?>
</footer>
<?php wp_footer(); ?>
</body>
</html>

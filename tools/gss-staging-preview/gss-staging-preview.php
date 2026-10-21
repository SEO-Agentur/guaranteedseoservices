<?php
/**
 * Plugin Name: GSS Staging Preview (temporary)
 * Description: Lets administrators preview the new "gss" theme and migrated page content on the live URLs before cutover. Visit any URL with ?gss_preview=1 to enable (cookie), ?gss_preview=0 to disable, ?gss_original=1 to view the live version for one request. Visitors are never affected. Remove after the migration is complete.
 * Version: 1.1.0
 */
defined( 'ABSPATH' ) || exit;

const GSS_PREVIEW_THEME = 'gss';
const GSS_NEXT_META     = '_gss_next_content';

function gss_preview_on() {
	static $on = null;
	if ( null !== $on ) {
		return $on;
	}
	if ( ! function_exists( 'wp_validate_auth_cookie' ) ) {
		return false; // too early (pluggables not loaded) — decide later, never fatal.
	}
	if ( isset( $_GET['gss_preview'] ) && ! headers_sent() ) {
		$enable = '1' === $_GET['gss_preview'];
		setcookie( 'gss_preview', $enable ? '1' : '', $enable ? time() + DAY_IN_SECONDS : time() - 3600, '/', COOKIE_DOMAIN, is_ssl(), true );
		$_COOKIE['gss_preview'] = $enable ? '1' : '';
	}
	if ( isset( $_GET['gss_original'] ) ) {
		return $on = false; // per-request override: show the current live version
	}
	if ( empty( $_COOKIE['gss_preview'] ) ) {
		return $on = false;
	}
	$uid = wp_validate_auth_cookie( '', 'logged_in' );
	return $on = ( $uid && user_can( $uid, 'edit_theme_options' ) && is_dir( get_theme_root() . '/' . GSS_PREVIEW_THEME ) );
}

add_filter( 'template', fn( $t ) => gss_preview_on() ? GSS_PREVIEW_THEME : $t, 99 );
add_filter( 'stylesheet', fn( $t ) => gss_preview_on() ? GSS_PREVIEW_THEME : $t, 99 );
// Reuse the active theme's menu locations, logo, etc. until cutover.
add_filter( 'option_theme_mods_' . GSS_PREVIEW_THEME, function ( $mods ) {
	$active = (array) get_option( 'theme_mods_' . get_option( 'stylesheet' ) );
	unset( $active['custom_css_post_id'] ); // Additional CSS does not carry over to the new theme.
	return array_merge( $active, (array) $mods );
} );
add_filter( 'default_option_theme_mods_' . GSS_PREVIEW_THEME, function () {
	$active = (array) get_option( 'theme_mods_' . get_option( 'stylesheet' ) );
	unset( $active['custom_css_post_id'] );
	return $active;
} );
// Serve migrated content where present (front end only).
add_filter( 'the_posts', function ( $posts ) {
	if ( is_admin() || wp_is_json_request() || ! gss_preview_on() ) {
		return $posts;
	}
	foreach ( $posts as $p ) {
		$next = get_post_meta( $p->ID, GSS_NEXT_META, true );
		if ( $next ) {
			$p->post_content = $next;
		}
	}
	return $posts;
} );
add_action( 'send_headers', function () {
	if ( gss_preview_on() ) {
		nocache_headers();
		header( 'X-LiteSpeed-Cache-Control: no-cache' );
	}
} );
add_filter( 'show_admin_bar', fn( $show ) => gss_preview_on() ? false : $show );

add_action( 'init', function () {
	register_post_meta( 'page', GSS_NEXT_META, [
		'type'          => 'string',
		'single'        => true,
		'show_in_rest'  => true,
		'auth_callback' => fn() => current_user_can( 'edit_pages' ),
	] );
} );

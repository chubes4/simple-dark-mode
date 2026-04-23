<?php
/**
 * Plugin Name:       Simple Dark Mode
 * Plugin URI:        https://github.com/chubes4/simple-dark-mode
 * Description:       Zero-config system-aware dark mode for WordPress. Respects your OS preference on both frontend and admin — no settings, no toggle, it just works.
 * Version:           0.3.2
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Chris Huber
 * Author URI:        https://chubes.net
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       simple-dark-mode
 * Update URI:        https://github.com/chubes4/simple-dark-mode
 *
 * @package SimpleDarkMode
 */

defined( 'ABSPATH' ) || exit;

define( 'SIMPLE_DARK_MODE_VERSION', '0.3.2' );
define( 'SIMPLE_DARK_MODE_URL', plugin_dir_url( __FILE__ ) );
define( 'SIMPLE_DARK_MODE_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Get a cache-busting version for a plugin stylesheet.
 *
 * Uses file modification time when available, otherwise falls back to the
 * plugin's declared version constant.
 *
 * @param string $relative_path Path relative to the plugin root (e.g. 'assets/frontend.css').
 * @return string
 */
function simple_dark_mode_asset_version( $relative_path ) {
	$path = SIMPLE_DARK_MODE_PATH . $relative_path;
	return file_exists( $path ) ? (string) filemtime( $path ) : SIMPLE_DARK_MODE_VERSION;
}

/**
 * Enqueue the frontend dark-mode stylesheet on the public site.
 *
 * The dependency on `global-styles` is critical: WordPress outputs the
 * block-theme `:root` custom-property palette (`--wp--preset--color--base`,
 * etc.) via an inline <style id="global-styles-inline-css">. Without the
 * dep, our stylesheet would register earlier in <head> and get clobbered
 * by the global-styles palette that loads after it. Declaring the dep
 * forces our stylesheet to render AFTER the palette, so our dark-mode
 * custom-property overrides actually win the cascade.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		$rel = 'assets/frontend.css';
		wp_enqueue_style(
			'simple-dark-mode',
			SIMPLE_DARK_MODE_URL . $rel,
			array( 'global-styles' ),
			simple_dark_mode_asset_version( $rel )
		);
	}
);

/**
 * Enqueue the same frontend stylesheet inside the block editor iframe so
 * the authoring canvas matches the frontend preview in dark mode.
 *
 * `enqueue_block_assets` also fires on the public frontend, so we guard
 * with `is_admin()` to prevent a double-enqueue (which would still only
 * produce one HTTP request thanks to handle deduplication, but noise in
 * the cascade is noise). Per wp-common-block-scripts-and-styles(),
 * `enqueue_block_assets` only fires in admin when the block editor is
 * actually loading, so this guard precisely scopes us to the editor.
 */
add_action(
	'enqueue_block_assets',
	function () {
		if ( ! is_admin() ) {
			return;
		}
		$rel = 'assets/frontend.css';
		wp_enqueue_style(
			'simple-dark-mode-canvas',
			SIMPLE_DARK_MODE_URL . $rel,
			array(),
			simple_dark_mode_asset_version( $rel )
		);
	}
);

/**
 * Enqueue the wp-admin dark-mode stylesheet on admin pages AND on the
 * login screen (wp-login.php), so the whole WordPress-owned chrome is
 * consistent.
 */
$simple_dark_mode_enqueue_admin = function () {
	$rel = 'assets/admin.css';
	wp_enqueue_style(
		'simple-dark-mode-admin',
		SIMPLE_DARK_MODE_URL . $rel,
		array(),
		simple_dark_mode_asset_version( $rel )
	);
};
add_action( 'admin_enqueue_scripts', $simple_dark_mode_enqueue_admin );
add_action( 'login_enqueue_scripts', $simple_dark_mode_enqueue_admin );

/**
 * Enqueue the block editor (Gutenberg) chrome dark-mode stylesheet.
 *
 * This covers the editor UI *around* the canvas — sidebar, toolbar,
 * inserter, popovers, etc. The canvas itself picks up the frontend
 * stylesheet automatically via the `enqueue_block_assets` hook above.
 */
add_action(
	'enqueue_block_editor_assets',
	function () {
		$rel = 'assets/editor.css';
		wp_enqueue_style(
			'simple-dark-mode-editor',
			SIMPLE_DARK_MODE_URL . $rel,
			array(),
			simple_dark_mode_asset_version( $rel )
		);
	}
);

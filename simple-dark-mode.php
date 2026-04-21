<?php
/**
 * Plugin Name:       Simple Dark Mode
 * Plugin URI:        https://github.com/chubes4/simple-dark-mode
 * Description:       Zero-config system-aware dark mode for WordPress. Respects your OS preference on both frontend and admin — no settings, no toggle, it just works.
 * Version:           0.1.0
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

define( 'SIMPLE_DARK_MODE_VERSION', '0.1.0' );
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
 * Enqueue the frontend dark-mode stylesheet.
 *
 * `enqueue_block_assets` is the canonical WordPress hook for stylesheets
 * that need to apply to BOTH the frontend and the block editor canvas
 * (which renders inside an iframe). Per wp-includes/script-loader.php:
 *
 *     "Fires after enqueuing block assets for both editor and front-end."
 *
 * Using this single hook — instead of wp_enqueue_scripts +
 * add_editor_style() — correctly styles the editor canvas without the
 * theme-root path resolution that add_editor_style() imposes on plugins.
 */
add_action(
	'enqueue_block_assets',
	function () {
		$rel = 'assets/frontend.css';
		wp_enqueue_style(
			'simple-dark-mode',
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

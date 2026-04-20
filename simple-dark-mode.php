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
 * The stylesheet is wrapped entirely in `@media (prefers-color-scheme: dark)`,
 * so when the visitor's OS is in light mode this file is inert.
 */
add_action(
	'wp_enqueue_scripts',
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
 * Enqueue the wp-admin dark-mode stylesheet.
 */
add_action(
	'admin_enqueue_scripts',
	function () {
		$rel = 'assets/admin.css';
		wp_enqueue_style(
			'simple-dark-mode-admin',
			SIMPLE_DARK_MODE_URL . $rel,
			array(),
			simple_dark_mode_asset_version( $rel )
		);
	}
);

/**
 * Enqueue the block editor (Gutenberg) chrome dark-mode stylesheet.
 *
 * This covers the editor UI around the canvas — sidebar, toolbar, inserter,
 * popovers, etc. The canvas itself is styled by the frontend stylesheet via
 * `add_editor_style()` below.
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

/**
 * Register the frontend stylesheet as an editor style so the block editor
 * canvas matches the frontend preview in dark mode.
 */
add_action(
	'after_setup_theme',
	function () {
		add_editor_style( 'assets/frontend.css' );
	}
);

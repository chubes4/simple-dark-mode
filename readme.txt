=== Simple Dark Mode ===
Contributors: chubes
Tags: dark mode, dark theme, accessibility, color scheme, prefers-color-scheme
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 0.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Zero-config system-aware dark mode for WordPress. Respects your OS preference on both frontend and admin — no settings, no toggle, it just works.

== Description ==

Simple Dark Mode adds automatic dark mode support to WordPress. It respects the user's operating system preference via the `prefers-color-scheme` media query, with no settings page, no toggle, and no JavaScript.

**What you get:**

* **Frontend dark mode** — when the visitor's OS is in dark mode, the site switches to a tasteful dark palette. Targets block-theme CSS custom properties (`base`, `contrast`) for a clean theme-aware flip on modern themes, with body-level fallbacks for classic themes.
* **wp-admin dark mode** — the WordPress dashboard follows the same rule. Postboxes, tables, forms, notices all flip to dark when your OS is dark.
* **Block editor dark mode** — Gutenberg canvas and chrome both respect the preference. The canvas uses the same stylesheet as the frontend, so authoring matches preview.

**What it doesn't do:**

* No user-facing toggle (by design — this plugin is for people who want true OS-aware dark mode with zero UX overhead)
* No settings page
* No per-site configuration

Install it, activate it, done.

== Installation ==

1. Upload the plugin to `/wp-content/plugins/simple-dark-mode/` or install via the Plugins screen.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. That's it. Switch your OS to dark mode to see it in effect.

== Frequently Asked Questions ==

= Can users override the system preference? =

Not in 0.1.0. That's the point of the plugin: it's for people who want their site to follow the OS preference with zero friction. If you want a manual toggle, there are other plugins that do that.

= Does this work with my theme? =

Block themes using the standard `base` / `contrast` palette convention (including Twenty Twenty-Two through Twenty Twenty-Five) get the cleanest flip. Classic themes get body-level fallbacks — usable, but may have theme-specific surfaces that remain light. Pull requests with theme-specific improvements welcome.

= Will this override my theme's accent colors? =

No. Only the `base` (background) and `contrast` (foreground) palette slots are overridden. Your theme's accent colors are preserved, so your brand still shines through on the dark canvas.

= Does it load JavaScript? =

No. The plugin is CSS-only. All behavior is driven by the browser's `prefers-color-scheme` media query.

= What happens in light mode? =

Nothing. The stylesheets load but every rule is wrapped in `@media (prefers-color-scheme: dark)`, so in light mode they match nothing and have zero visual impact.

== Changelog ==

= 0.1.0 =
* Initial release.
* Frontend, wp-admin, and block editor dark mode support.
* Zero configuration, zero JavaScript.

== Upgrade Notice ==

= 0.1.0 =
Initial release.

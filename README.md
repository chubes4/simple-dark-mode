# Simple Dark Mode

Zero-config system-aware dark mode for WordPress. Respects your OS preference on both frontend and admin — no settings, no toggle, it just works.

## What it does

| Surface | Dark mode behavior |
|---|---|
| **Frontend** | Overrides `--wp--preset--color--base` / `--contrast` + body-level fallbacks |
| **wp-admin** | Dashboard, postboxes, forms, tables, notices all flip |
| **Block editor** | Canvas (via `add_editor_style()`) + chrome both respect the preference |

All styles are wrapped in `@media (prefers-color-scheme: dark)`. When your OS is in light mode, the plugin is inert — zero visual impact.

## Philosophy

- **No toggle. No settings page. No JavaScript.**
- Respects the user's OS preference, nothing more.
- Theme-aware where possible (block themes), body-level fallbacks where not (classic themes).
- Preserves your theme's accent colors so brand identity survives the flip.

## Install

**Git / manual:**

```bash
cd wp-content/plugins
git clone https://github.com/chubes4/simple-dark-mode.git
```

Then activate via the Plugins screen or WP-CLI:

```bash
wp plugin activate simple-dark-mode
```

**WordPress.org plugin directory:** coming soon.

## How it works

Three hooks, three stylesheets:

```
enqueue_block_assets          →  assets/frontend.css  (frontend + editor canvas)
admin_enqueue_scripts         →  assets/admin.css     (wp-admin)
enqueue_block_editor_assets   →  assets/editor.css    (block editor chrome)
```

`enqueue_block_assets` is the canonical WordPress hook for stylesheets that need to apply to both the public frontend **and** the block editor canvas (which renders inside an iframe). One hook, two contexts, no duplicates.

Each stylesheet is wrapped entirely in `@media (prefers-color-scheme: dark)`. The frontend stylesheet overrides two CSS custom properties that most modern block themes use:

```css
--wp--preset--color--base      (background)
--wp--preset--color--contrast  (foreground)
```

For classic themes that don't follow this convention, body-level fallback rules handle the most common surfaces (body, links, forms, tables, code blocks, etc.).

## Palette

GitHub-dark-inspired neutrals. Intentionally generic so any theme's accent colors remain visible against the dark canvas.

| Token | Value |
|---|---|
| Background | `#0d1117` |
| Surface | `#161b22` |
| Text | `#e6edf3` |
| Muted text | `#9ba3ad` |
| Border | `#30363d` |
| Link | `#58a6ff` |
| Link hover | `#79c0ff` |
| Visited | `#a371f7` |

## License

GPL-2.0-or-later. Same as WordPress.

## Author

[Chris Huber](https://chubes.net) — [@chubes4](https://github.com/chubes4)

# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

Travelify — a free classic (non-block) WordPress travel/blog theme by Colorlib, **v3.1.0**, derived from the Attitude theme by Theme Horse. Text domain `travelify`, nineteen bundled translations. Verified against **WordPress 7.0 on PHP 8.5**; requires WP 6.0 / PHP 7.4. WooCommerce-compatible.

This is a theme folder, not an application project: **there is no build step and no toolchain committed** (the Grunt 0.4 stack was deleted in 3.1.0 — it can't run on modern Node and its paths pointed at directories that don't exist here). Files ship exactly as they sit on disk. `style.css`, `rtl.css`, `editor-style.css` and `library/css/fonts.css` are edited by hand; `library/js/*.js` ship unminified and are what's enqueued.

Because it lives on wp.org, changes must survive Theme Review: everything output escaped, every user-facing string translated with the `travelify` text domain, every public function prefixed `travelify_`.

## Commands

```bash
# i18n (run from the theme root; needs a current wp-cli phar for PHP 8.5)
wp i18n make-pot . languages/travelify.pot --domain=travelify --exclude=node_modules
cd languages && for po in *.po; do
  msgmerge --quiet --update --backup=none --no-fuzzy-matching "$po" travelify.pot
  msgattrib --clear-fuzzy --empty -o "$po.tmp" "$po" && mv "$po.tmp" "$po"
  msgfmt -o "${po%.po}.mo" "$po"
done

# PHP compatibility (0 errors expected across 7.4-8.5)
phpcs --standard=PHPCompatibilityWP --runtime-set testVersion 7.4-8.5 --extensions=php .

# Release zip (.gitattributes export-ignores the repo-only files)
git archive --format=zip --prefix=travelify/ -o travelify.zip HEAD
```

Every `make-pot` run must report **zero extraction warnings** — a placeholder string needs its `translators:` comment *immediately before the gettext call*, not before the surrounding `sprintf(`.

**To run it**, symlink this folder into a WordPress install's `wp-content/themes/`. The verification lab is `/Users/silkalns/Projects/colorlib-theme-lab/wp` (SQLite drop-in, `PHP_CLI_SERVER_WORKERS=6 php -S 127.0.0.1:8099` from `wp/`; the workers matter — the block editor never reaches network idle on a single-threaded server).

## Architecture

### Hook-driven templates

Template files (`index.php`, `single.php`, `page.php`, `archive.php`, `search.php`, `templates/*.php`) contain almost no markup. They only fire `do_action( 'travelify_*' )`; the rendering functions live in `library/structure/` and attach with `add_action`. Each `do_action` is preceded by a comment listing what's hooked to it and at what priority — **keep those comments in sync**.

```text
header.php   → travelify_links, travelify_header (travelify_headerdetails)
index.php    → travelify_main_container → travelify_content()      [content-extensions.php]
                 └ get_template_part( 'content', {left|right|no}sidebar )
                     └ travelify_loop_content → travelify_theloop()
                         └ travelify_theloop_for_{archive,page,single,search,template_*}
footer.php   → travelify_footer (widgets → site-generator → social → copyright → back-to-top)
```

| Path | Role |
| --- | --- |
| `library/structure/*-extensions.php` | All front-end markup, attached to `travelify_*` hooks |
| `library/functions/functions.php` | Enqueues, widget areas, custom header, WooCommerce wrappers, `body_class`, query filters |
| `library/functions/customizer.php` | Customizer panels/sections/settings, sanitizers, the `wp_head` inline colour CSS |
| `library/panel/` | Metabox, theme-option defaults, custom Customizer controls |
| `library/js/` | Theme JS — no jQuery on the front end |
| `library/css/fonts.css`, `library/fonts/` | Bundled Ubuntu webfont |

Root `functions.php` defines `TRAVELIFY_VERSION` then `require`s those files inside `travelify_setup()` on `after_setup_theme`. **A new library file must be added to that require list** — nothing autoloads.

### Two parallel settings systems

Both are live; know which one a setting belongs to before touching it.

1. **`travelify_theme_options`** — one serialized option array (layout, slider, social URLs, feed URL, legacy logo URL). Read it through the global, never `get_option` directly:

   ```php
   global $travelify_theme_options_settings;
   $options = $travelify_theme_options_settings;
   ```

   Defaults are in [library/panel/themeoptions-defaults.php](library/panel/themeoptions-defaults.php). **Any new key must be added to `$travelify_theme_options_defaults`** or array access warns on fresh installs.

2. **Theme mods** — the colour pickers plus `travelify_footer_textbox`, rendered into an inline `<style>` by `travelify_customizer_css()` on `wp_head`.

### Security invariants (hardened in 3.1.0 — keep these)

- **Colours printed into `wp_head` go through `travelify_css_color()`**, which re-validates at output time; mods saved by older versions may hold arbitrary text. `travelify_sanitize_hexcolor()` returns `''` on failure, never the raw input.
- **The layout metabox validates against `travelify_get_sidebar_layouts()`** and stores nothing else. `$_POST` is unslashed and sanitized; the nonce action is `travelify_save_sidebar_layout`.
- **The FeedBurner redirect re-validates with `esc_url_raw()`** and goes through `wp_redirect()` — a stored value with CR/LF would otherwise inject response headers via the old bare `header()` call. It must stay `wp_redirect`, not `wp_safe_redirect`: the destination is external by definition.
- Anything rendering user-supplied data must be escaped; that sweep is done.

### Front-end JavaScript

`library/js/functions.js` and `slider.js` are **plain DOM APIs, no jQuery**, enqueued in the footer with no dependencies, versioned from `TRAVELIFY_VERSION`. Build DOM with `createElement`/`textContent`, not `innerHTML`.

- **The mobile menu is theme-owned** (`initMobileMenu`). It replaced a TinyNav `<select>`. Note that TinyNav shipped *bundled inside* the old `functions.min.js` — the standalone `library/js/tinynav.js` was never enqueued, which reads as "there was no mobile menu" if you only check the enqueues and the unminified sources. It worked; it was just a `<select>`. Don't reintroduce one.
- **JS-driven layout must not live only in `style.css`.** `slider.js` sets the slide positioning inline and `functions.js` owns the menu's `display`, because a site can serve this version's markup against an older stylesheet — a child theme carrying its own copy of `style.css`, or a minify/CDN layer holding a cached one. 3.1.0 moved that layout into CSS and broke exactly that way (every slide full height down the page, dead menu button). Cosmetics stay in CSS; anything the component needs to *function* is set from JS. `t6-stale-css` covers this by serving the 3.0.9 stylesheet against current markup.
- The toggle's visible label deliberately reuses the **`Primary Menu`** msgid, because that string is already translated in all nineteen locales and it's the only new visible string.
- **`slider.js` replaces jQuery Cycle.** Every effect the Customizer offers maps through the `EFFECTS` table; ones with no CSS equivalent fall back to a cross-fade. Keep the map exhaustive — a stored value with no entry must still resolve.
- **All slides stay `position: absolute`** and `slider.js` sets the track height from the tallest one. Making the active slide `relative` lets the track height change mid-transition, which moves the bottom-anchored pager out from under the pointer.
- `travelify_featured_post_slider()` **skips posts with no featured image**: `.featured-text` is positioned over the image, so an imageless slide has zero height.

### Customizer

`library/js/customizer.js` rewrites one `<style id="travelify-customizer-preview">` block rather than setting inline styles — inline styles can't express `:hover`/`:focus`, so the old version previewed half of each rule. Colours use `postMessage`; **the logo, header display mode and sidebar layout deliberately use the refresh transport** — their old handlers rebuilt the header and shuffled `#primary`/`#secondary` by hand and drifted out of step with the templates.

`customizer-slider-control.js` delegates every listener from `document`: **the Customizer renders control content lazily, so the control does not exist at `DOMContentLoaded`.** Binding directly to the list silently does nothing.

### Sidebar layout resolution

Post meta `travelify_sidebarlayout` → `$options['default_layout']`, forced to `default` on archive/search/home. Implemented **twice** — `travelify_content()` (picks the `content-*.php` partial) and `travelify_body_class()` (sets the class the CSS keys off). Change both together.

### Logo

Core's `custom-logo` is the source of truth. `travelify_migrate_header_logo()` moves a URL stored in the old option across once (guarded by the `travelify_logo_migrated` mod); `travelify_headerdetails()` still renders the raw option as a fallback where no attachment matched. The old setting stays registered but has no control.

### Child-theme overridability

Display functions are wrapped in `if ( ! function_exists( 'travelify_x' ) )`. Preserve that on anything emitting markup. `travelify_add_files` and `travelify_add_functionality` fire inside `travelify_setup()`.

## Verifying changes

No committed test suite; regressions are visual and silent. The bar set by 3.1.0:

- WP 7.0 / PHP 8.5 with `WP_DEBUG` + `WP_DEBUG_LOG`: home, single, password-protected, page, category, tag, author, date, search, empty search, 404, feed, attachment, paged — plus admin: Customizer, block editor, widgets, menus, posts list. Target is an **empty debug.log**.
- **Run the browser checks in WebKit as well as Chromium.** `playwright.webkit` with an iPhone device profile is the closest thing to iOS Safari available here. Everything through 3.1.0 was checked in Chromium only, and the bug that forced 3.1.1 was reported from an iPhone.
- Exercise the option-driven paths: all five sidebar layouts globally and per post, all eleven slider effects, slider on/off, all three header modes, all three blog page templates, RTL.
- The official [theme unit test data](https://github.com/WPTT/theme-test-data) imported and every post/page walked: no 500s, no horizontal overflow, no console errors.
- Theme Check: **0 required, 0 warnings** (only the optional block-styles/block-patterns suggestions remain).
- `phpcs --standard=PHPCompatibilityWP --runtime-set testVersion 7.4-8.5`: 0 errors.
- WooCommerce shop, product, cart, checkout, my-account render clean.

**Check contrast, not just visibility.** The mobile sub-menu shipped briefly as white-on-white: present, focusable, and completely unreadable, while `isVisible()` passed.

**The brand green is deliberately below WCAG AA.** `#57ad68` and `#439f55` give 2.77:1 and 3.32:1 against white, as nav background and as link text alike. That was measured, raised, and kept on purpose -- the green is the theme's identity and darkening it changed the look of every install that never set its own colours. Do not "fix" it. The greys (`#727272`) and the `:focus-visible` ring, by contrast, *are* accessibility fixes and should stay. The contrast suites record the green pairs as waived with their measured value so a change still shows up.

**Input type and the box styling rule.** `input[type="search"]` had to be added to the `border/background/border-radius` rule in `style.css` -- it lists text, password, email and textarea, and anything not in it falls back to the browser's inset border and square corners. Adding a new input type anywhere means checking that rule.

**Test against a populated site, not a fixture.** Running on a bare install hid all seven defects found in the 3.1.0 verification pass -- the double `h1` needs a static front page, the heading jumps need widgets, the metabox bug needs the block editor. The lab in `/Users/silkalns/Projects/colorlib-theme-lab/wp` is the quick harness; the Local site `local-wp` (`http://local-wp.local`, WooCommerce + Jetpack + the Colorlib plugins) is the realistic one. Attribute failures before fixing them: `colorlib-404-customizer` replaces the 404 template wholesale, and the unit-test content deliberately contains its own `<h1>`s and duplicate caption ids.

## Conventions

- Every function prefixed `travelify_`; template-level ones wrapped in `if ( ! function_exists() )`.
- **Line endings vary per file** — several are CRLF. Match what a file already uses; rewriting a file with Python's default text mode silently flattens CRLF to LF and produces a whole-file diff.
- Version lives in the `style.css` header and is read once into `TRAVELIFY_VERSION`, which cache-busts every asset — never hardcode a version in an enqueue. Bump it in `style.css`, `readme.txt` (Stable tag) and both changelogs (`readme.txt` and `README.md`).
- Assets are conditionally enqueued: slider JS only where the slider renders.

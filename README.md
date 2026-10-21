# guaranteedseoservices.com — theme & content source

* `theme/src/gss.css` — the single source of all presentation (edit here).
* `tools/build-css.mjs` — builds `theme/gss/style.css`, `assets/critical.css`, `assets/editor.css` (`cd tools && npm i postcss csso && node build-css.mjs`).
* `theme/gss/` — the WordPress theme "Guaranteed Flex 2" (deployed folder `wp-content/themes/gss`).
* `pages/migrated/*.html` — Gutenberg block markup of every page as deployed (incl. `design-system.html`).
* `pages/gaps/*.md` — component extensions proposed during migration (merged into gss.css §23).
* `docs/REPORT.md` — inventory, duplication report, measurements, regression results, remaining issues, how to build new pages.
* `docs/components.md` — component API + Gutenberg recipes. `docs/metrics/` — before/after measurements.
* `tools/gss-staging-preview/` — temporary admin-only preview plugin used for the cutover (deactivated).

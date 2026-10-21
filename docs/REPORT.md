# guaranteedseoservices.com — design-system consolidation report

Date: 21 Oct 2026 · Theme: **Guaranteed Flex 2** (folder `gss`, live) · Spec page: **/design-system/** (private, noindex)

## 1. Inventory (before)

| Item | Finding |
|---|---|
| Active theme | `guaranteed-flex` 1.3.1 (custom, flexbox, 50 widget areas, most page-specific: "dentists", "local-seo"…) |
| Public pages | 11: home, guaranteed-seo-results, guaranteed-seo-ranking, guaranteed-seo, m-e-e-a-t-our-team, guaranteed-local-seo, performance-based-seo, guaranteed-seo-company, guaranteed-seo-packages, case-studies, seo-guarantee. 0 posts. |
| Editor | Classic Editor plugin active → every page was one big Custom HTML blob (no editable blocks). |
| Page "mini design systems" | **8 parallel implementations** of the same components: `.gfm--home`, `.gfm--guarantee`, `.gfm--cases`, `.gfm--packages` (in theme style.css, 74 KB of 81 KB), `.gsr` (results + ranking, in-page `<style>`), `.gsg`, `.gsa`, `.gfm--local`, `.gcs-*`, legacy `.gss-*` (in-page `<style>` 7.9–14 KB each). |
| Duplicated components | band/section, wrap/container, eyebrow (`gfm-k`, `gfm-eyebrow`, `gfm-kicker`, `eb`, `ey`, `gcs-kick`, `gss-eye`), button (9 definitions, 5 blues: #1463ff #1a5cff #1a5fe0 #1f5fd1 #1f6feb), card (9), icon badge (8), breadcrumbs (7), hero (9), FAQ accordion (9, three different +/chevron styles), CTA band (9), URL capture form (8), lead form (7), pricing plan (2), tables (7), checklists (11), tabs/toggles (4, each with hard-coded `#id:checked` selectors), utilities `gfm-u1…u14` per page. |
| Icons | 309 inline SVGs (81 KB of HTML); 104 unique 24px icons that are ~52 real icons drawn slightly differently. |
| Inline style attributes | 203 across pages (mostly widths, colours, positions). |
| Head CSS per page | Seooptimierung Toolkit "critical CSS" textarea **52,781 B** (a minified copy of the whole old stylesheet + Inter @font-face) + theme critical 673 B + Additional CSS (mega menu) 5,189 B + 8.4 KB block-library CSS caused by default sidebar widgets that were hidden with CSS. |
| Duplicate H1 — root cause | `page.php` always printed `<h1>the_title()</h1>` in `.article-header`; every page also has its own hero H1 in content. Pages hid the template header with `body:has(.gsg) .article-header{display:none!important}` etc. → 2 H1 in the HTML on 10/11 pages (CSS-only suppression). |
| Routing bug | `/case-studies/` rendered the empty "SEO Case Studies" CPT archive (plugin `guaranteed-seo-core`, `gss_case` has_archive) instead of the page. |
| Mobile bugs | Horizontal page overflow at 375 px on ranking (582 px), results (395), company (623), packages (406). |
| Cache bug | Toolkit strips `?ver=` from assets and the Hostinger CDN caches static files a week → CSS changes did not reach visitors. |

## 2. Architecture (after)

* **Theme = presentation**: `theme/src/gss.css` is the single source (tokens → base → chrome → layout → components → extensions). `tools/build-css.mjs` splits it, with **no rule emitted twice**, into
  * `assets/critical.css` – above-the-fold primitives, inlined (`#gss-critical`),
  * `style.css` – everything else, external & cacheable (`?v=filemtime` survives the optimiser),
  * `assets/editor.css` – canvas rules for the block editor.
* **Gutenberg = content**: pages are native blocks (Group sections, Heading, Paragraph, List, Buttons, Details) with small Custom HTML islands only for icons, forms, SVG charts and mock UIs. Classic Editor deactivated.
* Components: one base class + explicit modifiers (`gss-card`, `gss-card--gold`, `gss-btn--ghost`…). 300 classes, each defined once; see `docs/components.md` and /design-system/.
* Icon sprite `assets/icons.svg` (52 named symbols); pages reference `<use href=…#name>`.
* Templates: `page.php` canvas (content owns the single H1; template prints none) vs classic layout (title is the H1); `front-page.php` canvas + page directory; 11 generic widget positions (was 50); menus `primary`, `footer`.
* Patterns (Inserter → "Guaranteed SEO design system"): hero-split, card-grid, faq, pricing, steps, cta.
* Block styles registered: group card/soft, button navy, list checks/ticks, details faq, table gss.
* Theme dequeues the Toolkit critical-CSS snapshot and the core CSS of blocks it styles itself.

## 3. CSS measurements (raw / gzip, logged-out)

| | Before | After |
|---|---|---|
| Inline `<style>` per page | 67,092 / 13,157 + page block 7.9–14 KB / 2.4–3.3 KB | **9,725 / 3,078** (critical 9,590 + core img 135) |
| Truly critical CSS | 52.8 KB toolkit + 0.7 KB theme (= whole site CSS) | **9.6 KB** |
| External theme CSS | style.css 81,126 / ~17 KB, loaded twice-equivalent (inline copy + async file) | style.css **49,516 / 10,551** |
| Total CSS a first visit downloads | ~150–160 KB raw | **~59 KB raw / ~13.6 KB gz** |
| Per-page CSS | 7.9–14 KB in content | **0** |
| HTML per page | 102–144 KB | **42–63 KB** |
| DOM elements | 572–1,136 | 431–844 (≈ –20–25 %) |
| Duplicate declarations critical vs external | 100 % | 0 |

Target check: critical < 10 KB ✅ · page-specific CSS ≈ 0 ✅ · primary theme CSS < 30 KB ❌ (49.5 KB raw, 10.6 KB gz). It is ~30 % of the previous payload; the remainder is the breadth of the 11 rich page designs (mock UIs, interactive tabs, charts). Further trimming would mean simplifying designs, which the brief ruled out.

## 4. Regression results
* Layout metrics at 375/768/1024/1440: `docs/metrics/baseline-layout.tsv` vs `after-layout.tsv`. Page heights mostly within ±10 % (largest change −13 %, seo-guarantee at 375 px, from tighter spacing); **no horizontal overflow on any page at any width** (4 pages overflowed before); exactly **1 H1** on every page.
* All 12 pages validate in the block editor (0 invalid blocks).
* Content: per-page script checks by the migration agents — visible text, hrefs, ids, form names/actions/values, JSON-LD and inline scripts identical to originals (copy was transcribed by hand for seo-guarantee and case-studies; spot-checked, not machine-diffed).
* Desktop screenshots: every page compared original vs new by the migration agents; 10 of 11 re-checked by the lead after go-live/preview. Mobile verified by iframe metrics (no screenshot pass at 375 px by the lead).
* Known intentional normalisations: single brand blue #1a5fe0, unified radii/shadows/h1 size, a few column ratios (e.g. 1.25:1 instead of 1.4:1), FAQ markers unified per variant, CTA bands use one gradient + photo fade. Page-by-page notes are in the agent reports summarised in `pages/gaps/*.md`.

## 5. Remaining issues (not changed — content/plugin decisions)
1. **404 targets used by forms and links**: `/get-started/` (all lead forms on home, packages, guarantee, cases, local, footer menu), `/contact/` (guaranteed-seo, team), `/technical-seo/`, `/content-seo/`, `/ecommerce-seo/`, `/international-seo/`, `/enterprise-seo/`, `/seo-audit/`, `/guaranteed-seo-rankings/`, `/measuring-seo-results/`, `/packages/`, `/methodology/`, `/books/`. `/guarantee/` redirects to `/guaranteed-local-seo/` (likely wrong). `/case-studies/#methodology` anchor missing. Founder social links are `href="#"`.
2. Several forms have no handler (`action="#gss-proposal"`, company forms GET to `/`).
3. The `gss_case` archive is disabled from the theme (`register_post_type_args`); the proper fix belongs in `guaranteed-seo-core`.
4. Toolkit "remove query strings" + CDN: theme works around it for style.css; `icons.svg` changes need a new file name or CDN purge.
5. Primary CSS above the 30 KB target (see §3).
6. Page directory heading on the home page is a translatable theme string (filter `gf_page_directory_heading`).
7. Temporary plugin "GSS Staging Preview" is **deactivated** but still installed — delete it in Plugins when you're happy (it also left a hidden `_gss_next_content` meta on 12 pages; harmless).
8. Auto-draft pages created by editor validation are cleaned up by WordPress automatically.
9. Old theme `guaranteed-flex` and the companion homepage installer remain installed (inactive) for rollback. Old page versions are in each page's Revisions; full UpdraftPlus backup taken before the work.
10. Incident: on activation the preview plugin caused a fatal error for ~2–3 minutes (self-referencing theme-mods filter once `gss` was the active theme); fixed by deactivating it.

## 6. Building future landing pages (no new CSS)
1. Create a page → insert patterns from "Guaranteed SEO design system", or copy sections from /design-system/ / an existing page.
2. Exactly one H1 (in the hero). Sections are Group blocks `gss-section …` > `gss-wrap`.
3. Use only classes from `docs/components.md`; icons from the sprite; no `<style>`, no `style=""` except data custom properties (`--w`, `--h`, `--c`, `--x`, `--y`, `--gss-cover`, `--gss-media`).
4. Only a genuinely new visual justifies a theme change: extend an existing component first, else add a generic `gss-` component in `theme/src/gss.css`, run `node tools/build-css.mjs`, add it to /design-system/, verify at 375/768/1024/1440, deploy `style.css` + `assets/critical.css`.

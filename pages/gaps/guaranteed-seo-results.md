# Gaps: guaranteed-seo-results

## Proposed classes

### `gss-tag--orange` (tag modifier)
For the "Low–Medium" reliability pills. The original used an orange pill (#ffedd5 / #c2410c), sitting between amber ("Medium") and red. None of the existing tag colours is orange.
```css
.gss-tag--orange{background:#ffedd5;color:#c2410c}
```
Used in: the "Which SEO Metrics Are Reliable?" table (rows "Average position" and "Revenue attribution").

## System issues found (no page CSS written; these need a fix in gss.css or in the deployment)

1. **`gss-flow` never applies to text blocks inside `.gss-page`.** `.gss-page :where(h1,h2,h3,h4,p,ul,ol,…){margin:0}` has specificity (0,1,0). `:where(.gss-flow,.gss-card,.gss-head__main,.gss-hero__copy,.gss-cta__copy)>*+*` has (0,0,0), so it always loses. As a result an h2 and the paragraph after it touch, and so do a card h3 and the list after it (the originals had a 10–12px gap). Proposed fix: raise the flow rule to the reset's specificity and keep it after the reset in the file:
   ```css
   .gss-page :where(.gss-flow,.gss-card,.gss-head__main,.gss-hero__copy,.gss-cta__copy)>*+*{margin-top:var(--gss-flow,10px)}
   ```
   This still loses to component overrides such as `.gss-card h3+p` (0,2,1), `.gss-hero__copy>.gss-lead` (0,2,0) and `gss-mt*` (!important). I did not add per-element utilities to work around it. The one exception is `gss-mt-sm` on the FAQ grid.
2. **`a.gss-btn` inside `.gss-page` shows ink-coloured text.** `.gss-page a{color:inherit}` (0,1,1) beats `.gss-btn{color:#fff}` (0,1,0). I avoided `<a class="gss-btn">` and used core/button, which is styled through `.gss-page .wp-block-button__link`. Proposed fix: `.gss-page a.gss-btn{color:#fff}`, plus the same for the `--ghost`/`--light` colours.
3. **The deployed theme is behind the repo build.** Live `/wp-content/themes/gss/style.css` has no `.gss-stats--2/--3/--6` or `.gss-steps--3/--5` (none of the `--gss-cols:N` rules). The repo build `theme/gss/style.css` does have them. This page uses `gss-stats gss-stats--2`, so it shows 4 squeezed KPI columns until the theme is redeployed.
4. **A legacy inline stylesheet `#seooptimierung-critical-css-inline-css` (~52 KB) is still printed on `?gss_preview=1`.** It redefines `.gss-btn{background:var(--b);border:0…}`. `--b` is undefined in the new theme, so every `button.gss-btn` (the hero form submit and the CTA submit) renders transparent, as white text on white. It loads after the theme's critical CSS. It needs to be disabled or regenerated for the migrated pages.
5. (FYI) `.gss-bar>i` in gss.css never reads `--w`, so the documented `<i style="--w:62%">` has no effect. Not used on this page.

## JS hooks kept
- No legacy class names are queried by this page's scripts. The inline hand-off script uses `form[data-gsr-handoff]`, `input[type=url]`, `#gsr-funnel` and `[name=target_url]` (inside the funnel). All of these are kept: `data-gsr-handoff` sits on both forms, both inputs stay `type="url" name="website"`, and `id="gsr-funnel"` is the group anchor of the funnel section.
- `form.js` (plugin) is kept verbatim inside the funnel section. It only queries `[data-gss-*]` hooks inside the shortcode output.

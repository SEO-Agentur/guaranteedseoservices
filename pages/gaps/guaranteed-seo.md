# Gaps: guaranteed-seo

Migrated file: `pages/migrated/guaranteed-seo.html` (legacy `.gsg` mini-system removed).
All proposals are generic, append them at the end of gss.css (after section 8 / 16 / 10 respectively is fine, but they must come
*after* `.gss-card--green/--red` and `.gss-card--accent` so the border colours win).

## 1. Card accent colour modifiers (control matrix, 4 coloured columns)
`gss-card--accent` only takes its colour from `--gss-accent`, which needs an inline style. Named modifiers instead:

```css
.gss-card--accent-green{--gss-accent:var(--gss-green)}
.gss-card--accent-blue{--gss-accent:var(--gss-blue)}
.gss-card--accent-amber{--gss-accent:var(--gss-amber)}
.gss-card--accent-red{--gss-accent:var(--gss-red)}
/* whole 1px outline in the accent colour too (original matrix cards: 4px top + 1px coloured frame) */
.gss-card--accent-frame{border-color:var(--gss-accent,var(--gss-blue))}
```
Used: matrix cards, e.g. `gss-card gss-card--green gss-card--accent gss-card--accent-green gss-card--accent-frame`.

## 2. Tinted card surfaces missing from the set (blue, amber)
`--green` / `--red` exist; the matrix uses blue and amber tints, and the "Key Takeaway" card is amber.
```css
.gss-card--blue{background:var(--gss-blue-25);border-color:#d6e3fb;box-shadow:none}
.gss-card--amber{background:#fffaf0;border-color:#f6e3b8;box-shadow:none}
```
Used: matrix column 2 (blue), column 3 (amber), worked-example "Key Takeaway" card (amber).

## 3. `gss-scale` labelled scale (label · bar · label [· bar · label])
Used for "High Provider Control ↔ Low Provider Control" under the matrix and the 3-step risk spectrum
(Ethical / Grey-Hat / Black-Hat). Reusable for any "low ↔ high" legend.
```css
.gss-scale{display:grid;grid-template-columns:auto minmax(0,1fr) auto;gap:16px;align-items:center;font-size:13px}
.gss-scale--5{grid-template-columns:auto minmax(0,1fr) auto minmax(0,1fr) auto;gap:12px}
.gss-scale b{display:block}
.gss-scale span{display:block;color:var(--gss-muted)}
.gss-scale--5 span{font-size:12px}
:where(.gss-scale:not(.gss-scale--5))>:last-child{text-align:right}
.gss-scale .gss-bar{margin-top:0}
.gss-scale--5 .gss-bar{height:4px}
@media(max-width:640px){.gss-scale,.gss-scale--5{grid-template-columns:minmax(0,1fr)}.gss-scale>:last-child{text-align:left}.gss-scale--5 .gss-bar{display:none}}
```
Markup: `<div class="gss-scale"><div><b>…</b><span>…</span></div><div class="gss-bar gss-bar--grad"></div><div>…</div></div>`.

## 4. Two-stop gradient bars for risk segments
```css
.gss-bar--safe-warn{background:linear-gradient(90deg,var(--gss-green),var(--gss-amber))}
.gss-bar--warn-danger{background:linear-gradient(90deg,var(--gss-amber),var(--gss-red))}
```
Used: the two connectors in the risk spectrum (originally inline `style="background:linear-gradient(...)"`).

## 5. Amber/caution text colour (sibling of `gss-up` / `gss-down`)
```css
.gss-caution{color:#b45309;font-weight:700}
```
Used: "Grey-Hat Tactics" label (Ethical uses `gss-up`, Black-Hat uses `gss-down`).

## 6. Boxed link list
"Further Reading" shows each link as its own bordered box, not as rule-separated rows.
```css
.gss-linklist--boxed{gap:10px}
.gss-linklist--boxed a,.gss-linklist--boxed li:first-child a,.gss-linklist--boxed>a:first-child{display:block;border:1px solid var(--gss-line);border-radius:10px;padding:12px 14px;background:#fff}
```
Markup: `<div class="gss-linklist gss-linklist--boxed"><a href="…"><b>Title</b><span>Text</span></a>…</div>`.

## System-level issues found while checking (not page-specific, please fix centrally)
* **Flow rhythm never applies to headings/paragraphs.** `.gss-page :where(h1,h2,h3,h4,p,…){margin:0}` has specificity (0,1,0)
  and beats `:where(.gss-flow,.gss-card,.gss-head__main,.gss-hero__copy,.gss-cta__copy)>*+*{margin-top:…}` (0,0,0).
  Result: h2 → p, p → p inside cards/heads/flow groups have 0 gap (measured `margin-top:0px`). Suggested fix:
  `:where(.gss-page) :where(h1,h2,h3,h4,p,ul,ol,blockquote,figure,table,details,form){margin:0}`.
* **Legacy inline critical CSS on the preview** (`<style id="seooptimierung-critical-css-inline-css">`, 411 rules) still
  contains old `gss-*` rules, e.g. `.gss-btn{background:var(--b)}` (undefined var → transparent buttons, white text on light bg),
  `.gss-card{border:1px solid var(--ln)}`, `.gss-chip`, `.gss-crumb`. All `gss-btn` buttons on this page render invisible
  until that critical CSS is regenerated/removed for the new theme.

* **`.gss-page a{color:inherit}` (0,1,1) beats `.gss-btn{color:#fff}` (0,1,0)**: raw-HTML `a.gss-btn` links get ink-coloured text
  on a blue background. On this page the two standalone link buttons were therefore built as core/buttons (which win via
  `.gss-page .wp-block-button__link`); `<button class="gss-btn">` in the forms is unaffected. Suggested fix:
  `:where(.gss-page) a{text-decoration:none;color:inherit}`.

## JS hooks kept
None (page has no scripts besides JSON-LD, no plugin hooks).

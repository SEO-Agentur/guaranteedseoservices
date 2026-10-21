# Gaps: guaranteed-local-seo

Everything else on the page is built from existing gss.css classes. The classes below are new. Each one is generic and reusable, and its CSS is given exactly. I tested all of them by injecting them into the `?gss_preview=1` page.

## 1. `gss-grid--halves`: two equal columns on wide screens only
Several content-heavy paired sections (each half holds its own inner grid, mock or chart) were 1:1 above 1080px and stacked below. `gss-grid--2` already splits at 561px, which crushes the inner grids, and `--split` is 1.25:1.
```css
@media(min-width:1101px){.gss-grid--halves{grid-template-columns:repeat(2,minmax(0,1fr))}}
```
Used in: #how-it-works, the GBP/structure section, #metrics.

## 2. `gss-stats--compact`: small icon + label tiles (4-up, 2-up on mobile)
These are the hero's four feature tiles. With the standard `gss-stat--row` padding the label "optimization" overflows the tile.
```css
.gss-stats--compact{gap:8px}
.gss-stats--compact .gss-stat{padding:8px 6px;gap:6px;font-size:11.5px;font-weight:600;line-height:1.25;color:var(--gss-ink)}
.gss-stats--compact .gss-ico{width:30px;height:30px;border-radius:8px}
.gss-stats--compact .gss-ico .gss-icon{width:17px;height:17px}
```
Used in: hero (`div.gss-stats.gss-stats--compact > div.gss-stat.gss-stat--row > span.gss-ico.gss-ico--sm + text`).

## 3. `gss-glogo`: multicolour "Google" wordmark in SERP mocks
The original used inline `style="color:…"` on each letter, which is no longer allowed.
```css
.gss-glogo{font-size:18px;line-height:1;letter-spacing:-.02em}
.gss-glogo i{font-style:normal;color:#4285f4}
.gss-glogo i:nth-child(2),.gss-glogo i:nth-child(6){color:#ea4335}
.gss-glogo i:nth-child(3){color:#fbbc05}
.gss-glogo i:nth-child(5){color:#34a853}
```
Used in: hero SERP mock, `<b class="gss-glogo"><i>G</i><i>o</i><i>o</i><i>g</i><i>l</i><i>e</i></b>` inside `.gss-searchbar`.

## 4. `gss-stars`: review star colour
```css
.gss-stars{color:#e6a700;letter-spacing:1px}
```
Used in: SERP results and the Business Profile mock.

## 5. `gss-score--blue`: blue variant of the numbered score circle
The ranking-factor list alternates green, blue, amber, orange and red number circles. The olive tone (#8a7a2e) was normalised to `--3` (amber).
```css
.gss-score--blue{background:var(--gss-blue)}
```
Used in: #how-it-works factor list (items 2 and 4).

## 6. `gss-overlay`: floating key/label box over a visual
This is the map legend card. It sits top-right over the map on desktop and drops below the map on mobile. The parent `.gss-card` is already `position:relative`.
```css
.gss-overlay{position:absolute;top:24px;right:24px;z-index:1;background:#fff;border-radius:10px;box-shadow:0 6px 20px rgba(15,35,56,.12);padding:12px 14px;font-size:12px;color:var(--gss-ink)}
.gss-overlay>b{display:block;font-size:14px;margin-bottom:6px}
@media(max-width:720px){.gss-overlay{position:static;margin-top:10px;box-shadow:none;border:1px solid var(--gss-line)}}
```
Used in: "Local Search Potential in Mellieħa" map card (`div.gss-overlay > b + div.gss-legend.gss-legend--stack`).

## 7. `gss-photos`: two-up photo placeholders in a profile mock
```css
.gss-photos{display:grid;grid-template-columns:1fr 1fr;gap:6px}
.gss-photos>*{aspect-ratio:4/3;border-radius:8px;background:linear-gradient(135deg,#c9d8e8,#7d96b3)}
.gss-photos>*+*{background:linear-gradient(135deg,#e6dccd,#a89275)}
```
Used in: Google Business Profile mock (#gbp).

## Theme-level findings (not page CSS; they affect every migrated page)
1. **The plugin inline CSS `seooptimierung-critical-css-inline-css` (52 KB, printed in `<head>`) is active on the `gss_preview` render and clashes with gss.css.**
   - It defines old-namespace `.gss-btn{background:var(--b)}`, which leaves `--b` undefined. The result is transparent buttons: the hero button and form submit were invisible in the preview.
   - It also redefines `.gss-card`, `.gss-chip`, `.gss-hero`, `.gss-lead`, `.gss-chips` and `.gss-bars`.
   - It contains the old global-styles rule `:root :where(.is-layout-flow)>*{margin-block:24px 0}`, which adds 24px between every child of every group block.
   - I compared visuals with that stylesheet disabled in my tab. It needs to be disabled or regenerated for gss-theme pages before go-live.
2. Inside `.gss-page`, `a.gss-btn` gets `color:inherit` from `.gss-page a` (0,1,1), which beats `.gss-btn{color:#fff}` (0,1,0). Anchor buttons therefore show dark text on blue. Here I used core/button for the hero CTA instead, which the existing `.gss-page .wp-block-button__link` rule covers. Suggested fix:
   ```css
   .gss-page a.gss-btn,.gss-page a.gss-btn:hover{color:#fff}
   .gss-page a.gss-btn--ghost,.gss-page a.gss-btn--ghost:hover{color:var(--gss-ink)}
   .gss-page a.gss-btn--light,.gss-page a.gss-btn--light:hover{color:var(--gss-navy)}
   ```
3. `:where(.gss-flow,.gss-card,…)>*+*` (specificity 0) loses to the reset `.gss-page :where(h1,…,p,ul,…){margin:0}` (0,1,0). The flow rhythm therefore never applies to headings, paragraphs or lists, only to divs. I used `gss-mt` / `gss-mt-sm` where spacing mattered.

## JS hooks kept
None. The page has no script apart from the FAQPage JSON-LD.

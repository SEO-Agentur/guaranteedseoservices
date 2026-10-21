# Gaps: guaranteed-seo-company

Everything else on the page is built from existing gss.css classes. The classes below are new. Each one is generic and its CSS is given exactly. I tested all of them by injecting them into the `?gss_preview=1` page.

## 1. `gss-stagger`: diagonally offset stack of floating pills (decorative)
This is the hero's four step pills. It is hidden on small screens, as in the original (hidden ≤860px; here ≤800px to match the grid breakpoints).
```css
.gss-stagger{display:flex;flex-direction:column;align-items:flex-start;gap:10px}
.gss-stagger>:nth-child(2){margin-left:24px}
.gss-stagger>:nth-child(3){margin-left:48px}
.gss-stagger>:nth-child(4){margin-left:72px}
.gss-stagger>:nth-child(5){margin-left:96px}
@media(max-width:800px){.gss-stagger{display:none}}
```
Used in: hero, `div.gss-stagger[aria-hidden] > span.gss-chip.gss-chip--pill > i.gss-dot-num > svg.gss-icon(check)`.

## 2. `.gss-dot-num .gss-icon`: icon inside the number dot inherits white
Without this, `.gss-chip .gss-icon{color:blue}` paints the check blue on the blue dot, so it disappears.
```css
.gss-dot-num .gss-icon{color:inherit;width:14px;height:14px}
```
Used in: hero step pills.

## 3. `.gss-table .is-center`: centre a single column (state class, like `is-hl`)
`gss-table--center` would also centre the "Weight" and "What to look for" columns.
```css
.gss-table :is(th,td).is-center{text-align:center}
```
Used in: scorecard table (Provider A/B/C columns).

## 4. Fix for the `gss-bar` fill width
components.md documents `<i style="--w:70%">`, but gss.css never reads `--w`, so every bar renders 100% full.
```css
.gss-bar>i{width:var(--w,100%)}
```
Used in: scorecard total row.

## 5. `gss-meter` (+`--green --amber --red`): coloured value with a matching bar beneath
The original used inline `style="color:var(--green)"` on the cell and `style="background:…"` on the fill.
```css
.gss-meter{display:block;font-weight:800;color:var(--gss-meter,var(--gss-blue))}
.gss-meter .gss-bar>i{background:var(--gss-meter,var(--gss-blue))}
.gss-meter--green{--gss-meter:var(--gss-green)}
.gss-meter--amber{--gss-meter:var(--gss-amber)}
.gss-meter--red{--gss-meter:var(--gss-red)}
```
Used in: `tr.is-total` of the scorecard: `<span class="gss-meter gss-meter--green">73%<span class="gss-bar"><i style="--w:73%"></i></span></span>`.

## 6. `gss-faq__more`: borderless "show all" disclosure that wraps more accordion items
```css
.gss-faq details.gss-faq__more{background:none;border:0;padding:0}
.gss-faq__more>summary{padding:8px 30px 8px 0;color:var(--gss-blue);font-size:14px}
.gss-faq--chevron .gss-faq__more>summary::after{border-color:var(--gss-blue)}
.gss-faq details.gss-faq__more>.gss-faq{padding:6px 0 0;color:inherit;font-size:inherit}
```
Used in: "15 Questions" accordion, `details.gss-faq__more > summary + div.gss-faq.gss-faq--numbered.gss-faq--chevron` holding questions 6–15.

## Theme-level findings (affect every migrated page)
1. **The plugin inline CSS `seooptimierung-critical-css-inline-css` clashes with gss.css.**
   - It defines old `.gss-btn{background:var(--b)}`, which makes buttons transparent, and redefines `.gss-card`, `.gss-chip`, `.gss-hero` and more.
   - It adds `:root :where(.is-layout-flow)>*{margin-block:24px 0}` to every group child.
   - It must be disabled or regenerated for gss-theme pages. I compared with it disabled.
2. `a.gss-btn` inside `.gss-page` inherits the text colour, because `.gss-page a{color:inherit}` beats `.gss-btn{color:#fff}`. This page's hero CTA is `<a class="gss-btn">` (inline next to a text link, as in the original), so it needs this fix:
   ```css
   .gss-page a.gss-btn,.gss-page a.gss-btn:hover{color:#fff}
   .gss-page a.gss-btn--ghost,.gss-page a.gss-btn--ghost:hover{color:var(--gss-ink)}
   .gss-page a.gss-btn--light,.gss-page a.gss-btn--light:hover{color:var(--gss-navy)}
   ```
3. The zero-specificity `gss-flow` / card rhythm loses to the `.gss-page :where(h1,…,p,ul){margin:0}` reset. I used `gss-mt-sm` / `gss-mt` where spacing mattered.

## JS hooks kept
None. The only script is the FAQPage JSON-LD. The inline `onclick="window.print();return false;"` on "Download as PDF →" is kept verbatim inside a core/html block.

# Gaps: m-e-e-a-t-our-team

Migrated file: `pages/migrated/m-e-e-a-t-our-team.html` (legacy `.gsa` mini-system removed).
Append after the existing rules they extend (hero §11, cards §8, steps §14, people §18, layout §4).

## 1. Hero whose visual sits on the bottom edge
Founder portraits are flush with the bottom of the hero (original: `padding:80px 0 0`, copy column `padding-bottom:80px`).
```css
.gss-hero--flush-bottom{padding-bottom:0}
.gss-hero--flush-bottom .gss-hero__copy{padding-bottom:clamp(40px,6vw,80px)}
```
Used: hero section (`gss-hero gss-hero--mountains gss-hero--flush-bottom`, grid `gss-grid--end`).

## 2. Striped "office" placeholder
Original office image placeholder had vertical window mullions over the gradient.
```css
.gss-placeholder--stripes{min-height:340px}
.gss-placeholder--stripes::before{content:"";position:absolute;inset:0;background:repeating-linear-gradient(90deg,transparent 0 120px,rgba(10,35,80,.55) 120px 128px)}
```
Used: `#approach` section, left column.

## 3. Role / job-title line
Blue 600 13px line under a person's name (founder cards). `.gss-author span` and `.gss-card p` would otherwise override it.
```css
.gss-role,.gss-author .gss-role,.gss-card .gss-role{display:block;color:var(--gss-blue);font-weight:600;font-size:13px;margin-bottom:8px}
```
Used: founder cards (`<div class="gss-card gss-author">` + `gss-avatar--lg`).

## 4. Book cover ink colour + book item
Two covers have dark/orange title text (light cover, black cover). Colour is data, so it is passed as a custom property next to `--gss-cover`
(`style="--gss-cover:linear-gradient(160deg,#f2f2ef,#d9d9d4);--gss-cover-ink:#111"`). `overflow-wrap` stops "Conversionzauber" overflowing narrow covers.
```css
.gss-cover{color:var(--gss-cover-ink,#fff);overflow-wrap:anywhere}   /* replaces color:#fff in the existing rule */
.gss-book b{display:block;font-size:13.5px;margin-top:10px}
.gss-book small{display:block;color:var(--gss-muted);font-size:12px}
```
Note: the original covers are two-stop gradients, kept verbatim as the `--gss-cover` value (still a data custom property, not a style).
If the lead prefers flat hex only, use the first stop of each gradient.

## 5. Card media strip + body (case-study cards)
`gss-card--flush` cards with a 110px colour/image strip on top and padded body. Strip colour is data via `--gss-media`
(original was inline `style="background:linear-gradient(...)"`); later this can hold a real image.
```css
.gss-card__media{height:110px;background:var(--gss-media,linear-gradient(135deg,#9fb6d6,#e7eef8))}
.gss-card__body{padding:14px}
.gss-card__body h3{font-size:15px;margin-bottom:4px}
.gss-card__body p{font-size:13px;margin-bottom:8px}
```
Used: "Selected case studies" cards (`<div class="gss-card gss-card--flush"><div class="gss-card__media" style="--gss-media:…"></div><div class="gss-card__body">…`).

## 6. Vertical step + blue ring number
"How we work": number ring on top, title and text below (no connecting line, so `gss-steps--timeline` does not fit).
```css
.gss-step--col{flex-direction:column;gap:10px}
.gss-step__num--blue{border:2px solid var(--gss-blue);color:var(--gss-blue)}
```
Used: the four steps (`div.gss-step.gss-step--col` inside `gss-grid gss-grid--4 gss-grid--2-xs`).

## 7. Keep 2 columns on phones
The original keeps books, tools, case cards and steps in 2 columns below 600px; every `gss-grid--n` drops to 1 column below 561px
(the books section alone grew from 1345px to 3001px tall at 375px wide).
```css
@media(max-width:560px){.gss-grid--2-xs{grid-template-columns:repeat(2,minmax(0,1fr))}}
```
Used: books, tools, case studies, steps grids.

## System-level issues (also listed in guaranteed-seo.md; fix centrally)
* `.gss-page a{color:inherit}` (0,1,1) beats `.gss-btn{color:#fff}` / `.gss-btn--light{color:navy}` (0,1,0): every **raw-HTML** `a.gss-btn`
  gets ink text (dark on blue / navy) and `gss-btn--light` on the dark guarantee band renders **white on white**. Native core/button
  links are fine (`.gss-page .wp-block-button__link` wins), so standalone buttons on this page use core/buttons; the hero row
  (button + text link) and the dark band pair (light + line) stay raw `a.gss-btn` and need the fix. Suggested:
  `:where(.gss-page) a{text-decoration:none;color:inherit}`.
* Flow rhythm (`gss-flow`, card, head) does not apply to h2/p because the `.gss-page :where(h1…p…){margin:0}` reset has higher specificity.
* Inline critical CSS `#seooptimierung-critical-css-inline-css` still ships old `gss-*` rules (`.gss-btn{background:var(--b)}` → transparent buttons).

## JS hooks kept
None (no scripts besides JSON-LD, no plugin hooks).

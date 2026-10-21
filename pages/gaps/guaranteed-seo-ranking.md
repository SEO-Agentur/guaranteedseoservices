# Gaps: guaranteed-seo-ranking

## Proposed classes

### `gss-grid--8` (grid modifier)
For the 8-tile "SERP opportunity diagnostic" row. The original had 8 columns on desktop, 4 at tablet and 2 on mobile. `gss-grid--6` is the widest grid that exists.
```css
.gss-grid--8{grid-template-columns:repeat(2,minmax(0,1fr))}
@media(min-width:801px){.gss-grid--8{grid-template-columns:repeat(4,minmax(0,1fr))}}
@media(min-width:1101px){.gss-grid--8{grid-template-columns:repeat(8,minmax(0,1fr))}}
```
Used in: section `#gsr-diagnostic`.

### `gss-skel` width as a data value (change to an existing class)
Skeleton lines in mock UIs have varying lengths, which the original set with `style="width:80%"`. Proposal: let the length come in as a custom property, the same pattern as `gss-bar`.
```css
.gss-skel{width:var(--w,100%)}
```
Used in: the "best running shoes" SERP mock, as `<span class="gss-skel" style="--w:80%">`.

### `gss-thumb--muted`, `gss-thumb--faint` (thumb modifiers)
The hero SERP mock fades the competitor thumbnails. The original used inline `opacity:.6` and `opacity:.4`.
```css
.gss-thumb--muted{opacity:.6}.gss-thumb--faint{opacity:.4}
```
Used in: hero SERP mock, results #2 and #3.

## Composed (no new CSS)
- Red and green eyebrows ("Red flags…", "Signs of a trustworthy approach") use `gss-eyebrow gss-down` and `gss-eyebrow gss-up`. If you prefer explicit modifiers, add `.gss-eyebrow--red{color:var(--gss-red)}` and `.gss-eyebrow--green{color:var(--gss-green)}`.
- The SERP-feature legend (colour dot + bold title + text) uses `ul.gss-features`, where each `li` holds `<span class="gss-legend"><span style="--c:#…"></span></span>` and a `div` with the `b` and the text.
- The "Featured Snippet" row in the mock is `gss-card--row`. The thumbnail is wrapped in `gss-ico gss-ico--plain` so that it does not get `flex:1`.

## System issues found (same as guaranteed-seo-results.md, they affect this page too)
1. `gss-flow` loses to `.gss-page :where(...){margin:0}`, so heading→paragraph→list gaps collapse. Proposed fix: `.gss-page :where(.gss-flow,.gss-card,.gss-head__main,.gss-hero__copy,.gss-cta__copy)>*+*{margin-top:var(--gss-flow,10px)}`.
2. `.gss-page a{color:inherit}` overrides `.gss-btn` text colour on `<a>`. All link buttons here are core/button for that reason.
3. The deployed `style.css` is behind the repo build (the `--gss-cols` modifiers are missing). This page does not depend on them.
4. Legacy inline `#seooptimierung-critical-css-inline-css` redefines `.gss-btn` with `background:var(--b)`, so the hero form submit and the CTA submit render transparent on `?gss_preview=1`. It must be removed or regenerated.

## JS hooks kept
- No legacy class names are queried. The hand-off script needs `form[data-gsr-handoff]` (both forms keep it), `input[type=url]` (kept, `name="website"`), `#gsr-funnel` (group anchor on the funnel section) and `[name=target_url]` (inside the `[gss_get_started]` output).
- `form.js` is kept verbatim. Anchors `#gsr-analysis` (hero form card) and `#gsr-diagnostic` (section anchor) are kept for in-page links.

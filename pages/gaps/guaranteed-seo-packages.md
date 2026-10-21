# Gaps: guaranteed-seo-packages

All rules are generic and use only gss tokens. Append them to `theme/src/gss.css` after section 21, so that same-specificity overrides win.
`gss-h2--sm` is shared with the home gaps file; merge it once.

## 1. `gss-tabs--deep`: tabs whose nav and panels sit deeper than the root
The existing tabs need `.gss-tabs__nav` and `.gss-tabs__panel` to be direct children of `.gss-tabs`. These layouts need otherwise:
* the compare toggle sits inside a `gss-head`, next to the h2;
* in the quiz, the options and the recommendation sit in two columns of a `gss-grid`.

With `--deep`, the radios stay direct children of the root. The labels can live in any `.gss-tabs__nav`, and the panels in any `.gss-tabs__panels` container.
The chosen-label colours are read from custom properties so that nav variants can restyle them (see 2).
Used in: `#compare` (`gss-tabs gss-tabs--dim gss-tabs--deep`) and the quiz ("Which Package Fits Your Business?").
```css
.gss-tabs--deep:has(>input:nth-of-type(1):checked) .gss-tabs__nav label:nth-of-type(1),.gss-tabs--deep:has(>input:nth-of-type(2):checked) .gss-tabs__nav label:nth-of-type(2),.gss-tabs--deep:has(>input:nth-of-type(3):checked) .gss-tabs__nav label:nth-of-type(3),.gss-tabs--deep:has(>input:nth-of-type(4):checked) .gss-tabs__nav label:nth-of-type(4),.gss-tabs--deep:has(>input:nth-of-type(5):checked) .gss-tabs__nav label:nth-of-type(5),.gss-tabs--deep:has(>input:nth-of-type(6):checked) .gss-tabs__nav label:nth-of-type(6){background:var(--gss-tab-on-bg,var(--gss-navy));border-color:var(--gss-tab-on-bd,var(--gss-navy));color:var(--gss-tab-on-fg,#fff);--gss-tab-dot:var(--gss-blue)}
.gss-tabs--deep:has(>input:nth-of-type(1):checked) .gss-tabs__panels>.gss-tabs__panel:nth-of-type(1),.gss-tabs--deep:has(>input:nth-of-type(2):checked) .gss-tabs__panels>.gss-tabs__panel:nth-of-type(2),.gss-tabs--deep:has(>input:nth-of-type(3):checked) .gss-tabs__panels>.gss-tabs__panel:nth-of-type(3),.gss-tabs--deep:has(>input:nth-of-type(4):checked) .gss-tabs__panels>.gss-tabs__panel:nth-of-type(4),.gss-tabs--deep:has(>input:nth-of-type(5):checked) .gss-tabs__panels>.gss-tabs__panel:nth-of-type(5),.gss-tabs--deep:has(>input:nth-of-type(6):checked) .gss-tabs__panels>.gss-tabs__panel:nth-of-type(6){display:block}
.gss-head .gss-tabs__nav{margin-bottom:0}
```
Note: do not nest a second tab set inside a `--deep` one. The descendant selectors would also match the inner set.

## 2. `gss-tabs__nav--stack`: vertical radio-style option list
Used in: the quiz options ("I want to test the approach" …). Each option is a white row with a radio circle; the chosen one gets a blue border and a filled circle. This replaces the legacy `.gfm-quiz .opts`.
```css
.gss-tabs__nav--stack{display:grid;gap:8px;max-width:440px;margin:18px 0 0;padding:0;border:0;border-radius:0;background:none;--gss-tab-on-bg:#fff;--gss-tab-on-bd:var(--gss-blue);--gss-tab-on-fg:var(--gss-ink)}
.gss-tabs__nav--stack label{display:flex;align-items:center;gap:14px;background:#fff;border:1px solid var(--gss-line);border-radius:var(--gss-r-sm);padding:10px 14px;font-size:14px;font-weight:500;transition:border-color .15s}
.gss-tabs__nav--stack label:hover{border-color:#b9c9de}
.gss-tabs__nav--stack label::before{content:"";flex:0 0 20px;height:20px;border-radius:50%;border:2px solid var(--gss-tab-dot,#b9c6d4);background:var(--gss-tab-dot,#fff);box-shadow:inset 0 0 0 3px #fff}
```

## 3. `gss-photo-fade` (+ `--wide`): the site photo fading in on the right of a section or card
Used in:
* the quiz section (`gss-section gss-section--sky gss-photo-fade`; legacy `.gfm-quiz .gfm-wrap::after`, 30% wide, opacity .35);
* the "Package evidence" card (`gss-card gss-card--sky gss-photo-fade gss-photo-fade--wide`; legacy `.gfm-evid`, 40% wide, opacity .45, text kept clear with padding-right 38%).
```css
.gss-photo-fade{position:relative;overflow:hidden}
.gss-photo-fade::after{content:"";position:absolute;right:0;top:0;bottom:0;width:30%;background:var(--gss-hero-img) center/cover no-repeat;opacity:.35;-webkit-mask-image:linear-gradient(90deg,transparent,#000 60%);mask-image:linear-gradient(90deg,transparent,#000 60%);pointer-events:none}
.gss-photo-fade--wide{padding-right:38%}.gss-photo-fade--wide::after{width:40%;opacity:.45}
@media(max-width:680px){.gss-photo-fade::after{display:none}.gss-photo-fade--wide{padding-right:clamp(18px,2vw,24px)}}
```

## 4. `gss-pip`: small status dot for schedules and matrices
Used in: the "Execution cadence · illustrative schedule" table. `<i class="gss-pip">` is an active push and `gss-pip--off` an inactive one; the colour can be overridden with `--c`.
```css
.gss-pip{display:inline-block;width:10px;height:10px;border-radius:50%;background:var(--c,var(--gss-blue));margin:0 2px;vertical-align:middle}
.gss-pip--off{background:#cfd8e3}
```

## 5. `gss-h2--sm`: compact title (shared with home)
Used in: the guarantee band h2 (21px originally); the quiz recommendation h3 (22px); the "Package evidence" h3 (22px); "Build the right plan around your package." (an h2 styled at 20px originally).
```css
.gss-page .gss-h2--sm,.gss-h2--sm{font-size:clamp(21px,2.2vw,24px);line-height:1.2}
```

## 6. Card text colour reset (fix to an existing component)
A white `gss-card` inside a dark band (`gss-cta`, `gss-section--dark`) inherits white text. This affects the package picker form in `#choose`: its radio labels were white on white.
```css
.gss-card{color:var(--gss-ink)}   /* add to the base .gss-card rule; .gss-card--dark already sets #fff later */
```
Also note: `.gss-cta :is(p,.gss-sub)` recolours every `<p>` inside a white card in a CTA band. For that reason the picker uses a `div.gss-fine` for "Your selected package".

## 7. System fixes shared with home (see `pages/gaps/home.md` §5)
* a) the flow reset's specificity;
* b) `a.gss-btn` colours;
* c) the `seooptimierung-critical-css-inline-css` plugin stylesheet, which overrides `.gss-btn`, `.gss-card`, `.gss-chip`, `.gss-hero` and `.gss-lead`, must be dequeued.

This page depends on a), for spacing in the plan and quiz cards, the finder card and the head blocks, and on c).

## Legacy utilities
The "--- packages ---" stylesheet section has no `gfm-uN` rules. Every legacy component class mapped to an existing gss class, except the gaps above.

## JS hooks kept
None needed. The page has no script except the FAQ JSON-LD. The plugin `form.js` hooks (`[data-gss-plan-option]`, `[data-gss-price-card]`, `.gss-price-card`, `name="package_id"`) were not in the original, so none were added.
* The picker's radios (`name="package"`, ids `sel-*`, values) are kept, as are its `name="website"` URL field and its form `action="/get-started/" method="get"`.
* Every plan, quiz and CTA label still points at `sel-*`.
* The finder form keeps `method="post"` and its names `website`, `goal`, `budget` and `email`.

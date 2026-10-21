# Gaps: home (front page)

All rules are generic and use only gss tokens. Append them to `theme/src/gss.css`, after section 21 (Mock UI), so that same-specificity overrides win.

## 1. `gss-path`: illustrative path overlay (dots and labels on a hero photo)
Used in: the hero (`section.gss-hero--photo`), the "Your URL → Our SEO Pushes → Growing Rankings → More Traffic" path.
Positions are data values written as custom properties on each item (`style="--x:40.8%;--y:91%"`; the svg takes `--x` and `--w`).
This replaces the legacy `gfm-u1` … `gfm-u8` utilities.
```css
/* path overlay: decorative dots + labels positioned over a hero photo (data: --x/--y per item, --x/--w on the svg) */
.gss-path{position:absolute;inset:0;z-index:1;pointer-events:none}
.gss-path>svg{position:absolute;top:0;left:var(--x,0);width:var(--w,100%);height:100%}
.gss-path__dot,.gss-path__lbl{position:absolute;left:var(--x,0);top:var(--y,0)}
.gss-path__dot{width:16px;height:16px;border-radius:50%;background:#f3d58a;border:3px solid #fff;box-shadow:0 0 0 4px rgba(214,160,68,.35)}
.gss-path__lbl{font-size:11px;font-weight:600;line-height:1.55;color:#fff;background:rgba(15,35,56,.72);-webkit-backdrop-filter:blur(4px);backdrop-filter:blur(4px);padding:5px 9px;border-radius:6px;white-space:nowrap}
@media(max-width:1100px){.gss-path{display:none}}
```

## 2. `gss-h2--sm`: compact section title (about 24px)
Used in: the "How it works" inline header (legacy `gfm-u11`, 24px), the Results card h2 (24px) and the guarantee band h2 (28px originally).
The packages page uses it too, and also on h3 elements that need a 22–24px title.
```css
.gss-page .gss-h2--sm,.gss-h2--sm{font-size:clamp(21px,2.2vw,24px);line-height:1.2}
```

## 3. `gss-checks--cards`: each list item as a small white card
Used in: the "Why this model" list (`ul.gss-checks.gss-checks--icons.gss-checks--green.gss-checks--cards`).
The rule also makes `--green` colour the sprite icons of the `--icons` variant (today `--icons` forces navy).
```css
.gss-checks--cards{gap:12px}
.gss-checks--cards li{background:#fff;border:1px solid var(--gss-line);border-radius:var(--gss-r);padding:14px 16px;font-weight:600}
.gss-checks--icons.gss-checks--green .gss-icon{color:var(--gss-green)}
```

## 4. `gss-hero__copy--narrow`: keeps the hero copy at the original measure
In the 1fr + 400px hero, the copy column is about 760px wide. Without this modifier:
* the h1 runs to 2 lines instead of 3;
* the lead runs under the path labels (overlap).

The original page capped the h1 at 620px and the lead at 500px.
Used in: the home hero (`gss-hero__copy gss-hero__copy--narrow`).
```css
.gss-hero__copy--narrow{max-width:620px}.gss-hero__copy--narrow>.gss-lead{max-width:500px}
```

## 5. System fixes found while comparing (these affect every page, not only home)
**a) The vertical rhythm (`gss-flow`, card, head and hero/cta copy) is dead for p, h and ul elements.**
* `.gss-page :where(h1,…,form){margin:0}` has specificity (0,1,0).
* It beats the zero-specificity rule `:where(.gss-flow,.gss-card,.gss-head__main,.gss-hero__copy,.gss-cta__copy)>*+*{margin-top:…}`.
* As a result, h2 + `.gss-sub`, card h3 + hint and eyebrow → h2 → p all touch.

Fix: lower the reset's specificity so the flow rule, which is later in the file, wins.
```css
:where(.gss-page) :where(h1,h2,h3,h4,p,ul,ol,blockquote,figure,table,details,form){margin:0}  /* replaces the .gss-page :where(...) reset, in critical.css and style.css */
```
**b) `<a class="gss-btn gss-btn--light|--ghost">` loses its text colour.**
`.gss-page a{color:inherit}` (0,1,1) beats `.gss-btn--light` / `.gss-btn--ghost` (0,1,0). The home CTA "Get Started Now →" renders white on white, and "Common Questions →" turns muted.
```css
.gss-page a.gss-btn{color:#fff}.gss-page a.gss-btn--ghost{color:var(--gss-ink)}.gss-page a.gss-btn--light{color:var(--gss-navy)}
```
**c) Environment conflict (not CSS to merge).**
In `?gss_preview=1` the old toolkit plugin still inlines `<style id="seooptimierung-critical-css-inline-css">` (52 KB). It redefines these classes:
* `.gss-btn` (`background:var(--b)`, `border:0`, `color:#fff!important`);
* `.gss-card`;
* `.gss-chip`;
* `.gss-hero` (its own padding and `::after`);
* `.gss-lead`, `.gss-chips` and `.gss-bars`.

With it active, the ghost buttons and plan CTAs render white on white and the hero gains extra padding. It must be dequeued for gss-theme pages, or at cutover. All comparisons for this page were made with that stylesheet disabled in my own tab.

## How legacy utilities map
| legacy | now |
|---|---|
| `gfm-u1`–`gfm-u8` (path overlay positions) | `gss-path` + `--x/--y/--w` custom properties (gap 1) |
| `gfm-u9` (trust band padding 22px) | `gss-section--tight gss-section--line` (40px at desktop; see differences) |
| `gfm-u10` (How it works padding-top 8px) | `gss-section--top-0` |
| `gfm-u11` (h2 24px) | `gss-h2--sm` (gap 2) |
| `gfm-u12` (small ghost button) | `gss-btn gss-btn--ghost gss-btn--sm` |
| `gfm-u13` (navy tag, absolutely positioned bottom-right of the chart card) | `gss-tag gss-tag--navy`, in flow below the chart |
| `gfm-u14` (centred FAQ header) | `gss-head gss-head--center` |

## JS hooks kept
None needed. The page has no script except the FAQ JSON-LD. The plugin's `form.js` hooks (`.gss-hero-form`, `[data-gss-price-card]`, `name="package_id"` …) were not present in the original, so none were added. The hero form keeps `action="/get-started/" method="get"`, `name="website"`, `name="package"` and its radio ids `pk-*`; the plan labels still point at those ids.

# GSS design system — component API and Gutenberg recipes

Single source of truth for presentation: `theme/src/gss.css` (built into the `gss` theme).
Every class below has exactly **one** authoritative definition there. Pages contain **content only**:
no `<style>` blocks, no `style=""` attributes (exception: data values such as a chart bar
length, written as a custom property: `style="--w:72%"`), no page-scoped class prefixes.

Icons: `<svg class="gss-icon" aria-hidden="true"><use href="/wp-content/themes/gss/assets/icons.svg#NAME"/></svg>`
Names: check, arrow-right, search, search-plus, target, calendar, eye, cursor, users, chart-bars,
chart-bars-solid, chart-box, layout, trend-up, trophy, cart, globe, doc-check, doc, clipboard,
clipboard-check, shield, shield-check, shield-check-solid, briefcase, clock, mountain, arrow-up-right,
percent, mail, pin, phone, directions, home, building, franchise, code, settings, tag, link, megaphone,
headset, check-circle, info, database, list, warning, package, bolt, check-square, rocket, wallet.
Map of every legacy inline icon → name: `/wp-content/themes/gss/assets/icon-map.json`
(key = the inner markup of a 24×24 `<svg>`, whitespace collapsed to single spaces and `> <` → `><`).
Large illustrative SVGs (charts, maps, seals, shields) stay inline.

## Page skeleton
The page template renders `main.gss-page` edge to edge when the content contains an `<h1>`
(exactly one H1 per page, inside the hero). The theme never prints a second title H1.

```
section.gss-section[ --soft | --sky | --dark | --tight | --flush | --top-0 | --line ]
  div.gss-wrap[ --narrow ]
    div.gss-head  (optional section header row)
      div.gss-head__main  > p.gss-eyebrow + h2 + p.gss-sub
      p.gss-head__note | a.gss-link
    …components…
```

## Layout
| Class | Use |
|---|---|
| `gss-grid gss-grid--2/3/4/5/6` | Equal columns, collapse 1→2→n at 560/800/1100px. |
| `gss-grid--split` / `--split-r` | 1.25fr/1fr two-column (text + media/form). |
| `gss-grid--1-2`, `--2-1` | Asymmetric. |
| `gss-grid--hero` | 3-col hero (copy · visual · form); 2-col at tablet with copy full width. |
| `gss-grid--hero-form` | Copy + fixed 400px form column. |
| `gss-grid--thirds` | 1.2 / .9 / 1 bottom-of-page trio (FAQ · links · author). |
| modifiers | `--gap-lg`, `--gap-sm`, `--center`, `--start`, `--end`; child `gss-span-2`. |
| `gss-row` (+`--sm`, `--between`, `--center`) | Inline flex row (CTAs, chips, links). |
| `gss-flow` | Adds 10px vertical rhythm between children (`--gss-flow` to tune). |
| `gss-mt`, `gss-mt-sm` | Section-level top spacing (28px / 12px). |

## Typography
`gss-eyebrow` (+`--muted --gold --light --pill`), `h1` (hero only), `h2`/`gss-h2`, `h3`/`gss-h3`,
`gss-lead`, `gss-sub`, `gss-muted`, `gss-small`, `gss-fine`, `gss-num` (big number),
`gss-big` (green KPI number), `gss-up` / `gss-down` (trend text).

## Actions
`gss-btn` (primary blue) + `--navy --ghost --light --line --pill --block --sm`;
core/button: block style `is-style-navy` / `is-style-outline`.
`gss-link` (+`--ink`) for text links with arrows.

## Surfaces
`gss-card` + `--flat --soft --sky --compact --lg --flush --float --gold --amber --blue --green --red
--accent (top border, colour via --gss-accent) --hover --row (icon left) --center --stack`
(`gss-card__foot` pushes to bottom in `--stack`). `gss-ico` icon badge
(+`--round --sm --lg --white --line --plain --gold --red --tint`).
`gss-note` (+`--sky --center`) info line with icon. `gss-divider`.

## Meta
`gss-tag` (+`--green --gold --red --amber --solid --navy --caps --square --outline`),
`gss-chips` > `gss-chip` (+`--pill --plain --dark`), `gss-crumbs` (breadcrumbs: links separated by `<span>/</span>`),
`gss-pills` (skill/tool pills).

## Lists
`gss-checks` (+`--blue --green --circle --ring --x --warn --lines --icons`), `gss-ticks` (+`--light`),
`gss-features` (+`--2`) icon + bold title + text, `gss-linklist` (related links/resources),
`gss-timeline` (dated vertical timeline).

## Hero
`section.gss-hero` (+`--image` photo fades in right, `--photo` full photo with white fade (home),
`--dark` dark photo overlay, `--mountains` drawn ridge, `--fade` soft bottom glow) > `gss-wrap` >
`gss-grid …` > `div.gss-hero__copy` (crumbs, eyebrow, h1, lead, chips/ctas) + visual (`gss-hero__art`,
`gss-mock`) and/or form card.

## Forms
`form.gss-form` (labels + inputs), `gss-form__row` (number · label · field rows),
`gss-step-label` + `gss-dot-num` (+`--navy --lg --ring`), `gss-choices` (+`--2`) > `label.gss-choice`
(+`--btn`) radio cards, `form.gss-urlform` (+`--pill --joined`) inline URL capture, `.gss-funnel` (plugin).
Keep every `name`, `id`, `action`, `method`, `required`, `data-*` attribute unchanged.

## Pricing
`gss-plans` > `gss-plan` (+`--popular --sky`) with `gss-plan__ribbon`, `gss-plan__price` (`<small>/ month</small>`),
`gss-plan__name`, `gss-plan__desc`, `ul.gss-checks`, CTA, `gss-plan__foot`.

## Process
`ol.gss-steps` (4 columns by default, `--5`; styles `--timeline --arrows --stack`; `gss-step--col`) > `li.gss-step` > `span.gss-step__num` (+`--solid --sky --gold`) + div(b/h3 + text).

## Data
`gss-stats` (4 cols default, `--2 --3 --compact`) > `gss-stat` (+`--ink --row`), `gss-kpi`, `gss-table-wrap` > `table.gss-table`
(+`--wide --center --compact --upper`; row `is-total`, cell `is-hl`), `gss-score` (+`--3 --2 --1 --na`),
`gss-bar` > `<i style="--w:70%">` (+`--grad`), `gss-legend` (items set `--c`), `gss-bars`, `gss-chart`.

## Accordion
`div.gss-faq` (+`--numbered --chevron`, nested `gss-faq__more`) > core/details or `<details><summary>`.

## People / proof
`gss-quote` (+`--box`), `gss-author` (+`--center`), `gss-avatar` (+`--sm --lg --blue`),
`gss-portrait` + `gss-portrait__ini`, `gss-cover` (book cover, colour via `--gss-cover`), `gss-placeholder`.

## CTA / guarantee
`section.gss-cta` (+`--center --light`) dark gradient band with photo fade; `gss-guarantee` grid
(seal · copy · links) inside `gss-card gss-card--gold`.

## Interaction (CSS only)
Tabs: `div.gss-tabs` > N × `input.gss-sr[type=radio][name][id]` (first `checked`) +
`div.gss-tabs__nav` (+`--grid`) > N × `label[for]` + N × `div.gss-tabs__panel`.
`gss-tabs--highlight` (all panels visible inside `.gss-tabs__panels`, chosen one emphasised; mobile shows only chosen).
`gss-tabs--dim` (elements with `data-tab="1|2"` dim when the other tab is chosen).
Filter: `div.gss-filter` > `input.gss-sr[type=radio][value=all|ecommerce|local|technical|enterprise|international]`
+ `label[data-for=value][for=id]` + cards with `data-cat="local"`; `[data-hide-filtered]` hides when any filter ≠ all.

## Mock UI (illustrations)
`gss-mock` (+`--tilt --dark`), `gss-searchbar`, `gss-mock__tabs` (`.is-on`), `gss-result` (+`--hl --you`),
`gss-skel`, `gss-thumb`, `gss-seg` (`.is-on`).

## Gutenberg recipes (validated markup)
Section:
```
<!-- wp:group {"tagName":"section","className":"gss-section gss-section--soft"} -->
<section class="wp-block-group gss-section gss-section--soft"><!-- wp:group {"className":"gss-wrap"} -->
<div class="wp-block-group gss-wrap"> … </div>
<!-- /wp:group --></section>
<!-- /wp:group -->
```
Heading / paragraph / list:
```
<!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Title</h1><!-- /wp:heading -->
<!-- wp:heading {"className":"gss-h3","level":3} --><h3 class="wp-block-heading gss-h3">Card title</h3><!-- /wp:heading -->
<!-- wp:paragraph {"className":"gss-eyebrow"} --><p class="gss-eyebrow">Eyebrow</p><!-- /wp:paragraph -->
<!-- wp:list {"className":"gss-checks"} --><ul class="wp-block-list gss-checks"><!-- wp:list-item --><li>Item</li><!-- /wp:list-item --></ul><!-- /wp:list -->
```
Buttons:
```
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/x/">Label</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#y">Label</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
```
FAQ item:
```
<!-- wp:details --><details class="wp-block-details"><summary>Question?</summary><!-- wp:paragraph --><p>Answer.</p><!-- /wp:paragraph --></details><!-- /wp:details -->
```
Anything with icons, forms, inputs, SVG, scripts, tables with icons, or tight inline structure →
`<!-- wp:html -->…<!-- /wp:html -->` (raw HTML, still uses only gss-* classes).
Never put `<svg>`, `<form>`, `<input>` or `<script>` inside paragraph/heading/list blocks.

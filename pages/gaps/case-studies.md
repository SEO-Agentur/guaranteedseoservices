# Gaps: case-studies

No new classes are proposed. Everything is composed from existing gss classes.

## Filter rebuild (legacy `#f-*` with `data-cat` mapped to `gss-filter`)
- The wrapper `core/group` `div.gss-filter` holds the inputs as direct children, through a `core/html` block
  with no wrapper. Inside it are the filter-bar section, the `#featured` section and the `#cases` section.
  (The legacy CSS keyed off `.gfm--cases:has(#f-…)`, so the filter had to span several sections.)
- The input ids are kept (`f-all`, `f-ecom`, `f-local`, `f-tech`, `f-ent`, `name="cf"`), and `value` is added: all, ecommerce, local, technical, enterprise.
- Labels: `for` is kept, and `data-for` is added with the same values.
- Card `data-cat`: ecom→ecommerce, local→local, tech→technical, ent→enterprise.
- `#featured` was hidden by the legacy CSS for local/tech/ent but shown for all/ecom. It is now `data-cat="ecommerce"`,
  which gives the same behaviour. A group block cannot save a `data-cat` attribute, so this one section is a
  `core/html` block (`<section class="gss-section gss-section--sky gss-section--tight" id="featured" data-cat="ecommerce">`).
- For review: the generic gss-filter rule hides every `[data-cat]` element inside the wrapper when a filter is active.
  Only the 4 case cards and `#featured` carry `data-cat`.

## Legacy utility translation (`gfm-uN`)
| legacy | meaning | migrated to |
|---|---|---|
| u1 | filter band padding 28px | `gss-section--tight` |
| u2 | featured band padding 36px | `gss-section--tight` |
| u3/u4 | legend dots blue/green | `gss-legend` items with `style="--c:#1a5fe0"` / `--c:#1fb36b` |
| u5 | chart svg width 100% | `div.gss-chart` wrapper |
| u6 | band padding 44px | default `gss-section` (44–64px) |
| u7 | related H2 26px | default h2 (24–34px) |
| u8 | related sub 13.5px | `gss-sub gss-small` |

## Possible future component (not used, not required)
- `gss-select`: a fake select box ("Last 6 months"). It is rendered with `gss-chip` for now.

## JS hooks kept
None. The page has no script.

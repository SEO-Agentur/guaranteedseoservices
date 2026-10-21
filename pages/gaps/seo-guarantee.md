# Gaps: seo-guarantee

## 1. `gss-tabs--head`: tab switcher on the same row as the section heading
Original: the H2 "Clear commitments start with clear boundaries." sits on the left and the
"What We Commit To / What We Cannot Control" pill toggle sits on the right of the same row, with the two
highlight panels below. `gss-tabs` needs `.gss-tabs__nav` as a **direct child** of `.gss-tabs`, so the
nav cannot go inside a `gss-head`. This modifier lays the tabs container out as a wrapping flex row:
the heading and nav share the first line, and the panels take a full line.

```css
.gss-tabs--head{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:24px 40px}
.gss-tabs--head>:is(h2,h3,.gss-head__main){flex:1 1 auto;margin:0}
.gss-tabs--head>.gss-tabs__nav{margin:0}
.gss-tabs--head>:is(.gss-tabs__panels,.gss-tabs__panel){flex:1 1 100%}
```
Used on: section 3 (`div.gss-tabs.gss-tabs--highlight.gss-tabs--head`).
If this is not merged, the page still works: the heading, then the nav, then the panels stack vertically,
because `.gss-tabs` is a block by default and `gss-tabs__nav` is inline-flex.

## 2. `gss-skel` width via `--w` (data value)
Original hero agreement mock: two skeleton lines at 90% and 70% width (legacy `gfm-u1`/`gfm-u2`).
```css
.gss-skel{width:var(--w,auto)}
```
Markup: `<span class="gss-skel" style="--w:90%"></span>`. This is the same custom-property pattern as `gss-bar > i`.
Without the rule, both lines render full width.

## Legacy utility translation (`gfm-uN`)
| legacy | meaning | migrated to |
|---|---|---|
| u1/u2 | skeleton widths 90/70% | `gss-skel` + `--w` (gap 2) |
| u3 | bounds section padding-top 48px | default `gss-section` padding (44–64px) |
| u4 | head align-items center | `gss-tabs--head` (align-items:center) |
| u5 | agreement section padding-top 16px | `gss-section--top-0` |
| u6 | dashboard header flex/space-between | `gss-row gss-row--between gss-row--sm` |
| u7 | dashboard title 16px | `b.gss-h3` (17px) |
| u8 | chart svg width 100% | `div.gss-chart` wrapper |
| u9 | meta label weight 600 | `gss-table td:first-child` (600) |
| u10 | padding-top 8px (FAQ + explore) | `gss-section--top-0` |
| u11 | FAQ link margin-top 16px | `gss-mt-sm` (12px) |
| u12 | "Explore the details" H2 at 22–28px | dropped: default h2 size (24–34px) |

## JS hooks kept
None. The page has no script except the FAQPage JSON-LD, which is kept verbatim.

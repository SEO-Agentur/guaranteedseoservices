# Gaps: performance-based-seo

Four small, generic additions. All are colour/size data hooks or grid helpers; no page-specific names.

## 1. `gss-ico--tint` (solid icon badge in any data colour)
Original service tiles/cards use solid icon badges in six service colours (blue, green, purple, amber, teal, navy).
`gss-ico--solid` only supports blue.
```css
.gss-ico--tint{background:var(--c,var(--gss-blue));color:#fff}
```
Used: "Our SEO Services at a Glance" tiles (6x) and "Detailed SEO Services" card headers (7x), each with `style="--c:#hex"`.

## 2. `gss-score--tint` + icon inside a score dot
The Service-to-Problem matrix shows a check in a dot coloured per column. `gss-score` has fixed colours only, and
`.gss-table .gss-icon` (blue, margin-right 8px) breaks an icon placed inside a score dot.
```css
.gss-score--tint{background:var(--c,var(--gss-green))}
.gss-score .gss-icon,.gss-table .gss-score .gss-icon{width:14px;height:14px;margin:0;color:inherit}
```
Used: matrix table cells, `<span class="gss-score gss-score--tint" style="--c:#hex" role="img" aria-label="Recommended"><svg class="gss-icon">…check…</svg></span>`.

## 3. `gss-bars` bar heights as data
`.gss-bars>*` has no height, so bars collapse to 0. Heights are data values.
```css
.gss-bars>*{height:var(--h,100%)}
```
Used: hero dark traffic mock, `<span style="--h:28%"></span>` x12.

## 4. `gss-span-2` (card spanning two grid columns)
Original Service Selection Tool form spans 2 of 3 columns next to the last service card.
```css
@media(min-width:561px){.gss-span-2{grid-column:span 2}}
```
Used: `form#gss-tool` inside `.gss-grid--3` in "Detailed SEO Services".

## 5. Legend legibility on dark mocks
`.gss-legend` uses `--gss-muted` text, which is nearly invisible on `gss-mock--dark` (original legend text #dbe5f7).
```css
.gss-mock--dark .gss-legend{color:#dbe5f7}
```
Used: hero dark traffic mock legend (Technical, Content, Local, Ecommerce, International, Authority).

## 6. Theme bug (not page-specific): `a.gss-btn` text renders ink instead of white
`.gss-page a{color:inherit}` (specificity 0,1,1) beats `.gss-btn{color:#fff}` (0,1,0), so every anchor button
in page content shows dark text on blue (e.g. hero "Find the Right SEO Services for My Website",
"Learn how we build your SEO strategy"). `<button class="gss-btn">` is unaffected. Proposed fix:
```css
.gss-page a.gss-btn{color:#fff}
.gss-page a.gss-btn--ghost{color:var(--gss-ink)}
.gss-page a.gss-btn--light{color:var(--gss-navy)}
```

## JS hooks kept
None (the page has no script other than FAQPage JSON-LD; no legacy class names are queried).

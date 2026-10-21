# Guaranteed Flex 2 (folder `gss`)

One consolidated design system for guaranteedseoservices.com.

* **Theme = presentation** (tokens, components, layouts, block styles, patterns, template positions).
* **Gutenberg = content** (headlines, copy, prices, FAQs, CTAs, links, images).

## Files
| File | Purpose |
|---|---|
| `style.css` | All non-critical component CSS (generated, minified, cacheable). |
| `assets/critical.css` | Above-the-fold primitives, inlined once per page (generated). |
| `assets/editor.css` | Canvas rules re-targeted to the block editor (generated). |
| `assets/icons.svg` | Icon sprite. `<svg class="gss-icon" aria-hidden="true"><use href="/wp-content/themes/gss/assets/icons.svg#NAME"/></svg>` |
| `patterns/*.php` | Reusable Gutenberg patterns (Inserter → Patterns → "Guaranteed SEO design system"). |
| `page.php` | Pages whose content contains an H1 render on the full-width canvas (`main.gss-page`) and the theme prints **no** title H1. Otherwise the title is the single H1. |

**Never edit the generated CSS files directly.** Edit `theme/src/gss.css` in the Git repo and run `node tools/build-css.mjs`.

The living specification of every component and variant is the private page **/design-system/**.

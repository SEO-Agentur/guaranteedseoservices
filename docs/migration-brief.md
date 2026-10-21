# Page migration brief (for migration agents)

Goal: re-express an existing WordPress page on guaranteedseoservices.com with the shared `gss-*`
design system, as **valid Gutenberg block markup**, preserving its content and its current look.

Read first:
* `/home/claude/repo/docs/components.md` — component API + validated Gutenberg recipes.
* `/home/claude/repo/theme/src/gss.css` — the ONLY stylesheet. Read it fully; use only classes defined there
  (or ones you add to your gaps file, see below).

## Hard rules
1. Content is sacred: keep every headline, paragraph, list item, price, FAQ, table cell, link `href`,
   anchor `id` (used by in-page links like `#pricing`), JSON-LD `<script type="application/ld+json">`,
   other `<script>` blocks, shortcodes (e.g. `[gss_get_started]`) and every form attribute
   (`action`, `method`, `name`, `id`, `value`, `required`, `placeholder`, `data-*`, `for`) **verbatim**.
   Do not invent, rewrite, shorten or "improve" copy. Do not add or remove sections.
2. No `<style>` blocks, no `style=""` attributes (sole exception: data values as custom properties,
   e.g. `<i style="--w:62%">` in a `gss-bar`, or `style="--c:#1e9e5a"` on a legend item / `--gss-cover` on a book cover).
   No legacy classes (`gfm-*`, `gsr`, `gsg`, `gsa`, `gcs-*`, old `gss-*` names that are not in gss.css, `gfm-uN` utilities).
3. Exactly one `<h1>` (the hero headline). Keep the existing heading hierarchy (H2 sections, H3 cards).
4. Structure: page = sequence of top-level `core/group` sections (`tagName: section`, class `gss-section …`
   or `gss-hero …` or `gss-cta …`) each containing a `core/group` `gss-wrap`. Use native blocks
   (heading, paragraph, list, buttons, details, group) for editable text where the structure is simple;
   use `core/html` for cards with icons, forms, mock UIs, SVG charts, tables with icons, tabs/filters, scripts.
   Prefer a group/heading/paragraph skeleton with small `core/html` islands over one giant html block,
   but never break validity — every block must validate.
5. Icons: 24×24 inline SVGs become sprite references
   `<svg class="gss-icon" aria-hidden="true"><use href="/wp-content/themes/gss/assets/icons.svg#NAME"/></svg>`
   (the prepare script below already does this). Large illustrative SVGs stay inline but must not use
   page-specific classes; keep their own presentation attributes (fill/stroke/viewBox).
6. Interactions that used page-specific `:has(#id:checked)` CSS must be rebuilt with the generic
   `gss-tabs` / `gss-tabs--highlight` / `gss-tabs--dim` / `gss-filter` patterns (see components.md).
   Plugin JS hooks must keep working: `[data-gss-funnel]`, `[data-gss-stop]`, `[data-gss-plan-option]`,
   `[data-gss-price-card]`, `.gss-hero-form`, `.gss-price-card`, `[data-gsr-handoff]`, `name="package_id"`.
   If a page `<script>` queries legacy class names, keep those class names on the elements as hooks
   (add them alongside gss classes) and list them in your gaps file under "JS hooks kept".
7. Visual fidelity: match layout, column structure, ordering, spacing rhythm, colours (unified palette
   is fine), backgrounds, card styles and responsive behaviour of the original as closely as the shared
   system allows. Small normalisations (identical blue, identical radius) are intended.
8. Missing component? First try to compose from existing classes/modifiers. If something genuinely
   new is needed (a structural visual that the system cannot express), DO NOT write page CSS.
   Instead add it to `/home/claude/repo/pages/gaps/<slug>.md` with: proposed generic class name(s)
   (`gss-` prefix, BEM-ish, reusable wording — never page names), the exact CSS rules, and where you
   used it. Use the class in your markup. Keep gaps minimal and reusable; the lead will review/merge
   them into gss.css.

## Workflow (your own Chrome tab only — use the tab id you were given; never touch other tabs)
1. Navigate your tab to `https://guaranteedseoservices.com/wp-admin/edit.php?post_type=page`.
2. Prepare source with javascript_tool (replace SLUG):
```js
const h=await (await fetch('/wp-admin/post-new.php?post_type=page')).text();
window.__nonce=(h.match(/createNonceMiddleware\(\s*"([a-f0-9]+)"/)||[])[1];
const p=(await (await fetch('/wp-json/wp/v2/pages?slug=SLUG&context=edit',{headers:{'X-WP-Nonce':window.__nonce}})).json())[0];
window.__id=p.id;
const map=await (await fetch('/wp-content/themes/gss/assets/icon-map.json',{cache:'no-store'})).json();
const norm=s=>s.replace(/\s+/g,' ').replace(/> </g,'><').trim();
let raw=p.content.raw;
raw=raw.replace(/<svg\b[^>]*viewBox="0 0 24 24"[^>]*>[\s\S]*?<\/svg>/g,s=>{const n=map[norm(s.replace(/^<svg[^>]*>/,'').replace(/<\/svg>$/,''))];return n?`<svg class="gss-icon" aria-hidden="true"><use href="/wp-content/themes/gss/assets/icons.svg#${n}"/></svg>`:s;});
window.__src=raw;
document.body.innerHTML='<input type="file" id="bridge"><pre id="out"></pre>';
document.getElementById('out').textContent=raw; p.id+' '+raw.length
```
3. Read it with `get_page_text` (it may be long; if output is truncated, show slices:
   `document.getElementById('out').textContent=window.__src.slice(A,B)` and read again). If the result says
   `[BLOCKED: ...]`, show smaller slices; as a last resort replace `?` by `¿` in the displayed slice and
   convert back when writing.
   For pages whose CSS is NOT inside the content (`gfm--home`, `gfm--packages`, `gfm--guarantee`, `gfm--cases`),
   the original CSS is in `https://guaranteedseoservices.com/wp-content/themes/guaranteed-flex/style.css`
   (sections "--- home ---", "--- guarantee ---", "--- cases ---", "--- packages ---"); fetch and show the
   relevant section the same way.
4. Look at the ORIGINAL rendering: take screenshots of `https://guaranteedseoservices.com/SLUG/?gss_original=1`
   (scroll through the whole page; scale 0.5 is enough). Note column structures and visuals.
5. Write the migrated block markup to `/home/claude/repo/pages/migrated/<slug>.html` (Write tool).
6. Upload + validate + stage it:
   * `read_page` (filter interactive) on your tab to get the ref of `#bridge`; `file_upload` your file to it.
   * Navigate is NOT needed; run:
```js
const txt=await document.getElementById('bridge').files[0].text();
// validate in a hidden block-editor iframe
const f=document.createElement('iframe');f.style.cssText='position:fixed;left:-3000px;width:1200px;height:800px';f.src='/wp-admin/post-new.php?post_type=page';document.body.appendChild(f);
await new Promise(r=>{let n=0;const t=setInterval(()=>{n++;const w=f.contentWindow;if((w&&w.wp&&w.wp.blocks&&w.wp.blocks.getBlockTypes().length>50)||n>80){clearInterval(t);r()}},500)});
const walk=(bs,o=[])=>{for(const b of bs){if(!b.isValid)o.push(b.name+' INVALID: '+JSON.stringify(b.validationIssues||[]).slice(0,400)+' :: '+(b.originalContent||'').slice(0,200));walk(b.innerBlocks,o)}return o};
const issues=walk(f.contentWindow.wp.blocks.parse(txt));
const stats={h1:(txt.match(/<h1[\s>]/g)||[]).length,style:(txt.match(/<style|style="(?!--)/g)||[]).length,legacy:(txt.match(/class="[^"]*\b(gfm-|gsr\b|gsg\b|gsa\b|gcs-)/g)||[]).length};
if(!issues.length){await fetch('/wp-json/wp/v2/pages/'+window.__id,{method:'POST',headers:{'X-WP-Nonce':window.__nonce,'Content-Type':'application/json'},body:JSON.stringify({meta:{_gss_next_content:txt}})});}
f.remove(); JSON.stringify({issues,stats})
```
   (The block-editor iframe creates an auto-draft page; that is harmless.) Fix every invalid block until
   `issues` is empty. This stores your markup in a private meta field — the live page is NOT changed.
7. Compare: screenshot `https://guaranteedseoservices.com/SLUG/?gss_preview=1` (new theme + your markup)
   against the original at desktop width; then check mobile by loading both URLs in a 375px-wide iframe
   inside your tab and measuring `document.documentElement.scrollWidth` (must be ≤ 375, i.e. no horizontal
   overflow) and section heights. Iterate on markup (and gaps CSS proposals) until the page matches.
   You cannot change the theme CSS yourself; if a gap blocks fidelity, describe it precisely in the gaps file.
8. Do NOT publish, do NOT edit post_content, do NOT change any other page, plugin, theme or setting.

## Final report (your last message)
Per page: path of the migrated file, validation result, h1 count, remaining visual differences
(be specific), gaps file path + summary of proposed classes, JS hooks kept, anything risky
(forms, scripts, shortcodes) and broken/404 links you noticed.

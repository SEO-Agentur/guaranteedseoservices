<?php
/**
 * Title: Hero — copy + lead form
 * Slug: gss/hero-split
 * Categories: gss
 * Description: Page hero with breadcrumb, eyebrow, the page's single H1, lead, chips, CTA and a form card.
 */
?>
<!-- wp:group {"tagName":"section","className":"gss-hero gss-hero--image"} -->
<section class="wp-block-group gss-hero gss-hero--image"><!-- wp:group {"className":"gss-wrap"} -->
<div class="wp-block-group gss-wrap"><!-- wp:group {"className":"gss-grid gss-grid--split gss-grid--center"} -->
<div class="wp-block-group gss-grid gss-grid--split gss-grid--center"><!-- wp:group {"className":"gss-hero__copy"} -->
<div class="wp-block-group gss-hero__copy"><!-- wp:html -->
<nav class="gss-crumbs" aria-label="Breadcrumb"><a href="/">Home</a><span>/</span>Page title</nav>
<!-- /wp:html -->
<!-- wp:paragraph {"className":"gss-eyebrow"} -->
<p class="gss-eyebrow">Eyebrow</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Page headline (the only H1)</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"gss-lead"} -->
<p class="gss-lead">One or two sentences that explain the page.</p>
<!-- /wp:paragraph -->
<!-- wp:html -->
<div class="gss-chips"><span class="gss-chip"><svg class="gss-icon" aria-hidden="true"><use href="/wp-content/themes/gss/assets/icons.svg#check"/></svg>Benefit one</span><span class="gss-chip"><svg class="gss-icon" aria-hidden="true"><use href="/wp-content/themes/gss/assets/icons.svg#check"/></svg>Benefit two</span></div>
<!-- /wp:html -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">Primary action →</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
<!-- wp:html -->
<form class="gss-card gss-card--float gss-form" action="#" method="get"><h2 class="gss-h3">Form title</h2><p class="gss-fine">Short hint.</p><label for="pat-url">Website URL</label><input id="pat-url" type="url" name="website" placeholder="https://yourwebsite.com" required><button class="gss-btn gss-btn--block" type="submit">Submit →</button><ul class="gss-ticks"><li>Free</li><li>No obligation</li></ul></form>
<!-- /wp:html --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

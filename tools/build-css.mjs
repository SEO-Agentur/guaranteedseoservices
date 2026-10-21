// Build: theme/src/gss.css -> gss/assets/critical.css (inlined) + guaranteed-flex/style.css (async, cacheable)
// A rule goes to CRITICAL when its selector list matches CRIT (above-the-fold primitives). No rule is emitted twice.
import fs from 'fs'; import postcss from 'postcss'; import {minify} from 'csso';
const root='/home/claude/repo/theme';
const src=fs.readFileSync(root+'/src/gss.css','utf8');
const header=src.match(/^\/\*[\s\S]*?\*\//)[0];
const CRIT=new RegExp('^('+[
 ':root','\\*','html','body','img','a$','button','h1,h2','h1',':where\\(h1',':where\\(p',':focus-visible','\\.skip-link','\\.screen-reader','\\.container$',
 '\\.site-header','\\.header-inner','\\.site-branding','\\.custom-logo','\\.header-navigation( ul| a|$| \\.menu>li(>a|\\.menu-cta>a)?$| \\.sub-menu$)','\\.header-actions','\\.mobile-navigation(\\{|$| summary|\\[open\\])',
 '\\.gss-page(\\{|$| :where| h[1-3]| a$)',':where\\(\\.gss-page\\) (:where|a$)','\\.gss-h2','\\.gss-h3',':where\\(\\.gss-flow,','\\.gss-section(\\{|$|--(tight|soft|sky|dark$))','\\.gss-wrap(\\{|$)','\\.gss-grid','\\.gss-head(\\{|$|__main)','\\.gss-row(\\{|$)',
 '\\.gss-eyebrow(\\{|$|--pill)','\\.gss-lead','\\.gss-btn(\\{|$|--(navy|ghost|block)$)','\\.gss-icon(\\{|$)','\\.gss-ico(\\{|$| \\.gss-icon)',
 '\\.gss-card(\\{|$)','\\.gss-chips','\\.gss-chip(\\{|$)','\\.gss-crumbs(\\{|$)','\\.gss-hero(\\{|$| h1|__copy>\\.gss-lead)','\\.gss-sr'
].join('|')+')');
const isCrit=sel=>sel.split(',').some(s=>CRIT.test(s.trim()));
const crit=postcss.parse(''),main=postcss.parse('');
function place(node,targetRoot,parentClone){(parentClone||targetRoot).append(node.clone());}
const css=postcss.parse(src);
css.each(node=>{
 if(node.type==='comment')return;
 if(node.type==='rule'){(isCrit(node.selector)?crit:main).append(node.clone());return;}
 if(node.type==='atrule'&&node.nodes){const c=node.clone({nodes:[]}),m=node.clone({nodes:[]});node.each(ch=>{if(ch.type==='comment')return;(ch.type==='rule'&&isCrit(ch.selector)?c:m).append(ch.clone());});if(c.nodes.length)crit.append(c);if(m.nodes.length)main.append(m);return;}
 main.append(node.clone());
});
const critMin=minify(crit.toString(),{restructure:false}).css, mainMin=minify(main.toString(),{restructure:false}).css;
fs.writeFileSync(root+'/gss/assets/critical.css',critMin+'\n');
fs.writeFileSync(root+'/gss/style.css',header+'\n'+mainMin+'\n');
// editor.css: canvas-scoped rules re-targeted to the editor body (add_editor_style maps body -> .editor-styles-wrapper)
const ed=postcss.parse('');css.walkRules(r=>{if(r.selector.includes('.gss-page')&&r.parent.type==='root'){const c=r.clone();c.selector=c.selector.split(',').filter(x=>x.includes('.gss-page')).map(x=>x.replace(/\.gss-page/g,'body')).join(',');ed.append(c);}});
fs.writeFileSync(root+'/gss/assets/editor.css',minify(ed.toString(),{restructure:false}).css+'\n');
console.log('critical',critMin.length,'main',mainMin.length,'total',critMin.length+mainMin.length);

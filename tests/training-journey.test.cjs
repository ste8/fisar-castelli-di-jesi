// Run with node --experimental-vm-modules tests/training-journey.test.cjs.
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const source = fs.readFileSync('theme/fisar-cdj/assets/js/training-journey.js', 'utf8');
const flush = () => new Promise(resolve => setImmediate(resolve));
async function setup(reduce = false) {
  const canvas = {hidden:true,style:{}}, button = {hidden:true,disabled:false,events:{},addEventListener(n,f){this.events[n]=f;}};
  const classes = new Set();
  const figure = {dataset:{document:'/animation.json',player:'/runtime.js'},querySelector:s=>s==='canvas'?canvas:button,
    classList:{add:v=>classes.add(v),remove:v=>classes.delete(v)}};
  const reduced = {matches:reduce,addEventListener(n,f){this.change=f;}};
  const document = {hidden:false,events:{},querySelector:()=>figure,addEventListener(n,f){this.events[n]=f;}};
  const frames = new Map(), calls = [];
  let observe, imports=0, now=0, next=0, options;
  const window = {IntersectionObserver(){},WebGL2RenderingContext(){},addEventListener(){}};
  const context = vm.createContext({window,document,matchMedia:()=>reduced,
    IntersectionObserver:class{constructor(callback){observe=callback;}observe(){}disconnect(){}},
    performance:{now:()=>now},requestAnimationFrame:f=>{frames.set(++next,f);return next;},cancelAnimationFrame:id=>frames.delete(id),
    fetch:async()=>({ok:true,json:async()=>({timing:{durationTicks:1080,ticksPerSecond:240}})}),console:{warn(){}}});
  const runtime = new vm.SyntheticModule(['checkCoverage','createPlayer'],function(){
    this.setExport('checkCoverage',async()=>({supported:true}));
    this.setExport('createPlayer',async passed=>{options=passed;return {seek:t=>calls.push(t),dispose(){}};});
  },{context});
  await runtime.link(()=>{}); await runtime.evaluate();
  vm.runInContext(source,context,{importModuleDynamically:()=>{imports++;return runtime;}});
  return {canvas,button,classes,reduced,document,calls,imports:()=>imports,options:()=>options,
    enter:ratio=>observe([{isIntersecting:ratio>0,intersectionRatio:ratio}]),
    async advance(ms){now+=ms;const pending=[...frames.values()];frames.clear();pending.forEach(f=>f(now));await flush();}};
}
(async()=>{
  const e=await setup();
  assert.equal(e.imports(),0,'Offscreen illustrations do not load the runtime.');
  e.enter(.1); await flush(); assert.equal(e.imports(),0);
  e.enter(1); await flush(); await e.advance(32); await flush();
  assert.ok(e.classes.has('is-rendered')); assert.ok(e.button.disabled);
  await e.advance(5000); assert.equal(e.calls.at(-1),1079); assert.equal(e.button.disabled,false);
  const settled=e.calls.length; e.enter(0); e.enter(1); await flush();
  assert.equal(e.calls.length,settled,'Returning to the section does not autoplay again.');
  e.button.events.click(); await flush(); assert.ok(e.button.disabled);
  e.document.hidden=true; e.document.events.visibilitychange();
  assert.equal(e.calls.at(-1),1079); assert.equal(e.button.disabled,false);
  e.document.hidden=false; e.reduced.matches=true; e.reduced.change();
  assert.ok(e.canvas.hidden); assert.ok(e.button.hidden); assert.ok(!e.classes.has('is-rendered'));
  const r=await setup(true); r.enter(1); await flush(); assert.equal(r.imports(),0,'Reduced motion keeps the complete SVG without loading Clayzo.');
  const pending=await setup(); pending.enter(1); await flush(); pending.enter(0); await pending.advance(32); await flush();
  assert.equal(pending.button.hidden,false); assert.equal(pending.button.disabled,false,'Leaving while loading still permits a later explicit replay.');
  pending.options().onError(new Error('WebGL unavailable'));
  assert.ok(pending.canvas.hidden); assert.ok(pending.button.hidden); assert.ok(!pending.classes.has('is-rendered'));
  console.log('Training journey: lazy load, once/replay, hidden page, reduced motion, interrupted loading and fallback passed.');
})().catch(error=>{console.error(error);process.exitCode=1;});

// Run with node tests/follow-motion.test.cjs. Interaction lifecycle, without browser dependencies.
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const source = fs.readFileSync('theme/fisar-cdj/assets/js/follow-motion.js','utf8');
const flush = () => new Promise(resolve => setImmediate(resolve));
function setup(reduce = false) {
  const animations = [];
  const accent = {animate(frames,options){let resolve,reject;const finished=new Promise((a,b)=>{resolve=a;reject=b;});
    const animation={frames,options,finished,cancelled:false,finish:resolve,cancel(){this.cancelled=true;reject(new Error('cancelled'));}};
    animations.push(animation);return animation;}};
  const cards = ['whatsapp','instagram','facebook','newsletter'].map(key=>({dataset:{},events:{},classList:{contains:n=>n==='follow-card--'+key},
    querySelector:()=>accent,querySelectorAll:()=>[accent,accent,accent],addEventListener(n,f){this.events[n]=f;},contains:()=>false}));
  const reduced={matches:reduce,events:{},addEventListener(n,f){this.events[n]=f;}};
  const document={hidden:false,events:{},querySelector:()=>({querySelectorAll:()=>cards}),addEventListener(n,f){this.events[n]=f;}};
  const window={events:{},addEventListener(n,f){this.events[n]=f;}};
  vm.runInNewContext(source,{window,document,Element:{prototype:{animate(){}}},matchMedia:()=>reduced});
  return {cards,animations,reduced,document,window};
}
(async()=>{
  const e=setup();assert.equal(e.animations.length,0,'No animation runs on load, and no visibility observer is needed.');
  e.cards[0].events.pointerenter({pointerType:'touch'});assert.equal(e.animations.length,0);
  for(let i=0;i<4;i++){
    e.cards[i].events.pointerenter({pointerType:'mouse'});assert.equal(e.cards[i].dataset.iconEffect,'running');
    const live=e.animations.length;e.cards[i].events.pointerenter({pointerType:'mouse'});assert.equal(e.animations.length,live,'No live reset.');
    if(i===1)assert.equal(e.animations.at(-1).options.duration,1600);
    e.animations.forEach(a=>a.finish());await flush();assert.equal(e.cards[i].dataset.iconEffect,'idle');
  }
  e.cards[1].events.focusin({relatedTarget:null});assert.equal(e.cards[1].dataset.iconEffect,'running');
  e.reduced.matches=true;e.reduced.events.change();await flush();assert.ok(e.cards.every(c=>c.dataset.iconEffect==='idle'));
  const r=setup(true);r.cards[0].events.pointerenter({pointerType:'mouse'});assert.equal(r.animations.length,0);
  const hidden=setup();hidden.cards[0].events.pointerenter({pointerType:'mouse'});hidden.document.hidden=true;hidden.document.events.visibilitychange();await flush();
  assert.ok(hidden.animations.every(a=>a.cancelled));assert.equal(hidden.cards[0].dataset.iconEffect,'idle');
  const race=setup();race.cards[1].events.pointerenter({pointerType:'mouse'});race.window.events.pagehide();race.cards[1].events.pointerenter({pointerType:'mouse'});await flush();
  assert.equal(race.cards[1].dataset.iconEffect,'running','A cancelled promise cannot reset a later interaction.');
  race.animations.forEach(a=>a.finish());await flush();
  console.log('Follow channels: no entrance, four finite effects, no live reset, keyboard/touch, reduced motion and cleanup passed.');
})().catch(error=>{console.error(error);process.exitCode=1;});

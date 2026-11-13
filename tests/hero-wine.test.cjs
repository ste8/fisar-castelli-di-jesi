// Run with node --experimental-vm-modules tests/hero-wine.test.cjs.
// Public controller behaviour with the published runtime API replaced by an in-memory module.
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');
const source = fs.readFileSync(path.resolve(__dirname, '../theme/fisar-cdj/assets/js/hero-wine.js'), 'utf8');
const flush = () => new Promise(resolve => setImmediate(resolve));
function element() {
  let hidden = false;
  const classes = new Set(), events = {}, visibility = [];
  return { classes, events, visibility, dataset: {}, style: {}, removed: false,
    get hidden() { return hidden; }, set hidden(value) { if (hidden !== value) visibility.push(value); hidden = value; },
    classList: { add: value => classes.add(value), remove: value => classes.delete(value), contains: value => classes.has(value) },
    setAttribute() {}, append() {}, remove() { this.removed = true; }, addEventListener(name, callback) { events[name] = callback; } };
}
async function setup() {
  const glass = element(), canvas = element(), trigger = element(), reduced = { matches: false }, warnings = [];
  const document = { hidden: false, createElement: tag => tag === 'canvas' ? canvas : trigger };
  glass.dataset = { wineDocument: '/wine.json', winePlayer: '/runtime.js' };
  glass.querySelector = () => ({ currentSrc: '/glass.webp' });
  const frames = new Map(), seeks = [];
  let now = 0, frameId = 0, options, failNext = false, disposed = false;
  const context = vm.createContext({ window: { WebGL2RenderingContext() {} }, document, matchMedia: () => reduced,
    fetch: async () => ({ ok: true, json: async () => ({}), arrayBuffer: async () => new ArrayBuffer(1) }),
    performance: { now: () => now }, requestAnimationFrame: callback => { const id = ++frameId; frames.set(id, callback); return id; },
    cancelAnimationFrame: id => frames.delete(id), console: { warn: (...args) => warnings.push(args) } });
  const runtime = new vm.SyntheticModule(['createPlayer', 'checkCoverage'], function () {
    this.setExport('checkCoverage', async () => ({ supported: true, reasons: [] }));
    this.setExport('createPlayer', async passed => {
      options = passed;
      return { seek: async tick => { if (failNext) { failNext = false; throw new Error('GPU unavailable'); } seeks.push(tick); },
        pause() {}, dispose() { disposed = true; } };
    });
  }, { context });
  await runtime.link(() => {}); await runtime.evaluate();
  vm.runInContext(source, context, { importModuleDynamically: () => runtime });
  const controller = context.window.fisarCdjCreateHeroWine(glass);
  async function advance(ms) { now += ms; const callbacks = [...frames.values()]; frames.clear(); callbacks.forEach(callback => callback(now)); await flush(); }
  return { controller, glass, canvas, trigger, reduced, document, warnings, seeks, advance,
    options: () => options, failNextSeek: () => { failNext = true; }, disposed: () => disposed };
}
(async () => {
  const env = await setup();
  env.controller.start(); await flush(); await env.advance(32); await flush();
  assert.equal(env.canvas.hidden, false);
  assert.ok(env.glass.classes.has('has-wine-renderer'));
  assert.equal(env.options().releaseWhenOffscreen, false, 'The static glass retains its already-rendered pixels between waves.');
  env.canvas.visibility.length = 0;
  env.controller.finish(); await flush();
  assert.equal(env.canvas.hidden, false, 'Stopping does not blank the whole glass.');
  assert.ok(env.glass.classes.has('has-wine-renderer'));
  assert.ok(!env.glass.classes.has('has-wine-motion'));
  env.trigger.events.pointerenter({ pointerType: 'mouse', clientX: 10, clientY: 10 }); await flush();
  assert.ok(env.glass.classes.has('has-wine-motion'));
  const live = env.seeks.length;
  env.trigger.events.pointermove({ pointerType: 'mouse', clientX: 30, clientY: 30 }); await flush();
  assert.equal(env.seeks.length, live, 'Movement does not reset a wave already in progress.');
  await env.advance(5000);
  assert.ok(!env.glass.classes.has('has-wine-motion'));
  env.trigger.events.pointermove({ pointerType: 'mouse', clientX: 50, clientY: 50 }); await flush();
  assert.ok(env.glass.classes.has('has-wine-motion'), 'Mouse movement replays the wave without leaving the wine.');
  env.controller.pause();
  const paused = env.seeks.length;
  env.trigger.events.pointermove({ pointerType: 'mouse', clientX: 70, clientY: 70 }); await flush();
  assert.equal(env.seeks.length, paused, 'Pointer movement respects a paused wave.');
  env.controller.finish(); await flush();
  const stopped = env.seeks.length;
  env.trigger.events.pointermove({ pointerType: 'mouse', clientX: 71, clientY: 71 });
  env.trigger.events.pointermove({ pointerType: 'touch', clientX: 90, clientY: 90 });
  env.reduced.matches = true;
  env.trigger.events.pointermove({ pointerType: 'mouse', clientX: 100, clientY: 100 }); await flush();
  assert.equal(env.seeks.length, stopped, 'Jitter, touch and reduced motion cannot trigger a wave.');
  assert.ok(!env.canvas.visibility.includes(true), 'Repeated waves never hide the already-presented canvas.');
  env.reduced.matches = false;
  env.failNextSeek(); env.controller.start(); await flush();
  assert.equal(env.canvas.hidden, true, 'A render error restores the native image.');
  assert.ok(!env.glass.classes.has('has-wine-renderer')); assert.equal(env.warnings.length, 1);
  env.controller.dispose();
  assert.ok(env.canvas.removed && env.trigger.removed && env.disposed());
  console.log('Wine: persistent presentation, movement without re-entry, no live resets, pause, touch/reduced-motion and error fallback passed.');
})().catch(error => { console.error(error); process.exitCode = 1; });

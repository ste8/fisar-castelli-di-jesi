// Behavioural contracts: finite motion, accessible selection and autoplay suspension.
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');
const source = fs.readFileSync(path.resolve(__dirname, '../theme/fisar-cdj/assets/js/hero-collage.js'), 'utf8');

function element() {
  const events = {}, attrs = {}, classes = new Set();
  return { events, attrs, hidden: false,
    classList: { add: name => classes.add(name), remove: name => classes.delete(name), contains: name => classes.has(name),
      toggle(name, active) { if (active) classes.add(name); else classes.delete(name); } },
    addEventListener(name, callback) { events[name] = callback; },
    setAttribute(name, value) { attrs[name] = value; }, getAttribute(name) { return attrs[name]; } };
}
function setup({ reducedMotion = false, slideCount = 3, webAnimations = true, delayedImages = false } = {}) {
  const hero = element(), album = element(), pause = element(), status = element(), window = element();
  const allAnimations = [], wineCalls = [], imageResolvers = [];
  const wine = Object.fromEntries(['start', 'pause', 'resume', 'finish', 'dispose'].map(name => [name, () => wineCalls.push(name)]));
  window.fisarCdjCreateHeroWine = () => wine;
  const slides = Array.from({ length: slideCount }, (_, index) => {
    const photos = Array.from({ length: index === 2 ? 2 : 6 }, () => {
      const photo = element();
      if (webAnimations) photo.animate = (keyframes, options) => {
        let resolve, reject;
        const finished = new Promise((yes, no) => { resolve = yes; reject = no; });
        const animation = { keyframes, options, finished, state: 'running', pause() { this.state = 'paused'; }, play() { this.state = 'running'; },
          cancel() { this.state = 'cancelled'; reject(new Error('Cancelled')); }, finish: resolve };
        allAnimations.push(animation); return animation;
      };
      return photo;
    });
    const images = photos.map(() => ({ decode: () => delayedImages && index === 0 ? new Promise(resolve => imageResolvers.push(resolve)) : Promise.resolve() }));
    return Object.assign(element(), { photos, querySelectorAll: selector => selector === 'figure' ? photos : images });
  });
  const dots = slides.map(element);
  const controls = Object.assign(element(), { querySelectorAll: () => dots, querySelector: selector => selector === '[data-pause]' ? pause : status });
  album.querySelector = selector => selector === '.hero-collage__controls' ? (slideCount > 1 ? controls : null) : element();
  const gallery = { closest: selector => selector === '.hero' ? hero : album, querySelectorAll: () => slides };
  const reduced = Object.assign(element(), { matches: reducedMotion }), desktop = Object.assign(element(), { matches: true });
  const document = Object.assign(element(), { hidden: false, querySelectorAll: () => [gallery] });
  let tick = null, intersection;
  vm.runInNewContext(source, { document, window, matchMedia: query => query.includes('reduced-motion') ? reduced : desktop,
    setTimeout: callback => { tick = callback; return 1; }, clearTimeout: () => { tick = null; },
    IntersectionObserver: class { constructor(callback) { intersection = callback; } observe() {} } });
  return { hero, album, pause, status, slides, dots, controls, reduced, desktop, document, window, allAnimations, wineCalls,
    tick: () => tick, intersect: visible => intersection([{ isIntersecting: visible }]), resolveImages: () => imageResolvers.forEach(resolve => resolve()) };
}
const flush = () => new Promise(resolve => setImmediate(resolve));
(async () => {
  const normal = setup();
  await flush();
  assert.equal(normal.allAnimations.length, 6, 'Entrance animates the native six photographs without cloning them.');
  assert.equal(normal.wineCalls.filter(name => name === 'start').length, 1);
  assert.equal(typeof normal.tick(), 'function');
  normal.tick()();
  assert.equal(normal.dots[1].attrs['aria-current'], 'true');
  assert.equal(normal.wineCalls.filter(name => name === 'start').length, 1, 'Automatic photo changes never restart the wine.');
  assert.equal(normal.slides[0].attrs['aria-hidden'], 'true', 'Leaving photographs are hidden from assistive technology.');
  assert.equal(normal.slides[1].attrs['aria-hidden'], 'false');
  assert.equal(normal.allAnimations.slice(6).filter(animation => animation.state === 'running').length, 12, 'Outgoing and incoming photographs transition independently.');
  normal.hero.events.focusin();
  assert.equal(normal.tick(), null);
  assert.ok(normal.allAnimations.slice(6).every(animation => animation.state === 'paused'));
  normal.pause.events.click();
  assert.equal(typeof normal.tick(), 'function');
  assert.ok(normal.wineCalls.includes('resume'));
  normal.dots[2].events.click();
  assert.equal(normal.tick(), null, 'Manual selection pauses autoplay but permits its finite transition.');
  normal.controls.events.keydown({ key: 'ArrowRight', preventDefault() {} });
  assert.equal(normal.dots[0].attrs['aria-current'], 'true', 'Keyboard wraps the last group to the first.');
  await flush();
  assert.equal(normal.dots[0].attrs['aria-current'], 'true', 'Cancelled transitions cannot restore an old group.');
  assert.equal(normal.wineCalls.filter(name => name === 'start').length, 1, 'Manual selection and keyboard changes never restart the wine.');
  normal.desktop.matches = false; normal.desktop.events.change();
  assert.equal(normal.tick(), null);
  assert.ok(normal.allAnimations.every(animation => animation.state === 'cancelled'));
  assert.ok(normal.slides.every(slide => !slide.classList.contains('is-leaving')));
  normal.desktop.matches = true; normal.desktop.events.change();
  normal.pause.events.click(); normal.intersect(false);
  assert.equal(normal.tick(), null, 'Nothing autoplays outside the viewport.');

  const pointer = setup(); await flush();
  pointer.pause.events.pointerdown(); pointer.hero.events.focusin(); pointer.pause.events.click();
  assert.equal(pointer.tick(), null, 'Pointer pause keeps its intended action despite focus pausing first.');
  pointer.pause.events.pointerdown(); pointer.hero.events.focusin(); pointer.pause.events.click();
  assert.equal(typeof pointer.tick(), 'function');
  pointer.hero.events.focusin({ target: pointer.pause });
  assert.equal(typeof pointer.tick(), 'function', 'Late focus on Pause cannot undo explicit Resume.');
  pointer.document.hidden = true; pointer.document.events.visibilitychange();
  assert.equal(pointer.tick(), null);
  pointer.window.events.pagehide();
  assert.ok(pointer.wineCalls.includes('dispose'));

  const reduced = setup({ reducedMotion: true }); await flush();
  assert.equal(reduced.pause.disabled, true); assert.equal(reduced.tick(), null); assert.equal(reduced.allAnimations.length, 0);
  reduced.dots[2].events.click();
  assert.equal(reduced.dots[2].attrs['aria-current'], 'true'); assert.equal(reduced.wineCalls.length, 0);

  const single = setup({ slideCount: 1 }); await flush();
  assert.equal(single.tick(), null); assert.equal(single.allAnimations.length, 6, 'A single group still gets an entrance.');

  const slow = setup({ delayedImages: true });
  slow.tick()(); slow.resolveImages(); await flush();
  assert.equal(slow.dots[1].attrs['aria-current'], 'true');
  assert.equal(slow.wineCalls.filter(name => name === 'start').length, 0, 'Late image decoding cannot start an obsolete entrance or animate wine during a change.');

  const fallback = setup({ webAnimations: false }); await flush();
  fallback.tick()(); assert.equal(fallback.dots[1].attrs['aria-current'], 'true'); assert.equal(fallback.allAnimations.length, 0);
  console.log('Hero: native photographs, entrance/change, pause/resume, stale transitions, single group, visibility and reduced motion passed.');
})().catch(error => { console.error(error); process.exitCode = 1; });

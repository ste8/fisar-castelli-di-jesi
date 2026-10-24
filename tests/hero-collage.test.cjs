// Dependency-free behavioural checks for the theme's progressive enhancement.
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');
const source = fs.readFileSync(path.resolve(__dirname, '../theme/fisar-cdj/assets/js/hero-collage.js'), 'utf8');

function element() {
  const events = {};
  const attrs = {};
  return {
    events, attrs, hidden: false,
    classList: { toggle() {} },
    addEventListener(name, callback) { events[name] = callback; },
    setAttribute(name, value) { attrs[name] = value; },
    getAttribute(name) { return attrs[name]; }
  };
}

function setup(reducedMotion = false) {
  const hero = element(), album = element(), pause = element(), status = element();
  const slides = [element(), element(), element()];
  const dots = [element(), element(), element()];
  const controls = Object.assign(element(), {
    querySelectorAll: () => dots,
    querySelector: selector => selector === '[data-pause]' ? pause : status
  });
  album.querySelector = () => controls;
  const gallery = {
    closest: selector => selector === '.hero' ? hero : album,
    querySelectorAll: () => slides
  };
  const reduced = Object.assign(element(), { matches: reducedMotion });
  const desktop = Object.assign(element(), { matches: true });
  const document = Object.assign(element(), { hidden: false, querySelectorAll: () => [gallery] });
  let tick = null;
  let intersection;
  vm.runInNewContext(source, {
    document,
    matchMedia: query => query.includes('reduced-motion') ? reduced : desktop,
    setTimeout: callback => { tick = callback; return 1; },
    clearTimeout: () => { tick = null; },
    IntersectionObserver: class { constructor(callback) { intersection = callback; } observe() {} }
  });
  return { hero, pause, status, slides, dots, controls, reduced, desktop, document,
    tick: () => tick, intersect: visible => intersection([{ isIntersecting: visible }]) };
}

const normal = setup();
assert.equal(typeof normal.tick(), 'function');
normal.tick()();
assert.equal(normal.dots[1].attrs['aria-current'], 'true');
normal.desktop.matches = false;
normal.desktop.events.change();
assert.equal(normal.tick(), null, 'No timer for a hidden narrow-layout album');
normal.desktop.matches = true;
normal.desktop.events.change();
normal.hero.events.focusin();
assert.equal(normal.tick(), null, 'Keyboard focus pauses autoplay');
normal.dots[2].events.click();
normal.controls.events.keydown({ key: 'ArrowRight', preventDefault() {} });
assert.equal(normal.dots[0].attrs['aria-current'], 'true', 'Wrap last to first');
normal.pause.events.click();
assert.equal(typeof normal.tick(), 'function', 'Explicit resume');
normal.intersect(false);
assert.equal(normal.tick(), null, 'No timer outside viewport');

const reduced = setup(true);
assert.equal(reduced.pause.disabled, true);
assert.equal(reduced.tick(), null, 'Reduced motion disables autoplay');
reduced.dots[2].events.click();
assert.equal(reduced.dots[2].attrs['aria-current'], 'true', 'Manual selection remains available');
assert.equal(reduced.tick(), null);
console.log('Hero: autoplay, breakpoint, focus, keyboard, visibility and reduced motion passed.');

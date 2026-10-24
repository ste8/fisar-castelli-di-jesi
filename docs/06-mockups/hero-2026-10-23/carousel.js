'use strict';

// Pure geometry, also exercised without a browser. No duplicated photograph nodes.
function advancePhotoStrip(offset, distance, width) {
  if (width <= 0) return { offset: 0, turns: 0 };
  const total = offset + distance;
  return { offset: total % width, turns: Math.floor(total / width) };
}

function createPhotoCarousel(gallery, keys, makePhoto) {
  const PHOTOS_PER_PANEL = 6;
  const SPEED_PX_PER_SECOND = 14;
  if (new Set(keys).size !== keys.length || keys.length !== PHOTOS_PER_PANEL * 2) {
    throw new Error('L’album richiede dodici fotografie diverse.');
  }
  const strip = document.createElement('div');
  strip.className = 'review-photo-strip';
  for (let index = 0; index < keys.length; index += PHOTOS_PER_PANEL) {
    const panel = document.createElement('div');
    panel.className = 'review-photo-panel';
    panel.dataset.panel = String(index / PHOTOS_PER_PANEL + 1);
    panel.append(...keys.slice(index, index + PHOTOS_PER_PANEL).map(key => makePhoto(key, false)));
    strip.append(panel);
  }
  gallery.replaceChildren(strip);

  const album = gallery.closest('.hero__album');
  const hero = gallery.closest('.hero');
  const controls = album.querySelector('.review-carousel-controls');
  const pause = controls.querySelector('[data-action="pause"]');
  const status = controls.querySelector('[role="status"]');
  const simulateReduced = document.querySelector('#simulate-reduced-motion');
  const preference = matchMedia('(prefers-reduced-motion: reduce)');
  const listeners = new AbortController();
  let offset = 0;
  let width = gallery.clientWidth;
  let frame = 0;
  let lastTime = 0;
  let paused = false;
  let hovered = false;
  let visible = true;
  let destroyed = false;
  let pointerToggle = null;

  const reduced = () => preference.matches || simulateReduced.checked;
  const canRun = () => !destroyed && !paused && !hovered && !reduced() && visible && !document.hidden;
  const render = () => { strip.style.transform = `translate3d(${-offset}px, 0, 0)`; };

  function tick(time) {
    frame = 0;
    if (!canRun()) return;
    const elapsed = lastTime ? Math.min(time - lastTime, 100) : 0;
    lastTime = time;
    const next = advancePhotoStrip(offset, elapsed * SPEED_PX_PER_SECOND / 1000, width);
    offset = next.offset;
    for (let count = 0; count < next.turns; count++) strip.append(strip.firstElementChild);
    render();
    frame = requestAnimationFrame(tick);
  }

  function updatePlayback() {
    cancelAnimationFrame(frame);
    frame = 0;
    lastTime = 0;
    pause.disabled = reduced();
    pause.textContent = reduced() ? 'Fermo' : paused ? 'Riprendi' : 'Pausa';
    pause.setAttribute('aria-label', paused ? 'Riprendi lo scorrimento delle foto' : 'Metti in pausa lo scorrimento delle foto');
    document.querySelector('#motion-note').textContent = reduced()
      ? 'Movimento ridotto: scorrimento automatico disattivato. Puoi esplorare le altre foto con le frecce.'
      : 'Scorrimento lento; pausa al passaggio del mouse, all’uso della tastiera e quando la hero non è visibile. Le frecce fermano lo scorrimento.';
    if (canRun()) frame = requestAnimationFrame(tick);
  }

  function movePanel(direction) {
    paused = true;
    if (direction > 0) strip.append(strip.firstElementChild);
    else strip.prepend(strip.lastElementChild);
    offset = 0;
    render();
    status.textContent = `Gruppo di foto ${strip.firstElementChild.dataset.panel} di 2`;
    updatePlayback();
  }

  const on = (target, event, handler) => target.addEventListener(event, handler, { signal: listeners.signal });
  // Remember pointer intent before focusin pauses the hero; keyboard focus still stops it.
  on(pause, 'pointerdown', () => { pointerToggle = !paused; });
  on(pause, 'pointercancel', () => { pointerToggle = null; });
  on(pause, 'click', () => {
    paused = pointerToggle === null ? !paused : pointerToggle;
    pointerToggle = null;
    updatePlayback();
  });
  on(controls.querySelector('[data-action="previous"]'), 'click', () => movePanel(-1));
  on(controls.querySelector('[data-action="next"]'), 'click', () => movePanel(1));
  on(album, 'pointerenter', event => { if (event.pointerType === 'mouse') { hovered = true; updatePlayback(); } });
  on(album, 'pointerleave', () => { hovered = false; updatePlayback(); });
  on(hero, 'focusin', () => { paused = true; updatePlayback(); });
  on(document, 'visibilitychange', updatePlayback);
  on(simulateReduced, 'change', updatePlayback);
  on(preference, 'change', updatePlayback);
  const resize = new ResizeObserver(() => {
    const nextWidth = gallery.clientWidth;
    offset = width ? offset / width * nextWidth : 0;
    width = nextWidth;
    render();
  });
  resize.observe(gallery);
  const intersection = new IntersectionObserver(entries => {
    visible = entries[0].isIntersecting;
    updatePlayback();
  });
  intersection.observe(hero);
  updatePlayback();

  return {
    destroy() {
      destroyed = true;
      cancelAnimationFrame(frame);
      listeners.abort();
      resize.disconnect();
      intersection.disconnect();
      strip.remove();
      status.textContent = '';
    }
  };
}

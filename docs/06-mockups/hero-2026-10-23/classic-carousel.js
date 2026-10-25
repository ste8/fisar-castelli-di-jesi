'use strict';

function classicSlideIndex(index, count) {
  return ((index % count) + count) % count;
}

function classicCollageGroups(keys) {
  const PHOTOS_PER_COLLAGE = 6;
  if (!keys.length || keys.length % PHOTOS_PER_COLLAGE !== 0 || new Set(keys).size !== keys.length) {
    throw new Error('Ogni collage richiede sei foto, senza ripetizioni tra i gruppi.');
  }
  const groups = [];
  for (let index = 0; index < keys.length; index += PHOTOS_PER_COLLAGE) {
    groups.push(keys.slice(index, index + PHOTOS_PER_COLLAGE));
  }
  return groups;
}

function createClassicCollageCarousel(gallery, keys, makePhoto) {
  const INTERVAL_MS = 6000;
  const groups = classicCollageGroups(keys);
  const hero = gallery.closest('.hero');
  const album = gallery.closest('.hero__album');
  const controls = album.querySelector('.review-carousel-controls');
  const dots = controls.querySelector('.review-classic-dots');
  const pause = controls.querySelector('[data-action="pause"]');
  const status = controls.querySelector('[role="status"]');
  const note = document.querySelector('#motion-note');
  const simulateReduced = document.querySelector('#simulate-reduced-motion');
  const previewSelect = document.querySelector('#collage-preview');
  const preference = matchMedia('(prefers-reduced-motion: reduce)');
  const desktopLayout = matchMedia('(min-width: 64rem)');
  const listeners = new AbortController();
  const slides = groups.map(group => {
    const slide = document.createElement('div');
    slide.className = 'review-collage-slide';
    const panel = document.createElement('div');
    panel.className = 'review-photo-panel';
    panel.append(...group.map(key => makePhoto(key, false)));
    slide.append(panel);
    return slide;
  });
  let index = 0;
  let timer = 0;
  let paused = false;
  let hovered = false;
  let visible = true;
  let destroyed = false;
  let pointerToggle = null;
  const reduced = () => preference.matches || Boolean(simulateReduced?.checked);

  const indicators = slides.map((slide, position) => {
    const button = document.createElement('button');
    button.type = 'button';
    button.setAttribute('aria-label', `Mostra collage ${position + 1} di ${slides.length}`);
    button.title = `Collage ${position + 1} di ${slides.length}`;
    button.addEventListener('click', () => showPhoto(position, true), { signal: listeners.signal });
    return button;
  });
  gallery.replaceChildren(...slides);
  dots.replaceChildren(...indicators);
  previewSelect?.replaceChildren(...groups.map((group, position) => {
    const option = document.createElement('option');
    option.value = String(position);
    option.textContent = `Collage ${position + 1} di ${groups.length}`;
    return option;
  }));

  function updatePlayback() {
    clearTimeout(timer);
    hero.classList.toggle('review-no-motion', reduced());
    pause.disabled = reduced();
    // Fixed, trusted icon markup; the complete action remains in the accessible name.
    pause.innerHTML = paused || reduced()
      ? '<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path d="m8 5 11 7-11 7Z" fill="currentColor" stroke="#160f10" stroke-width="2" stroke-linejoin="round"/></svg>'
      : '<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path d="M8 5v14M16 5v14" fill="none" stroke="#160f10" stroke-width="6"/><path d="M8 5v14M16 5v14" fill="none" stroke="currentColor" stroke-width="3"/></svg>';
    pause.setAttribute('aria-label', reduced() ? 'Cambio automatico disattivato' : paused ? 'Riprendi il cambio dei collage' : 'Metti in pausa il cambio dei collage');
    pause.title = pause.getAttribute('aria-label');
    if (note) note.textContent = !desktopLayout.matches
      ? 'Su questa larghezza il collage, il calice e i controlli sono nascosti. Il cambio automatico è sospeso: allarga la finestra per confrontare le immagini accanto al titolo.'
      : reduced()
        ? 'Movimento ridotto: collage fermo e nessuna dissolvenza. I pallini permettono comunque di scegliere il gruppo da vedere.'
        : 'Un collage di sei foto ogni sei secondi. I pallini sotto le foto scelgono il gruppo e fermano il cambio automatico; l’icona accanto mette in pausa o riprende il giro.';
    if (!destroyed && !paused && !hovered && !reduced() && desktopLayout.matches && visible && !document.hidden) {
      timer = setTimeout(() => showPhoto(index + 1, false), INTERVAL_MS);
    }
  }

  function showPhoto(nextIndex, manual) {
    index = classicSlideIndex(nextIndex, slides.length);
    if (previewSelect) previewSelect.value = String(index);
    slides.forEach((slide, position) => {
      const active = position === index;
      slide.classList.toggle('is-active', active);
      slide.setAttribute('aria-hidden', String(!active));
      indicators[position].setAttribute('aria-current', String(active));
    });
    if (manual) {
      paused = true;
      status.textContent = `Collage ${index + 1} di ${slides.length}, sei fotografie`;
    }
    updatePlayback();
  }

  const on = (target, event, handler) => target?.addEventListener(event, handler, { signal: listeners.signal });
  on(pause, 'pointerdown', () => { pointerToggle = !paused; });
  on(previewSelect, 'change', () => showPhoto(Number(previewSelect.value), true));
  on(pause, 'pointercancel', () => { pointerToggle = null; });
  on(pause, 'click', () => {
    paused = pointerToggle === null ? !paused : pointerToggle;
    pointerToggle = null;
    updatePlayback();
  });
  on(controls.querySelector('[data-action="previous"]'), 'click', () => showPhoto(index - 1, true));
  on(controls.querySelector('[data-action="next"]'), 'click', () => showPhoto(index + 1, true));
  on(controls, 'keydown', event => {
    if (!['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
    event.preventDefault();
    showPhoto(index + (event.key === 'ArrowRight' ? 1 : -1), true);
  });
  on(hero, 'focusin', () => { paused = true; updatePlayback(); });
  on(album, 'pointerenter', event => { if (event.pointerType === 'mouse') { hovered = true; updatePlayback(); } });
  on(album, 'pointerleave', () => { hovered = false; updatePlayback(); });
  on(document, 'visibilitychange', updatePlayback);
  on(simulateReduced, 'change', updatePlayback);
  on(preference, 'change', updatePlayback);
  on(desktopLayout, 'change', updatePlayback);
  const intersection = new IntersectionObserver(entries => {
    visible = entries[0].isIntersecting;
    updatePlayback();
  });
  intersection.observe(hero);
  showPhoto(0, false);

  return {
    destroy() {
      destroyed = true;
      clearTimeout(timer);
      listeners.abort();
      intersection.disconnect();
      dots.replaceChildren();
      previewSelect?.replaceChildren();
      status.textContent = '';
      pause.title = '';
      hero.classList.remove('review-no-motion');
    }
  };
}

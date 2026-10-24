'use strict';

// Progressive enhancement: without JavaScript the first server-rendered collage remains.
document.querySelectorAll('[data-hero-carousel]').forEach(gallery => {
  const hero = gallery.closest('.hero');
  const album = gallery.closest('.hero__album');
  const slides = [...gallery.querySelectorAll('.hero-collage__slide')];
  const controls = album.querySelector('.hero-collage__controls');
  if (!controls || slides.length < 2) return;
  const dots = [...controls.querySelectorAll('[data-collage]')];
  const pause = controls.querySelector('[data-pause]');
  const status = controls.querySelector('[role="status"]');
  const reduced = matchMedia('(prefers-reduced-motion: reduce)');
  const desktop = matchMedia('(min-width: 64rem)');
  const INTERVAL_MS = 6000;
  let index = 0;
  let timer = 0;
  let paused = false;
  let hovered = false;
  let visible = true;
  let pointerToggle = null;

  function updatePlayback() {
    clearTimeout(timer);
    pause.disabled = reduced.matches;
    pause.setAttribute('aria-label', reduced.matches ? 'Cambio automatico disattivato' : paused ? 'Riprendi il cambio dei collage' : 'Metti in pausa il cambio dei collage');
    pause.title = pause.getAttribute('aria-label');
    // Fixed decorative markup; the action has a complete accessible name.
    pause.innerHTML = paused || reduced.matches
      ? '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m8 5 11 7-11 7Z" fill="currentColor" stroke="#160f10" stroke-width="2" stroke-linejoin="round"/></svg>'
      : '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M8 5v14M16 5v14" fill="none" stroke="#160f10" stroke-width="6"/><path d="M8 5v14M16 5v14" fill="none" stroke="currentColor" stroke-width="3"/></svg>';
    if (!paused && !hovered && !reduced.matches && desktop.matches && visible && !document.hidden) {
      timer = setTimeout(() => showCollage(index + 1, false), INTERVAL_MS);
    }
  }

  function showCollage(next, manual) {
    index = ((next % slides.length) + slides.length) % slides.length;
    slides.forEach((slide, position) => {
      const active = position === index;
      slide.classList.toggle('is-active', active);
      slide.setAttribute('aria-hidden', String(!active));
      dots[position].setAttribute('aria-current', String(active));
    });
    if (manual) {
      paused = true;
      status.textContent = `Collage ${index + 1} di ${slides.length}`;
    }
    updatePlayback();
  }

  dots.forEach((dot, position) => dot.addEventListener('click', () => showCollage(position, true)));
  // Capture the intended pointer action before focusin pauses the carousel.
  pause.addEventListener('pointerdown', () => { pointerToggle = !paused; });
  pause.addEventListener('pointercancel', () => { pointerToggle = null; });
  pause.addEventListener('click', () => {
    paused = pointerToggle === null ? !paused : pointerToggle;
    pointerToggle = null;
    updatePlayback();
  });
  controls.addEventListener('keydown', event => {
    if (!['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
    event.preventDefault();
    showCollage(index + (event.key === 'ArrowRight' ? 1 : -1), true);
  });
  hero.addEventListener('focusin', () => { paused = true; updatePlayback(); });
  album.addEventListener('pointerenter', event => { if (event.pointerType === 'mouse') { hovered = true; updatePlayback(); } });
  album.addEventListener('pointerleave', () => { hovered = false; updatePlayback(); });
  document.addEventListener('visibilitychange', updatePlayback);
  reduced.addEventListener('change', updatePlayback);
  desktop.addEventListener('change', updatePlayback);
  const observer = new IntersectionObserver(entries => {
    visible = entries[0].isIntersecting;
    updatePlayback();
  });
  observer.observe(hero);
  controls.hidden = false;
  updatePlayback();
});

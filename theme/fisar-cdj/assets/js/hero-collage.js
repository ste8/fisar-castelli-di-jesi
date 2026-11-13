'use strict';

// Without JavaScript the first server-rendered collage and the native glass remain.
document.querySelectorAll('[data-hero-carousel]').forEach(gallery => {
  const hero = gallery.closest('.hero');
  const album = gallery.closest('.hero__album');
  const slides = [...gallery.querySelectorAll('.hero-collage__slide')];
  if (!slides.length) return;
  const controls = album.querySelector('.hero-collage__controls');
  const dots = controls ? [...controls.querySelectorAll('[data-collage]')] : [];
  const pause = controls?.querySelector('[data-pause]');
  const status = controls?.querySelector('[role="status"]');
  const reduced = matchMedia('(prefers-reduced-motion: reduce)');
  const desktop = matchMedia('(min-width: 64rem)');
  const INTERVAL_MS = 6000;
  let index = 0, timer, paused = false, hovered = false, visible = true, pointerToggle = null;
  let animations = [], motion = 0, entered = false, wine;
  const figures = slide => [...slide.querySelectorAll('figure')];
  const canAnimate = () => desktop.matches && !reduced.matches && visible && !document.hidden;

  function setActive() {
    slides.forEach((slide, position) => {
      const active = position === index;
      slide.classList.toggle('is-active', active);
      slide.classList.remove('is-leaving');
      slide.setAttribute('aria-hidden', String(!active));
      dots[position]?.setAttribute('aria-current', String(active));
    });
  }

  function settleMotion() {
    motion++;
    animations.forEach(animation => animation.cancel());
    animations = [];
    wine?.finish();
    setActive();
  }

  function animate(target, keyframes, options) {
    const animation = target.animate(keyframes, { fill: 'both', easing: 'cubic-bezier(.2,.7,.25,1)', ...options });
    animations.push(animation);
  }

  function finishPhotosWhenReady(id) {
    void Promise.allSettled(animations.map(animation => animation.finished)).then(() => {
      if (id !== motion) return;
      animations.forEach(animation => animation.cancel());
      animations = [];
      setActive();
    });
  }

  function startWine() {
    if (!wine) wine = window.fisarCdjCreateHeroWine?.(album.querySelector('.hero__glass'));
    wine?.start();
  }

  function updatePlayback() {
    clearTimeout(timer);
    if (pause) {
      pause.disabled = reduced.matches;
      pause.setAttribute('aria-label', reduced.matches ? 'Cambio automatico disattivato' : paused ? 'Riprendi il cambio dei collage' : 'Metti in pausa il cambio dei collage');
      pause.title = pause.getAttribute('aria-label');
      pause.innerHTML = paused || reduced.matches
        ? '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m8 5 11 7-11 7Z" fill="currentColor" stroke="#160f10" stroke-width="2" stroke-linejoin="round"/></svg>'
        : '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M8 5v14M16 5v14" fill="none" stroke="#160f10" stroke-width="6"/><path d="M8 5v14M16 5v14" fill="none" stroke="currentColor" stroke-width="3"/></svg>';
    }
    if (slides.length > 1 && !paused && !hovered && canAnimate()) timer = setTimeout(() => showCollage(index + 1, false), INTERVAL_MS);
  }

  async function enterAlbum() {
    if (entered || !canAnimate()) return;
    entered = true;
    const id = motion;
    await Promise.allSettled([...slides[0].querySelectorAll('img')].map(image => image.decode?.()));
    if (id !== motion || index !== 0 || paused || !canAnimate()) return;
    const photos = figures(slides[0]);
    if (!photos.every(photo => typeof photo.animate === 'function')) return;
    photos.forEach((photo, position) => {
      const rotation = position % 2 ? -3 : 2;
      animate(photo, [{ opacity: 0, transform: `translate(${45 + position * 5}px,${28 + position * 4}px) rotate(${rotation + 7}deg)` },
        { opacity: 1, transform: `translate(0,0) rotate(${rotation}deg)` }], { duration: 740, delay: position * 130 });
    });
    startWine();
    finishPhotosWhenReady(id);
    updatePlayback();
  }

  function showCollage(next, manual) {
    const target = ((next % slides.length) + slides.length) % slides.length;
    const previous = index;
    settleMotion();
    index = target;
    setActive();
    if (manual) {
      paused = true;
      if (status) status.textContent = `Collage ${index + 1} di ${slides.length}`;
    }
    const incoming = figures(slides[index]);
    if (previous !== index && canAnimate() && incoming.every(photo => typeof photo.animate === 'function')) {
      slides[previous].classList.add('is-leaving');
      figures(slides[previous]).forEach((photo, position) => {
        const rotation = position % 2 ? -3 : 2;
        animate(photo, [{ opacity: 1, transform: `rotate(${rotation}deg)` },
          { opacity: 0, transform: `translate(-42px,18px) rotate(${rotation - 7}deg)` }], { duration: 440, delay: 320 + position * 180 });
      });
      incoming.forEach((photo, position) => {
        const rotation = position % 2 ? -3 : 2;
        animate(photo, [{ opacity: 0, transform: `translate(55px,-16px) rotate(${rotation + 8}deg)` },
          { opacity: 1, transform: `rotate(${rotation}deg)` }], { duration: 620, delay: 400 + position * 180 });
      });
      finishPhotosWhenReady(motion);
    }
    updatePlayback();
  }

  dots.forEach((dot, position) => dot.addEventListener('click', () => showCollage(position, true)));
  pause?.addEventListener('pointerdown', () => { pointerToggle = !paused; });
  pause?.addEventListener('pointercancel', () => { pointerToggle = null; });
  pause?.addEventListener('click', () => {
    paused = pointerToggle === null ? !paused : pointerToggle;
    pointerToggle = null;
    animations.forEach(animation => paused ? animation.pause() : animation.play());
    if (paused) wine?.pause(); else wine?.resume();
    updatePlayback();
  });
  controls?.addEventListener('keydown', event => {
    if (!['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
    event.preventDefault();
    showCollage(index + (event.key === 'ArrowRight' ? 1 : -1), true);
  });
  hero.addEventListener('focusin', event => {
    // This control expresses the user's intent; late browser focus must not undo Resume.
    if (event?.target === pause) return;
    paused = true;
    animations.forEach(animation => animation.pause());
    wine?.pause();
    updatePlayback();
  });
  album.addEventListener('pointerenter', event => { if (event.pointerType === 'mouse') { hovered = true; updatePlayback(); } });
  album.addEventListener('pointerleave', () => { hovered = false; updatePlayback(); });
  function environmentChanged() {
    if (!canAnimate()) settleMotion();
    else void enterAlbum();
    updatePlayback();
  }
  document.addEventListener('visibilitychange', environmentChanged);
  reduced.addEventListener('change', environmentChanged);
  desktop.addEventListener('change', environmentChanged);
  new IntersectionObserver(entries => { visible = entries[0].isIntersecting; environmentChanged(); }).observe(hero);
  window.addEventListener('pagehide', () => { clearTimeout(timer); settleMotion(); wine?.dispose(); wine = null; });
  window.addEventListener('pageshow', event => { if (event.persisted) environmentChanged(); });
  if (controls) controls.hidden = false;
  void enterAlbum();
  updatePlayback();
});

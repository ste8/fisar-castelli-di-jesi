'use strict';

// Optional native SVG/WAAPI decoration. All names, cards and destinations stay native and still.
(() => {
  const grid = document.querySelector('.follow-channels--motion');
  if (!grid || !Element.prototype.animate) return;
  const reduced = matchMedia('(prefers-reduced-motion: reduce)');
  const cards = [...grid.querySelectorAll('.follow-card')];
  const states = cards.map(card => ({ card, animations: [], hovering: false, run: 0 }));
  const timing = { duration: 850, easing: 'ease-in-out' };

  function animate(state, element, frames, options = {}) {
    if (!element) return;
    const animation = element.animate(frames, { ...timing, ...options });
    state.animations.push(animation);
  }
  function settle(state) {
    ++state.run;
    state.animations.forEach(animation => animation.cancel());
    state.animations = [];
    state.hovering = false;
    state.card.dataset.iconEffect = 'idle';
  }
  async function effect(state) {
    if (reduced.matches || document.hidden || state.hovering) return;
    const card = state.card;
    const run = ++state.run;
    state.hovering = true;
    card.dataset.iconEffect = 'running';
    state.animations = [];
    try {
      if (card.classList.contains('follow-card--whatsapp')) {
        animate(state, card.querySelector('.follow-icon__dots'), [{ opacity: 0, transform: 'translateY(3px)' }, { opacity: 1, transform: 'translateY(0)', offset: .2 }, { opacity: 1, offset: .75 }, { opacity: 0 }], { duration: 1100 });
        card.querySelectorAll('.follow-icon__dots i').forEach((dot, i) => animate(state, dot,
          [{ transform: 'translateY(0)' }, { transform: 'translateY(-3px)', offset: .4 }, { transform: 'translateY(0)' }], { duration: 480, delay: 140 + i * 120 }));
      } else if (card.classList.contains('follow-card--instagram')) {
        animate(state, card.querySelector('.follow-icon__shine'), [{ opacity: 0, transform: 'scale(.4) rotate(-20deg)' }, { opacity: 1, transform: 'scale(1.1) rotate(0deg)', offset: .3 }, { opacity: 1, transform: 'scale(1) rotate(6deg)', offset: .65 }, { opacity: 0, transform: 'scale(.8) rotate(15deg)' }], { duration: 1600 });
      } else if (card.classList.contains('follow-card--facebook')) {
        animate(state, card.querySelector('.follow-icon__ring'), [{ opacity: 0, transform: 'scale(.7)' }, { opacity: .65, offset: .2 }, { opacity: 0, transform: 'scale(1.35)' }]);
      } else if (card.classList.contains('follow-card--newsletter')) {
        animate(state, card.querySelector('.follow-icon__flap'), [{ transform: 'scaleY(1)' }, { transform: 'scaleY(-1)', offset: .28 }, { transform: 'scaleY(-1)', offset: .65 }, { transform: 'scaleY(1)' }], { duration: 1200 });
        animate(state, card.querySelector('.follow-icon__paper'), [{ opacity: 0, transform: 'translateY(0)' }, { opacity: 1, transform: 'translateY(-7px)', offset: .35 }, { opacity: 1, transform: 'translateY(-7px)', offset: .65 }, { opacity: 0, transform: 'translateY(0)' }], { duration: 1050, delay: 150 });
      }
      await Promise.all(state.animations.map(animation => animation.finished));
    } catch (_) { /* Cancellation or unsupported effect leaves the native icon intact. */ }
    finally {
      if (run === state.run) { state.animations = []; state.hovering = false; card.dataset.iconEffect = 'idle'; }
    }
  }
  states.forEach(state => {
    state.card.dataset.iconEffect = 'idle';
    state.card.addEventListener('pointerenter', event => { if (event.pointerType === 'mouse') void effect(state); });
    state.card.addEventListener('focusin', event => { if (!state.card.contains(event.relatedTarget)) void effect(state); });
  });
  reduced.addEventListener('change', () => { if (reduced.matches) states.forEach(settle); });
  document.addEventListener('visibilitychange', () => { if (document.hidden) states.forEach(settle); });
  window.addEventListener('pagehide', () => { states.forEach(settle); });
})();

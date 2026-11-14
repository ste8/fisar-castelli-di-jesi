'use strict';

// Optional decoration: the content and complete SVG remain usable without the player.
(() => {
  const figure = document.querySelector('.training-journey');
  if (!figure || !('IntersectionObserver' in window) || !('WebGL2RenderingContext' in window)) return;
  const canvas = figure.querySelector('canvas');
  const replay = figure.querySelector('button');
  const reduced = matchMedia('(prefers-reduced-motion: reduce)');
  let player, animation, ready, frame, runId = 0, running = false, inView = false, played = false, failed = false;

  function staticImage() {
    canvas.hidden = true;
    figure.classList.remove('is-rendered');
  }
  function fail(error) {
    if (failed) return;
    failed = true;
    ++runId;
    cancelAnimationFrame(frame);
    running = false;
    staticImage();
    replay.hidden = true;
    player?.dispose();
    console.warn('Percorso animato non disponibile: illustrazione statica mantenuta.', error);
  }
  function finish() {
    ++runId;
    running = false;
    cancelAnimationFrame(frame);
    replay.disabled = false;
    if (player && !failed) {
      try { player.seek(animation.timing.durationTicks - 1); } catch (error) { fail(error); }
    }
  }
  async function prepare() {
    if (!ready) ready = (async () => {
      const [runtime, document] = await Promise.all([
        import(figure.dataset.player),
        fetch(figure.dataset.document).then(response => {
          if (!response.ok) throw new Error('Documento del percorso non disponibile.');
          return response.json();
        })
      ]);
      animation = document;
      const coverage = await runtime.checkCoverage(animation);
      if (!coverage.supported) throw new Error(coverage.reasons.join('; '));
      canvas.hidden = false;
      canvas.style.visibility = 'hidden';
      player = await runtime.createPlayer({canvas, document: animation, loop: false, muted: true,
        maxPixelRatio: 2, releaseWhenOffscreen: false, onError: fail});
      if (failed) { player.dispose(); return; }
      await player.seek(0);
      await new Promise(resolve => requestAnimationFrame(resolve));
      await player.seek(0);
    })();
    return ready;
  }
  async function start() {
    if (running || failed || reduced.matches || document.hidden || !inView) return;
    const id = ++runId;
    played = running = true;
    replay.disabled = true;
    try {
      await prepare();
      if (failed) return;
      replay.hidden = reduced.matches;
      replay.disabled = running;
      if (id !== runId) return;
      await player.seek(0);
      canvas.style.visibility = '';
      canvas.hidden = false;
      figure.classList.add('is-rendered');
      replay.hidden = false;
      const startTime = performance.now();
      let lastFrame = -1;
      const duration = animation.timing.durationTicks * 1000 / animation.timing.ticksPerSecond;
      async function draw(now) {
        if (!running || id !== runId) return;
        const elapsed = now - startTime;
        if (elapsed >= duration) { finish(); return; }
        const position = Math.floor(elapsed / (1000 / 30));
        try {
          if (position !== lastFrame) {
            lastFrame = position;
            await player.seek(Math.round(elapsed * animation.timing.ticksPerSecond / 1000));
          }
          if (running && id === runId) frame = requestAnimationFrame(time => { void draw(time); });
        } catch (error) { fail(error); }
      }
      frame = requestAnimationFrame(time => { void draw(time); });
    } catch (error) { fail(error); }
  }
  const observer = new IntersectionObserver(entries => {
    inView = entries[0].isIntersecting && entries[0].intersectionRatio >= .25;
    if (inView && !played) void start();
    else if (!inView && running) finish();
  }, {threshold: .25});
  observer.observe(figure);
  replay.addEventListener('click', () => { void start(); });
  reduced.addEventListener('change', () => {
    if (reduced.matches) { finish(); staticImage(); replay.hidden = true; }
    else if (!played && inView) void start();
    else if (player && !failed) replay.hidden = false;
  });
  document.addEventListener('visibilitychange', () => { if (document.hidden && running) finish(); });
  window.addEventListener('pagehide', event => {
    finish();
    if (!event.persisted) { observer.disconnect(); failed = true; player?.dispose(); }
  });
})();

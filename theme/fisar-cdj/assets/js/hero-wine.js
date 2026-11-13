'use strict';

// Optional decoration: the native image remains the fallback and the source of truth.
window.fisarCdjCreateHeroWine = function (glass) {
  const image = glass?.querySelector('img');
  if (!image || !glass.dataset.wineDocument || !glass.dataset.winePlayer || !('WebGL2RenderingContext' in window)) return null;
  const canvas = document.createElement('canvas');
  canvas.className = 'hero__wine';
  canvas.hidden = true;
  canvas.setAttribute('aria-hidden', 'true');
  glass.append(canvas);
  const trigger = document.createElement('span');
  trigger.className = 'hero__wine-trigger';
  trigger.setAttribute('aria-hidden', 'true');
  glass.append(trigger);
  const reduced = matchMedia('(prefers-reduced-motion: reduce)');
  const duration = 4800;
  let player, ready, frame, token = 0, running = false, elapsed = 0, started = 0, lastFrame = -1, disabled = false;
  let painting = Promise.resolve(), pointer = null;

  function restoreImage() {
    canvas.hidden = true;
    canvas.style.visibility = '';
    glass.classList.remove('has-wine-renderer');
    glass.classList.remove('has-wine-motion');
  }

  // Serialize renders so an old frame cannot overwrite a later stop or restart.
  function paint(tick, id) {
    painting = painting.then(async () => {
      if (disabled || token !== id || !player) return;
      await player.seek(tick);
      if (disabled || token !== id) return;
      canvas.hidden = false;
      canvas.style.visibility = '';
      if (!glass.classList.contains('has-wine-renderer')) glass.classList.add('has-wine-renderer');
    }).catch(error => { if (token === id) fail(error); });
    return painting;
  }

  function finish() {
    const id = ++token;
    running = false;
    elapsed = duration;
    cancelAnimationFrame(frame);
    glass.classList.remove('has-wine-motion');
    player?.pause();
    // Keep the already-presented glass on screen. Never hide/reveal the canvas on replay.
    if (player && !disabled) void paint(0, id);
  }

  function fail(error) {
    if (disabled) return;
    disabled = true;
    finish();
    restoreImage();
    player?.dispose();
    player = null;
    console.warn('Calice animato non disponibile: immagine statica mantenuta.', error);
  }

  async function prepare() {
    if (ready) return ready;
    ready = (async () => {
      const [runtime, animation, bytes] = await Promise.all([
        import(glass.dataset.winePlayer),
        fetch(glass.dataset.wineDocument).then(response => {
          if (!response.ok) throw new Error('Documento del calice non disponibile.');
          return response.json();
        }),
        fetch(image.currentSrc || image.src).then(response => {
          if (!response.ok) throw new Error('Immagine del calice non disponibile.');
          return response.arrayBuffer();
        }).then(buffer => new Uint8Array(buffer))
      ]);
      const coverage = await runtime.checkCoverage(animation);
      if (!coverage.supported) throw new Error(coverage.reasons.join('; '));
      // Measure the final canvas size before its first presentation, without hiding the native image.
      canvas.style.visibility = 'hidden';
      canvas.hidden = false;
      const created = await runtime.createPlayer({ canvas, document: animation, loop: false, muted: true,
        maxPixelRatio: 2, releaseWhenOffscreen: false, loaders: { imageAssetLoader: () => bytes }, onError: fail });
      if (disabled) { created.dispose(); return; }
      player = created;
      await player.seek(0);
      await new Promise(resolve => requestAnimationFrame(resolve));
      await player.seek(0);
    })();
    return ready;
  }

  async function draw(id, now) {
    if (!running || token !== id || !player) return;
    elapsed = Math.min(duration, now - started);
    if (elapsed >= duration) { finish(); return; }
    const position = Math.floor(elapsed / (1000 / 30));
    if (position !== lastFrame) {
      lastFrame = position;
      await paint(Math.round(elapsed * 240 / 1000), id);
      if (!running || token !== id) return;
      glass.classList.add('has-wine-motion');
    }
    frame = requestAnimationFrame(time => { void draw(id, time); });
  }

  function activate() {
    const id = ++token;
    running = true;
    started = performance.now() - elapsed;
    void prepare().then(() => {
      if (id === token && running && !disabled) void draw(id, performance.now());
    }).catch(error => { if (!disabled) fail(error); });
  }

  function start() {
    if (disabled) return;
    elapsed = 0;
    lastFrame = -1;
    activate();
  }

  function reactToMouse(event) {
    if (event.pointerType !== 'mouse' || document.hidden || reduced.matches || running || elapsed < duration) return;
    start();
  }
  trigger.addEventListener('pointerenter', event => {
    pointer = { x: event.clientX, y: event.clientY };
    reactToMouse(event);
  });
  trigger.addEventListener('pointermove', event => {
    if (event.pointerType !== 'mouse') return;
    const next = { x: event.clientX, y: event.clientY };
    if (pointer && Math.hypot(next.x - pointer.x, next.y - pointer.y) < 4) return;
    pointer = next;
    // Movement can replay a settled wave without requiring an exit, but never resets a live one.
    reactToMouse(event);
  });
  trigger.addEventListener('pointerleave', () => { pointer = null; });

  return {
    start,
    pause() {
      if (!running) return;
      elapsed = Math.min(duration, performance.now() - started);
      token++;
      running = false;
      cancelAnimationFrame(frame);
    },
    resume() { if (!disabled && !running && elapsed < duration) activate(); },
    finish,
    dispose() { disabled = true; finish(); restoreImage(); player?.dispose(); canvas.remove(); trigger.remove(); }
  };
};

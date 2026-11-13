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
  const duration = 4800;
  let player, ready, frame, token = 0, running = false, elapsed = 0, started = 0, lastFrame = -1, disabled = false;

  function finish() {
    token++;
    running = false;
    elapsed = duration;
    cancelAnimationFrame(frame);
    canvas.hidden = true;
    glass.classList.remove('has-wine-motion');
    player?.pause();
  }

  function fail(error) {
    if (disabled) return;
    finish();
    disabled = true;
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
      const created = await runtime.createPlayer({ canvas, document: animation, loop: false, muted: true,
        maxPixelRatio: 2, loaders: { imageAssetLoader: () => bytes }, onError: fail });
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
      try { await player.seek(Math.round(elapsed * 240 / 1000)); }
      catch (error) { if (token === id) fail(error); return; }
      if (!running || token !== id) return;
      canvas.hidden = false;
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

  return {
    start() { if (!disabled) { elapsed = 0; lastFrame = -1; activate(); } },
    pause() {
      if (!running) return;
      elapsed = Math.min(duration, performance.now() - started);
      token++;
      running = false;
      cancelAnimationFrame(frame);
    },
    resume() { if (!disabled && !running && elapsed < duration) activate(); },
    finish,
    dispose() { finish(); disabled = true; player?.dispose(); canvas.remove(); }
  };
};

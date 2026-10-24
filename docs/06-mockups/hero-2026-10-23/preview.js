'use strict';

async function startReview() {
  const response = await fetch('photos.json');
  if (!response.ok) throw new Error('Impossibile caricare la selezione fotografica.');
  const { photos, variants } = await response.json();
  const gallery = document.querySelector('.hero-collage');
  const sources = document.querySelector('.review-sources');
  const controls = document.querySelector('.review-controls');
  const buttons = new Map();
  let carousel = null;

  function makePhoto(key, showCaption) {
    const photo = photos[key];
    const figure = document.createElement('figure');
    figure.className = 'wp-block-image';
    figure.style.setProperty('--photo-focus', photo.focus);
    const image = document.createElement('img');
    image.src = `images/${key}.webp`;
    image.alt = photo.alt;
    image.decoding = 'async';
    figure.append(image);
    if (showCaption) {
      const caption = document.createElement('figcaption');
      caption.textContent = `${photo.alt} · ${photo.original}`;
      figure.append(caption);
    }
    return figure;
  }

  function selectVariant(variant) {
    if (carousel) carousel.destroy();
    carousel = null;
    gallery.classList.toggle('review-moving', Boolean(variant.motion && !variant.classic));
    gallery.classList.toggle('review-classic', Boolean(variant.classic));
    document.querySelector('.hero').classList.toggle('hero--review-moving', Boolean(variant.motion));
    document.querySelector('.hero').classList.toggle('hero--review-classic', Boolean(variant.classic));
    document.querySelector('.review-carousel-controls').classList.toggle('classic-controls', Boolean(variant.classic));
    document.querySelector('.review-classic-dots').hidden = !variant.classic;
    document.querySelector('[data-action="previous"]').hidden = Boolean(variant.classic);
    document.querySelector('[data-action="next"]').hidden = Boolean(variant.classic);
    document.querySelector('#collage-select-label').hidden = !variant.classic;
    document.querySelector('[data-action="previous"]').setAttribute('aria-label', variant.classic ? 'Mostra il collage precedente' : 'Mostra le foto precedenti');
    document.querySelector('[data-action="next"]').setAttribute('aria-label', variant.classic ? 'Mostra il collage successivo' : 'Mostra le foto successive');
    document.querySelector('.review-carousel-controls').hidden = !variant.motion;
    document.querySelector('#reduced-motion-label').hidden = !variant.motion;
    document.querySelector('#motion-note').hidden = !variant.motion;
    if (variant.classic) {
      carousel = createClassicCollageCarousel(gallery, variant.photos, makePhoto);
    } else if (variant.motion) {
      carousel = createPhotoCarousel(gallery, variant.photos, makePhoto);
    } else {
      gallery.replaceChildren(...variant.photos.map(key => makePhoto(key, false)));
    }
    sources.replaceChildren(...variant.photos.map(key => makePhoto(key, true)));
    document.querySelector('#variant-title').textContent = `${variant.id} — ${variant.title}`;
    document.querySelector('#variant-description').textContent = variant.description;
    buttons.forEach((button, id) => button.setAttribute('aria-pressed', String(id === variant.id)));
    history.replaceState(null, '', `#${variant.id}`);
  }

  variants.forEach(variant => {
    const button = document.createElement('button');
    button.type = 'button';
    button.textContent = `${variant.id} · ${variant.title}`;
    button.addEventListener('click', () => selectVariant(variant));
    controls.append(button);
    buttons.set(variant.id, button);
  });

  const tone = document.querySelector('#photo-tone');
  tone.addEventListener('change', () => document.body.classList.toggle('review-toned', tone.checked));
  document.body.classList.toggle('review-toned', tone.checked);
  document.querySelector('#show-glass').addEventListener('change', event => {
    const visible = event.target.checked;
    document.querySelector('.hero').classList.toggle('hero--glass', visible);
    document.querySelector('.hero__glass').hidden = !visible;
  });
  selectVariant(variants.find(variant => `#${variant.id}` === location.hash) || variants[0]);
}

startReview().catch(error => {
  document.querySelector('#review-error').textContent = error.message;
});

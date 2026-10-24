'use strict';

// Versione approvata: nessun selettore o simulatore del provino.
async function startApprovedHero() {
  const response = await fetch('photos.json');
  if (!response.ok) throw new Error('Impossibile caricare la selezione fotografica.');
  const { photos, variants } = await response.json();
  const approved = variants.find(variant => variant.id === 'E');
  if (!approved) throw new Error('La selezione fotografica approvata non è disponibile.');
  const gallery = document.querySelector('.hero-collage');

  function makePhoto(key) {
    const photo = photos[key];
    const figure = document.createElement('figure');
    figure.className = 'wp-block-image';
    figure.style.setProperty('--photo-focus', photo.focus);
    const image = document.createElement('img');
    image.src = `images/${key}.webp`;
    image.alt = photo.alt;
    image.decoding = 'async';
    figure.append(image);
    return figure;
  }

  createClassicCollageCarousel(gallery, approved.photos, makePhoto);
  document.querySelector('.review-carousel-controls').hidden = false;
}

// Se il JSON non è disponibile, testo e CTA restano utilizzabili, senza
// controlli vuoti. Non mostrare un pannello diagnostico nella versione pulita.
startApprovedHero().catch(error => console.error(error));

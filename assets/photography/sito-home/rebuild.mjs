import { createHash } from 'node:crypto';
import { readFileSync, readdirSync, existsSync } from 'node:fs';
import { dirname, resolve, basename } from 'node:path';
import { fileURLToPath } from 'node:url';
import { execFileSync } from 'node:child_process';

const archive = dirname(fileURLToPath(import.meta.url));
const originals = resolve(archive, 'originals');
const derivatives = resolve(archive, 'optimized');
const manifest = JSON.parse(readFileSync(resolve(archive, 'selection.json'), 'utf8'));
const inventory = JSON.parse(readFileSync(resolve(archive, 'inventory.json'), 'utf8'));
const checkOnly = process.argv.includes('--check');
const recorded = new Set(inventory.files.map(entry => entry.file));
const actual = readdirSync(originals).filter(name => /\.(jpe?g|png|webp)$/i.test(name));
if (actual.length !== recorded.size || actual.some(name => !recorded.has(name))) {
  throw new Error('Inventario non allineato: registrare i nuovi originali prima di rigenerare.');
}
for (const entry of inventory.files) {
  if (basename(entry.file) !== entry.file) throw new Error('Nome originale non valido.');
  const bytes = readFileSync(resolve(originals, entry.file));
  const hash = createHash('sha256').update(bytes).digest('hex');
  if (bytes.length !== entry.bytes || hash !== entry.sha256) {
    throw new Error(`Originale diverso dall'inventario: ${entry.file}`);
  }
}
// Validare tutto prima di scrivere qualsiasi derivato.
for (const [key, photo] of Object.entries(manifest.photos)) {
  if (!/^[a-z0-9-]+$/.test(key) || !recorded.has(photo.original)) {
    throw new Error(`Selezione non valida: ${key}`);
  }
  if (checkOnly && !existsSync(resolve(derivatives, `${key}.webp`))) {
    throw new Error(`Derivato mancante: ${key}`);
  }
}
const approved = manifest.variants.find(variant => variant.id === 'E');
if (!approved || !approved.photos.length || approved.photos.length % 6 !== 0) {
  throw new Error('La selezione E deve contenere gruppi completi di sei foto.');
}
const hashes = approved.photos.map(key => {
  const photo = manifest.photos[key];
  if (!photo) throw new Error(`Foto non definita: ${key}`);
  return inventory.files.find(entry => entry.file === photo.original).sha256;
});
if (new Set(hashes).size !== hashes.length) {
  throw new Error('La selezione E contiene fotografie ripetute, anche se con nomi diversi.');
}
if (!checkOnly) {
  for (const [key, photo] of Object.entries(manifest.photos)) {
    execFileSync('magick', [resolve(originals, photo.original), '-auto-orient', '-resize', '1200x1200>', '-quality', '86', resolve(derivatives, `${key}.webp`)]);
  }
}
console.log(`${inventory.files.length} originali verificati; ${Object.keys(manifest.photos).length} derivati ${checkOnly ? 'presenti' : 'rigenerati'}.`);

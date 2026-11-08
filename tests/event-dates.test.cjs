const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const source = fs.readFileSync(require('node:path').join(__dirname, '../theme/fisar-cdj/assets/js/event-dates.js'), 'utf8');
let rows = [];
const listeners = {};
const frames = [];
const observed = new Set();
class Observer {
	observe(row) { observed.add(row); }
	disconnect() { observed.clear(); }
}
function row(full, short) {
	const classes = new Set();
	const time = { width: 100, getBoundingClientRect() { return { width: this.width }; } };
	const label = { textContent: full, dataset: { dateShort: short }, getBoundingClientRect() { return { width: this.textContent.length * 10 }; } };
	return { time, label, classes, classList: { add: value => classes.add(value), remove: value => classes.delete(value) }, querySelector: selector => selector === 'time' || selector === '.event-card__date-value' ? time : label };
}
const window = { ResizeObserver: Observer, addEventListener: (name, callback) => { listeners[name] = callback; }, requestAnimationFrame: callback => frames.push(callback) };
vm.runInNewContext(source, { document: { querySelectorAll: () => rows }, window, ResizeObserver: Observer });
function flush() { while (frames.length) frames.shift()(); }
flush();
rows = [row('8 Ottobre 2026', '8 Ott 2026')];
listeners['fisar:archive-updated'](); flush();
assert.equal(rows[0].label.textContent, '8 Ott 2026', 'Dates added after an initially empty search are fitted.');
rows[0].time.width = 500;
listeners.resize(); flush();
assert.equal(rows[0].label.textContent, '8 Ottobre 2026');
rows[0].time.width = 100;
listeners.resize(); flush();
const previous = rows[0];
rows.push(row('9 Settembre 2025', '9 Set 2025'));
listeners['fisar:archive-updated'](); flush();
assert.equal(previous.label.dataset.dateFull, '8 Ottobre 2026', 'Rescanning must not replace the full date with its shortened label.');
rows = [rows[1]];
listeners['fisar:archive-updated'](); flush();
assert.equal(observed.size, 1, 'Detached result cards are no longer observed.');
assert.equal(observed.has(previous), false);
rows[0].time.width = 5;
listeners.resize(); flush();
assert.equal(rows[0].classes.has('has-responsive-date'), false, 'Extreme zoom still permits wrapping.');
console.log('Responsive event dates: new/empty results, rescanning, resize, observer cleanup and wrapping passed.');

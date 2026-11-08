const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const source = fs.readFileSync(path.join(__dirname, '../theme/fisar-cdj/assets/js/archive-filters.js'), 'utf8');
const tick = () => new Promise(resolve => setImmediate(resolve));

class Element {
	constructor(name = '') { this.name = name; this.value = ''; this.textContent = ''; this.handlers = {}; this.attributes = {}; this.children = []; this.dataset = {}; }
	addEventListener(name, callback) { this.handlers[name] = callback; }
	setAttribute(name, value) { this.attributes[name] = value; }
	get childNodes() { return this.children; }
	replaceChildren(...nodes) { this.children = nodes; }
	querySelector(selector) {
		if (selector === '.archive-results') return this.children.find(node => node.isCounter);
		if (selector === '[data-archive-reset]') return this.children.find(node => node.isReset);
		return null;
	}
	insertBefore(node, reference) { node.parent = this; this.children.splice(this.children.indexOf(reference), 0, node); }
	remove() { this.parent.children.splice(this.parent.children.indexOf(this), 1); }
	fire(name, extra = {}) {
		const event = { target: this, prevented: false, preventDefault() { this.prevented = true; }, ...extra };
		if (this.handlers[name]) this.handlers[name](event);
		return event;
	}
}

function setup(withFetch = true) {
	const names = ['fisar_text', 'fisar_year', 'fisar_month', 'fisar_category', 'fisar_per_page'];
	const fields = Object.fromEntries(names.map(name => [name, new Element(name)]));
	fields.fisar_per_page.value = '12';
	for (const field of Object.values(fields)) field.tagName = field.name === 'fisar_text' ? 'INPUT' : 'SELECT';
	const form = new Element();
	form.action = 'https://example.test/news/';
	form.dataset.archiveScope = 'blog';
	form.elements = Object.values(fields);
	form.querySelector = () => fields.fisar_text;
	const results = new Element();
	results.children = [Object.assign(new Element(), { textContent: 'Original results' })];
	const counter = Object.assign(new Element(), { isCounter: true, textContent: '32 risultati' });
	const summary = new Element();
	summary.children = [counter];
	const hint = new Element();
	const document = {
		title: 'Blog', activeElement: fields.fisar_text,
		querySelector: selector => ({ '.archive-filters': form, '[data-archive-results]': results, '[data-archive-summary]': summary })[selector],
		getElementById: () => hint,
		importNode: node => Object.assign(new Element(), { textContent: node.textContent, isReset: node.isReset, href: node.href }),
	};
	const location = { href: form.action, origin: 'https://example.test' };
	const handlers = {};
	const timers = new Map();
	const requests = [];
	const histories = [];
	const pages = new Map();
	let nextTimer = 0;
	function page(label, count, reset) {
		const nextResults = new Element();
		nextResults.children = [Object.assign(new Element(), { textContent: label })];
		const nextSummary = new Element();
		nextSummary.children = [Object.assign(new Element(), { textContent: count, isCounter: true })];
		if (reset) nextSummary.children.unshift(Object.assign(new Element(), { isReset: true, href: reset }));
		return { title: label, querySelector: selector => ({ '[data-archive-results]': nextResults, '[data-archive-summary]': nextSummary, '.archive-filters': form })[selector] };
	}
	const window = {
		location, AbortController,
		DOMParser: class { parseFromString(token) { return pages.get(token); } },
		setTimeout(callback, delay) { const id = ++nextTimer; timers.set(id, { callback, delay }); return id; },
		clearTimeout(id) { timers.delete(id); },
		addEventListener(name, callback) { handlers[name] = callback; },
		dispatchEvent(event) { window.lastEvent = event.type; },
		history: Object.fromEntries(['pushState', 'replaceState'].map(method => [method, (state, title, url) => { histories.push({ method, url }); location.href = url; }])),
	};
	if (withFetch) window.fetch = (url, options) => new Promise((resolve, reject) => {
		assert.equal(options.credentials, 'same-origin');
		const request = { url, signal: options.signal, reject, resolve(label, count = '1 risultato', reset = null, overrides = {}) {
			const token = 'page-' + pages.size;
			pages.set(token, page(label, count, reset));
			resolve({ ok: true, url, text: async () => token, ...overrides });
		} };
		requests.push(request);
	});
	vm.runInNewContext(source, { window, document, URL, CustomEvent: class { constructor(type) { this.type = type; } } });
	return { form, fields, results, counter, summary, hint, document, window, requests, histories, timers,
		flush() { const pending = [...timers.values()]; timers.clear(); pending.forEach(timer => timer.callback()); },
		change(name, value) { fields[name].value = value; form.fire('change', { target: fields[name] }); },
		fire: (name, event = {}) => handlers[name](event),
	};
}

async function run() {
	const select = setup();
	select.document.activeElement = select.fields.fisar_per_page;
	select.change('fisar_per_page', '24');
	select.flush();
	assert.equal(new URL(select.requests[0].url).search, '?fisar_per_page=24');
	select.requests[0].resolve('Twenty-four cards', '32 risultati');
	await tick();
	assert.equal(select.results.children[0].textContent, 'Twenty-four cards');
	assert.equal(select.document.activeElement, select.fields.fisar_per_page, 'Automatic updates preserve focus.');
	assert.equal(select.counter.textContent, '32 risultati');
	assert.equal(select.window.lastEvent, 'fisar:archive-updated');
	select.change('fisar_year', '2025'); select.flush();
	select.requests[1].resolve('Year 2025', '17 risultati', 'https://example.test/news/?fisar_per_page=24'); await tick();
	assert.ok(select.summary.querySelector('[data-archive-reset]').href.includes('fisar_per_page=24'));
	const historyCount = select.histories.length;
	select.window.location.href = 'https://example.test/news/?fisar_per_page=24';
	select.fire('popstate');
	assert.equal(select.fields.fisar_year.value, '');
	assert.equal(select.fields.fisar_per_page.value, '24');
	select.requests[2].resolve('Back to 24', '32 risultati'); await tick();
	assert.equal(select.histories.length, historyCount, 'Back/forward must not create new history entries.');
	assert.equal(select.summary.querySelector('[data-archive-reset]'), undefined);

	const typing = setup();
	typing.fields.fisar_text.value = 'vin'; typing.fields.fisar_text.fire('input');
	typing.fields.fisar_text.value = 'vini giapponesi'; typing.fields.fisar_text.fire('input');
	assert.equal(typing.timers.size, 1);
	assert.equal([...typing.timers.values()][0].delay, 650);
	typing.flush();
	assert.equal(typing.requests.length, 1);
	typing.requests[0].resolve('Japanese wines'); await tick();
	assert.equal(typing.histories[0].method, 'replaceState', 'Typing should not fill the Back history with intermediate words.');
	assert.equal(typing.fields.fisar_text.value, 'vini giapponesi');

	const race = setup();
	race.change('fisar_year', '2025'); race.flush();
	race.change('fisar_year', '2026'); race.flush();
	assert.equal(race.requests[0].signal.aborted, true);
	race.requests[1].resolve('Newest results'); await tick();
	race.requests[0].resolve('Stale results'); await tick();
	assert.equal(race.results.children[0].textContent, 'Newest results');

	const ime = setup();
	ime.fields.fisar_text.fire('compositionstart');
	ime.fields.fisar_text.value = '日本'; ime.fields.fisar_text.fire('input');
	assert.equal(ime.timers.size, 0);
	assert.equal(ime.form.fire('submit').prevented, true);
	assert.equal(ime.requests.length, 0);
	ime.fields.fisar_text.fire('compositionend'); ime.flush();
	ime.requests[0].resolve('Composed text'); await tick();

	const failure = setup();
	failure.change('fisar_month', '3'); failure.flush();
	failure.requests[0].reject(new Error('offline')); await tick();
	assert.match(failure.counter.textContent, /Premi Filtra/);
	assert.equal(failure.form.fire('submit').prevented, false, 'The visible button must fall back to a native GET after network failure.');
	assert.equal(failure.results.children[0].textContent, 'Original results');

	const hidden = setup();
	hidden.change('fisar_year', '2025'); hidden.flush(); hidden.fire('pagehide');
	assert.equal(hidden.requests[0].signal.aborted, true);
	hidden.requests[0].resolve('Too late'); await tick();
	assert.equal(hidden.results.children[0].textContent, 'Original results');
	assert.equal(hidden.counter.textContent, '32 risultati');
	const fallback = setup(false);
	assert.equal(Object.keys(fallback.form.handlers).length, 0);
	console.log('Archive live filters: focus, debounce/IME, stale requests, URL/history, page size, native fallback and cleanup passed.');
}
run().catch(error => { console.error(error); process.exitCode = 1; });

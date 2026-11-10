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
	hasAttribute(name) { return Object.hasOwn(this.attributes, name); }
	closest(selector) { return this.matches === selector ? this : null; }
	focus(options) { this.focusOptions = options; this.focused = true; }
	scrollIntoView(options) { this.scrollOptions = options; }
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
		const event = { target: this, button: 0, prevented: false, preventDefault() { this.prevented = true; }, ...extra };
		if (this.handlers[name]) this.handlers[name](event);
		return event;
	}
}

function setup(withFetch = true, scope = 'blog') {
	const names = scope === 'courses' ? ['search', 'anno', 'tipologia', 'citta', 'per_pagina'] : ['search', 'anno', 'mese', 'categoria', 'per_pagina'];
	const fields = Object.fromEntries(names.map(name => [name, new Element(name)]));
	fields.per_pagina.value = '12';
	for (const field of Object.values(fields)) field.tagName = field.name === 'search' ? 'INPUT' : 'SELECT';
	const form = new Element();
	form.action = scope === 'courses' ? 'https://example.test/corsi/#past-courses' : 'https://example.test/news/';
	form.dataset.archiveScope = scope;
	form.elements = Object.values(fields);
	form.querySelector = () => fields.search;
	const results = new Element();
	results.children = [Object.assign(new Element(), { textContent: 'Original results' })];
	const counter = Object.assign(new Element(), { isCounter: true, textContent: '32 risultati' });
	const summary = new Element();
	summary.children = [counter];
	const hint = new Element();
	const document = {
		title: 'Blog', activeElement: fields.search,
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
	select.document.activeElement = select.fields.per_pagina;
	select.change('per_pagina', '24');
	select.flush();
	assert.equal(new URL(select.requests[0].url).search, '?per_pagina=24');
	select.requests[0].resolve('Twenty-four cards', '32 risultati');
	await tick();
	assert.equal(select.results.children[0].textContent, 'Twenty-four cards');
	assert.equal(select.document.activeElement, select.fields.per_pagina, 'Automatic updates preserve focus.');
	assert.equal(select.counter.textContent, '32 risultati');
	assert.equal(select.window.lastEvent, 'fisar:archive-updated');
	select.change('anno', '2025'); select.flush();
	select.requests[1].resolve('Year 2025', '17 risultati', 'https://example.test/news/?per_pagina=24'); await tick();
	assert.ok(select.summary.querySelector('[data-archive-reset]').href.includes('per_pagina=24'));
	const historyCount = select.histories.length;
	select.window.location.href = 'https://example.test/news/?per_pagina=24';
	select.fire('popstate');
	assert.equal(select.fields.anno.value, '');
	assert.equal(select.fields.per_pagina.value, '24');
	select.requests[2].resolve('Back to 24', '32 risultati'); await tick();
	assert.equal(select.histories.length, historyCount, 'Back/forward must not create new history entries.');
	assert.equal(select.summary.querySelector('[data-archive-reset]'), undefined);
	const legacy = setup();
	legacy.window.location.href = 'https://example.test/news/?fisar_text=wine&fisar_year=2025&fisar_month=3&fisar_category=servizi&fisar_per_page=24';
	legacy.fire('popstate');
	assert.equal(legacy.fields.search.value, 'wine');
	assert.equal(legacy.fields.anno.value, '2025');
	assert.equal(legacy.fields.mese.value, '3');
	assert.equal(legacy.fields.categoria.value, 'servizi');
	assert.equal(legacy.fields.per_pagina.value, '24');
	legacy.requests[0].resolve('Old link restored'); await tick();
	legacy.change('mese', '4'); legacy.flush();
	assert.equal(new URL(legacy.requests[1].url).search, '?search=wine&anno=2025&mese=4&categoria=servizi&per_pagina=24');
	legacy.requests[1].resolve('New names emitted'); await tick();
	legacy.window.location.href = 'https://example.test/news/?search=&fisar_text=wine';
	legacy.fire('popstate');
	assert.equal(legacy.fields.search.value, '', 'Explicit new values take precedence over legacy aliases, including an empty value.');
	legacy.requests[2].resolve('Empty search'); await tick();

	const typing = setup();
	typing.fields.search.value = 'vin'; typing.fields.search.fire('input');
	typing.fields.search.value = 'vini giapponesi'; typing.fields.search.fire('input');
	assert.equal(typing.timers.size, 1);
	assert.equal([...typing.timers.values()][0].delay, 650);
	typing.flush();
	assert.equal(typing.requests.length, 1);
	typing.requests[0].resolve('Japanese wines'); await tick();
	assert.equal(typing.histories[0].method, 'replaceState', 'Typing should not fill the Back history with intermediate words.');
	assert.equal(typing.fields.search.value, 'vini giapponesi');

	const race = setup();
	race.change('anno', '2025'); race.flush();
	race.change('anno', '2026'); race.flush();
	assert.equal(race.requests[0].signal.aborted, true);
	race.requests[1].resolve('Newest results'); await tick();
	race.requests[0].resolve('Stale results'); await tick();
	assert.equal(race.results.children[0].textContent, 'Newest results');

	const ime = setup();
	ime.fields.search.fire('compositionstart');
	ime.fields.search.value = '日本'; ime.fields.search.fire('input');
	assert.equal(ime.timers.size, 0);
	assert.equal(ime.form.fire('submit').prevented, true);
	assert.equal(ime.requests.length, 0);
	ime.fields.search.fire('compositionend'); ime.flush();
	ime.requests[0].resolve('Composed text'); await tick();

	const failure = setup();
	failure.change('mese', '3'); failure.flush();
	failure.requests[0].reject(new Error('offline')); await tick();
	assert.match(failure.counter.textContent, /Premi Cerca/);
	assert.equal(failure.form.fire('submit').prevented, true, 'Retry must remain on the same document after network failure.');
	assert.equal(failure.results.children[0].textContent, 'Original results');
	assert.equal(failure.requests.length, 2);
	failure.requests[1].resolve('Retry succeeded'); await tick();
	assert.equal(failure.results.children[0].textContent, 'Retry succeeded');

	const navigation = setup();
	navigation.fields.anno.value = '2025';
	navigation.fields.search.value = 'wine';
	navigation.fields.per_pagina.value = '24';
	const reset = Object.assign(new Element(), { matches: '[data-archive-reset]', href: 'https://example.test/news/?per_pagina=24' });
	assert.equal(navigation.summary.fire('click', { target: reset }).prevented, true, 'Reset must not navigate the document.');
	assert.equal(navigation.fields.anno.value, '');
	assert.equal(navigation.fields.search.value, '');
	assert.equal(navigation.fields.per_pagina.value, '24');
	navigation.requests[0].resolve('All posts', '32 risultati'); await tick();
	assert.equal(navigation.fields.search.focused, true, 'Reset returns keyboard focus to the preserved search field.');
	assert.equal(navigation.fields.search.focusOptions.preventScroll, true);
	assert.equal(navigation.results.scrollOptions, undefined, 'Reset must preserve the scroll position.');
	const next = Object.assign(new Element(), { matches: '.pagination--archive a.page-numbers', href: 'https://example.test/news/page/2/?per_pagina=24' });
	assert.equal(navigation.results.fire('click', { target: next }).prevented, true);
	navigation.requests[1].resolve('Page two', '32 risultati'); await tick();
	assert.equal(navigation.results.children[0].textContent, 'Page two');
	assert.equal(navigation.results.focused, true);
	assert.equal(navigation.results.scrollOptions.block, 'start');
	assert.equal(navigation.window.location.href, next.href);
	assert.equal(navigation.results.attributes.tabindex, '-1');
	for (const modifier of ['metaKey', 'ctrlKey', 'shiftKey', 'altKey']) {
		assert.equal(navigation.results.fire('click', { target: next, [modifier]: true }).prevented, false);
	}
	assert.equal(navigation.results.fire('click', { target: next, button: 1 }).prevented, false);
	next.target = '_blank';
	assert.equal(navigation.results.fire('click', { target: next }).prevented, false);
	next.target = ''; next.setAttribute('download', '');
	assert.equal(navigation.results.fire('click', { target: next }).prevented, false);
	assert.equal(navigation.results.fire('click', { target: new Element() }).prevented, false, 'Article links retain normal navigation.');
	reset.href = 'https://external.test/news/';
	assert.equal(navigation.summary.fire('click', { target: reset }).prevented, false);
	assert.equal(navigation.requests.length, 2, 'Only plain same-origin reset/pagination clicks are enhanced.');

	const pendingReset = setup();
	pendingReset.change('anno', '2025'); pendingReset.flush();
	reset.href = 'https://example.test/news/';
	pendingReset.summary.fire('click', { target: reset });
	assert.equal(pendingReset.requests[0].signal.aborted, true);
	pendingReset.requests[1].resolve('Reset wins', '32 risultati'); await tick();
	pendingReset.requests[0].resolve('Old filtered list'); await tick();
	assert.equal(pendingReset.results.children[0].textContent, 'Reset wins');

	const hidden = setup();
	hidden.change('anno', '2025'); hidden.flush(); hidden.fire('pagehide');
	assert.equal(hidden.requests[0].signal.aborted, true);
	hidden.requests[0].resolve('Too late'); await tick();
	assert.equal(hidden.results.children[0].textContent, 'Original results');
	assert.equal(hidden.counter.textContent, '32 risultati');
	const courses = setup(true, 'courses');
	courses.fields.anno.value = '2022';
	courses.fields.tipologia.value = 'introductory';
	courses.change('citta', 'Città d’Arco'); courses.flush();
	const courseUrl = new URL(courses.requests[0].url);
	assert.equal(courseUrl.searchParams.get('citta'), 'Città d’Arco');
	assert.equal(courseUrl.searchParams.get('tipologia'), 'introductory');
	assert.equal(courseUrl.searchParams.has('mese'), false);
	courses.requests[0].resolve('Courses', '2 risultati'); await tick();
	const courseNext = Object.assign(new Element(), { matches: '.pagination--archive a.page-numbers', href: 'https://example.test/corsi/?anno=2022&tipologia=introductory&citta=Citt%C3%A0%20d%E2%80%99Arco&corsi_conclusi=2#past-courses' });
	assert.equal(courses.results.fire('click', { target: courseNext }).prevented, true);
	courses.requests[1].resolve('Course page two', '2 risultati'); await tick();
	assert.equal(courses.fields.citta.value, 'Città d’Arco');
	const courseReset = Object.assign(new Element(), { matches: '[data-archive-reset]', href: 'https://example.test/corsi/?per_pagina=24#past-courses' });
	courses.summary.fire('click', { target: courseReset });
	assert.equal(courses.fields.tipologia.value, '');
	assert.equal(courses.fields.citta.value, '');
	assert.equal(courses.fields.per_pagina.value, '24');
	courses.requests[2].resolve('All courses', '36 risultati'); await tick();
	courses.window.location.href = courseNext.href;
	courses.fire('popstate'); courses.requests[3].resolve('Course page two', '2 risultati'); await tick();
	assert.equal(courses.fields.tipologia.value, 'introductory');
	assert.equal(courses.fields.citta.value, 'Città d’Arco');
	const fallback = setup(false);
	assert.equal(Object.keys(fallback.form.handlers).length, 0);
	console.log('Archive live filters: focus, debounce/IME, stale requests, URL/history, page size, partial reset/pagination, retries and cleanup passed.');
}
run().catch(error => { console.error(error); process.exitCode = 1; });

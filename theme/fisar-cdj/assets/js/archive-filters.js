(function () {
	'use strict';

	const form = document.querySelector('.archive-filters');
	const results = document.querySelector('[data-archive-results]');
	const summary = document.querySelector('[data-archive-summary]');
	const counter = summary && summary.querySelector('.archive-results');
	if (!form || !results || !counter || !window.fetch || !window.AbortController || !window.DOMParser) {
		return;
	}
	const textField = form.querySelector('[name="fisar_text"]');
	const controls = Array.from(form.elements).filter(control => control.name);
	const hint = document.getElementById('archive-auto-hint');
	if (hint) {
		hint.textContent = 'I risultati si aggiornano automaticamente quando cambi una selezione o fai una breve pausa nella scrittura. Puoi anche premere Filtra.';
	}
	let timer;
	let controller;
	let version = 0;
	let composing = false;
	let nativeFallback = false;
	let lastCount = counter.textContent;

	function cancelPending() {
		window.clearTimeout(timer);
		version++;
		if (controller) controller.abort();
		controller = null;
		results.setAttribute('aria-busy', 'false');
		counter.textContent = lastCount;
	}

	function formUrl() {
		const url = new URL(form.action, window.location.href);
		for (const control of controls) {
			const value = control.value.trim();
			if (value && !(control.name === 'fisar_per_page' && value === '12')) {
				url.searchParams.set(control.name, value);
			}
		}
		return url;
	}

	function restoreControls(url) {
		for (const control of controls) {
			control.value = url.searchParams.get(control.name) || (control.name === 'fisar_per_page' ? '12' : '');
		}
	}

	async function load(url, historyMode) {
		cancelPending();
		const requestVersion = version;
		controller = new window.AbortController();
		results.setAttribute('aria-busy', 'true');
		counter.textContent = 'Aggiornamento…';
		try {
			const response = await window.fetch(url.href, { credentials: 'same-origin', signal: controller.signal });
			if (!response.ok || new URL(response.url).origin !== window.location.origin) throw new Error('Archive request failed');
			const page = new window.DOMParser().parseFromString(await response.text(), 'text/html');
			if (requestVersion !== version) return;
			const nextResults = page.querySelector('[data-archive-results]');
			const nextSummary = page.querySelector('[data-archive-summary]');
			const nextForm = page.querySelector('.archive-filters');
			const nextCounter = nextSummary && nextSummary.querySelector('.archive-results');
			if (!nextResults || !nextCounter || !nextForm || nextForm.dataset.archiveScope !== form.dataset.archiveScope) throw new Error('Archive content missing');
			results.replaceChildren(...Array.from(nextResults.childNodes).map(node => document.importNode(node, true)));
			const previousReset = summary.querySelector('[data-archive-reset]');
			if (previousReset) previousReset.remove();
			const nextReset = nextSummary.querySelector('[data-archive-reset]');
			if (nextReset) summary.insertBefore(document.importNode(nextReset, true), counter);
			lastCount = nextCounter.textContent;
			counter.textContent = lastCount;
			results.setAttribute('aria-busy', 'false');
			document.title = page.title;
			if (url.href !== window.location.href && historyMode !== 'none') {
				window.history[historyMode === 'replace' ? 'replaceState' : 'pushState'](null, '', url.href);
			}
			nativeFallback = false;
			window.dispatchEvent(new CustomEvent('fisar:archive-updated'));
		} catch (error) {
			if (requestVersion !== version) return;
			results.setAttribute('aria-busy', 'false');
			counter.textContent = 'Non riesco ad aggiornare i risultati. Premi Filtra per riprovare.';
			nativeFallback = true;
		}
	}

	function schedule(delay, historyMode) {
		cancelPending();
		timer = window.setTimeout(() => load(formUrl(), historyMode), delay);
	}
	form.addEventListener('change', event => {
		if (event.target.tagName === 'SELECT') schedule(0, 'push');
	});
	textField.addEventListener('input', () => {
		if (!composing) schedule(650, 'replace');
	});
	textField.addEventListener('compositionstart', () => { composing = true; cancelPending(); });
	textField.addEventListener('compositionend', () => { composing = false; schedule(650, 'replace'); });
	form.addEventListener('submit', event => {
		if (nativeFallback) return;
		event.preventDefault();
		if (!composing) load(formUrl(), 'push');
	});
	function restorePage() {
		const url = new URL(window.location.href);
		restoreControls(url);
		load(url, 'none');
	}
	window.addEventListener('popstate', restorePage);
	window.addEventListener('pagehide', cancelPending);
	window.addEventListener('pageshow', event => { if (event.persisted) restorePage(); });
}());

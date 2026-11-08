(function () {
	'use strict';

	const form = document.querySelector('.archive-filters');
	const results = document.querySelector('[data-archive-results]');
	const summary = document.querySelector('[data-archive-summary]');
	const counter = summary && summary.querySelector('.archive-results');
	if (!form || !results || !counter || !window.fetch || !window.AbortController || !window.DOMParser) {
		return;
	}
	const textField = form.querySelector('[name="search"]');
	results.setAttribute('tabindex', '-1');
	const controls = Array.from(form.elements).filter(control => control.name);
	const legacyNames = { search: 'fisar_text', anno: 'fisar_year', mese: 'fisar_month', categoria: 'fisar_category', per_pagina: 'fisar_per_page' };
	const hint = document.getElementById('archive-auto-hint');
	if (hint) {
		hint.textContent = 'I risultati si aggiornano automaticamente quando cambi una selezione o fai una breve pausa nella scrittura. Puoi anche premere Cerca.';
	}
	let timer;
	let controller;
	let version = 0;
	let composing = false;
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
			if (value && !(control.name === 'per_pagina' && value === '12')) {
				url.searchParams.set(control.name, value);
			}
		}
		return url;
	}

	function restoreControls(url) {
		for (const control of controls) {
			control.value = url.searchParams.get(control.name) ?? url.searchParams.get(legacyNames[control.name]) ?? (control.name === 'per_pagina' ? '12' : '');
		}
	}

	async function load(url, historyMode, focusTarget = null) {
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
			if (focusTarget) {
				focusTarget.focus({ preventScroll: true });
				if (focusTarget === results) results.scrollIntoView({ block: 'start', behavior: 'instant' });
			}
			window.dispatchEvent(new CustomEvent('fisar:archive-updated'));
		} catch (error) {
			if (requestVersion !== version) return;
			results.setAttribute('aria-busy', 'false');
			counter.textContent = 'Non riesco ad aggiornare i risultati. Premi Cerca per riprovare.';
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
		event.preventDefault();
		if (!composing) load(formUrl(), 'push');
	});
	function followArchiveLink(event, selector, focusTarget) {
		if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
		const link = event.target.closest(selector);
		if (!link || link.hasAttribute('download') || (link.target && link.target !== '_self')) return;
		const url = new URL(link.href, window.location.href);
		if (url.origin !== window.location.origin) return;
		event.preventDefault();
		restoreControls(url);
		load(url, 'push', focusTarget);
	}
	summary.addEventListener('click', event => followArchiveLink(event, '[data-archive-reset]', textField));
	results.addEventListener('click', event => followArchiveLink(event, '.pagination--archive a.page-numbers', results));
	function restorePage() {
		const url = new URL(window.location.href);
		restoreControls(url);
		load(url, 'none');
	}
	window.addEventListener('popstate', restorePage);
	window.addEventListener('pagehide', cancelPending);
	window.addEventListener('pageshow', event => { if (event.persisted) restorePage(); });
}());

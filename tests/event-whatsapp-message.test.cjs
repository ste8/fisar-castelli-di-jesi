const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');

class Element {
	constructor(tag = 'div') { this.tag = tag; this.children = []; this.handlers = {}; this.value = ''; this.disabled = false; this.hidden = false; this.dataset = {}; this.attributes = {}; }
	setAttribute(name, value) { this.attributes[name] = value; }
	removeAttribute(name) { delete this.attributes[name]; }
	addEventListener(name, callback) { this.handlers[name] = callback; }
	appendChild(child) { this.children.push(child); }
	replaceChildren() { this.children = []; this.textContent = ''; }
	get childElementCount() { return this.children.length; }
	focus() { this.focused = true; }
	select() { this.selected = true; }
	showModal() { this.open = true; }
	close() { this.open = false; this.handlers.close(); }
	async trigger(event) { return this.handlers[event](); }
}
const source = fs.readFileSync(path.join(__dirname, '../plugin/fisar-cdj-core/assets/js/event-whatsapp-message.js'), 'utf8');
const tick = () => new Promise(resolve => setImmediate(resolve));
async function run() {
	const elements = {};
	const get = id => elements[id] ||= Object.assign(new Element(), { id });
	const button = get('fisar-whatsapp-message-open');
	button.dataset = { postId: '42', nonce: 'test-nonce', url: '/wp-admin/admin-ajax.php', saved: '1' };
	const fields = [
		{ name: '_fisar_event_date', id: '_fisar_event_date', value: '2027-01-01', type: 'date' },
		{ name: '_fisar_event_registration_notes', id: 'notes', value: 'Testo originale', type: 'textarea' },
	];
	let richDirty = false;
	const richEditor = { isDirty: () => richDirty, isHidden: () => false, getContent: () => '<p>Nota visuale modificata</p>' };
	let dirty = false;
	let newPost = false;
	let saving = false;
	let middleware;
	let response = { ok: true, json: async () => ({ success: true, data: { message: '*Titolo*\nTesto _corsivo_ <img src=x onerror=alert(1)>', warnings: ['Bozza non pubblicata.'] } }) };
	let failClipboard = false;
	let copied;
	let calls = 0;
	vm.runInNewContext(source, {
		wp: {
			domReady: callback => callback(),
			data: { select: () => ({ isEditedPostDirty: () => dirty, isEditedPostNew: () => newPost, isSavingPost: () => saving }) },
			apiFetch: { use: callback => { middleware = callback; } },
		},
		window: { tinymce: { get: id => id === 'notes' ? richEditor : undefined } },
		document: { getElementById: get, querySelectorAll: () => fields, createElement: tag => new Element(tag), createTextNode: text => ({ tag: '#text', text }) },
		AbortController, URLSearchParams,
		fetch: async (url, options) => {
			calls++;
			assert.equal(url, '/wp-admin/admin-ajax.php');
			assert.equal(options.method, 'POST');
			assert.equal(options.credentials, 'same-origin');
			assert.equal(options.body.get('post_id'), '42');
			assert.equal(options.body.get('nonce'), 'test-nonce');
			return response;
		},
		navigator: { clipboard: { writeText: async value => { if (failClipboard) { throw new Error('Denied'); } copied = value; } } },
	});
	const reopen = async () => { await get('fisar-message-close').trigger('click'); await button.trigger('click'); await tick(); };
	assert.equal(calls, 0, 'No requests or clipboard writes on editor load.');
	await button.trigger('click'); await tick();
	assert.equal(get('fisar-whatsapp-message-dialog').open, true);
	assert.equal(get('fisar-message-title').focused, true);
	assert.equal(get('fisar-message-copy').disabled, false);
	assert.equal(get('fisar-message-warnings').hidden, false);
	assert.equal(get('fisar-message-status').textContent, '', 'No redundant ready notice.');
	assert.equal(get('fisar-message-help').hidden, false);
	assert.equal(get('fisar-whatsapp-message-dialog').attributes['aria-describedby'], 'fisar-message-help');
	const tags = node => [node.tag, ...node.children?.flatMap(tags) || []];
	const previewTags = tags(get('fisar-message-preview'));
	assert(previewTags.includes('strong') && previewTags.includes('em'));
	assert(!previewTags.includes('img'), 'Untrusted markup is displayed as literal text, never HTML.');
	await get('fisar-message-copy').trigger('click');
	assert.equal(copied, get('fisar-message-text').value, 'Copy preserves the single-marker source, not rendered text.');
	const plainText = node => node.tag === '#text' ? node.text : (node.children || []).map(plainText).join('');
	const formattedNodes = (node, tag) => [ ...(node.tag === tag ? [plainText(node)] : []), ...(node.children || []).flatMap(child => formattedNodes(child, tag)) ];
	for (const apostrophe of ["'", '’']) {
		const message = '🍷 *VISITA ALLA CANTINA EDOARDO DOTTORI*\n\nQuesta volta visiteremo l' + apostrophe + '*Azienda Agricola Edoardo Dottori*, immersa nelle colline.';
		get('fisar-message-text').value = message;
		await get('fisar-message-text').trigger('input');
		assert.deepEqual(formattedNodes(get('fisar-message-preview'), 'strong'), ['VISITA ALLA CANTINA EDOARDO DOTTORI', 'Azienda Agricola Edoardo Dottori'], 'Bold after a straight or typographic apostrophe is recognized.');
		assert.equal(plainText(get('fisar-message-preview')), message.replaceAll('*', ''), 'Apostrophes and surrounding text are preserved in the preview.');
		await get('fisar-message-copy').trigger('click');
		assert.equal(copied, message, 'Preview formatting must not change the copied source.');
		get('fisar-message-text').value = 'Un' + apostrophe + '_occasione_ e ' + apostrophe + '*una citazione*' + apostrophe + '.';
		await get('fisar-message-text').trigger('input');
		assert.deepEqual(formattedNodes(get('fisar-message-preview'), 'em'), ['occasione'], 'Italic also works after an apostrophe.');
		assert.deepEqual(formattedNodes(get('fisar-message-preview'), 'strong'), ['una citazione'], 'A closing apostrophe can follow a formatting marker.');
	}
	get('fisar-message-text').value = 'nome_file_normale e *grassetto non chiuso';
	await get('fisar-message-text').trigger('input');
	assert.deepEqual(formattedNodes(get('fisar-message-preview'), 'strong'), []);
	assert.deepEqual(formattedNodes(get('fisar-message-preview'), 'em'), [], 'Underscores within ordinary words are not formatting.');
	get('fisar-message-text').value = 'Modifica solo anteprima';
	await get('fisar-message-text').trigger('input');
	await get('fisar-message-copy').trigger('click');
	assert.equal(copied, 'Modifica solo anteprima');
	assert.equal(calls, 1, 'Editing/copying never saves or requests event changes.');
	failClipboard = true;
	await get('fisar-message-copy').trigger('click');
	assert.equal(get('fisar-message-text').selected, true);
	assert.match(get('fisar-message-status').textContent, /Ctrl\+C/);
	get('fisar-message-text').value = ' ';
	await get('fisar-message-text').trigger('input');
	assert.equal(get('fisar-message-copy').disabled, true);
	response = { ok: false, json: async () => ({ success: false, data: { message: 'Sessione scaduta' } }) };
	await reopen();
	assert.equal(get('fisar-message-status').textContent, 'Sessione scaduta');
	assert.equal(get('fisar-message-copy').disabled, true);
	assert.equal(get('fisar-message-text').value, '');
	assert.equal(get('fisar-whatsapp-message-dialog').dataset.state, 'error');
	response = { ok: true, json: async () => { throw new SyntaxError('Unexpected HTML login page'); } };
	await reopen();
	assert.match(get('fisar-message-status').textContent, /Controlla la connessione/);
	assert(!get('fisar-message-status').textContent.includes('SyntaxError'));
	response = { ok: true, json: async () => ({ success: true, data: { message: '*Messaggio aggiornato*', warnings: [] } }) };
	const requestsBeforeDirty = calls;
	dirty = true;
	await reopen();
	assert.equal(get('fisar-whatsapp-message-dialog').dataset.state, 'blocked');
	assert.equal(get('fisar-message-help').hidden, true, 'The unsaved warning has no editing/formatting instructions.');
	assert.equal(get('fisar-whatsapp-message-dialog').attributes['aria-describedby'], undefined, 'Hidden instructions are not an accessible dialog description.');
	assert.equal(get('fisar-message-copy').disabled, true);
	assert.equal(calls, requestsBeforeDirty, 'Never generate from stale saved data when WordPress has edits.');
	dirty = false;
	newPost = true;
	await reopen();
	assert.equal(calls, requestsBeforeDirty, 'A new unsaved event cannot generate.');
	newPost = false;
	saving = true;
	await reopen();
	assert.equal(calls, requestsBeforeDirty, 'Wait until the native save finishes.');
	saving = false;
	fields[0].value = '2027-01-02';
	await reopen();
	assert.equal(calls, requestsBeforeDirty, 'Custom fields are detected even if the native document store is clean.');
	const saveOptions = { url: '/wp-admin/post.php?post=42&meta-box-loader=1&nonce=test', body: { get: () => '42' } };
	await middleware({ url: '/autosave', body: saveOptions.body }, async () => ({ ok: true }));
	await reopen();
	assert.equal(calls, requestsBeforeDirty, 'Autosave must not clear the custom-field warning.');
	await assert.rejects(middleware(saveOptions, async () => { throw new Error('Save failed'); }));
	await reopen();
	assert.equal(calls, requestsBeforeDirty, 'A failed metabox save must not clear the warning.');
	let resolveSave;
	const savePromise = middleware(saveOptions, () => new Promise(resolve => { resolveSave = resolve; }));
	await reopen();
	assert.equal(calls, requestsBeforeDirty, 'An in-flight metabox save cannot generate stale data.');
	fields[0].value = '2027-01-03';
	resolveSave({ ok: true });
	await savePromise;
	await reopen();
	assert.equal(calls, requestsBeforeDirty, 'Edits made during save are still unsaved after the submitted snapshot succeeds.');
	fields[0].value = '2027-01-02';
	await reopen();
	assert.equal(get('fisar-whatsapp-message-dialog').dataset.state, 'ready');
	assert.equal(calls, requestsBeforeDirty + 1, 'After successful saving, reopening generates without a refresh.');
	assert.equal(get('fisar-message-help').hidden, false, 'Instructions return for the editable message.');
	assert.equal(get('fisar-whatsapp-message-dialog').attributes['aria-describedby'], 'fisar-message-help');
	fields.push({ name: '_fisar_event_whatsapp_contacts[1][value]', id: 'new-contact', type: 'tel', value: '3350000001' });
	await reopen();
	assert.equal(get('fisar-whatsapp-message-dialog').dataset.state, 'blocked', 'Dynamic contact rows are tracked.');
	fields.pop();
	await reopen();
	assert.equal(get('fisar-whatsapp-message-dialog').dataset.state, 'ready', 'Undoing a field change restores the clean state.');
	richDirty = true;
	await reopen();
	assert.equal(get('fisar-whatsapp-message-dialog').dataset.state, 'blocked', 'Visual rich-text edits are detected before TinyMCE updates its textarea.');
	richDirty = false;
	await reopen();
	assert.equal(get('fisar-whatsapp-message-dialog').dataset.state, 'ready', 'Clean TinyMCE initialization does not create a false unsaved warning.');
	await get('fisar-message-close').trigger('click');
	assert.equal(button.focused, true);
	console.log('WhatsApp dialog: preview/copy, reopen, unsaved/new fields, successful/failed/autosave, concurrent edits and focus passed.');
}
run().catch(error => { console.error(error); process.exitCode = 1; });

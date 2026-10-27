/* Native excerpt bridge: no WordPress/browser dependencies. */
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');

const source = fs.readFileSync(path.join(__dirname, '../plugin/fisar-cdj-core/assets/js/editor-excerpt.js'), 'utf8');
function setup(initial, fieldValue = 'Descrizione salvata') {
	let excerpt = initial;
	let listener;
	let pagehide;
	let removed;
	let subscription;
	let unsubscribed = false;
	const edits = [];
	const field = { value: fieldValue, addEventListener: (name, callback) => { assert.equal(name, 'input'); listener = callback; } };
	vm.runInNewContext(source, {
		document: { getElementById: (id) => { assert.equal(id, 'excerpt'); return field; } },
		window: { addEventListener: (name, callback, options) => { assert.equal(name, 'pagehide'); assert.equal(options.once, true); pagehide = callback; } },
		wp: {
			domReady: (callback) => callback(),
			data: {
				select: () => ({ getEditedPostAttribute: () => excerpt }),
				dispatch: () => ({
					removeEditorPanel: (name) => { removed = name; },
					editPost: (data) => { excerpt = data.excerpt; edits.push(data.excerpt); },
				}),
				subscribe: (callback) => { subscription = callback; return () => { unsubscribed = true; }; },
			},
		},
	});
	return { field, edits, removed, input: () => listener(), update: (value) => { excerpt = value; subscription(); }, unload: () => pagehide(), isUnsubscribed: () => unsubscribed };
}

const article = setup('Testo iniziale');
assert.equal(article.field.value, 'Testo iniziale');
assert.equal(article.removed, 'post-excerpt');
article.field.value = 'Nuova descrizione';
article.input();
article.field.value = '';
article.input();
assert.deepEqual(article.edits, ['Nuova descrizione', '']);
article.update('Ripristino dal documento');
assert.equal(article.field.value, 'Ripristino dal documento');
article.unload();
assert.equal(article.isUnsubscribed(), true);
assert.equal(setup(undefined).field.value, 'Descrizione salvata');
console.log('Native excerpt: initialization, edit, clear, restore and cleanup passed.');

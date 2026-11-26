/* Instagram photo tags use normalized coordinates on the actual square social image. */
(function () {
	'use strict';
	function normalizeHandle(value) { return value.trim().replace(/^@+/, '').toLowerCase(); }
	function validHandle(value) { return /^[a-z0-9_](?:[a-z0-9_.]{0,28}[a-z0-9_])?$/i.test(value) && !value.includes('..'); }
	function normalizedPoint(x, y, rect) {
		return { x: Math.max(0, Math.min(1, (x - rect.left) / rect.width)), y: Math.max(0, Math.min(1, (y - rect.top) / rect.height)) };
	}
	class SocialPhotoTags {
		constructor(doc) {
			this.doc = doc;
			this.dialog = this.create('dialog', undefined, 'fisar-social-dialog fisar-social-tags-dialog');
			this.dialog.setAttribute('aria-labelledby', 'fisar-social-tags-title');
			const header = this.create('div', undefined, 'fisar-social-dialog-header');
			this.title = this.create('h2'); this.title.id = 'fisar-social-tags-title'; this.title.tabIndex = -1;
			header.append(this.title, this.button('Annulla', () => this.dialog.close()));
			const body = this.create('div', undefined, 'fisar-social-dialog-body');
			const help = this.create('p', 'Scegli il punto sulla foto e inserisci lo username esatto di un profilo pubblico. I tag saranno visibili solo su Instagram.');
			const layout = this.create('div', undefined, 'fisar-social-tags-layout');
			this.stage = this.button('', (event) => {
				if (event.detail) this.point = normalizedPoint(event.clientX, event.clientY, this.stage.getBoundingClientRect());
				this.renderMarkers(); this.username.focus();
			});
			this.stage.className = 'fisar-social-tag-stage'; this.stage.setAttribute('aria-label', 'Scegli posizione del tag sulla foto');
			this.stage.setAttribute('aria-describedby', 'fisar-social-tags-keyboard');
			this.stage.addEventListener('keydown', (event) => {
				const steps = { ArrowLeft: [-0.02, 0], ArrowRight: [0.02, 0], ArrowUp: [0, -0.02], ArrowDown: [0, 0.02] };
				if (!steps[event.key]) return;
				event.preventDefault();
				const [x, y] = steps[event.key]; this.point = { x: Math.max(0, Math.min(1, this.point.x + x)), y: Math.max(0, Math.min(1, this.point.y + y)) };
				this.renderMarkers();
			});
			this.image = this.create('img'); this.image.draggable = false;
			this.markers = this.create('span', undefined, 'fisar-social-tag-markers'); this.markers.setAttribute('aria-hidden', 'true');
			this.stage.append(this.image, this.markers);
			const photo = this.create('div');
			const keyboard = this.create('p', 'Puoi spostare il punto anche con i tasti freccia.', 'description'); keyboard.id = 'fisar-social-tags-keyboard';
			this.position = this.create('p', '', 'screen-reader-text'); this.position.setAttribute('role', 'status');
			photo.append(this.stage, keyboard, this.position);
			const controls = this.create('div', undefined, 'fisar-social-tag-controls');
			const label = this.create('label', 'Username Instagram'); label.htmlFor = 'fisar-social-tag-handle';
			this.username = this.create('input'); this.username.id = label.htmlFor; this.username.type = 'text'; this.username.maxLength = 31;
			this.username.autocomplete = 'off'; this.username.setAttribute('autocapitalize', 'none'); this.username.spellcheck = false; this.username.placeholder = '@nomeutente';
			this.username.addEventListener('keydown', (event) => { if (event.key === 'Enter') { event.preventDefault(); this.add(); } });
			this.error = this.create('p', '', 'fisar-social-warning fisar-social-error'); this.error.setAttribute('role', 'alert'); this.error.hidden = true;
			this.addButton = this.button('Aggiungi tag', () => this.add());
			this.cancelEdit = this.button('Annulla modifica', () => this.resetInput()); this.cancelEdit.hidden = true;
			const actions = this.create('div', undefined, 'fisar-social-actions'); actions.append(this.addButton, this.cancelEdit);
			this.count = this.create('p', '', 'description');
			this.list = this.create('ul', undefined, 'fisar-social-tag-list'); this.list.setAttribute('aria-label', 'Persone taggate nella foto');
			controls.append(label, this.username, actions, this.error, this.count, this.list); layout.append(photo, controls); body.append(help, layout);
			const footer = this.create('div', undefined, 'fisar-social-dialog-footer');
			footer.append(this.button('Conferma tag', () => {
				if (this.username.value.trim()) { this.showError('Aggiungi il tag che stai scrivendo oppure annulla la modifica prima di confermare.'); this.username.focus(); return; }
				this.onSave(this.tags.map((tag) => ({ ...tag }))); this.dialog.close();
			}, 'button button-primary'));
			this.dialog.append(header, body, footer); doc.body.append(this.dialog);
			this.dialog.addEventListener('close', () => this.onClose?.());
		}
		create(tag, text, className) {
			const node = this.doc.createElement(tag); if (text !== undefined) node.textContent = text; if (className) node.className = className; return node;
		}
		button(text, action, className = 'button') { const node = this.create('button', text, className); node.type = 'button'; node.addEventListener('click', action); return node; }
		open({ image, index, tags, otherCount, limit, onSave, onClose }) {
			Object.assign(this, { tags: tags.map((tag) => ({ ...tag })), otherCount, limit, onSave, onClose });
			this.title.textContent = `Tag (Instagram) · foto ${index + 1}`;
			this.image.src = image.url; this.image.alt = image.alt || image.title || `Foto ${index + 1}`;
			this.resetInput(); this.renderList(); this.dialog.showModal(); this.title.focus();
		}
		showError(message) { this.error.textContent = message; this.error.hidden = !message; }
		resetInput() {
			this.editIndex = null; this.point = { x: 0.5, y: 0.5 }; this.username.value = ''; this.addButton.textContent = 'Aggiungi tag'; this.cancelEdit.hidden = true; this.showError(''); this.renderMarkers();
		}
		renderMarkers() {
			this.markers.replaceChildren();
			this.tags.forEach((tag) => {
				const marker = this.create('span', `@${tag.handle}`, 'fisar-social-image-tag');
				marker.style.left = `${tag.x * 100}%`; marker.style.top = `${tag.y * 100}%`; this.markers.append(marker);
			});
			const point = this.create('span', '+', 'fisar-social-tag-point'); point.style.left = `${this.point.x * 100}%`; point.style.top = `${this.point.y * 100}%`; this.markers.append(point);
			this.position.textContent = `Posizione: ${Math.round(this.point.x * 100)}% da sinistra, ${Math.round(this.point.y * 100)}% dall’alto.`;
		}
		renderList() {
			this.list.replaceChildren(); this.count.textContent = `${this.otherCount + this.tags.length} / ${this.limit} tag nelle foto del post`;
			this.tags.forEach((tag, index) => {
				const item = this.create('li');
				const link = this.create('a', `@${tag.handle}`); link.href = `https://www.instagram.com/${encodeURIComponent(tag.handle)}/`; link.target = '_blank'; link.rel = 'noopener noreferrer';
				const edit = this.button('Modifica', () => {
					this.editIndex = index; this.username.value = `@${tag.handle}`; this.point = { x: tag.x, y: tag.y }; this.addButton.textContent = 'Aggiorna tag'; this.cancelEdit.hidden = false; this.showError(''); this.renderMarkers(); this.username.focus();
				}); edit.setAttribute('aria-label', `Modifica tag @${tag.handle}`);
				const remove = this.button('Rimuovi', () => { this.tags.splice(index, 1); this.resetInput(); this.renderList(); this.username.focus(); }); remove.setAttribute('aria-label', `Rimuovi tag @${tag.handle}`);
				item.append(link, edit, remove); this.list.append(item);
			});
			this.renderMarkers();
		}
		add() {
			const handle = normalizeHandle(this.username.value);
			if (!validHandle(handle)) { this.showError('Inserisci uno username Instagram valido, senza spazi o link.'); this.username.focus(); return; }
			if (this.tags.some((tag, index) => tag.handle === handle && index !== this.editIndex)) { this.showError('Questo profilo è già taggato nella foto.'); return; }
			if (this.editIndex === null && this.tags.length + this.otherCount >= this.limit) { this.showError(`Puoi aggiungere al massimo ${this.limit} tag nelle foto del post.`); return; }
			const tag = { handle, ...this.point };
			if (this.editIndex === null) this.tags.push(tag); else this.tags[this.editIndex] = tag;
			this.resetInput(); this.renderList(); this.username.focus();
		}
	}
	if (typeof module !== 'undefined') module.exports = { normalizeHandle, validHandle, normalizedPoint, SocialPhotoTags };
	if (typeof window !== 'undefined') window.FisarSocialPhotoTags = SocialPhotoTags;
})();

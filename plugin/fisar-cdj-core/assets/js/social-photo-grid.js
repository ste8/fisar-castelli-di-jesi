/* Photo preview is also the editor: native drag/drop, accessible positions and removal. */
(function () {
	'use strict';
	function movePhoto(ids, id, position) {
		const from = ids.indexOf(id);
		if (from < 0 || !Number.isInteger(position) || position < 0 || position >= ids.length) return [...ids];
		const next = [...ids]; next.splice(from, 1); next.splice(position, 0, id);
		return next;
	}
	class SocialPhotoGrid {
		constructor({ root, getIds, getMedia, onChange, onAdd, canAdd, announce }) {
			Object.assign(this, { root, getIds, getMedia, onChange, onAdd, canAdd, announce });
			this.disabled = false; this.dragId = null;
			root.addEventListener('dragstart', (event) => this.startDrag(event));
			root.addEventListener('dragover', (event) => this.over(event));
			root.addEventListener('drop', (event) => this.drop(event));
			root.addEventListener('dragend', () => this.cancelDrag());
			root.addEventListener('dragleave', (event) => { if (!root.contains(event.relatedTarget)) this.clearTarget(); });
		}
		create(tag, className, text) {
			const node = this.root.ownerDocument.createElement(tag);
			if (className) node.className = className;
			if (text !== undefined) node.textContent = text;
			return node;
		}
		button(text, label, action, className) {
			const node = this.create('button', className, text); node.type = 'button'; node.setAttribute('aria-label', label);
			node.addEventListener('click', action); return node;
		}
		render(focusId) {
			this.cancelDrag(); this.root.replaceChildren();
			const ids = this.getIds();
			ids.forEach((id, index) => {
				const image = this.getMedia(id);
				const tile = this.create('li', 'fisar-social-photo'); tile.dataset.photoId = id; tile.draggable = !this.disabled;
				const img = this.create('img'); img.src = image?.preview || image?.url || image?.thumbnail || ''; img.alt = image?.alt || image?.title || `Foto ${index + 1}`; img.draggable = false;
				const number = this.create('span', 'fisar-social-photo-number', String(index + 1)); number.setAttribute('aria-hidden', 'true');
				const remove = this.button('×', `Rimuovi foto ${index + 1}`, () => {
					const next = this.getIds().filter((value) => value !== id); this.onChange(next); this.render(next[Math.min(index, next.length - 1)]);
					this.announce(`Foto rimossa dal post social. ${next.length} foto selezionate.`);
				}, 'fisar-social-photo-remove');
				const positionLabel = this.create('label', 'fisar-social-photo-position-label', 'Posizione'); positionLabel.htmlFor = `fisar-social-position-${id}`;
				const position = this.create('select'); position.id = positionLabel.htmlFor; position.setAttribute('aria-label', `Posizione della foto ${index + 1}`);
				ids.forEach((_, n) => { const option = this.create('option', '', String(n + 1)); option.value = String(n); position.append(option); }); position.value = String(index);
				position.addEventListener('change', () => this.move(id, Number(position.value)));
				const controls = this.create('div', 'fisar-social-photo-position'); controls.append(positionLabel, position);
				tile.append(img, number, remove, controls); this.root.append(tile);
			});
			const addTile = this.create('li', 'fisar-social-photo-add');
			const add = this.button('+', 'Aggiungi foto dall’articolo', this.onAdd, 'fisar-social-photo-add-button');
			add.append(this.create('span', '', 'Aggiungi foto')); add.disabled = !this.canAdd() || this.disabled;
			if (!this.canAdd()) add.title = 'Tutte le foto dell’articolo sono già selezionate.';
			addTile.append(add); this.root.append(addTile);
			this.setDisabled(this.disabled);
			if (focusId !== undefined) this.root.querySelector(`[data-photo-id="${focusId}"] select`)?.focus();
		}
		move(id, position) {
			if (this.disabled) return;
			const ids = movePhoto(this.getIds(), id, position);
			this.onChange(ids); this.render(id); this.announce(`Foto spostata in posizione ${position + 1} di ${ids.length}.`);
		}
		setDisabled(disabled) {
			this.disabled = disabled;
			if (disabled) this.cancelDrag();
			this.root.querySelectorAll('[data-photo-id]').forEach((tile) => { tile.draggable = !disabled; });
			this.root.querySelectorAll('button, select').forEach((control) => { control.disabled = disabled || (control.classList.contains('fisar-social-photo-add-button') && !this.canAdd()); });
		}
		startDrag(event) {
			const tile = event.target.closest('[data-photo-id]');
			if (this.disabled || !tile || !this.root.contains(tile) || event.target.closest('button, select, label')) { event.preventDefault(); return; }
			this.dragId = Number(tile.dataset.photoId); tile.classList.add('is-dragging');
			event.dataTransfer.effectAllowed = 'move'; event.dataTransfer.setData('text/plain', String(this.dragId));
		}
		over(event) {
			if (this.disabled || this.dragId === null) return;
			event.preventDefault(); event.dataTransfer.dropEffect = 'move'; this.clearTarget();
			const tile = event.target.closest('[data-photo-id]');
			if (tile && this.root.contains(tile) && Number(tile.dataset.photoId) !== this.dragId) tile.classList.add('is-drop-target');
		}
		drop(event) {
			if (this.disabled || this.dragId === null) return;
			event.preventDefault();
			const tile = event.target.closest('[data-photo-id]');
			const position = tile && this.root.contains(tile) ? this.getIds().indexOf(Number(tile.dataset.photoId)) : this.getIds().length - 1;
			const id = this.dragId; this.cancelDrag(); this.move(id, position);
		}
		clearTarget() { this.root.querySelectorAll('.is-drop-target').forEach((tile) => tile.classList.remove('is-drop-target')); }
		cancelDrag() {
			this.dragId = null; this.clearTarget();
			this.root.querySelectorAll('.is-dragging').forEach((tile) => tile.classList.remove('is-dragging'));
		}
	}
	if (typeof module !== 'undefined') module.exports = { movePhoto, SocialPhotoGrid };
	if (typeof window !== 'undefined') window.FisarSocialPhotoGrid = SocialPhotoGrid;
})();

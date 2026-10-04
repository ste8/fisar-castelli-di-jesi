(function () {
	'use strict';

	var toggle = document.querySelector('.menu-toggle');
	var navigation = document.getElementById('primary-navigation');
	if (!toggle || !navigation) {
		return;
	}
	var submenus = [];
	navigation.querySelectorAll('.primary-nav .menu-item-has-children').forEach(function (item) {
		var link = item.querySelector(':scope > a');
		var submenu = item.querySelector(':scope > .sub-menu');
		if (!link || !submenu) {
			return;
		}
		var button = document.createElement('button');
		button.type = 'button';
		button.className = 'submenu-toggle';
		button.textContent = link.textContent;
		submenu.id = item.id + '-submenu';
		button.setAttribute('aria-controls', submenu.id);
		button.setAttribute('aria-expanded', 'false');
		link.replaceWith(button);
		submenu.hidden = true;
		var disclosure = { item: item, button: button, submenu: submenu };
		submenus.push(disclosure);
		button.addEventListener('click', function () {
			var open = button.getAttribute('aria-expanded') !== 'true';
			closeSubmenus();
			button.setAttribute('aria-expanded', String(open));
			submenu.hidden = !open;
		});
		item.addEventListener('focusout', function (event) {
			if (!item.contains(event.relatedTarget)) {
				closeSubmenus();
			}
		});
	});

	function closeSubmenus() {
		submenus.forEach(function (disclosure) {
			disclosure.button.setAttribute('aria-expanded', 'false');
			disclosure.submenu.hidden = true;
		});
	}

	function closeMenu() {
		closeSubmenus();
		toggle.setAttribute('aria-expanded', 'false');
		navigation.classList.remove('is-open');
		document.body.classList.remove('menu-is-open');
	}

	toggle.addEventListener('click', function () {
		var isOpen = toggle.getAttribute('aria-expanded') === 'true';
		if (isOpen) {
			closeMenu();
			return;
		}
		toggle.setAttribute('aria-expanded', String(!isOpen));
		navigation.classList.toggle('is-open', !isOpen);
		document.body.classList.toggle('menu-is-open', !isOpen);
	});

	document.addEventListener('keydown', function (event) {
		if (event.key !== 'Escape') {
			return;
		}
		var openSubmenu = submenus.find(function (disclosure) {
			return !disclosure.submenu.hidden;
		});
		if (openSubmenu) {
			closeSubmenus();
			openSubmenu.button.focus();
		} else if (toggle.getAttribute('aria-expanded') === 'true') {
			closeMenu();
			toggle.focus();
		}
	});

	document.addEventListener('click', function (event) {
		if (!submenus.some(function (disclosure) { return disclosure.item.contains(event.target); })) {
			closeSubmenus();
		}
		if (toggle.getAttribute('aria-expanded') === 'true' && !navigation.contains(event.target) && !toggle.contains(event.target)) {
			closeMenu();
		}
	});

	navigation.addEventListener('click', function (event) {
		var link = event.target.closest('a');
		if (!link || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) {
			return;
		}
		var destination = new URL(link.href);
		var samePage = destination.origin === window.location.origin && destination.pathname === window.location.pathname;
		closeMenu();
		if (samePage && destination.hash) {
			var target = document.getElementById(decodeURIComponent(destination.hash.slice(1)));
			if (target) {
				target.setAttribute('tabindex', '-1');
				target.focus({ preventScroll: true });
				target.addEventListener('blur', function () { target.removeAttribute('tabindex'); }, { once: true });
			}
		}
	});

	window.addEventListener('resize', function () {
		if (window.matchMedia('(min-width: 70rem)').matches) {
			closeMenu();
		}
	});
}());

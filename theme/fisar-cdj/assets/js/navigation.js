(function () {
	'use strict';

	var toggle = document.querySelector('.menu-toggle');
	var navigation = document.getElementById('primary-navigation');
	if (!toggle || !navigation) {
		return;
	}

	function closeMenu() {
		toggle.setAttribute('aria-expanded', 'false');
		navigation.classList.remove('is-open');
		document.body.classList.remove('menu-is-open');
	}

	toggle.addEventListener('click', function () {
		var isOpen = toggle.getAttribute('aria-expanded') === 'true';
		toggle.setAttribute('aria-expanded', String(!isOpen));
		navigation.classList.toggle('is-open', !isOpen);
		document.body.classList.toggle('menu-is-open', !isOpen);
	});

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			closeMenu();
			toggle.focus();
		}
	});

	document.addEventListener('click', function (event) {
		if (toggle.getAttribute('aria-expanded') === 'true' && !navigation.contains(event.target) && !toggle.contains(event.target)) {
			closeMenu();
		}
	});

	window.addEventListener('resize', function () {
		if (window.matchMedia('(min-width: 64rem)').matches) {
			closeMenu();
		}
	});
}());


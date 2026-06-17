(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		if (document.documentElement.dataset.janPolishReady === '1') {
			return;
		}

		document.documentElement.dataset.janPolishReady = '1';

		var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var revealSelectors = ['.jan-card', '.jan-stat', '.jan-steps > *', '.jan-section h2'];
		var revealIndex = 0;

		revealSelectors.forEach(function (selector) {
			document.querySelectorAll(selector).forEach(function (element) {
				if (!element.classList.contains('jan-reveal')) {
					element.classList.add('jan-reveal');
				}

				if (!reducedMotion && !element.style.transitionDelay) {
					element.style.transitionDelay = (Math.min(revealIndex % 8, 7) * 70) + 'ms';
				}

				revealIndex += 1;
			});
		});

		var revealItems = Array.prototype.slice.call(document.querySelectorAll('.jan-reveal'));

		if (reducedMotion || !('IntersectionObserver' in window)) {
			revealItems.forEach(function (element) {
				element.classList.add('is-visible');
				element.style.transitionDelay = '';
			});
		} else {
			var revealObserver = new IntersectionObserver(function (entries, observer) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}

					entry.target.classList.add('is-visible');
					observer.unobserve(entry.target);
				});
			}, {
				threshold: 0.15
			});

			revealItems.forEach(function (element) {
				revealObserver.observe(element);
			});
		}

		var statItems = Array.prototype.slice.call(document.querySelectorAll('.jan-stat__num[data-target]'));

		function decimalsFor(value) {
			var text = String(value);
			return text.indexOf('.') === -1 ? 0 : text.split('.')[1].length;
		}

		function formatNumber(value, decimals) {
			if (decimals > 0) {
				return value.toFixed(decimals);
			}

			return String(Math.round(value));
		}

		function setFinalValue(element) {
			var target = parseFloat(element.dataset.target);
			var decimals = decimalsFor(element.dataset.target);
			var prefix = element.dataset.prefix || '';
			var suffix = element.dataset.suffix || '';

			if (!Number.isFinite(target)) {
				return;
			}

			element.textContent = prefix + formatNumber(target, decimals) + suffix;
			element.dataset.janCounted = '1';
		}

		function animateCount(element) {
			if (element.dataset.janCounted === '1') {
				return;
			}

			var target = parseFloat(element.dataset.target);

			if (!Number.isFinite(target)) {
				return;
			}

			var decimals = decimalsFor(element.dataset.target);
			var prefix = element.dataset.prefix || '';
			var suffix = element.dataset.suffix || '';
			var duration = 1400;
			var startTime = null;

			function tick(timestamp) {
				if (startTime === null) {
					startTime = timestamp;
				}

				var progress = Math.min((timestamp - startTime) / duration, 1);
				var eased = 1 - Math.pow(1 - progress, 3);
				var current = target * eased;

				element.textContent = prefix + formatNumber(current, decimals) + suffix;

				if (progress < 1) {
					window.requestAnimationFrame(tick);
					return;
				}

				setFinalValue(element);
			}

			window.requestAnimationFrame(tick);
		}

		if (reducedMotion || !('IntersectionObserver' in window)) {
			statItems.forEach(setFinalValue);
			return;
		}

		var countObserver = new IntersectionObserver(function (entries, observer) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}

				animateCount(entry.target);
				observer.unobserve(entry.target);
			});
		}, {
			threshold: 0.35
		});

		statItems.forEach(function (element) {
			countObserver.observe(element);
		});
	});
}());

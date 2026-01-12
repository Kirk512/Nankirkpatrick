/**
 * NK Theme JavaScript
 *
 * Handles interactive functionality for the NK Theme.
 *
 * @package NK_Theme
 * @since 1.0.0
 */

(function () {
	'use strict';

	/**
	 * Reviews toggle functionality.
	 * Toggles between showing only reviews that mention Nan vs. all reviews.
	 */
	function initReviewsToggle() {
		const containers = document.querySelectorAll('.nk-reviews');

		containers.forEach(function (container) {
			const toggle = container.querySelector('.nk-reviews__toggle');
			const items = container.querySelectorAll('.nk-reviews__item');
			const shownCount = container.querySelector('.nk-reviews__shown-count');
			const statusText = container.querySelector('.nk-reviews__status');

			if (!toggle) {
				return;
			}

			// Count matching vs total.
			let matchingCount = 0;
			let totalCount = items.length;

			items.forEach(function (item) {
				if (item.dataset.matches === '1') {
					matchingCount++;
				}
			});

			toggle.addEventListener('click', function () {
				const isPressed = toggle.getAttribute('aria-pressed') === 'true';
				const newState = !isPressed;

				toggle.setAttribute('aria-pressed', newState.toString());

				if (newState) {
					// Show all reviews.
					container.setAttribute('data-show-all', 'true');
					items.forEach(function (item) {
						item.style.display = '';
					});
					if (shownCount) {
						shownCount.textContent = totalCount;
					}
					if (statusText) {
						statusText.textContent = 'Showing all reviews.';
					}
				} else {
					// Show only matching reviews.
					container.removeAttribute('data-show-all');
					items.forEach(function (item) {
						if (item.dataset.matches === '0') {
							item.style.display = 'none';
						} else {
							item.style.display = '';
						}
					});
					if (shownCount) {
						shownCount.textContent = matchingCount;
					}
					if (statusText) {
						statusText.textContent = 'Showing reviews that mention Nan.';
					}
				}
			});
		});
	}

	/**
	 * Smooth scroll for anchor links.
	 */
	function initSmoothScroll() {
		document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
			anchor.addEventListener('click', function (e) {
				const targetId = this.getAttribute('href');
				if (targetId === '#') {
					return;
				}

				const target = document.querySelector(targetId);
				if (target) {
					e.preventDefault();
					target.scrollIntoView({
						behavior: 'smooth',
						block: 'start',
					});

					// Update focus for accessibility.
					target.setAttribute('tabindex', '-1');
					target.focus();
				}
			});
		});
	}

	/**
	 * Header scroll behavior - add shadow on scroll.
	 */
	function initHeaderScroll() {
		const header = document.querySelector('.nk-site-header');
		if (!header) {
			return;
		}

		let lastScroll = 0;

		window.addEventListener('scroll', function () {
			const currentScroll = window.pageYOffset;

			if (currentScroll > 10) {
				header.classList.add('is-scrolled');
			} else {
				header.classList.remove('is-scrolled');
			}

			lastScroll = currentScroll;
		}, { passive: true });
	}

	/**
	 * Animate elements on scroll (simple fade-in).
	 */
	function initScrollAnimations() {
		if (!('IntersectionObserver' in window)) {
			return;
		}

		const animatedElements = document.querySelectorAll('.nk-card, .nk-reviews__item');

		const observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-visible');
						observer.unobserve(entry.target);
					}
				});
			},
			{
				threshold: 0.1,
				rootMargin: '0px 0px -50px 0px',
			}
		);

		animatedElements.forEach(function (el) {
			el.classList.add('animate-on-scroll');
			observer.observe(el);
		});
	}

	/**
	 * Initialize all theme functionality.
	 */
	function init() {
		initReviewsToggle();
		initSmoothScroll();
		initHeaderScroll();
		initScrollAnimations();
	}

	// Run on DOM ready.
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();

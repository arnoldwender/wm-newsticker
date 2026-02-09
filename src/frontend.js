/**
 * WM Newsticker - Frontend Script
 *
 * Handles all ticker animations and controls on the frontend.
 * Supports: scroll, fade, slide, and typing animations.
 *
 * @package WM_Newsticker
 * @since 1.4.6
 */

( function () {
	'use strict';

	/**
	 * Check if user prefers reduced motion.
	 *
	 * @return {boolean} True if reduced motion is preferred.
	 */
	function prefersReducedMotion() {
		return (
			window.matchMedia &&
			window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches
		);
	}

	/**
	 * Initialize a scroll-type ticker.
	 *
	 * @param {HTMLElement} wrapper     The ticker wrapper element.
	 * @param {boolean}     hasControls Whether the ticker has controls.
	 */
	function initScrollTicker( wrapper, hasControls ) {
		var track = wrapper.querySelector( '.wm-newsticker-track' );
		if ( ! track ) {
			return;
		}

		var playing = true;
		var prog = wrapper.querySelector( '.wm-newsticker-progress-bar' );
		var pauseOnHover = wrapper.dataset.pauseOnHover === 'true';

		/**
		 * Pause the scroll animation.
		 */
		function pause() {
			track.style.animationPlayState = 'paused';
			if ( prog ) {
				prog.style.animationPlayState = 'paused';
			}
			playing = false;
		}

		/**
		 * Resume the scroll animation.
		 */
		function play() {
			track.style.animationPlayState = 'running';
			if ( prog ) {
				prog.style.animationPlayState = 'running';
			}
			playing = true;
		}

		if ( hasControls ) {
			var playPauseBtn = wrapper.querySelector(
				'.wm-control-play-pause'
			);
			var iconPause = playPauseBtn
				? playPauseBtn.querySelector( '.wm-icon-pause' )
				: null;
			var iconPlay = playPauseBtn
				? playPauseBtn.querySelector( '.wm-icon-play' )
				: null;

			/**
			 * Update the play/pause icon visibility.
			 */
			function updateIcons() {
				if ( iconPause ) {
					iconPause.style.display = playing ? 'block' : 'none';
				}
				if ( iconPlay ) {
					iconPlay.style.display = playing ? 'none' : 'block';
				}
			}

			if ( playPauseBtn ) {
				playPauseBtn.addEventListener( 'click', function () {
					if ( playing ) {
						pause();
					} else {
						play();
					}
					updateIcons();
				} );
			}
		}

		if ( pauseOnHover ) {
			wrapper.addEventListener( 'mouseenter', function () {
				if ( playing ) {
					pause();
					if ( hasControls && typeof updateIcons === 'function' ) {
						// Icons are updated through closure.
					}
				}
			} );
			wrapper.addEventListener( 'mouseleave', function () {
				play();
				if ( hasControls && typeof updateIcons === 'function' ) {
					// Icons are updated through closure.
				}
			} );
		}
	}

	/**
	 * Initialize a slide-type ticker (fade, slide, typing).
	 *
	 * @param {HTMLElement} wrapper     The ticker wrapper element.
	 * @param {boolean}     hasControls Whether the ticker has controls.
	 * @param {number}      duration    Duration per slide in milliseconds.
	 * @param {number}      total       Total number of slides.
	 */
	function initSlideTicker( wrapper, hasControls, duration, total ) {
		var slides = wrapper.querySelectorAll( '.wm-newsticker-slide' );
		var current = 0;
		var paused = false;
		var prog = wrapper.querySelector( '.wm-newsticker-progress-bar' );
		var pauseOnHover = wrapper.dataset.pauseOnHover === 'true';

		/**
		 * Show a specific slide by index.
		 *
		 * @param {number} idx The slide index to show.
		 */
		function show( idx ) {
			slides.forEach( function ( slide, i ) {
				slide.classList.remove( 'active', 'exit' );
				if ( i === idx ) {
					slide.classList.add( 'active' );
				} else if ( i === ( idx - 1 + total ) % total ) {
					slide.classList.add( 'exit' );
				}
			} );

			if ( prog ) {
				prog.style.animation = 'none';
				prog.offsetHeight; // Trigger reflow.
				prog.style.animation = '';
			}
		}

		/**
		 * Advance to the next slide.
		 */
		function next() {
			current = ( current + 1 ) % total;
			show( current );
		}

		/**
		 * Go to the previous slide.
		 */
		function prev() {
			current = ( current - 1 + total ) % total;
			show( current );
		}

		/**
		 * Interval tick handler.
		 */
		function tick() {
			if ( ! paused ) {
				next();
			}
		}

		if ( total > 1 ) {
			setInterval( tick, duration );

			if ( hasControls ) {
				var playPauseBtn = wrapper.querySelector(
					'.wm-control-play-pause'
				);
				var prevBtn = wrapper.querySelector( '.wm-control-prev' );
				var nextBtn = wrapper.querySelector( '.wm-control-next' );
				var iconPause = playPauseBtn
					? playPauseBtn.querySelector( '.wm-icon-pause' )
					: null;
				var iconPlay = playPauseBtn
					? playPauseBtn.querySelector( '.wm-icon-play' )
					: null;

				/**
				 * Pause the slide rotation.
				 */
				function pauseSlides() {
					paused = true;
					if ( iconPause ) {
						iconPause.style.display = 'none';
					}
					if ( iconPlay ) {
						iconPlay.style.display = 'block';
					}
					if ( prog ) {
						prog.style.animationPlayState = 'paused';
					}
				}

				/**
				 * Resume the slide rotation.
				 */
				function playSlides() {
					paused = false;
					if ( iconPause ) {
						iconPause.style.display = 'block';
					}
					if ( iconPlay ) {
						iconPlay.style.display = 'none';
					}
					if ( prog ) {
						prog.style.animationPlayState = 'running';
					}
				}

				if ( playPauseBtn ) {
					playPauseBtn.addEventListener( 'click', function () {
						if ( paused ) {
							playSlides();
						} else {
							pauseSlides();
						}
					} );
				}
				if ( prevBtn ) {
					prevBtn.addEventListener( 'click', function () {
						prev();
					} );
				}
				if ( nextBtn ) {
					nextBtn.addEventListener( 'click', function () {
						next();
					} );
				}

				if ( pauseOnHover ) {
					wrapper.addEventListener( 'mouseenter', function () {
						pauseSlides();
					} );
					wrapper.addEventListener( 'mouseleave', function () {
						playSlides();
					} );
				}
			} else if ( pauseOnHover ) {
				wrapper.addEventListener( 'mouseenter', function () {
					paused = true;
				} );
				wrapper.addEventListener( 'mouseleave', function () {
					paused = false;
				} );
			}
		}
	}

	/**
	 * Initialize a single ticker instance.
	 *
	 * @param {HTMLElement} wrapper The ticker wrapper element.
	 */
	function initTicker( wrapper ) {
		var animationType = wrapper.dataset.animation || 'scroll';
		var hasControls = wrapper.dataset.hasControls === 'true';
		var speed = parseFloat( wrapper.dataset.speed ) || 5;
		var itemCount = parseInt( wrapper.dataset.items, 10 ) || 1;

		if ( animationType === 'scroll' ) {
			initScrollTicker( wrapper, hasControls );
		} else {
			initSlideTicker(
				wrapper,
				hasControls,
				speed * 1000,
				itemCount
			);
		}
	}

	/**
	 * Initialize all tickers on the page.
	 */
	function initAllTickers() {
		var tickers = document.querySelectorAll( '.wm-newsticker-wrapper' );
		tickers.forEach( function ( ticker ) {
			// Skip already initialized tickers.
			if ( ticker.dataset.initialized ) {
				return;
			}
			ticker.dataset.initialized = 'true';

			// If user prefers reduced motion, pause all CSS animations.
			if ( prefersReducedMotion() ) {
				var track = ticker.querySelector( '.wm-newsticker-track' );
				if ( track ) {
					track.style.animationPlayState = 'paused';
				}
			}

			initTicker( ticker );
		} );
	}

	// Initialize when DOM is ready.
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAllTickers );
	} else {
		initAllTickers();
	}
} )();

/**
 * Bootstrap 5.3 Carousel - Slider Component
 * Uses Bootstrap's built-in carousel slider functionality
 * Requires Bootstrap 5.3+ JavaScript bundle
 */

(function() {
  'use strict';

  /**
   * Initialize Bootstrap Carousel Slider
   * Supports data attributes for configuration:
   * - data-bs-ride: 'carousel' for auto-play
   * - data-bs-interval: milliseconds for slide interval
   * - data-bs-pause: 'hover' or 'false' to control pause behavior
   * - data-bs-touch: 'true' or 'false' for touch support
   * - data-bs-wrap: 'true' or 'false' to loop slides
   */
  class BootstrapCarouselSlider {
    constructor(element) {
      this.element = element;
      this.carousel = null;
      this.init();
    }

    init() {
      // Check if Bootstrap is available - wait for it if necessary
      if (typeof bootstrap === 'undefined' || !bootstrap.Carousel) {
        // Retry initialization after a delay
        setTimeout(() => {
          if (typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
            this.initializeCarousel();
          } else {
            console.warn('Bootstrap 5.3+ is required for carousel slider functionality');
          }
        }, 100);
        return;
      }

      // Initialize immediately if Bootstrap is available
      this.initializeCarousel();
    }

    initializeCarousel() {
      // Initialize Bootstrap carousel with element
      this.carousel = new bootstrap.Carousel(this.element, {
        interval: this.element.dataset.bsInterval 
          ? parseInt(this.element.dataset.bsInterval, 10) 
          : 5000,
        pause: this.element.dataset.bsPause || 'hover',
        ride: this.element.dataset.bsRide === 'carousel' ? 'carousel' : false,
        touch: this.element.dataset.bsTouch !== 'false',
        wrap: this.element.dataset.bsWrap !== 'false'
      });

      // Set up event listeners for indicators
      this.setupIndicators();
    }

    setupIndicators() {
      const indicators = this.element.querySelectorAll('[data-bs-slide-to]');
      
      indicators.forEach((indicator) => {
        indicator.addEventListener('click', (e) => {
          e.preventDefault();
          const slideIndex = parseInt(indicator.dataset.bsSlideTo, 10);
          if (this.carousel) {
            this.carousel.to(slideIndex);
          }
        });
      });
    }

    getCarousel() {
      return this.carousel;
    }

    // Public methods to control carousel
    next() {
      if (this.carousel) this.carousel.next();
    }

    prev() {
      if (this.carousel) this.carousel.prev();
    }

    to(index) {
      if (this.carousel) this.carousel.to(index);
    }

    pause() {
      if (this.carousel) this.carousel.pause();
    }

    cycle() {
      if (this.carousel) this.carousel.cycle();
    }

    dispose() {
      if (this.carousel) this.carousel.dispose();
    }

    static getInstance(element) {
      const instance = element._bootstrapCarouselSlider;
      if (!instance) {
        element._bootstrapCarouselSlider = new BootstrapCarouselSlider(element);
      }
      return element._bootstrapCarouselSlider;
    }
  }

  /**
   * Auto-initialize all carousels with data-bs-ride attribute
   */
  function initializeCarousels() {
    const carousels = document.querySelectorAll('[data-bs-ride="carousel"]');
    carousels.forEach((carousel) => {
      BootstrapCarouselSlider.getInstance(carousel);
    });
  }

  /**
   * Initialize on DOM ready
   */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeCarousels);
  } else {
    initializeCarousels();
  }

  /**
   * Re-initialize carousels if new ones are added dynamically
   */
  if (typeof MutationObserver !== 'undefined') {
    const observer = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        if (mutation.addedNodes && mutation.addedNodes.length > 0) {
          mutation.addedNodes.forEach((node) => {
            if (node.nodeType === 1 && node.classList && node.classList.contains('carousel')) {
              BootstrapCarouselSlider.getInstance(node);
            }
          });
        }
      });
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  }

  // Expose to window for manual control if needed
  window.BootstrapCarouselSlider = BootstrapCarouselSlider;

})();

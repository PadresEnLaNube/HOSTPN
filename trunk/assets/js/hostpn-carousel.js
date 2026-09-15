/**
 * HOSTPN Carousel Component
 *
 * Custom carousel/slider component for the HOSTPN plugin
 *
 * @package    hostpn
 * @subpackage hostpn/assets/js
 */

(function($) {
  'use strict';

  /**
   * HOSTPN_Carousel class
   */
  window.HOSTPN_Carousel = class {
    constructor(element, options = {}) {
      this.carousel = $(element);
      this.currentIndex = 0;
      this.autoplayInterval = null;
      this.isTransitioning = false;

      // Default options
      this.options = $.extend({
        autoplay: false,
        autoplaySpeed: 5000,
        speed: 500,
        loop: true,
        dots: true,
        nav: true,
        counter: false,
        itemsDesktop: 1,
        itemsMobile: 1,
        onSlideChange: null,
        swipe: true
      }, options);

      this.init();
    }

    init() {
      if (!this.carousel.length) return;

      this.buildStructure();
      this.bindEvents();

      if (this.options.autoplay) {
        this.startAutoplay();
      }

      // Update on load
      this.updateCarousel(false);
    }

    buildStructure() {
      // Wrap slides
      const slides = this.carousel.children();
      this.slideCount = slides.length;

      if (this.slideCount === 0) {
        this.carousel.html('<div class="hostpn-carousel-empty"><i class="material-icons-outlined">image</i><p>' + (window.hostpn_i18n?.no_images || 'No images available') + '</p></div>');
        return;
      }

      slides.addClass('hostpn-carousel-slide');
      slides.wrapAll('<div class="hostpn-carousel-container"><div class="hostpn-carousel-track"></div></div>');

      this.container = this.carousel.find('.hostpn-carousel-container');
      this.track = this.carousel.find('.hostpn-carousel-track');

      // Add navigation arrows
      if (this.options.nav && this.slideCount > 1) {
        this.carousel.append(`
          <button class="hostpn-carousel-nav hostpn-carousel-prev" aria-label="Previous">
            <i class="material-icons-outlined">chevron_left</i>
          </button>
          <button class="hostpn-carousel-nav hostpn-carousel-next" aria-label="Next">
            <i class="material-icons-outlined">chevron_right</i>
          </button>
        `);

        this.prevBtn = this.carousel.find('.hostpn-carousel-prev');
        this.nextBtn = this.carousel.find('.hostpn-carousel-next');
      }

      // Add dots
      if (this.options.dots && this.slideCount > 1) {
        const dotsHtml = '<div class="hostpn-carousel-dots">' +
          Array.from({length: this.slideCount}, (_, i) =>
            `<button class="hostpn-carousel-dot ${i === 0 ? 'hostpn-carousel-dot-active' : ''}" data-index="${i}" aria-label="Slide ${i + 1}"></button>`
          ).join('') +
          '</div>';

        this.carousel.append(dotsHtml);
        this.dots = this.carousel.find('.hostpn-carousel-dot');
      }

      // Add counter
      if (this.options.counter && this.slideCount > 1) {
        this.carousel.append('<div class="hostpn-carousel-counter"><span class="hostpn-carousel-current">1</span> / <span class="hostpn-carousel-total">' + this.slideCount + '</span></div>');
        this.counter = this.carousel.find('.hostpn-carousel-counter');
      }

      // Add autoplay indicator
      if (this.options.autoplay && this.slideCount > 1) {
        this.carousel.append('<div class="hostpn-carousel-autoplay-indicator"><i class="material-icons-outlined">pause</i></div>');
        this.autoplayIndicator = this.carousel.find('.hostpn-carousel-autoplay-indicator');
      }

      // Set data attributes for responsive items
      this.carousel.attr('data-items-desktop', this.options.itemsDesktop);
      this.carousel.attr('data-items-mobile', this.options.itemsMobile);
    }

    bindEvents() {
      const self = this;

      // Navigation buttons
      if (this.prevBtn) {
        this.prevBtn.on('click', () => this.prev());
      }
      if (this.nextBtn) {
        this.nextBtn.on('click', () => this.next());
      }

      // Dots
      if (this.dots) {
        this.dots.on('click', function() {
          const index = parseInt($(this).data('index'));
          self.goToSlide(index);
        });
      }

      // Autoplay indicator
      if (this.autoplayIndicator) {
        this.autoplayIndicator.on('click', () => this.toggleAutoplay());
      }

      // Keyboard navigation - only when carousel is focused
      this.carousel.attr('tabindex', '0');
      this.carousel.on('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
          e.preventDefault();
          this.prev();
        } else if (e.key === 'ArrowRight') {
          e.preventDefault();
          this.next();
        }
      });

      // Touch/swipe support
      if (this.options.swipe) {
        let startX = 0;
        let currentX = 0;
        let isDragging = false;

        this.carousel.on('touchstart mousedown', (e) => {
          startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
          isDragging = true;
        });

        this.carousel.on('touchmove mousemove', (e) => {
          if (!isDragging) return;
          currentX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
        });

        this.carousel.on('touchend mouseup mouseleave', () => {
          if (!isDragging) return;

          const diff = startX - currentX;

          if (Math.abs(diff) > 50) {
            if (diff > 0) {
              this.next();
            } else {
              this.prev();
            }
          }

          isDragging = false;
        });
      }

      // Pause autoplay on hover
      if (this.options.autoplay) {
        this.carousel.on('mouseenter', () => this.pauseAutoplay());
        this.carousel.on('mouseleave', () => {
          if (this.autoplayIndicator && this.autoplayIndicator.find('i').text() === 'pause') {
            this.startAutoplay();
          }
        });
      }
    }

    updateCarousel(animate = true) {
      if (!this.track || this.isTransitioning) return;

      const slideWidth = 100 / this.getCurrentItemsPerSlide();
      const offset = -this.currentIndex * slideWidth;

      if (animate) {
        this.isTransitioning = true;
        this.track.css('transition', `transform ${this.options.speed}ms ease-in-out`);

        setTimeout(() => {
          this.isTransitioning = false;
        }, this.options.speed);
      } else {
        this.track.css('transition', 'none');
      }

      this.track.css('transform', `translateX(${offset}%)`);

      // Update dots
      if (this.dots) {
        this.dots.removeClass('hostpn-carousel-dot-active');
        this.dots.eq(this.currentIndex).addClass('hostpn-carousel-dot-active');
      }

      // Update counter
      if (this.counter) {
        this.counter.find('.hostpn-carousel-current').text(this.currentIndex + 1);
      }

      // Update navigation buttons
      if (!this.options.loop) {
        if (this.prevBtn) {
          this.prevBtn.prop('disabled', this.currentIndex === 0);
        }
        if (this.nextBtn) {
          this.nextBtn.prop('disabled', this.currentIndex === this.slideCount - 1);
        }
      }

      // Callback
      if (this.options.onSlideChange) {
        this.options.onSlideChange(this.currentIndex);
      }
    }

    getCurrentItemsPerSlide() {
      const windowWidth = $(window).width();
      return windowWidth <= 768 ? this.options.itemsMobile : this.options.itemsDesktop;
    }

    next() {
      if (this.slideCount <= 1 || this.isTransitioning) return;

      if (this.currentIndex < this.slideCount - 1) {
        this.currentIndex++;
      } else if (this.options.loop) {
        this.currentIndex = 0;
      } else {
        return; // Don't do anything if at the end and not looping
      }

      this.updateCarousel();
    }

    prev() {
      if (this.slideCount <= 1 || this.isTransitioning) return;

      if (this.currentIndex > 0) {
        this.currentIndex--;
      } else if (this.options.loop) {
        this.currentIndex = this.slideCount - 1;
      } else {
        return; // Don't do anything if at the start and not looping
      }

      this.updateCarousel();
    }

    goToSlide(index) {
      if (this.isTransitioning || index < 0 || index >= this.slideCount || index === this.currentIndex) return;

      this.currentIndex = index;
      this.updateCarousel();
    }

    startAutoplay() {
      if (this.slideCount <= 1) return;

      this.stopAutoplay();
      this.autoplayInterval = setInterval(() => this.next(), this.options.autoplaySpeed);

      if (this.autoplayIndicator) {
        this.autoplayIndicator.find('i').text('pause');
      }
    }

    stopAutoplay() {
      if (this.autoplayInterval) {
        clearInterval(this.autoplayInterval);
        this.autoplayInterval = null;
      }

      if (this.autoplayIndicator) {
        this.autoplayIndicator.find('i').text('play_arrow');
      }
    }

    pauseAutoplay() {
      this.stopAutoplay();
    }

    toggleAutoplay() {
      if (this.autoplayInterval) {
        this.stopAutoplay();
      } else {
        this.startAutoplay();
      }
    }

    destroy() {
      this.stopAutoplay();

      // Remove event listeners
      if (this.prevBtn) this.prevBtn.off('click');
      if (this.nextBtn) this.nextBtn.off('click');
      if (this.dots) this.dots.off('click');
      if (this.autoplayIndicator) this.autoplayIndicator.off('click');

      // Remove carousel-specific listeners
      this.carousel.off('keydown');
      this.carousel.off('touchstart mousedown');
      this.carousel.off('touchmove mousemove');
      this.carousel.off('touchend mouseup mouseleave');
      this.carousel.off('mouseenter mouseleave');

      // Remove added elements
      this.carousel.find('.hostpn-carousel-nav').remove();
      this.carousel.find('.hostpn-carousel-dots').remove();
      this.carousel.find('.hostpn-carousel-counter').remove();
      this.carousel.find('.hostpn-carousel-autoplay-indicator').remove();

      // Unwrap slides
      const slides = this.carousel.find('.hostpn-carousel-slide');
      slides.unwrap('.hostpn-carousel-track').unwrap('.hostpn-carousel-container');
      slides.removeClass('hostpn-carousel-slide');

      // Remove instance data
      this.carousel.removeData('hostpn-carousel');
    }
  };

  /**
   * jQuery plugin wrapper
   */
  $.fn.hostpnCarousel = function(options) {
    return this.each(function() {
      const $this = $(this);
      let instance = $this.data('hostpn-carousel');

      if (!instance) {
        instance = new HOSTPN_Carousel(this, options);
        $this.data('hostpn-carousel', instance);
      }

      return instance;
    });
  };

  /**
   * Auto-initialize carousels with data attributes
   */
  $(document).ready(function() {
    $('.hostpn-carousel[data-auto-init="true"]').each(function() {
      const $carousel = $(this);
      const options = {
        autoplay: $carousel.data('autoplay') === true,
        autoplaySpeed: $carousel.data('autoplay-speed') || 5000,
        speed: $carousel.data('speed') || 500,
        loop: $carousel.data('loop') !== false,
        dots: $carousel.data('dots') !== false,
        nav: $carousel.data('nav') !== false,
        counter: $carousel.data('counter') === true,
        itemsDesktop: $carousel.data('items-desktop') || 1,
        itemsMobile: $carousel.data('items-mobile') || 1,
        swipe: $carousel.data('swipe') !== false
      };

      $carousel.hostpnCarousel(options);
    });
  });

})(jQuery);

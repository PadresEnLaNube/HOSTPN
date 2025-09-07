/**
 * HOSTPN Accommodation Public JavaScript
 * 
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    hostpn
 * @subpackage hostpn/assets/js/public
 */

(function($) {
    'use strict';

    // DOM Ready
    $(document).ready(function() {
        
        // Initialize accommodation functionality
        initAccommodationFeatures();
        initAccommodationNavigation();
        initAccommodationSearch();
        initAccommodationFilters();
        
    });

    /**
     * Initialize accommodation features display
     */
    function initAccommodationFeatures() {
        
        // Feature count display
        $('.hostpn-feature-category').each(function() {
            var $category = $(this);
            var $featureList = $category.find('.hostpn-feature-list');
            var featureCount = $featureList.find('.hostpn-feature-item').length;
            
            // Add count badge if features exist
            if (featureCount > 0) {
                var $countBadge = $('<span class="hostpn-feature-count-badge">' + featureCount + '</span>');
                $category.find('h4').append($countBadge);
            }
        });

        // Feature category toggle functionality
        $('.hostpn-toggle-header').on('click', function() {
            var $header = $(this);
            var $category = $header.closest('.hostpn-feature-category');
            var $content = $category.find('.hostpn-toggle-content');
            var $icon = $header.find('.hostpn-toggle-icon');
            
            // Toggle content visibility
            $content.slideToggle(300);
            
            // Toggle icon rotation
            $icon.toggleClass('hostpn-rotated');
            
            // Toggle header state
            $header.toggleClass('hostpn-expanded');
        });
        
        // Initialize all toggles as collapsed on desktop, expanded on mobile
        if (window.innerWidth <= 768) {
            $('.hostpn-toggle-content').show();
            $('.hostpn-toggle-header').addClass('hostpn-expanded');
            $('.hostpn-toggle-icon').addClass('hostpn-rotated');
        } else {
            $('.hostpn-toggle-content').hide();
            $('.hostpn-toggle-header').removeClass('hostpn-expanded');
            $('.hostpn-toggle-icon').removeClass('hostpn-rotated');
        }

        // Lazy loading for feature images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('hostpn-lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Initialize accommodation navigation
     */
    function initAccommodationNavigation() {
        
        // Smooth scroll to top
        $('.hostpn-back-to-archive').on('click', function(e) {
            e.preventDefault();
            
            $('html, body').animate({
                scrollTop: 0
            }, 800);
        });

        // Navigation hover effects
        $('.hostpn-nav-previous a, .hostpn-nav-next a').hover(
            function() {
                $(this).find('i').addClass('hostpn-animate');
            },
            function() {
                $(this).find('i').removeClass('hostpn-animate');
            }
        );

        // Keyboard navigation
        $(document).keydown(function(e) {
            if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                if (e.keyCode === 37) { // Left arrow
                    $('.hostpn-nav-previous a').click();
                } else if (e.keyCode === 39) { // Right arrow
                    $('.hostpn-nav-next a').click();
                }
            }
        });
    }

    /**
     * Initialize accommodation search functionality
     */
    function initAccommodationSearch() {
        
        var $searchInput = $('.hostpn-accommodation-search-input');
        var $searchToggle = $('.hostpn-accommodation-search-toggle');
        var $accommodationCards = $('.hostpn-accommodation-card');
        
        if ($searchInput.length && $searchToggle.length) {
            
            // Toggle search input
            $searchToggle.on('click', function() {
                $searchInput.toggleClass('hostpn-display-none');
                $searchInput.focus();
            });

            // Search functionality
            $searchInput.on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                
                $accommodationCards.each(function() {
                    var $card = $(this);
                    var cardText = $card.text().toLowerCase();
                    var cardTitle = $card.find('.hostpn-accommodation-title').text().toLowerCase();
                    var cardLocation = $card.find('.hostpn-location-text').text().toLowerCase();
                    
                    if (cardText.includes(searchTerm) || 
                        cardTitle.includes(searchTerm) || 
                        cardLocation.includes(searchTerm)) {
                        $card.show();
                    } else {
                        $card.hide();
                    }
                });

                // Show/hide no results message
                var visibleCards = $accommodationCards.filter(':visible').length;
                if (visibleCards === 0 && searchTerm !== '') {
                    showNoResultsMessage(searchTerm);
                } else {
                    hideNoResultsMessage();
                }
            });

            // Clear search on escape
            $searchInput.on('keydown', function(e) {
                if (e.keyCode === 27) { // Escape key
                    $(this).val('').trigger('input');
                    $(this).addClass('hostpn-display-none');
                }
            });
        }
    }

    /**
     * Initialize accommodation filters
     */
    function initAccommodationFilters() {
        
        // Filter by accommodation type
        $('.hostpn-accommodation-type-filter').on('change', function() {
            var selectedType = $(this).val();
            var $accommodationCards = $('.hostpn-accommodation-card');
            
            if (selectedType === '') {
                $accommodationCards.show();
            } else {
                $accommodationCards.each(function() {
                    var $card = $(this);
                    var cardType = $card.find('.hostpn-accommodation-type .hostpn-value').text();
                    
                    if (cardType === selectedType) {
                        $card.show();
                    } else {
                        $card.hide();
                    }
                });
            }
        });

        // Filter by features
        $('.hostpn-feature-filter').on('change', function() {
            var selectedFeatures = $('.hostpn-feature-filter:checked').map(function() {
                return $(this).val();
            }).get();
            
            var $accommodationCards = $('.hostpn-accommodation-card');
            
            if (selectedFeatures.length === 0) {
                $accommodationCards.show();
            } else {
                $accommodationCards.each(function() {
                    var $card = $(this);
                    var cardFeatures = $card.find('.hostpn-feature-group').map(function() {
                        return $(this).attr('class').match(/hostpn-feature-group-(\w+)/)[1];
                    }).get();
                    
                    var hasAllFeatures = selectedFeatures.every(function(feature) {
                        return cardFeatures.includes(feature);
                    });
                    
                    if (hasAllFeatures) {
                        $card.show();
                    } else {
                        $card.hide();
                    }
                });
            }
        });
    }

    /**
     * Show no results message
     */
    function showNoResultsMessage(searchTerm) {
        if ($('.hostpn-no-search-results').length === 0) {
            var $noResults = $('<div class="hostpn-no-search-results hostpn-no-accommodations">' +
                '<div class="hostpn-no-accommodations-content">' +
                '<i class="material-icons-outlined hostpn-icon-large">search</i>' +
                '<h3>No accommodations found</h3>' +
                '<p>No accommodations match your search for "' + searchTerm + '". Try different keywords.</p>' +
                '<button class="hostpn-btn hostpn-btn-secondary hostpn-clear-search">Clear Search</button>' +
                '</div></div>');
            
            $('.hostpn-accommodation-grid').after($noResults);
            
            // Clear search button functionality
            $('.hostpn-clear-search').on('click', function() {
                $('.hostpn-accommodation-search-input').val('').trigger('input');
                $('.hostpn-no-search-results').remove();
            });
        }
    }

    /**
     * Hide no results message
     */
    function hideNoResultsMessage() {
        $('.hostpn-no-search-results').remove();
    }

    /**
     * Utility function to debounce search input
     */
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    /**
     * Utility function to format feature names
     */
    function formatFeatureName(featureKey) {
        return featureKey
            .replace('hostpn_', '')
            .replace(/_/g, ' ')
            .replace(/\b\w/g, function(l) {
                return l.toUpperCase();
            });
    }

    /**
     * Initialize lazy loading for images
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('hostpn-lazy');
                            imageObserver.unobserve(img);
                        }
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Initialize smooth scrolling
     */
    function initSmoothScrolling() {
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            var target = $(this.getAttribute('href'));
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        });
    }

    /**
     * Initialize tooltips
     */
    function initTooltips() {
        $('[title]').each(function() {
            var $element = $(this);
            var title = $element.attr('title');
            
            if (title) {
                $element.removeAttr('title');
                $element.attr('data-tooltip', title);
            }
        });

        // Simple tooltip implementation
        $('[data-tooltip]').on('mouseenter', function() {
            var $element = $(this);
            var tooltipText = $element.attr('data-tooltip');
            
            var $tooltip = $('<div class="hostpn-tooltip">' + tooltipText + '</div>');
            $('body').append($tooltip);
            
            var elementRect = this.getBoundingClientRect();
            $tooltip.css({
                position: 'fixed',
                top: elementRect.top - $tooltip.outerHeight() - 10,
                left: elementRect.left + (elementRect.width / 2) - ($tooltip.outerWidth() / 2),
                zIndex: 10000
            });
        }).on('mouseleave', function() {
            $('.hostpn-tooltip').remove();
        });
    }

    // Initialize additional functionality when window loads
    $(window).on('load', function() {
        initLazyLoading();
        initSmoothScrolling();
        initTooltips();
    });

    // Handle window resize
    $(window).on('resize', debounce(function() {
        // Reinitialize responsive features
        if (window.innerWidth <= 768) {
            $('.hostpn-feature-category h4').off('click').on('click', function() {
                var $category = $(this).closest('.hostpn-feature-category');
                var $featureList = $category.find('.hostpn-feature-list');
                
                $featureList.slideToggle(300);
                $(this).toggleClass('hostpn-expanded');
            });
        }
    }, 250));

})(jQuery);

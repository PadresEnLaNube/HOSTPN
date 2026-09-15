(function($) {
    'use strict';

    var $tooltipBox = null;
    var hideTimeout = null;
    var touchTimeout = null;

    function createTooltipBox() {
        if (!$tooltipBox) {
            $tooltipBox = $('<div class="hostpn-tooltip-box"></div>');
            $('body').append($tooltipBox);
        }
        return $tooltipBox;
    }

    function positionTooltip(element) {
        var box = createTooltipBox();
        var rect = element.getBoundingClientRect();
        var boxWidth = box.outerWidth();
        var boxHeight = box.outerHeight();
        var spaceAbove = rect.top;
        var left = rect.left + (rect.width / 2) - (boxWidth / 2);

        // Clamp left to viewport
        if (left < 4) left = 4;
        if (left + boxWidth > window.innerWidth - 4) left = window.innerWidth - boxWidth - 4;

        if (spaceAbove >= boxHeight + 10) {
            // Position above
            box.removeClass('hostpn-tooltip-box--bottom');
            box.css({
                top: rect.top - boxHeight - 8,
                left: left
            });
        } else {
            // Position below
            box.addClass('hostpn-tooltip-box--bottom');
            box.css({
                top: rect.bottom + 8,
                left: left
            });
        }
    }

    function getTooltipContent(el) {
        var $el = $(el);

        // HTML mode: data-hostpn-tooltip-content points to a selector
        var contentSelector = $el.attr('data-hostpn-tooltip-content');
        if (contentSelector) {
            var $source = $(contentSelector);
            if ($source.length) {
                return $source.html();
            }
        }

        // Title mode: data-hostpn-tooltip (moved from title on init)
        var text = $el.attr('data-hostpn-tooltip');
        if (text) {
            return $('<span>').text(text).html(); // escape HTML
        }

        return null;
    }

    function showTooltip(el) {
        clearTimeout(hideTimeout);
        clearTimeout(touchTimeout);

        var content = getTooltipContent(el);
        if (!content) return;

        var box = createTooltipBox();
        box.html(content);
        box.addClass('hostpn-tooltip-box--visible');
        positionTooltip(el);
    }

    function hideTooltip() {
        clearTimeout(touchTimeout);
        if ($tooltipBox) {
            $tooltipBox.removeClass('hostpn-tooltip-box--visible hostpn-tooltip-box--bottom');
        }
    }

    window.HOSTPN_Tooltips = {
        init: function(selector) {
            selector = selector || '.hostpn-tooltip';

            // Move title to data attribute to prevent native browser tooltip
            $(selector).each(function() {
                var $el = $(this);
                if ($el.attr('title') && !$el.attr('data-hostpn-tooltip')) {
                    $el.attr('data-hostpn-tooltip', $el.attr('title'));
                    $el.removeAttr('title');
                }
            });
        },

        show: function(element) {
            var el = element instanceof $ ? element[0] : element;
            if (el) showTooltip(el);
        },

        hide: function() {
            hideTooltip();
        }
    };

    // Event delegation — works for dynamic elements without re-init
    $(document).on('mouseenter', '.hostpn-tooltip', function() {
        // Move title on first hover if not yet moved
        var $el = $(this);
        if ($el.attr('title') && !$el.attr('data-hostpn-tooltip')) {
            $el.attr('data-hostpn-tooltip', $el.attr('title'));
            $el.removeAttr('title');
        }
        showTooltip(this);
    });

    $(document).on('mouseleave', '.hostpn-tooltip', function() {
        hideTooltip();
    });

    $(document).on('focusin', '.hostpn-tooltip', function() {
        var $el = $(this);
        if ($el.attr('title') && !$el.attr('data-hostpn-tooltip')) {
            $el.attr('data-hostpn-tooltip', $el.attr('title'));
            $el.removeAttr('title');
        }
        showTooltip(this);
    });

    $(document).on('focusout', '.hostpn-tooltip', function() {
        hideTooltip();
    });

    // Touch support: show on tap, auto-hide after 4s
    $(document).on('touchstart', '.hostpn-tooltip', function(e) {
        var $el = $(this);
        if ($el.attr('title') && !$el.attr('data-hostpn-tooltip')) {
            $el.attr('data-hostpn-tooltip', $el.attr('title'));
            $el.removeAttr('title');
        }
        showTooltip(this);
        touchTimeout = setTimeout(function() {
            hideTooltip();
        }, 4000);
    });

    // Auto-init on document ready
    $(document).ready(function() {
        HOSTPN_Tooltips.init();
    });
})(jQuery);

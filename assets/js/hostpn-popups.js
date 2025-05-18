(function($) {
    'use strict';
  
    window.HOSTPN_Popups = {
      open: function(popup, options = {}) {
        var popupElement = typeof popup === 'string' ? $('#' + popup) : popup;
        
        if (!popupElement.length) {
          return;
        }
  
        if (typeof options.beforeShow === 'function') {
          options.beforeShow();
        }
  
        // Show overlay
        $('.hostpn-popup-overlay').removeClass('hostpn-display-none-soft').fadeIn('fast');
  
        // Show the popup
        popupElement.addClass('hostpn-popup-active').fadeIn('fast');
        document.body.classList.add('hostpn-popup-open');
  
        // Add close button if not present
        if (!popupElement.find('.hostpn-popup-close').length) {
          var closeButton = $('<button class="hostpn-popup-close-wrapper"><i class="material-icons-outlined">close</i></button>');
          closeButton.on('click', function() {
            HOSTPN_Popups.close();
          });
          popupElement.find('.hostpn-popup-content').append(closeButton);
        }
  
        // Store and call callbacks if provided
        if (options.beforeShow) {
          popupElement.data('beforeShow', options.beforeShow);
        }
        if (options.afterClose) {
          popupElement.data('afterClose', options.afterClose);
        }
      },
  
      close: function() {
        // Hide all popups
        $('.hostpn-popup').fadeOut('fast');
  
        // Hide overlay
        $('.hostpn-popup-overlay').fadeOut('fast', function() {
          $(this).addClass('hostpn-display-none-soft');
        });
  
        // Call afterClose callback if exists
        $('.hostpn-popup').each(function() {
          const afterClose = $(this).data('afterClose');
          if (typeof afterClose === 'function') {
            afterClose();
            $(this).removeData('afterClose');
          }
        });
        
        document.body.classList.remove('hostpn-popup-open');
      }
    };
  
    // Initialize popup functionality
    $(document).ready(function() {
      // Close popup when clicking overlay
      $(document).on('click', '.hostpn-popup-overlay', function(e) {
        // Only close if the click was directly on the overlay
        if (e.target === this) {
          HOSTPN_Popups.close();
        }
      });
  
      // Prevent clicks inside popup from bubbling up to the overlay
      $(document).on('click', '.hostpn-popup', function(e) {
        e.stopPropagation();
      });
  
      // Close popup when pressing ESC key
      $(document).on('keyup', function(e) {
        if (e.keyCode === 27) { // ESC key
          HOSTPN_Popups.close();
        }
      });
  
      // Close popup when clicking close button
      $(document).on('click', '.hostpn-popup-close', function(e) {
        e.preventDefault();
        HOSTPN_Popups.close();
      });
    });
  })(jQuery); 
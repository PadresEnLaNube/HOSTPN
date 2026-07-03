(function($) {
	'use strict';

  $(document).ready(function() {
    $(document).on('submit', '.hostpn-form', function(e){
      var hostpn_form = $(this);
      var hostpn_btn = hostpn_form.find('input[type="submit"]');
      hostpn_btn.addClass('hostpn-link-disabled').siblings('.hostpn-waiting').removeClass('hostpn-display-none');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax_nopriv',
        hostpn_ajax_nopriv_nonce: hostpn_ajax.hostpn_ajax_nonce,
        userspn_ajax_nopriv_nonce: userspn_ajax.userspn_ajax_nonce,
        hostpn_get_nonce: hostpn_action.hostpn_get_nonce,
        hostpn_ajax_nopriv_type: 'hostpn_form_save',
        hostpn_form_id: hostpn_form.attr('id'),
        hostpn_form_type: hostpn_btn.attr('data-hostpn-type'),
        hostpn_form_subtype: hostpn_btn.attr('data-hostpn-subtype'),
        hostpn_form_user_id: hostpn_btn.attr('data-hostpn-user-id'),
        hostpn_form_post_id: hostpn_btn.attr('data-hostpn-post-id'),
        hostpn_form_post_type: hostpn_btn.attr('data-hostpn-post-type'),
        hostpn_ajax_keys: [],
      };

      if (!(typeof window['hostpn_window_vars'] !== 'undefined')) {
        window['hostpn_window_vars'] = [];
      }

      $(hostpn_form.find('input:not([type="submit"]), select, textarea')).each(function(index, element) {
        var is_multiple = $(this).parents('.userspn-html-multi-group').length;
        
        if (is_multiple) {
          if (!(typeof window['hostpn_window_vars']['form_field_' + element.name] !== 'undefined')) {
            window['hostpn_window_vars']['form_field_' + element.name] = [];
          }

          window['hostpn_window_vars']['form_field_' + element.name].push($(element).val());

          data[element.name] = window['hostpn_window_vars']['form_field_' + element.name];
        }else{
          if ($(this).is(':checkbox')) {
            if ($(this).is(':checked')) {
              data[element.name] = $(element).val();
            }else{
              data[element.name] = '';
            }
          }else if ($(this).is(':radio')) {
            if ($(this).is(':checked')) {
              data[element.name] = $(element).val();
            }
          }else{
            data[element.name] = $(element).val();
          }
        }

        data.hostpn_ajax_keys.push({
          id: element.name,
          node: element.nodeName,
          type: element.type,
          multiple: (is_multiple == 'multiple' ? true : false),
        });
      });

      $.post(ajax_url, data, function(response) {
        var response_json = $.parseJSON(response);

        if (response_json['error_key'] == 'hostpn_form_save_error_unlogged') {
          hostpn_get_main_message(hostpn_i18n.user_unlogged);

          if (!$('.userspn-profile-wrapper .user-unlogged').length) {
            $('.userspn-profile-wrapper').prepend('<div class="userspn-alert userspn-alert-warning user-unlogged">' + hostpn_i18n.user_unlogged + '</div>');
          }

          HOSTPN_Popups.open($('#userspn-profile-popup'));
          $('#userspn-login input#user_login').focus();
        }else if (response_json['error_key'] != '') {
          hostpn_get_main_message(hostpn_i18n.an_error_has_occurred);
        }else {
          hostpn_get_main_message(hostpn_i18n.saved_successfully);
        }

        if (response_json['update_list']) {
          $('.hostpn-' + data.hostpn_form_post_type + '-list').html(response_json['update_html']);
        }

        if (response_json['popup_close']) {
          HOSTPN_Popups.close();
          $('.hostpn-menu-more-overlay').fadeOut('fast');
        }

        if (response_json['check'] == 'post_check') {
          HOSTPN_Popups.close();
          $('.hostpn-menu-more-overlay').fadeOut('fast');
          $('.hostpn-' + data.hostpn_form_post_type + '-list-item[data-' + data.hostpn_form_post_type + '-id="' + data.hostpn_form_post_id + '"] .hostpn-check-wrapper i').text('task_alt');
        }else if (response_json['check'] == 'post_uncheck') {
          HOSTPN_Popups.close();
          $('.hostpn-menu-more-overlay').fadeOut('fast');
          $('.hostpn-' + data.hostpn_form_post_type + '-list-item[data-' + data.hostpn_form_post_type + '-id="' + data.hostpn_form_post_id + '"] .hostpn-check-wrapper i').text('radio_button_unchecked');
        }

        hostpn_btn.removeClass('hostpn-link-disabled').siblings('.hostpn-waiting').addClass('hostpn-display-none')
      });

      delete window['hostpn_window_vars'];
      return false;
    });

    $(document).on('click', '.hostpn-popup-open-ajax', function(e) {
      e.preventDefault();

      var hostpn_btn = $(this);
      var hostpn_ajax_type = hostpn_btn.attr('data-hostpn-ajax-type');
      var hostpn_accommodation_id = hostpn_btn.closest('.hostpn-accommodation').attr('data-hostpn_accommodation-id');
      var hostpn_part_id = hostpn_btn.closest('.hostpn-part').attr('data-hostpn_part-id');
      var hostpn_guest_id = hostpn_btn.closest('.hostpn-guest').attr('data-hostpn_guest-id');
      var hostpn_popup_element = $('#' + hostpn_btn.attr('data-hostpn-popup-id'));

      HOSTPN_Popups.open(hostpn_popup_element, {
        beforeShow: function(instance, popup) {
          var ajax_url = hostpn_ajax.ajax_url;
          var data = {
            action: 'hostpn_ajax',
            hostpn_ajax_type: hostpn_ajax_type,
            hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
            hostpn_get_nonce: hostpn_action.hostpn_get_nonce,
            hostpn_accommodation_id: hostpn_accommodation_id ? hostpn_accommodation_id : '',
            hostpn_part_id: hostpn_part_id ? hostpn_part_id : '',
            hostpn_guest_id: hostpn_guest_id ? hostpn_guest_id : '',
          };

          $.ajax({
            url: ajax_url,
            type: 'POST',
            data: data,
            success: function(response) {
              try {               
                // Check if response is already an object (parsed JSON)
                var response_json = typeof response === 'object' ? response : null;
                
                // If not an object, try to parse as JSON
                if (!response_json) {
                  try {
                    response_json = JSON.parse(response);
                  } catch (parseError) {
                    // If parsing fails, assume it's HTML content
                    hostpn_popup_element.find('.hostpn-popup-content').html(response);
                    
                    // Initialize media uploaders if function exists
                    if (typeof initMediaUpload === 'function') {
                      $('.hostpn-image-upload-wrapper').each(function() {
                        initMediaUpload($(this), 'image');
                      });
                      $('.hostpn-audio-upload-wrapper').each(function() {
                        initMediaUpload($(this), 'audio');
                      });
                      $('.hostpn-video-upload-wrapper').each(function() {
                        initMediaUpload($(this), 'video');
                      });
                    }
                    return;
                  }
                }

                // Handle JSON response
                if (response_json.error_key) {
                  var errorMessage = response_json.error_message || hostpn_i18n.an_error_has_occurred;
                  hostpn_get_main_message(errorMessage);
                  return;
                }

                // Handle successful JSON response with HTML content
                if (response_json.html) {
                  hostpn_popup_element.find('.hostpn-popup-content').html(response_json.html);
                  
                  // Initialize media uploaders if function exists
                  if (typeof initMediaUpload === 'function') {
                    $('.hostpn-image-upload-wrapper').each(function() {
                      initMediaUpload($(this), 'image');
                    });
                    $('.hostpn-audio-upload-wrapper').each(function() {
                      initMediaUpload($(this), 'audio');
                    });
                    $('.hostpn-video-upload-wrapper').each(function() {
                      initMediaUpload($(this), 'video');
                    });
                  }
                } else {
                  hostpn_get_main_message(hostpn_i18n.an_error_has_occurred);
                }
              } catch (e) {
                hostpn_get_main_message(hostpn_i18n.an_error_has_occurred);
              }
            },
            error: function(xhr, status, error) {
              console.log(hostpn_i18n.an_error_has_occurred);
            }
          });
        },
        afterClose: function() {
          hostpn_popup_element.find('.hostpn-popup-content').html(hostpn_ajax.popup_loader);
        },
      });
    });

    $(document).on('click', '.hostpn-accommodation-duplicate-post', function(e) {
      e.preventDefault();

      $('.hostpn-accommodations').fadeOut('fast');
      var hostpn_btn = $(this);
      var hostpn_accommodation_id = hostpn_btn.closest('.hostpn-accommodation').attr('data-hostpn_accommodation-id');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_accommodation_duplicate',
        hostpn_accommodation_id: hostpn_accommodation_id,
        hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
      };

      $.post(ajax_url, data, function(response) {
        var response_json = $.parseJSON(response);

        if (response_json['error_key'] != '') {
          hostpn_get_main_message(response_json['error_content']);
        }else{
          $('.hostpn-accommodations').html(response_json['html']);
        }
        
        $('.hostpn-accommodations').fadeIn('slow');
        $('.hostpn-menu-more-overlay').fadeOut('fast');
      });
    });

    $(document).on('click', '.hostpn-accommodation-remove', function(e) {
      e.preventDefault();

      $('.hostpn-accommodations').fadeOut('fast');
      var hostpn_accommodation_id = $('.hostpn-menu-more.hostpn-active').closest('.hostpn-accommodation').attr('data-hostpn_accommodation-id');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_accommodation_remove',
        hostpn_accommodation_id: hostpn_accommodation_id,
        hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
      };

      $.post(ajax_url, data, function(response) {
        var response_json = $.parseJSON(response);
       
        if (response_json['error_key'] != '') {
          hostpn_get_main_message(response_json['error_content']);
        }else{
          $('.hostpn-accommodations').html(response_json['html']);
          hostpn_get_main_message(hostpn_i18n.removed_successfully);
        }
        
        $('.hostpn-accommodations').fadeIn('slow');
        $('.hostpn-menu-more-overlay').fadeOut('fast');

        HOSTPN_Popups.close();
      });
    });

    $(document).on('click', '.hostpn-guest-duplicate-post', function(e) {
      e.preventDefault();

      $('.hostpn-guests').fadeOut('fast');
      var hostpn_btn = $(this);
      var hostpn_guest_id = hostpn_btn.closest('.hostpn-guest').attr('data-hostpn_guest-id');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_guest_duplicate',
        hostpn_guest_id: hostpn_guest_id,
        hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
      };

      $.post(ajax_url, data, function(response) {
        var response_json = $.parseJSON(response);

        if (response_json['error_key'] != '') {
          hostpn_get_main_message(response_json['error_content']);
        }else{
          $('.hostpn-guests').html(response_json['html']);
        }

        $('.hostpn-guests').fadeIn('slow');
        $('.hostpn-menu-more-overlay').fadeOut('fast');
      });
    });

    $(document).on('click', '.hostpn-guest-resend-notification', function(e) {
      e.preventDefault();

      var hostpn_btn = $(this);
      // Get guest ID from the parent .hostpn-guest element
      var hostpn_guest_id = hostpn_btn.closest('.hostpn-guest').attr('data-hostpn_guest-id');

      // Add loading indicator
      var original_html = hostpn_btn.html();
      hostpn_btn.html('<div class="hostpn-display-table hostpn-width-100-percent"><div class="hostpn-display-inline-table hostpn-width-70-percent"><p>' + (hostpn_i18n.sending || 'Sending...') + '</p></div><div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right"><i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">hourglass_empty</i></div></div>');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_guest_resend_notification',
        post_id: hostpn_guest_id,
        nonce: hostpn_ajax.hostpn_ajax_nonce,
      };

      $.post(ajax_url, data, function(response) {
        // Restore original button HTML
        hostpn_btn.html(original_html);

        if (response.success) {
          var message = (response.data && response.data.message) ? response.data.message : (hostpn_i18n.notification_sent || 'Notification sent successfully');
          hostpn_get_main_message(message);
        } else {
          var error_message = (response.data && response.data.message) ? response.data.message : (response.data || hostpn_i18n.an_error_has_occurred);
          hostpn_get_main_message(error_message);
        }

        // Close the contextual menu
        $('.hostpn-menu-more.hostpn-active').fadeOut('fast').removeClass('hostpn-active');
        $('.hostpn-menu-more-overlay').fadeOut('fast');
      }).fail(function(xhr, status, error) {
        // Restore original button HTML on error
        hostpn_btn.html(original_html);
        hostpn_get_main_message(hostpn_i18n.an_error_has_occurred);

        // Close the contextual menu
        $('.hostpn-menu-more.hostpn-active').fadeOut('fast').removeClass('hostpn-active');
        $('.hostpn-menu-more-overlay').fadeOut('fast');
      });
    });

    $(document).on('click', '.hostpn-guest-remove', function(e) {
      e.preventDefault();

      $('.hostpn-guests').fadeOut('fast');
      var hostpn_guest_id = $('.hostpn-menu-more.hostpn-active').closest('.hostpn-guest').attr('data-hostpn_guest-id');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_guest_remove',
        hostpn_guest_id: hostpn_guest_id,
        hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
      };

      $.post(ajax_url, data, function(response) {
        var response_json = $.parseJSON(response);
       
        if (response_json['error_key'] != '') {
          hostpn_get_main_message(response_json['error_content']);
        }else{
          $('.hostpn-guests').html(response_json['html']);
          hostpn_get_main_message(hostpn_i18n.removed_successfully);
        }
        
        $('.hostpn-guests').fadeIn('slow');
        $('.hostpn-menu-more-overlay').fadeOut('fast');

        HOSTPN_Popups.close();
      });
    });

    

    $(document).on('click', '.hostpn-part-duplicate-post', function(e) {
      e.preventDefault();

      $('.hostpn-parts').fadeOut('fast');
      var hostpn_btn = $(this);
      var hostpn_part_id = hostpn_btn.closest('.hostpn-part').attr('data-hostpn_part-id');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_part_duplicate',
        hostpn_part_id: hostpn_part_id,
        hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
      };

      $.post(ajax_url, data, function(response) {
        var response_json = $.parseJSON(response);

        if (response_json['error_key'] != '') {
          hostpn_get_main_message(response_json['error_content']);
        }else{
          $('.hostpn-parts').html(response_json['html']);
        }
        
        $('.hostpn-parts').fadeIn('slow');
        $('.hostpn-menu-more-overlay').fadeOut('fast');
      });
    });

    $(document).on('click', '.hostpn-part-remove', function(e) {
      e.preventDefault();

      $('.hostpn-parts').fadeOut('fast');
      var hostpn_part_id = $('.hostpn-menu-more.hostpn-active').closest('.hostpn-part').attr('data-hostpn_part-id');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_part_remove',
        hostpn_part_id: hostpn_part_id,
        hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
      };

      $.post(ajax_url, data, function(response) {
        var response_json = $.parseJSON(response);
       
        if (response_json['error_key'] != '') {
          hostpn_get_main_message(response_json['error_content']);
        }else{
          $('.hostpn-parts').html(response_json['html']);
          hostpn_get_main_message(hostpn_i18n.removed_successfully);
        }
        
        $('.hostpn-parts').fadeIn('slow');
        $('.hostpn-menu-more-overlay').fadeOut('fast');

        HOSTPN_Popups.close();
      });
    });

    $(document).on('click', '.hostpn-part-download', function(e) {
      e.preventDefault();

      var hostpn_btn = $(this);
      var hostpn_part_id = hostpn_btn.closest('.hostpn-part').attr('data-hostpn_part-id');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_part_download',
        hostpn_part_id: hostpn_part_id,
        hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
      };

      $.post(ajax_url, data, function(response) {
        var filename = 'part-of-traveler-' + hostpn_part_id + '.xml';
        var pom = document.createElement('a');
        var bb = new Blob([response], {type: 'text/plain'});

        pom.setAttribute('href', window.URL.createObjectURL(bb));
        pom.setAttribute('download', filename);

        pom.dataset.downloadurl = ['text/plain', pom.download, pom.href].join(':');
        pom.draggable = true; 
        pom.classList.add('dragout');
        pom.click();
      });
    });

    // Download CSV of stays (Parts)
    // Using event delegation to handle dynamically loaded popup content
    // Using form submission approach for reliable browser download
    $(document).on('click', '.hostpn-part-csv-download-btn', function(e) {
      e.preventDefault();
      console.log('[HOSTPN CSV] Button clicked');

      var btn = $(this);
      var form = btn.closest('.hostpn-part-csv-export-form');
      var yearInput = $('#hostpn-part-csv-year');
      var includeNameCheck = $('#hostpn-csv-include-name');
      var includeDocTypeCheck = $('#hostpn-csv-include-doc-type');
      var includeDocNumberCheck = $('#hostpn-csv-include-doc-number');

      if (!form.length || !yearInput.length) {
        console.error('[HOSTPN CSV] Form or year input not found');
        return;
      }

      var ajaxUrl = form.attr('data-hostpn-ajax-url');
      var nonce = form.attr('data-hostpn-nonce');

      console.log('[HOSTPN CSV] Ajax URL:', ajaxUrl);
      console.log('[HOSTPN CSV] Nonce exists:', !!nonce);

      if (!ajaxUrl || !nonce) {
        console.error('[HOSTPN CSV] Missing ajax URL or nonce');
        return;
      }

      var year = parseInt(yearInput.val(), 10);
      if (isNaN(year) || year < 2000 || year > 2100) {
        alert(hostpn_i18n.an_error_has_occurred || 'Please enter a valid year between 2000 and 2100.');
        return;
      }

      console.log('[HOSTPN CSV] Year:', year);
      console.log('[HOSTPN CSV] Include name:', includeNameCheck.length && includeNameCheck.is(':checked'));
      console.log('[HOSTPN CSV] Include doc type:', includeDocTypeCheck.length && includeDocTypeCheck.is(':checked'));
      console.log('[HOSTPN CSV] Include doc number:', includeDocNumberCheck.length && includeDocNumberCheck.is(':checked'));

      // Create a hidden form and submit it to trigger download
      console.log('[HOSTPN CSV] Creating form for download...');

      var downloadForm = $('<form>', {
        method: 'POST',
        action: ajaxUrl,
        style: 'display:none;'
      });

      // Add form fields
      downloadForm.append($('<input>', { type: 'hidden', name: 'action', value: 'hostpn_ajax' }));
      downloadForm.append($('<input>', { type: 'hidden', name: 'hostpn_ajax_type', value: 'hostpn_part_csv_download' }));
      downloadForm.append($('<input>', { type: 'hidden', name: 'hostpn_year', value: year }));
      downloadForm.append($('<input>', { type: 'hidden', name: 'hostpn_ajax_nonce', value: nonce }));
      downloadForm.append($('<input>', {
        type: 'hidden',
        name: 'include_guest_name',
        value: includeNameCheck.length && includeNameCheck.is(':checked') ? '1' : '0'
      }));
      downloadForm.append($('<input>', {
        type: 'hidden',
        name: 'include_doc_type',
        value: includeDocTypeCheck.length && includeDocTypeCheck.is(':checked') ? '1' : '0'
      }));
      downloadForm.append($('<input>', {
        type: 'hidden',
        name: 'include_doc_number',
        value: includeDocNumberCheck.length && includeDocNumberCheck.is(':checked') ? '1' : '0'
      }));

      // Append form to body and submit
      $('body').append(downloadForm);
      console.log('[HOSTPN CSV] Submitting form...');
      downloadForm.submit();

      console.log('[HOSTPN CSV] Form submitted, download should start');

      // Clean up form after a short delay
      setTimeout(function() {
        downloadForm.remove();
        console.log('[HOSTPN CSV] Form removed from DOM');
      }, 1000);
    });
  });
})(jQuery);

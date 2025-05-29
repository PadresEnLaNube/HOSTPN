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
        if ($(this).parents('.hostpn-html-multi-group').length) {
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
        });
      });

      $.post(ajax_url, data, function(response) {
        console.log('data');console.log(data);
        console.log('response');console.log(response);

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
            ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
            hostpn_get_nonce: hostpn_action.hostpn_get_nonce,
            hostpn_accommodation_id: hostpn_accommodation_id ? hostpn_accommodation_id : '',
            hostpn_part_id: hostpn_part_id ? hostpn_part_id : '',
            hostpn_guest_id: hostpn_guest_id ? hostpn_guest_id : '',
          };

          // Log the data being sent
          console.log('HOSTPN AJAX - Sending request with data:', data);

          $.ajax({
            url: ajax_url,
            type: 'POST',
            data: data,
            success: function(response) {
              try {
                // First try to parse the response as JSON
                var response_json = typeof response === 'string' ? JSON.parse(response) : response;
                
                // Check for error key in response
                if (response_json.error_key) {
                  hostpn_get_main_message('HOSTPN AJAX - Server returned error:', response_json.error_key);
                  // Display the error message if available, otherwise show generic error
                  var errorMessage = response_json.error_ || hostpn_i18n.an_error_has_occurred;
                  hostpn_get_main_message(errorMessage);
                  return;
                }

                // Check for HTML content
                if (response_json.html) {
                  console.log('HOSTPN AJAX - HTML content received');
                  console.log(response_json);
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
                  console.log('HOSTPN AJAX - Response missing HTML content');
                  console.log(hostpn_i18n.an_error_has_occurred);
                }
              } catch (e) {
                console.log('HOSTPN AJAX - Failed to parse response:', e);
                console.log('Raw response:', response);
                console.log(hostpn_i18n.an_error_has_occurred);
              }
            },
            error: function(xhr, status, error) {
              console.log('HOSTPN AJAX - Request failed:', status, error);
              console.log('Response:', xhr.responseText);
              console.log(hostpn_i18n.an_error_has_occurred);
            }
          });
        },
        afterClose: function() {
          hostpn_popup_element.find('.hostpn-popup-content').html('<div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>');
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
        console.log('data');console.log(data);console.log('response');console.log(response);
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
        console.log('data');console.log(data);console.log('response');console.log(response);
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
        console.log('data');console.log(data);console.log('response');console.log(response);
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
        console.log('data');console.log(data);console.log('response');console.log(response);
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
        console.log('data');console.log(data);console.log('response');console.log(response);
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
        console.log('data');console.log(data);console.log('response');console.log(response);
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
  });
})(jQuery);

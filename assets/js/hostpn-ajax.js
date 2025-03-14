(function($) {
	'use strict';

  $(document).ready(function() {
    $(document).on('submit', '.hostpn-form', function(e){
      var hostpn_form = $(this);
      var hostpn_btn = hostpn_form.find('input[type="submit"]');
      hostpn_btn.addClass('hostpn-link-disabled').siblings('.hostpn-waiting').removeClass('hostpn-display-none-soft');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax_nopriv',
        ajax_nonce: hostpn_ajax.ajax_nonce,
        hostpn_ajax_nopriv_type: 'hostpn_form_save',
        hostpn_form_id: hostpn_form.attr('id'),
        hostpn_form_type: hostpn_btn.attr('data-hostpn-type'),
        hostpn_form_subtype: hostpn_btn.attr('data-hostpn-subtype'),
        hostpn_form_user_id: hostpn_btn.attr('data-hostpn-user-id'),
        hostpn_form_post_id: hostpn_btn.attr('data-hostpn-post-id'),
        hostpn_form_post_type: hostpn_btn.attr('data-hostpn-post-type'),
        ajax_keys: [],
      };

      if (!(typeof window['hostpn_window_vars'] !== 'undefined')) {
        window['hostpn_window_vars'] = [];
      }

      $(hostpn_form.find('input:not([type="submit"]), select, textarea')).each(function(index, element) {
        if ($(this).parents('.hostpn-html-multi-group').length) {
          if (!(typeof window['hostpn_window_vars']['form_field_' + element.id] !== 'undefined')) {
            window['hostpn_window_vars']['form_field_' + element.id] = [];
          }

          window['hostpn_window_vars']['form_field_' + element.id].push($(element).val());

          data[element.id] = window['hostpn_window_vars']['form_field_' + element.id];
        }else{
          if ($(this).is(':checkbox') || $(this).is(':radio')) {
            if ($(this).is(':checked')) {
              data[element.id] = $(element).val();
            }else{
              data[element.id] = '';
            }
          }else{
            data[element.id] = $(element).val();
          }
        }

        data.ajax_keys.push({
          id: element.id,
          node: element.nodeName,
          type: element.type,
        });
      });

      $.post(ajax_url, data, function(response) {
        if ($.parseJSON(response)['error_key'] == 'hostpn_form_save_error_unlogged') {
          hostpn_get_main_message(hostpn_i18n.user_unlogged);

          if (!$('.users-wph-profile-wrapper .user-unlogged').length) {
            $('.users-wph-profile-wrapper').prepend('<div class="users-wph-alert users-wph-alert-warning user-unlogged">' + hostpn_i18n.user_unlogged + '</div>');
          }

          $.fancybox.open($('#users-wph-profile-popup'), {touch: false});
          $('#users-wph-login input#user_login').focus();
        }else if ($.parseJSON(response)['error_key'] == 'hostpn_form_save_error') {
          hostpn_get_main_message(hostpn_i18n.an_error_has_occurred);
        }else {
          hostpn_get_main_message(hostpn_i18n.saved_successfully);
        }

        if ($.parseJSON(response)['update_list']) {
          $('.hostpn-' + data.hostpn_form_post_type + '-list').html($.parseJSON(response)['update_html']);
        }

        if ($.parseJSON(response)['popup_close']) {
          $.fancybox.close(true);
          $('.hostpn-menu-more-overlay').fadeOut('fast');
        }

        if ($.parseJSON(response)['check'] == 'post_check') {
          $.fancybox.close(true);
          $('.hostpn-menu-more-overlay').fadeOut('fast');
          $('.hostpn-list-element[data-hostpn-element-id="' + data.hostpn_form_post_id + '"] .hostpn-check-wrapper i').text('host_alt');
        }else if ($.parseJSON(response)['check'] == 'post_uncheck') {
          $.fancybox.close(true);
          $('.hostpn-menu-more-overlay').fadeOut('fast');
          $('.hostpn-list-element[data-hostpn-element-id="' + data.hostpn_form_post_id + '"] .hostpn-check-wrapper i').text('radio_button_unchecked');
        }

        hostpn_btn.removeClass('hostpn-link-disabled').siblings('.hostpn-waiting').addClass('hostpn-display-none-soft')
      });

      delete window['hostpn_window_vars'];
      return false;
    });

    $(document).on('click', '.hostpn-popup-open-ajax', function(e) {
      e.preventDefault();

      var hostpn_btn = $(this);
      var hostpn_ajax_type = hostpn_btn.attr('data-hostpn-ajax-type');
      var accommodation_id = hostpn_btn.closest('.hostpn-list-element').attr('data-hostpn-element-id');
      var part_id = hostpn_btn.closest('.hostpn-list-element').attr('data-hostpn-element-id');
      var guest_id = hostpn_btn.closest('.hostpn-list-element').attr('data-hostpn-element-id');
      var popup_element = $('#' + hostpn_btn.attr('data-hostpn-popup-id'));

      $.fancybox.open(popup_element, {
        touch: false,
        beforeShow: function(instance, current, e) {
          var ajax_url = hostpn_ajax.ajax_url;
          var data = {
            action: 'hostpn_ajax',
            hostpn_ajax_type: hostpn_ajax_type,
            accommodation_id: accommodation_id,
            part_id: part_id,
            guest_id: guest_id,
          };

          $.post(ajax_url, data, function(response) {
            if ($.parseJSON(response)['error_key'] != '') {
              hostpn_get_main_message($.parseJSON(response)['error']);
            }else{
              popup_element.find('.hostpn-popup-content').html($.parseJSON(response)['html']);
            }
          });
        },
        afterShow: function(instance, current, e) {
          hostpn_select_country();
          hostpn_select_identity();
        },
        afterClose: function(instance, current, e) {
          popup_element.find('.hostpn-popup-content').html('<div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>');
        },
      },);
    });

    $(document).on('click', '.hostpn-guest-duplicate', function(e) {
      e.preventDefault();
      $('.hostpn-guests').fadeOut('fast');

      var hostpn_btn = $(this);
      var guest_id = hostpn_btn.closest('.hostpn-list-element').attr('data-hostpn-element-id');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_guest_duplicate',
        hostpn_duplicate: guest_id,
        guest_id: guest_id,
      };

      $.post(ajax_url, data, function(response) {
        if ($.parseJSON(response)['error_key'] != '') {
          hostpn_get_main_message($.parseJSON(response)['error']);
        }else{
          $('.hostpn-guests').html($.parseJSON(response)['html']);
        }
        
        $('.hostpn-guests').fadeIn('slow');
        $('.hostpn-menu-more-overlay').fadeOut('fast');
      });
    });

    $(document).on('click', '.hostpn-guest-remove', function(e) {
      e.preventDefault();

      $('.hostpn-guests').fadeOut('fast');
      var guest_id = $('.hostpn-menu-more.hostpn-active').closest('.hostpn-list-element').attr('data-hostpn-element-id');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_guest_remove',
        guest_id: guest_id,
      };

      $.post(ajax_url, data, function(response) {
        if ($.parseJSON(response)['error_key'] != '') {
          hostpn_get_main_message($.parseJSON(response)['error']);
        }else{
          $('.hostpn-guests').html($.parseJSON(response)['html']);
        }
        
        $('.hostpn-guests').fadeIn('slow');
        $('.hostpn-menu-more-overlay').fadeOut('fast');
        $.fancybox.close();
      });
    });

    $(document).on('click', '.hostpn-accommodation-duplicate', function(e) {
      e.preventDefault();
      $('.hostpn-accommodations').fadeOut('fast');

      var hostpn_btn = $(this);
      var accommodation_id = hostpn_btn.closest('.hostpn-list-element').attr('data-hostpn-element-id');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_accommodation_duplicate',
        hostpn_duplicate: guest_id,
        accommodation_id: accommodation_id,
      };

      $.post(ajax_url, data, function(response) {
        if ($.parseJSON(response)['error_key'] != '') {
          hostpn_get_main_message($.parseJSON(response)['error']);
        }else{
          $('.hostpn-accommodations').html($.parseJSON(response)['html']);
        }
        
        $('.hostpn-accommodations').fadeIn('slow');
        $('.hostpn-menu-more-overlay').fadeOut('fast');
      });
    });

    $(document).on('click', '.hostpn-accommodation-remove', function(e) {
      e.preventDefault();

      $('.hostpn-accommodations').fadeOut('fast');
      var accommodation_id = $('.hostpn-menu-more.hostpn-active').closest('.hostpn-list-element').attr('data-hostpn-element-id');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_accommodation_remove',
        accommodation_id: accommodation_id,
      };

      $.post(ajax_url, data, function(response) {
        if ($.parseJSON(response)['error_key'] != '') {
          hostpn_get_main_message($.parseJSON(response)['error']);
        }else{
          $('.hostpn-accommodations').html($.parseJSON(response)['html']);
        }
        
        $('.hostpn-accommodations').fadeIn('slow');
        $('.hostpn-menu-more-overlay').fadeOut('fast');
        $.fancybox.close();
      });
    });

    $(document).on('click', '.hostpn-part-download', function(e) {
      e.preventDefault();

      var hostpn_btn = $(this);
      var part_id = hostpn_btn.closest('.hostpn-list-element').attr('data-hostpn-element-id');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_part_download',
        part_id: part_id,
      };

      $.post(ajax_url, data, function(response) {
        var filename = 'part-of-traveler-' + part_id + '.xml';
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

    $(document).on('click', '.hostpn-part-duplicate', function(e) {
      e.preventDefault();
      $('.hostpn-parts').fadeOut('fast');

      var hostpn_btn = $(this);
      var part_id = hostpn_btn.closest('.hostpn-list-element').attr('data-hostpn-element-id');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_part_duplicate',
        hostpn_duplicate: part_id,
        part_id: part_id,
      };

      $.post(ajax_url, data, function(response) {
        if ($.parseJSON(response)['error_key'] != '') {
          hostpn_get_main_message($.parseJSON(response)['error']);
        }else{
          $('.hostpn-parts').html($.parseJSON(response)['html']);
        }
        
        $('.hostpn-parts').fadeIn('slow');
        $('.hostpn-menu-more-overlay').fadeOut('fast');
      });
    });

    $(document).on('click', '.hostpn-part-remove', function(e) {
      e.preventDefault();

      $('.hostpn-parts').fadeOut('fast');
      var part_id = $('.hostpn-menu-more.hostpn-active').closest('.hostpn-list-element').attr('data-hostpn-element-id');

      var ajax_url = hostpn_ajax.ajax_url;
      var data = {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_part_remove',
        part_id: part_id,
      };

      $.post(ajax_url, data, function(response) {
        if ($.parseJSON(response)['error_key'] != '') {
          hostpn_get_main_message($.parseJSON(response)['error']);
        }else{
          $('.hostpn-parts').html($.parseJSON(response)['html']);
        }
        
        $('.hostpn-parts').fadeIn('slow');
        $('.hostpn-menu-more-overlay').fadeOut('fast');
        $.fancybox.close();
      });
    });
  });
})(jQuery);

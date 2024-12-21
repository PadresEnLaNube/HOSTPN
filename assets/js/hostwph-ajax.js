(function($) {
	'use strict';

  $(document).ready(function() {
    $(document).on('submit', '.hostwph-form', function(e){
      var hostwph_form = $(this);
      var hostwph_btn = hostwph_form.find('input[type="submit"]');
      hostwph_btn.addClass('hostwph-link-disabled').siblings('.hostwph-waiting').removeClass('hostwph-display-none-soft');

      var ajax_url = hostwph_ajax.ajax_url;
      var data = {
        action: 'hostwph_ajax_nopriv',
        ajax_nonce: hostwph_ajax.ajax_nonce,
        hostwph_ajax_nopriv_type: 'hostwph_form_save',
        hostwph_form_id: hostwph_form.attr('id'),
        hostwph_form_type: hostwph_btn.attr('data-hostwph-type'),
        hostwph_form_subtype: hostwph_btn.attr('data-hostwph-subtype'),
        hostwph_form_user_id: hostwph_btn.attr('data-hostwph-user-id'),
        hostwph_form_post_id: hostwph_btn.attr('data-hostwph-post-id'),
        hostwph_form_post_type: hostwph_btn.attr('data-hostwph-post-type'),
        ajax_keys: [],
      };

      if (!(typeof window['hostwph_window_vars'] !== 'undefined')) {
        window['hostwph_window_vars'] = [];
      }

      $(hostwph_form.find('input:not([type="submit"]), select, textarea')).each(function(index, element) {
        if ($(this).parents('.hostwph-html-multi-group').length) {
          if (!(typeof window['hostwph_window_vars']['form_field_' + element.id] !== 'undefined')) {
            window['hostwph_window_vars']['form_field_' + element.id] = [];
          }

          window['hostwph_window_vars']['form_field_' + element.id].push($(element).val());

          data[element.id] = window['hostwph_window_vars']['form_field_' + element.id];
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
        console.log('data');console.log(data);
        if ($.parseJSON(response)['error_key'] == 'hostwph_form_save_error_unlogged') {
          hostwph_get_main_message(hostwph_i18n.user_unlogged);

          if (!$('.users-wph-profile-wrapper .user-unlogged').length) {
            $('.users-wph-profile-wrapper').prepend('<div class="users-wph-alert users-wph-alert-warning user-unlogged">' + hostwph_i18n.user_unlogged + '</div>');
          }

          $.fancybox.open($('#users-wph-profile-popup'), {touch: false});
          $('#users-wph-login input#user_login').focus();
        }else if ($.parseJSON(response)['error_key'] == 'hostwph_form_save_error') {
          hostwph_get_main_message(hostwph_i18n.an_error_has_occurred);
        }else {
          hostwph_get_main_message(hostwph_i18n.saved_successfully);
        }

        if ($.parseJSON(response)['update_list']) {
          $('.hostwph-' + data.hostwph_form_post_type + '-list').html($.parseJSON(response)['update_html']);
        }

        if ($.parseJSON(response)['popup_close']) {
          $.fancybox.close(true);
          $('.hostwph-menu-more-overlay').fadeOut('fast');
        }

        if ($.parseJSON(response)['check'] == 'post_check') {
          $.fancybox.close(true);
          $('.hostwph-menu-more-overlay').fadeOut('fast');
          $('.hostwph-list-element[data-hostwph-element-id="' + data.hostwph_form_post_id + '"] .hostwph-check-wrapper i').text('host_alt');
        }else if ($.parseJSON(response)['check'] == 'post_uncheck') {
          $.fancybox.close(true);
          $('.hostwph-menu-more-overlay').fadeOut('fast');
          $('.hostwph-list-element[data-hostwph-element-id="' + data.hostwph_form_post_id + '"] .hostwph-check-wrapper i').text('radio_button_unchecked');
        }

        hostwph_btn.removeClass('hostwph-link-disabled').siblings('.hostwph-waiting').addClass('hostwph-display-none-soft')
      });

      delete window['hostwph_window_vars'];
      return false;
    });

    $(document).on('click', '.hostwph-popup-open-ajax', function(e) {
      e.preventDefault();

      var hostwph_btn = $(this);
      var hostwph_ajax_type = hostwph_btn.attr('data-hostwph-ajax-type');
      var accomodation_id = hostwph_btn.closest('.hostwph-list-element').attr('data-hostwph-element-id');
      var part_id = hostwph_btn.closest('.hostwph-list-element').attr('data-hostwph-element-id');
      var guest_id = hostwph_btn.closest('.hostwph-list-element').attr('data-hostwph-element-id');
      var popup_element = $('#' + hostwph_btn.attr('data-hostwph-popup-id'));

      $.fancybox.open(popup_element, {
        touch: false,
        beforeShow: function(instance, current, e) {
          var ajax_url = hostwph_ajax.ajax_url;
          var data = {
            action: 'hostwph_ajax',
            hostwph_ajax_type: hostwph_ajax_type,
            accomodation_id: accomodation_id,
            part_id: part_id,
            guest_id: guest_id,
          };

          $.post(ajax_url, data, function(response) {
            console.log('data');console.log(data);
            if ($.parseJSON(response)['error_key'] != '') {
              hostwph_get_main_message($.parseJSON(response)['error']);
            }else{
              popup_element.find('.hostwph-popup-content').html($.parseJSON(response)['html']);
            }
          });
        },
        afterClose: function(instance, current, e) {
         popup_element.find('.hostwph-popup-content').html('<div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>');
        },
      },);
    });

    $(document).on('click', '.hostwph-guest-duplicate', function(e) {
      e.preventDefault();
      $('.hostwph-guests').fadeOut('fast');

      var hostwph_btn = $(this);
      var guest_id = hostwph_btn.closest('.hostwph-list-element').attr('data-hostwph-element-id');

      var ajax_url = hostwph_ajax.ajax_url;
      var data = {
        action: 'hostwph_ajax',
        hostwph_ajax_type: 'hostwph_guest_duplicate',
        hostwph_duplicate: guest_id,
        guest_id: guest_id,
      };

      $.post(ajax_url, data, function(response) {
        console.log('data');console.log(data);
        if ($.parseJSON(response)['error_key'] != '') {
          hostwph_get_main_message($.parseJSON(response)['error']);
        }else{
          $('.hostwph-guests').html($.parseJSON(response)['html']);
        }
        
        $('.hostwph-guests').fadeIn('slow');
        $('.hostwph-menu-more-overlay').fadeOut('fast');
      });
    });

    $(document).on('click', '.hostwph-guest-remove', function(e) {
      e.preventDefault();

      $('.hostwph-guests').fadeOut('fast');
      var guest_id = $('.hostwph-menu-more.hostwph-active').closest('.hostwph-list-element').attr('data-hostwph-element-id');

      var ajax_url = hostwph_ajax.ajax_url;
      var data = {
        action: 'hostwph_ajax',
        hostwph_ajax_type: 'hostwph_guest_remove',
        guest_id: guest_id,
      };

      $.post(ajax_url, data, function(response) {
        console.log('data');console.log(data);
        if ($.parseJSON(response)['error_key'] != '') {
          hostwph_get_main_message($.parseJSON(response)['error']);
        }else{
          $('.hostwph-guests').html($.parseJSON(response)['html']);
        }
        
        $('.hostwph-guests').fadeIn('slow');
        $('.hostwph-menu-more-overlay').fadeOut('fast');
        $.fancybox.close();
      });
    });

    $(document).on('click', '.hostwph-accomodation-duplicate', function(e) {
      e.preventDefault();
      $('.hostwph-accomodations').fadeOut('fast');

      var hostwph_btn = $(this);
      var accomodation_id = hostwph_btn.closest('.hostwph-list-element').attr('data-hostwph-element-id');

      var ajax_url = hostwph_ajax.ajax_url;
      var data = {
        action: 'hostwph_ajax',
        hostwph_ajax_type: 'hostwph_accomodation_duplicate',
        hostwph_duplicate: guest_id,
        accomodation_id: accomodation_id,
      };

      $.post(ajax_url, data, function(response) {
        console.log('data');console.log(data);
        if ($.parseJSON(response)['error_key'] != '') {
          hostwph_get_main_message($.parseJSON(response)['error']);
        }else{
          $('.hostwph-accomodations').html($.parseJSON(response)['html']);
        }
        
        $('.hostwph-accomodations').fadeIn('slow');
        $('.hostwph-menu-more-overlay').fadeOut('fast');
      });
    });

    $(document).on('click', '.hostwph-accomodation-remove', function(e) {
      e.preventDefault();

      $('.hostwph-accomodations').fadeOut('fast');
      var accomodation_id = $('.hostwph-menu-more.hostwph-active').closest('.hostwph-list-element').attr('data-hostwph-element-id');

      var ajax_url = hostwph_ajax.ajax_url;
      var data = {
        action: 'hostwph_ajax',
        hostwph_ajax_type: 'hostwph_accomodation_remove',
        accomodation_id: accomodation_id,
      };

      $.post(ajax_url, data, function(response) {
        console.log('data');console.log(data);
        if ($.parseJSON(response)['error_key'] != '') {
          hostwph_get_main_message($.parseJSON(response)['error']);
        }else{
          $('.hostwph-accomodations').html($.parseJSON(response)['html']);
        }
        
        $('.hostwph-accomodations').fadeIn('slow');
        $('.hostwph-menu-more-overlay').fadeOut('fast');
        $.fancybox.close();
      });
    });

    $(document).on('click', '.hostwph-part-duplicate', function(e) {
      e.preventDefault();
      $('.hostwph-parts').fadeOut('fast');

      var hostwph_btn = $(this);
      var part_id = hostwph_btn.closest('.hostwph-list-element').attr('data-hostwph-element-id');

      var ajax_url = hostwph_ajax.ajax_url;
      var data = {
        action: 'hostwph_ajax',
        hostwph_ajax_type: 'hostwph_part_duplicate',
        hostwph_duplicate: part_id,
        part_id: part_id,
      };

      $.post(ajax_url, data, function(response) {
        console.log('data');console.log(data);
        if ($.parseJSON(response)['error_key'] != '') {
          hostwph_get_main_message($.parseJSON(response)['error']);
        }else{
          $('.hostwph-parts').html($.parseJSON(response)['html']);
        }
        
        $('.hostwph-parts').fadeIn('slow');
        $('.hostwph-menu-more-overlay').fadeOut('fast');
      });
    });

    $(document).on('click', '.hostwph-part-remove', function(e) {
      e.preventDefault();

      $('.hostwph-parts').fadeOut('fast');
      var part_id = $('.hostwph-menu-more.hostwph-active').closest('.hostwph-list-element').attr('data-hostwph-element-id');

      var ajax_url = hostwph_ajax.ajax_url;
      var data = {
        action: 'hostwph_ajax',
        hostwph_ajax_type: 'hostwph_part_remove',
        part_id: part_id,
      };

      $.post(ajax_url, data, function(response) {
        console.log('data');console.log(data);
        if ($.parseJSON(response)['error_key'] != '') {
          hostwph_get_main_message($.parseJSON(response)['error']);
        }else{
          $('.hostwph-parts').html($.parseJSON(response)['html']);
        }
        
        $('.hostwph-parts').fadeIn('slow');
        $('.hostwph-menu-more-overlay').fadeOut('fast');
        $.fancybox.close();
      });
    });
  });
})(jQuery);

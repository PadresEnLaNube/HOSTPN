(function($) {
	'use strict';

	$(document).on('click', '.wph-tab-links', function(e){
    e.preventDefault();
    var tab_link = $(this);
    var tab_wrapper = $(this).closest('.wph-tabs-wrapper');
    
    tab_wrapper.find('.wph-tab-links').each(function(index, element) {
      $(this).removeClass('active');
      $($(this).attr('data-wph-id')).addClass('wph-display-none');
    });

    tab_wrapper.find('.wph-tab-content').each(function(index, element) {
      $(this).addClass('wph-display-none');
    });
    
    tab_link.addClass('active');
    tab_wrapper.find('#' + tab_link.attr('data-wph-id')).removeClass('wph-display-none');
  });

  $(document).on('click', '.hostwph-options-save-btn', function(e){
    e.preventDefault();
    var hostwph_btn = $(this);
    hostwph_btn.addClass('hostwph-link-disabled').siblings('.hostwph-waiting').removeClass('hostwph-display-none-soft');

    var ajax_url = hostwph_ajax.ajax_url;

    var data = {
      action: 'hostwph_ajax',
      ajax_nonce: hostwph_ajax.ajax_nonce,
      hostwph_ajax_type: 'hostwph_options_save',
      ajax_keys: [],
    };

    if (!(typeof window['hostwph_window_vars'] !== 'undefined')) {
      window['hostwph_window_vars'] = [];
    }

    $('.hostwph-options-fields input:not([type="submit"]), .hostwph-options-fields select, .hostwph-options-fields textarea').each(function(index, element) {
      if ($(this).attr('multiple') && $(this).parents('.hostwph-html-multi-group').length) {
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
      console.log(data);console.log(response);
      if ($.parseJSON(response)['error_key'] != '') {
        hostwph_get_main_message(hostwph_i18n.an_error_has_occurred);
      }else {
        hostwph_get_main_message(hostwph_i18n.saved_successfully);
      }

      hostwph_btn.removeClass('hostwph-link-disabled').siblings('.hostwph-waiting').addClass('hostwph-display-none-soft')
    });

    delete window['hostwph_window_vars'];
  });
})(jQuery);
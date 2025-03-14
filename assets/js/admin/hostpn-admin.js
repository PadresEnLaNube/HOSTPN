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

  $(document).on('click', '.hostpn-options-save-btn', function(e){
    e.preventDefault();
    var hostpn_btn = $(this);
    hostpn_btn.addClass('hostpn-link-disabled').siblings('.hostpn-waiting').removeClass('hostpn-display-none-soft');

    var ajax_url = hostpn_ajax.ajax_url;

    var data = {
      action: 'hostpn_ajax',
      ajax_nonce: hostpn_ajax.ajax_nonce,
      hostpn_ajax_type: 'hostpn_options_save',
      ajax_keys: [],
    };

    if (!(typeof window['hostpn_window_vars'] !== 'undefined')) {
      window['hostpn_window_vars'] = [];
    }

    $('.hostpn-options-fields input:not([type="submit"]), .hostpn-options-fields select, .hostpn-options-fields textarea').each(function(index, element) {
      if ($(this).attr('multiple') && $(this).parents('.hostpn-html-multi-group').length) {
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
      console.log(data);console.log(response);
      if ($.parseJSON(response)['error_key'] != '') {
        hostpn_get_main_message(hostpn_i18n.an_error_has_occurred);
      }else {
        hostpn_get_main_message(hostpn_i18n.saved_successfully);
      }

      hostpn_btn.removeClass('hostpn-link-disabled').siblings('.hostpn-waiting').addClass('hostpn-display-none-soft')
    });

    delete window['hostpn_window_vars'];
  });
})(jQuery);
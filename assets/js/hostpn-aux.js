(function($) {
	'use strict';

  $(document).ready(function() {
    if (window.HOSTPN_Tooltips) {
      HOSTPN_Tooltips.init();
    }

    if ($('.hostpn-select').length && $.fn.HOSTPN_Selector) {
      $('.hostpn-select').each(function(index) {
        if ($(this).attr('multiple') == 'true') {
          // For a multiple select
          $(this).HOSTPN_Selector({
            multiple: true,
            searchable: true,
            placeholder: typeof hostpn_i18n !== 'undefined' ? hostpn_i18n.select_options : '',
          });
        }else{
          // For a single select
          $(this).HOSTPN_Selector();
        }
      });
    }

    if ($.trumbowyg && typeof hostpn_trumbowyg !== 'undefined' && $('.hostpn-wysiwyg').length) {
      $.trumbowyg.svgPath = hostpn_trumbowyg.path;
      $('.hostpn-wysiwyg').each(function(index, element) {
        $(this).trumbowyg();
      });
    }
  });
})(jQuery);

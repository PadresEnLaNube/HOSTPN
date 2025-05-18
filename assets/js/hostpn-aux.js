(function($) {
	'use strict';

  $(document).ready(function() {
    if($('.hostpn-tooltip').length) {
      $('.hostpn-tooltip').tooltipster({maxWidth: 300, delayTouch:[0, 4000]});
    }

    if ($('.hostpn-select').length) {
      $('.hostpn-select').each(function(index) {
        if ($(this).attr('multiple') == 'true') {
          // For a multiple select
          $(this).HOSTPN_Selector({
            multiple: true,
            searchable: true,
            placeholder: hostpn_i18n.select_options,
          });
        }else{
          // For a single select
          $(this).HOSTPN_Selector();
        }
      });
    }

    $.trumbowyg.svgPath = hostpn_trumbowyg.path;
    $('.hostpn-wysiwyg').each(function(index, element) {
      $(this).trumbowyg();
    });
  });
})(jQuery);

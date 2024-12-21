(function($) {
	'use strict';

  $(document).ready(function() {
    if($('.hostwph-tooltip').length) {
      $('.hostwph-tooltip').tooltipster({maxWidth: 300, delayTouch:[0, 4000]});
    }

    if ($('.hostwph-select').length) {
      $('.hostwph-select').each(function(index) {
        if ($(this).children('option').length < 7) {
          $(this).select2({minimumResultsForSearch: -1, allowClear: true});
        }else{
          $(this).select2({allowClear: true});
        }
      });
    }

    $.trumbowyg.svgPath = hostwph_trumbowyg.path;
    $('.hostwph-wysiwyg').each(function(index, element) {
      $(this).trumbowyg();
    });

    $.fancybox.defaults.touch = false;
  });
})(jQuery);

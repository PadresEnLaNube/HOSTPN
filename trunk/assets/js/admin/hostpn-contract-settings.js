(function ($) {
  'use strict';

  var PREFIX = 'hostpn_ct_';

  /**
   * Parse a field ID into contract type and section key.
   * IDs follow the pattern: hostpn_ct_{type}_{section}
   */
  function parseFieldId(id) {
    if (id.indexOf(PREFIX) !== 0) return null;
    var rest = id.substring(PREFIX.length);
    var types = ['habitacion', 'turistico', 'lau'];
    for (var i = 0; i < types.length; i++) {
      if (rest.indexOf(types[i] + '_') === 0) {
        return {
          type: types[i],
          section: rest.substring(types[i].length + 1)
        };
      }
    }
    return null;
  }

  /**
   * Get the HTML content from a Trumbowyg editor or fallback to textarea value.
   */
  function getEditorContent($textarea) {
    if ($textarea.data('trumbowyg')) {
      return $textarea.trumbowyg('html');
    }
    return $textarea.val();
  }

  /**
   * Set HTML content in a Trumbowyg editor or fallback to textarea value.
   */
  function setEditorContent($textarea, html) {
    if ($textarea.data('trumbowyg')) {
      $textarea.trumbowyg('html', html);
    } else {
      $textarea.val(html);
    }
  }

  /**
   * Initialize Trumbowyg on editors that haven't been initialized yet.
   * Editors in hidden panels can't be initialized on page load.
   */
  function initEditorsInPanel($panel) {
    if (!$.trumbowyg || typeof hostpn_trumbowyg === 'undefined') return;
    $.trumbowyg.svgPath = hostpn_trumbowyg.path;
    $panel.find('.hostpn-wysiwyg').each(function () {
      if (!$(this).data('trumbowyg')) {
        $(this).trumbowyg();
      }
    });
  }

  // Tab switching
  $(document).on('click', '.hostpn-contracts-tab', function () {
    var type = $(this).data('contract-type');
    $('.hostpn-contracts-tab').removeClass('active');
    $(this).addClass('active');
    $('.hostpn-contracts-panel').hide();
    var $panel = $('.hostpn-contracts-panel[data-contract-type="' + type + '"]');
    $panel.show();
    // Initialize WYSIWYG editors on first show
    initEditorsInPanel($panel);
  });

  // Save template via AJAX
  $(document).on('click', '.hostpn-contract-save-template', function () {
    var $btn = $(this);
    var type = $btn.data('contract-type');
    var sections = {};

    // Find all editor textareas for this contract type via their IDs
    $('.hostpn-contracts-panel[data-contract-type="' + type + '"] .hostpn-wysiwyg').each(function () {
      var parsed = parseFieldId($(this).attr('id'));
      if (parsed && parsed.type === type) {
        sections[parsed.section] = getEditorContent($(this));
      }
    });

    $btn.prop('disabled', true).text(hostpn_ajax.translations.loading || 'Loading...');

    $.post(hostpn_ajax.ajax_url, {
      action: 'hostpn_ajax',
      hostpn_ajax_type: 'hostpn_save_contract_template',
      hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
      contract_type: type,
      sections: sections
    }, function (response) {
      var data = typeof response === 'string' ? JSON.parse(response) : response;
      $btn.prop('disabled', false).text(hostpn_contract_settings_i18n.save_template || 'Save template');

      if (data.error_key === '') {
        // Notify other tabs via localStorage
        try {
          localStorage.setItem('hostpn_contract_template_updated', JSON.stringify({
            type: type,
            time: Date.now()
          }));
        } catch (e) { /* ignore */ }

        // Show success feedback
        var $notice = $('<span class="hostpn-contract-save-notice">' + (hostpn_contract_settings_i18n.saved || 'Saved') + '</span>');
        $btn.after($notice);
        setTimeout(function () { $notice.fadeOut(300, function () { $(this).remove(); }); }, 2000);
      }
    }).fail(function () {
      $btn.prop('disabled', false).text(hostpn_contract_settings_i18n.save_template || 'Save template');
    });
  });

  // Restore defaults via AJAX
  $(document).on('click', '.hostpn-contract-restore-defaults', function () {
    var $btn = $(this);
    var type = $btn.data('contract-type');

    if (!confirm(hostpn_contract_settings_i18n.confirm_restore || 'Restore default texts? Unsaved changes will be lost.')) {
      return;
    }

    $btn.prop('disabled', true);

    $.post(hostpn_ajax.ajax_url, {
      action: 'hostpn_ajax',
      hostpn_ajax_type: 'hostpn_restore_contract_defaults',
      hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
      contract_type: type
    }, function (response) {
      var data = typeof response === 'string' ? JSON.parse(response) : response;
      $btn.prop('disabled', false);

      if (data.error_key === '' && data.template) {
        for (var key in data.template) {
          if (data.template.hasOwnProperty(key)) {
            var fieldId = PREFIX + type + '_' + key;
            var $textarea = $('#' + fieldId);
            if ($textarea.length) {
              setEditorContent($textarea, data.template[key]);
            }
          }
        }
      }
    }).fail(function () {
      $btn.prop('disabled', false);
    });
  });

})(jQuery);

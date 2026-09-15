(function ($) {
  'use strict';

  var shortcodeFieldMap = (typeof hostpn_contract_data !== 'undefined' && hostpn_contract_data.shortcode_field_map) ? hostpn_contract_data.shortcode_field_map : {};
  var templateCache = {};
  var updateTimer = null;

  /**
   * Get current contract type from the metabox data attribute.
   */
  function getContractType() {
    return $('.hostpn-contract-columns').data('contract-type') || 'habitacion';
  }

  /**
   * Get accommodation ID from the metabox data attribute.
   */
  function getAccommodationId() {
    return $('.hostpn-contract-columns').data('accommodation-id') || 0;
  }

  /**
   * Compute the full address from accommodation fields.
   */
  function getComputedAddress() {
    var address = $('#hostpn_accommodation_address').val() || '';
    var addressAlt = $('#hostpn_accommodation_address_alt').val() || '';
    var postal = $('#hostpn_accommodation_postal_code').val() || '';
    var city = $('#hostpn_accommodation_city').val() || '';
    var full = address + (addressAlt ? ', ' + addressAlt : '');
    if (postal || city) {
      full += ', ' + (postal + ' ' + city).trim();
    }
    return full;
  }

  /**
   * Format a date string (YYYY-MM-DD) to DD/MM/YYYY.
   */
  function formatDate(dateStr) {
    if (!dateStr) return '';
    var parts = dateStr.split('-');
    if (parts.length === 3) {
      return parts[2] + '/' + parts[1] + '/' + parts[0];
    }
    return dateStr;
  }

  /**
   * Simple HTML escape.
   */
  function esc(str) {
    if (!str) return '';
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
  }

  /**
   * Sanitize a string for use in a filename.
   */
  function sanitizeFilename(str) {
    return str.toLowerCase()
      .replace(/[^a-z0-9]/gi, '_')
      .replace(/_+/g, '_')
      .replace(/^_|_$/g, '');
  }

  /**
   * Get the current value for a shortcode by looking up its mapped field.
   */
  function getShortcodeValue(shortcode) {
    var fieldId = shortcodeFieldMap[shortcode];
    if (!fieldId) return '';

    if (fieldId === '_computed_address') {
      return getComputedAddress();
    }

    var $field = $('#' + fieldId);
    if (!$field.length) return '';

    var val = $field.val() || '';

    // Format date fields
    if ($field.attr('type') === 'date' && val) {
      return formatDate(val);
    }

    return val;
  }

  /**
   * Replace all shortcodes in HTML with current field values (client-side).
   */
  function replaceShortcodesInHtml(html) {
    for (var shortcode in shortcodeFieldMap) {
      if (shortcodeFieldMap.hasOwnProperty(shortcode)) {
        var value = getShortcodeValue(shortcode);
        var escaped = esc(value);
        var pattern = '\\[' + shortcode.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&') + '\\]';
        html = html.replace(new RegExp(pattern, 'g'), escaped || '<span class="hostpn-contract-placeholder">___</span>');
      }
    }
    return html;
  }

  /**
   * Update the live preview with current template + field values.
   */
  function updatePreview() {
    var $preview = $('#hostpn-contract-live-preview');
    if (!$preview.length) return;

    var contractType = getContractType();
    var template = templateCache[contractType];

    if (!template) {
      // Load template via AJAX on first use
      loadTemplate(contractType, function () {
        updatePreview();
      });
      return;
    }

    var html = '';
    for (var key in template) {
      if (template.hasOwnProperty(key)) {
        html += replaceShortcodesInHtml(template[key]);
      }
    }

    $preview.html(html);
  }

  /**
   * Load template from server via AJAX.
   */
  function loadTemplate(contractType, callback) {
    $.post(hostpn_ajax.ajax_url, {
      action: 'hostpn_ajax',
      hostpn_ajax_type: 'hostpn_get_contract_preview',
      hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
      contract_type: contractType,
      hostpn_accommodation_id: getAccommodationId()
    }, function (response) {
      var data = typeof response === 'string' ? JSON.parse(response) : response;
      if (data.error_key === '' && data.template) {
        templateCache[contractType] = data.template;
      }
      if (callback) callback();
    });
  }

  /**
   * Schedule a debounced preview update.
   */
  function schedulePreviewUpdate() {
    if (updateTimer) clearTimeout(updateTimer);
    updateTimer = setTimeout(updatePreview, 300);
  }

  // --- Event: field changes trigger live preview update ---
  $(document).on('input change', '.hostpn-contract-metabox-fields input, .hostpn-contract-metabox-fields select, .hostpn-contract-metabox-fields textarea', function () {
    schedulePreviewUpdate();
  });

  // Also listen to accommodation address fields for computed address
  $(document).on('input change', '#hostpn_accommodation_address, #hostpn_accommodation_address_alt, #hostpn_accommodation_postal_code, #hostpn_accommodation_city', function () {
    schedulePreviewUpdate();
  });

  // --- Event: Refresh button ---
  $(document).on('click', '.hostpn-contract-refresh-btn', function (e) {
    e.preventDefault();
    var contractType = getContractType();
    delete templateCache[contractType];
    updatePreview();
  });

  // --- Event: Generate PDF from preview ---
  $(document).on('click', '.hostpn-contract-generate-btn', function (e) {
    e.preventDefault();

    var $btn = $(this);
    if ($btn.prop('disabled')) return;

    var $preview = $('#hostpn-contract-live-preview');
    if (!$preview.length || !$preview.html().trim()) {
      return;
    }

    // Build inventory from checked features
    var inventory = buildInventory();

    // Create a temporary render container with the preview content + inventory
    var renderHtml = '<div id="hostpn-contract-render">' + $preview.html() + inventory + '</div>';
    var $container = $(renderHtml);
    $('body').append($container);

    $btn.prop('disabled', true);
    var $span = $btn.find('span').last();
    var origText = $span.text();
    $span.text(hostpn_contract_data.i18n.generating || 'Generating PDF...');

    var element = document.getElementById('hostpn-contract-render');
    element.style.opacity = '1';
    element.style.zIndex = '-1';
    element.style.position = 'absolute';
    element.style.left = '0';
    element.style.top = '0';

    var tenantName = $('#hostpn_contract_tenant_name').val() || 'contract';
    var startDate = $('#hostpn_contract_start_date').val() || '';

    var opt = {
      margin: [10, 10, 10, 10],
      filename: 'contrato_' + sanitizeFilename(tenantName) + (startDate ? '_' + startDate : '') + '.pdf',
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: {
        scale: 2,
        useCORS: true,
        letterRendering: true,
        windowWidth: element.scrollWidth,
        windowHeight: element.scrollHeight
      },
      jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
      pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
    };

    html2pdf().set(opt).from(element).save().then(function () {
      $('#hostpn-contract-render').remove();
      $btn.prop('disabled', false);
      $span.text(origText);
    }).catch(function (err) {
      console.error('hostpn-contract PDF error:', err);
      $('#hostpn-contract-render').remove();
      $btn.prop('disabled', false);
      $span.text(origText);
    });
  });

  /**
   * Build the inventory HTML table from checked feature checkboxes.
   */
  function buildInventory() {
    var categories = [
      { selector: '.hostpn-kitchen-feature', title: 'Cocina' },
      { selector: '.hostpn-room-feature', title: 'Habitaci\u00f3n' },
      { selector: '.hostpn-bathroom-feature', title: 'Ba\u00f1o' },
      { selector: '.hostpn-living-area-feature', title: 'Sal\u00f3n' },
      { selector: '.hostpn-audiovisual-feature', title: 'Audiovisual' }
    ];

    var rows = '';
    var hasItems = false;

    categories.forEach(function (cat) {
      var items = [];
      $(cat.selector).each(function () {
        var $wrapper = $(this).closest('.hostpn-input-wrapper');
        var $checkbox = $wrapper.find('input[type="checkbox"]');
        if ($checkbox.length && $checkbox.is(':checked')) {
          var label = $wrapper.find('label').text().trim();
          if (label) items.push(label);
        }
      });

      var additionalSelector = cat.selector.replace('-feature', '-additional-features');
      $(additionalSelector).find('input[type="text"]').each(function () {
        var val = $(this).val();
        if (val && val.trim()) items.push(val.trim());
      });

      if (items.length > 0) {
        hasItems = true;
        rows += '<tr><td><strong>' + esc(cat.title) + '</strong></td><td>' + items.map(esc).join(', ') + '</td></tr>';
      }
    });

    if (!hasItems) {
      return '';
    }

    return '<div class="contract-page-break"></div>' +
      '<h2>ANEXO: INVENTARIO</h2>' +
      '<table class="contract-inventory-table">' +
      '<thead><tr><th>Categor\u00eda</th><th>Elementos</th></tr></thead>' +
      '<tbody>' + rows + '</tbody>' +
      '</table>';
  }

  // --- Poll localStorage for template changes from Settings tab ---
  var lastTemplateUpdate = 0;
  setInterval(function () {
    try {
      var stored = localStorage.getItem('hostpn_contract_template_updated');
      if (stored) {
        var info = JSON.parse(stored);
        if (info.time > lastTemplateUpdate) {
          lastTemplateUpdate = info.time;
          // Clear cache and refresh preview
          delete templateCache[info.type];
          if (info.type === getContractType()) {
            updatePreview();
          }
        }
      }
    } catch (e) { /* ignore */ }
  }, 5000);

  // --- Initialize preview on page load ---
  $(document).ready(function () {
    if ($('#hostpn-contract-live-preview').length) {
      var contractType = getContractType();
      loadTemplate(contractType, function () {
        updatePreview();
      });
    }
  });

})(jQuery);

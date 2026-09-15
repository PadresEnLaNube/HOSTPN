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

    // Append inventory annex to live preview
    html += buildInventory();

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

    // Create a temporary render container with the preview content (includes inventory)
    var renderHtml = '<div id="hostpn-contract-render">' + $preview.html() + '</div>';
    var $container = $(renderHtml);
    $('body').append($container);

    $btn.prop('disabled', true);
    var $span = $btn.find('span').last();
    var origText = $span.text();
    $span.text(hostpn_contract_data.i18n.generating || 'Generating PDF...');

    var element = document.getElementById('hostpn-contract-render');

    // Override all CSS properties that hide the element.
    // html2canvas needs the element visible and on-screen to capture it.
    element.style.cssText = 'position:absolute; left:0; top:0; z-index:99999; opacity:1; pointer-events:none; width:210mm; min-height:297mm; padding:20mm; background:#fff; font-family:"Times New Roman",Times,serif; font-size:12pt; line-height:1.6; color:#000; box-sizing:border-box;';

    var tenantName = $('#hostpn_contract_tenant_name').val() || 'contract';
    var startDate = $('#hostpn_contract_start_date').val() || '';

    // Wait for the browser to complete layout before reading dimensions and capturing.
    requestAnimationFrame(function () {
      setTimeout(function () {
        var elWidth = element.scrollWidth || 794;
        var elHeight = element.scrollHeight || 1123;

        var opt = {
          margin: [10, 10, 10, 10],
          filename: 'contrato_' + sanitizeFilename(tenantName) + (startDate ? '_' + startDate : '') + '.pdf',
          image: { type: 'jpeg', quality: 0.98 },
          html2canvas: {
            scale: 2,
            useCORS: true,
            letterRendering: true,
            scrollX: 0,
            scrollY: 0,
            windowWidth: elWidth,
            windowHeight: elHeight
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
      }, 100);
    });
  });

  /**
   * Build the inventory HTML table from the html_multi enseres fields.
   */
  function buildInventory() {
    var $checkbox = $('#hostpn_contract_inventory_enabled');
    if (!$checkbox.length || !$checkbox.is(':checked')) {
      return '';
    }

    var $wrapper = $('.hostpn-contract-inventory-items');
    if (!$wrapper.length) return '';

    var rows = '';
    var hasItems = false;

    $wrapper.find('.hostpn-html-multi-group').each(function () {
      var nameInputs = $(this).find('input[name="hostpn_contract_inventory_name[]"]');
      var urlInputs = $(this).find('input[name="hostpn_contract_inventory_url[]"]');
      var name = nameInputs.length ? nameInputs.val().trim() : '';
      var url = urlInputs.length ? urlInputs.val().trim() : '';

      if (name) {
        hasItems = true;
        var urlCell = url ? '<a href="' + esc(url) + '" target="_blank">' + esc(url) + '</a>' : '';
        rows += '<tr><td>' + esc(name) + '</td><td>' + urlCell + '</td></tr>';
      }
    });

    if (!hasItems) {
      return '';
    }

    return '<div class="contract-page-break"></div>' +
      '<h2>ANEXO: LISTADO DE ENSERES ARRENDADOS</h2>' +
      '<table class="contract-inventory-table">' +
      '<thead><tr><th>Elemento</th><th>URL</th></tr></thead>' +
      '<tbody>' + rows + '</tbody>' +
      '</table>';
  }

  /**
   * Toggle visibility of inventory html_multi based on checkbox.
   */
  function toggleInventoryFields() {
    var $checkbox = $('#hostpn_contract_inventory_enabled');
    var $wrapper = $('#hostpn_contract_inventory_items').closest('.hostpn-input-wrapper');
    if (!$checkbox.length || !$wrapper.length) return;

    if ($checkbox.is(':checked')) {
      $wrapper.show();
    } else {
      $wrapper.hide();
    }
  }

  // --- Event: inventory checkbox toggle ---
  $(document).on('change', '#hostpn_contract_inventory_enabled', function () {
    toggleInventoryFields();
    schedulePreviewUpdate();
  });

  // --- Event: inventory fields changes ---
  $(document).on('input change', '.hostpn-contract-inventory-items input', function () {
    schedulePreviewUpdate();
  });

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

  // --- Initialize on page load ---
  $(document).ready(function () {
    if ($('#hostpn-contract-live-preview').length) {
      toggleInventoryFields();
      var contractType = getContractType();
      loadTemplate(contractType, function () {
        updatePreview();
      });
    }
  });

  // --- Event: html_multi add/remove triggers preview update ---
  $(document).on('click', '.hostpn-contract-inventory-items .hostpn-html-multi-add-btn, .hostpn-contract-inventory-items .hostpn-html-multi-remove-btn', function () {
    schedulePreviewUpdate();
  });

})(jQuery);

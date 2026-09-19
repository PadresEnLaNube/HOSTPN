(function ($) {
  'use strict';

  var shortcodeFieldMap = (typeof hostpn_contract_data !== 'undefined' && hostpn_contract_data.shortcode_field_map) ? hostpn_contract_data.shortcode_field_map : {};
  var templateCache = {};
  var updateTimer = null;
  var roomData = {};

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
   * Get the selected room's display name from the select dropdown.
   */
  function getComputedRoomName() {
    var $select = $('#hostpn_contract_room_id');
    if (!$select.length || !$select.val()) return '';
    return $select.find('option:selected').text() || '';
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

    if (fieldId === '_computed_room_name') {
      return getComputedRoomName();
    }

    var $field = $('#' + fieldId);
    if (!$field.length) return roomData[shortcode] || '';

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

  // --- Event: Room select change → load room/guest data and build summary ---
  $(document).on('change', '.hostpn-contract-room-select', function () {
    var roomId = $(this).val();
    var $summary = $('#hostpn-contract-room-summary');

    if (!roomId) {
      roomData = {};
      $summary.empty();
      schedulePreviewUpdate();
      return;
    }

    $.post(hostpn_ajax.ajax_url, {
      action: 'hostpn_ajax',
      hostpn_ajax_type: 'hostpn_room_get_guest_data',
      hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
      hostpn_room_id: roomId
    }, function (response) {
      var data = typeof response === 'string' ? JSON.parse(response) : response;
      if (data.error_key !== '') {
        schedulePreviewUpdate();
        return;
      }

      // Store room/guest data for shortcode resolution fallback
      roomData = {};
      if (data.guest) {
        roomData['hostpn-guest-name'] = data.guest.name || '';
        roomData['hostpn-guest-id-card'] = data.guest.nif || '';
        roomData['hostpn-guest-address'] = data.guest.address || '';
        roomData['hostpn-guest-email'] = data.guest.email || '';
      }
      if (data.contract) {
        roomData['hostpn-contract-duration'] = data.contract.duration || '';
        roomData['hostpn-contract-start-date'] = formatDate(data.contract.start_date);
        roomData['hostpn-contract-end-date'] = formatDate(data.contract.end_date);
        roomData['hostpn-contract-notice-days'] = data.contract.notice_days || '';
        roomData['hostpn-contract-rent-amount'] = data.contract.rent_amount || '';
        roomData['hostpn-contract-rent-words'] = data.contract.rent_words || '';
        roomData['hostpn-contract-payment-day'] = data.contract.payment_day || '';
        roomData['hostpn-contract-deposit-amount'] = data.contract.deposit_amount || '';
        roomData['hostpn-contract-deposit-words'] = data.contract.deposit_words || '';
      }

      // Build summary HTML
      var guestEditUrl = data.guest_edit_url || '';
      var roomEditUrl = data.room_edit_url || '';
      var empty = '<span class="hostpn-room-summary-empty">&mdash;</span>';

      function val(v) {
        return v ? esc(v) : empty;
      }

      var html = '';

      // Tenant section
      html += '<div class="hostpn-room-summary-section">';
      html += '<div class="hostpn-room-summary-header">';
      html += '<strong>' + esc(hostpn_contract_data.i18n.summary_tenant || 'Inquilino') + '</strong>';
      if (guestEditUrl) {
        html += '<a href="' + esc(guestEditUrl) + '" target="_blank" class="hostpn-room-summary-edit">';
        html += '<span class="material-icons-outlined" style="font-size:16px;vertical-align:middle;">edit</span> ';
        html += esc(hostpn_contract_data.i18n.summary_edit || 'Editar');
        html += '</a>';
      }
      html += '</div>';
      html += '<div class="hostpn-room-summary-row"><span class="hostpn-room-summary-label">' + esc(hostpn_contract_data.i18n.summary_name || 'Nombre') + ':</span> <span class="hostpn-room-summary-value">' + val(data.guest ? data.guest.name : '') + '</span></div>';
      html += '<div class="hostpn-room-summary-row"><span class="hostpn-room-summary-label">NIF:</span> <span class="hostpn-room-summary-value">' + val(data.guest ? data.guest.nif : '') + '</span></div>';
      html += '<div class="hostpn-room-summary-row"><span class="hostpn-room-summary-label">' + esc(hostpn_contract_data.i18n.summary_address || 'Direcci\u00f3n') + ':</span> <span class="hostpn-room-summary-value">' + val(data.guest ? data.guest.address : '') + '</span></div>';
      html += '<div class="hostpn-room-summary-row"><span class="hostpn-room-summary-label">Email:</span> <span class="hostpn-room-summary-value">' + val(data.guest ? data.guest.email : '') + '</span></div>';
      html += '</div>';

      // Contract details section
      html += '<div class="hostpn-room-summary-section">';
      html += '<div class="hostpn-room-summary-header">';
      html += '<strong>' + esc(hostpn_contract_data.i18n.summary_contract || 'Detalles del contrato') + '</strong>';
      if (roomEditUrl) {
        html += '<a href="' + esc(roomEditUrl) + '" target="_blank" class="hostpn-room-summary-edit">';
        html += '<span class="material-icons-outlined" style="font-size:16px;vertical-align:middle;">edit</span> ';
        html += esc(hostpn_contract_data.i18n.summary_edit || 'Editar');
        html += '</a>';
      }
      html += '</div>';

      var c = data.contract || {};
      html += '<div class="hostpn-room-summary-row"><span class="hostpn-room-summary-label">' + esc(hostpn_contract_data.i18n.summary_duration || 'Duraci\u00f3n') + ':</span> <span class="hostpn-room-summary-value">' + val(c.duration) + '</span></div>';
      html += '<div class="hostpn-room-summary-row"><span class="hostpn-room-summary-label">' + esc(hostpn_contract_data.i18n.summary_start || 'Inicio') + ':</span> <span class="hostpn-room-summary-value">' + val(formatDate(c.start_date)) + '</span></div>';
      html += '<div class="hostpn-room-summary-row"><span class="hostpn-room-summary-label">' + esc(hostpn_contract_data.i18n.summary_end || 'Fin') + ':</span> <span class="hostpn-room-summary-value">' + val(formatDate(c.end_date)) + '</span></div>';
      html += '<div class="hostpn-room-summary-row"><span class="hostpn-room-summary-label">' + esc(hostpn_contract_data.i18n.summary_rent || 'Alquiler') + ':</span> <span class="hostpn-room-summary-value">' + val(c.rent_amount ? c.rent_amount + ' EUR' + (c.rent_words ? ' (' + c.rent_words + ')' : '') : '') + '</span></div>';
      html += '<div class="hostpn-room-summary-row"><span class="hostpn-room-summary-label">' + esc(hostpn_contract_data.i18n.summary_payment_day || 'D\u00eda de pago') + ':</span> <span class="hostpn-room-summary-value">' + val(c.payment_day) + '</span></div>';
      html += '<div class="hostpn-room-summary-row"><span class="hostpn-room-summary-label">' + esc(hostpn_contract_data.i18n.summary_notice || 'Preaviso') + ':</span> <span class="hostpn-room-summary-value">' + val(c.notice_days ? c.notice_days + ' ' + (hostpn_contract_data.i18n.summary_days || 'd\u00edas') : '') + '</span></div>';
      html += '<div class="hostpn-room-summary-row"><span class="hostpn-room-summary-label">' + esc(hostpn_contract_data.i18n.summary_deposit || 'Dep\u00f3sito') + ':</span> <span class="hostpn-room-summary-value">' + val(c.deposit_amount ? c.deposit_amount + ' EUR' + (c.deposit_words ? ' (' + c.deposit_words + ')' : '') : '') + '</span></div>';
      html += '</div>';

      $summary.html(html);
      schedulePreviewUpdate();
    });
  });

  // --- Event: Refresh button ---
  $(document).on('click', '.hostpn-contract-refresh-btn', function (e) {
    e.preventDefault();
    var contractType = getContractType();
    delete templateCache[contractType];
    updatePreview();
  });

  /**
   * Build the inventory HTML table from the html_multi enseres fields.
   */
  function buildInventory() {
    var $checkbox = $('#hostpn_contract_inventory_enabled');
    if (!$checkbox.length || !$checkbox.is(':checked')) {
      return '';
    }

    var i18n = (typeof hostpn_contract_data !== 'undefined' && hostpn_contract_data.i18n)
      ? hostpn_contract_data.i18n : {};

    var categories = [
      { key: 'mobiliario', label: i18n.inv_mobiliario || 'Furniture' },
      { key: 'equipamiento_individual', label: i18n.inv_equipamiento_individual || 'Individual equipment' },
      { key: 'menaje_individual', label: i18n.inv_menaje_individual || 'Individual kitchenware' },
      { key: 'equipamiento_comunitario', label: i18n.inv_equipamiento_comunitario || 'Community equipment' },
      { key: 'otros_enseres', label: i18n.inv_otros_enseres || 'Other items' },
    ];

    var sectionsHtml = '';
    var hasAny = false;

    categories.forEach(function (cat) {
      var $wrapper = $('.hostpn_contract_inv_' + cat.key);
      if (!$wrapper.length) return;

      var rows = '';
      $wrapper.find('.hostpn-html-multi-group').each(function () {
        var name = $(this).find('input[name="hostpn_contract_inv_' + cat.key + '_name[]"]').val();
        var url = $(this).find('input[name="hostpn_contract_inv_' + cat.key + '_url[]"]').val();
        name = name ? name.trim() : '';
        url = url ? url.trim() : '';
        if (name) {
          hasAny = true;
          var urlCell = url ? '<a href="' + esc(url) + '" target="_blank">' + esc(url) + '</a>' : '';
          rows += '<tr><td>' + esc(name) + '</td><td>' + urlCell + '</td></tr>';
        }
      });

      if (rows) {
        sectionsHtml += '<h3>' + esc(cat.label) + '</h3>';
        sectionsHtml += '<table class="contract-inventory-table"><thead><tr>';
        sectionsHtml += '<th>' + (i18n.inventory_item || 'Item') + '</th>';
        sectionsHtml += '<th>' + (i18n.inventory_url || 'URL') + '</th>';
        sectionsHtml += '</tr></thead><tbody>' + rows + '</tbody></table>';
      }
    });

    if (!hasAny) return '';

    return '<div class="contract-page-break"></div>' +
      '<h2>' + (i18n.inventory_heading || 'ANNEX: LEASED ITEMS INVENTORY') + '</h2>' +
      sectionsHtml;
  }

  /**
   * Toggle visibility of inventory html_multi based on checkbox.
   */
  function toggleInventoryFields() {
    var $checkbox = $('#hostpn_contract_inventory_enabled');
    var $wrappers = $('.hostpn-contract-inventory-items');
    if (!$checkbox.length) return;

    var $containers = $wrappers.closest('.hostpn-input-wrapper, .hostpn-field-wrapper, [class*="hostpn_contract_inv_"]');
    if (!$containers.length) {
      $containers = $wrappers;
    }

    if ($checkbox.is(':checked')) {
      $containers.show();
    } else {
      $containers.hide();
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
    toggleInventoryFields();

    if ($('#hostpn-contract-live-preview').length) {
      // If a room is already selected, trigger load of its data for the summary
      var $roomSelect = $('.hostpn-contract-room-select');
      if ($roomSelect.length && $roomSelect.val()) {
        $roomSelect.trigger('change');
      }

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

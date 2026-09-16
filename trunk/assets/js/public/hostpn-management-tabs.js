(function ($) {
  'use strict';

  var cfg = window.hostpnMgmtTabs || {};
  var ajaxUrl = cfg.ajaxUrl || '';
  var nonce = cfg.nonce || '';
  var accommodationId = cfg.accommodationId || 0;
  var i18n = cfg.i18n || {};

  console.log('[hostpn-mgmt] External JS loaded. ajaxUrl:', ajaxUrl ? 'SET' : 'MISSING', 'nonce:', nonce ? 'SET' : 'MISSING', 'accommodationId:', accommodationId);

  /* ── Tab switching ── */
  $(document).on('click', '.hostpn-mgmt-tab-btn', function (e) {
    console.log('[hostpn-mgmt] Tab clicked:', $(this).data('tab'));
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();

    var tab = $(this).data('tab');
    var panel = $(this).closest('.hostpn-mgmt-panel');
    if (!panel.length) return;

    panel.find('.hostpn-mgmt-tab-btn').removeClass('active');
    $(this).addClass('active');

    panel.find('.hostpn-mgmt-tab-pane').hide();
    panel.find('.hostpn-mgmt-tab-pane[data-tab="' + tab + '"]').show();

    if (tab === 'financial') {
      loadFinancialTab(panel);
    }
  });

  /* ── Auto-load financial tab if it is the default visible tab ── */
  $(function () {
    var $panel = $('.hostpn-mgmt-panel');
    console.log('[hostpn-mgmt] DOM ready. Panel found:', $panel.length, 'Buttons:', $panel.find('.hostpn-mgmt-tab-btn').length, 'Panes:', $panel.find('.hostpn-mgmt-tab-pane').length);
    if ($panel.length) {
      var activeTab = $panel.find('.hostpn-mgmt-tab-btn.active').data('tab');
      console.log('[hostpn-mgmt] Active tab on load:', activeTab);
      if (activeTab === 'financial') {
        loadFinancialTab($panel);
      }
    }
  });

  /* ── Financial tab ── */
  var financialLoaded = false;

  function loadFinancialTab(panel) {
    if (financialLoaded) return;
    financialLoaded = true;

    var wrapper = panel.find('.hostpn-mgmt-financial-wrapper');
    var loading = wrapper.find('.hostpn-mgmt-loading');
    var content = wrapper.find('.hostpn-mgmt-financial-content');

    $.ajax({
      url: ajaxUrl,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_financial_frontend_load',
        hostpn_ajax_nonce: nonce,
        hostpn_accommodation_id: accommodationId
      },
      success: function (data) {
        console.log('[hostpn-mgmt] Financial AJAX success:', data);
        loading.hide();
        if (data && data.error_key === '' && data.html) {
          content.html(data.html);
        } else {
          content.html('<p class="hostpn-mgmt-financial-unavailable">' + (i18n.noFinancialData || 'No financial data available.') + '</p>');
        }
      },
      error: function (xhr, status, err) {
        console.error('[hostpn-mgmt] Financial AJAX error:', status, err, xhr.responseText && xhr.responseText.substring(0, 300));
        loading.hide();
        content.html('<p class="hostpn-mgmt-financial-unavailable">' + (i18n.noFinancialData || 'No financial data available.') + '</p>');
      }
    });
  }

  /* ── Cleaning tab ── */
  var cleaningAreas = [
    { id: 'bedroom', name: i18n.areaBedroom || 'Bedroom' },
    { id: 'bathroom', name: i18n.areaBathroom || 'Bathroom' },
    { id: 'kitchen', name: i18n.areaKitchen || 'Kitchen' },
    { id: 'common', name: i18n.areaCommon || 'Common areas' },
    { id: 'other', name: i18n.areaOther || 'Other' }
  ];

  $(document).on('change', '#hostpn-mgmt-cleaning-room', function () {
    var roomId = $(this).val();
    var content = $(this).closest('.hostpn-mgmt-cleaning-wrapper').find('.hostpn-mgmt-cleaning-content');

    if (!roomId) {
      content.empty();
      return;
    }

    content.html('<div class="hostpn-mgmt-loading"><i class="material-icons-outlined hostpn-spin">sync</i> ' + (i18n.loading || 'Loading...') + '</div>');

    $.ajax({
      url: ajaxUrl,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_cleaning_load',
        hostpn_ajax_nonce: nonce,
        hostpn_room_id: roomId
      },
      success: function (data) {
        if (data && data.error_key === '') {
          var tasks = data.tasks || {};
          renderCleaningAreas(content, roomId, tasks);
        } else {
          content.html('<p class="hostpn-mgmt-empty">' + (i18n.errorLoading || 'Error loading data.') + '</p>');
        }
      },
      error: function () {
        content.html('<p class="hostpn-mgmt-empty">' + (i18n.errorLoading || 'Error loading data.') + '</p>');
      }
    });
  });

  function renderCleaningAreas(container, roomId, tasks) {
    var html = '<div class="hostpn-mgmt-cleaning-areas">';

    for (var i = 0; i < cleaningAreas.length; i++) {
      var area = cleaningAreas[i];
      var task = tasks[area.id] || {};
      var done = task.done === '1' || task.done === true;
      var date = task.date || '';
      var notes = task.notes || '';

      html += '<div class="hostpn-mgmt-cleaning-area" data-area="' + area.id + '">';
      html += '<input type="checkbox" class="hostpn-mgmt-cleaning-check" ' + (done ? 'checked' : '') + '>';
      html += '<span class="hostpn-mgmt-cleaning-name' + (done ? ' done' : '') + '">' + escHtml(area.name) + '</span>';
      html += '<input type="date" class="hostpn-mgmt-cleaning-date" value="' + escAttr(date) + '">';
      html += '<textarea class="hostpn-mgmt-cleaning-notes" placeholder="' + escAttr(i18n.notes || 'Notes') + '">' + escHtml(notes) + '</textarea>';
      html += '</div>';
    }

    html += '</div>';
    html += '<div class="hostpn-mgmt-cleaning-actions">';
    html += '<button type="button" class="hostpn-mgmt-btn hostpn-mgmt-cleaning-save" data-room-id="' + roomId + '">';
    html += '<i class="material-icons-outlined">save</i> ' + (i18n.save || 'Save');
    html += '</button>';
    html += '<button type="button" class="hostpn-mgmt-btn hostpn-mgmt-btn-secondary hostpn-mgmt-cleaning-mark-all" data-room-id="' + roomId + '">';
    html += '<i class="material-icons-outlined">done_all</i> ' + (i18n.markAllDone || 'Mark all completed');
    html += '</button>';
    html += '</div>';

    container.html(html);
  }

  $(document).on('change', '.hostpn-mgmt-cleaning-check', function () {
    var nameEl = $(this).closest('.hostpn-mgmt-cleaning-area').find('.hostpn-mgmt-cleaning-name');
    if ($(this).is(':checked')) {
      nameEl.addClass('done');
      var dateInput = $(this).closest('.hostpn-mgmt-cleaning-area').find('.hostpn-mgmt-cleaning-date');
      if (!dateInput.val()) {
        dateInput.val(todayISO());
      }
    } else {
      nameEl.removeClass('done');
    }
  });

  $(document).on('click', '.hostpn-mgmt-cleaning-mark-all', function () {
    var wrapper = $(this).closest('.hostpn-mgmt-cleaning-wrapper');
    wrapper.find('.hostpn-mgmt-cleaning-check').each(function () {
      if (!$(this).is(':checked')) {
        $(this).prop('checked', true).trigger('change');
      }
    });
  });

  $(document).on('click', '.hostpn-mgmt-cleaning-save', function () {
    var btn = $(this);
    var roomId = btn.data('room-id');
    var wrapper = btn.closest('.hostpn-mgmt-cleaning-wrapper');
    var tasks = {};

    wrapper.find('.hostpn-mgmt-cleaning-area').each(function () {
      var area = $(this).data('area');
      tasks[area] = {
        done: $(this).find('.hostpn-mgmt-cleaning-check').is(':checked') ? '1' : '0',
        date: $(this).find('.hostpn-mgmt-cleaning-date').val(),
        notes: $(this).find('.hostpn-mgmt-cleaning-notes').val()
      };
    });

    btn.prop('disabled', true);

    $.ajax({
      url: ajaxUrl,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_cleaning_save',
        hostpn_ajax_nonce: nonce,
        hostpn_room_id: roomId,
        cleaning_tasks: JSON.stringify(tasks)
      },
      success: function (data) {
        btn.prop('disabled', false);
        if (data && data.error_key === '') {
          showMessage(wrapper, 'success', i18n.saved || 'Saved successfully.');
        } else {
          showMessage(wrapper, 'error', (data && data.error_content) || i18n.errorSaving || 'Error saving.');
        }
      },
      error: function () {
        btn.prop('disabled', false);
        showMessage(wrapper, 'error', i18n.errorSaving || 'Error saving.');
      }
    });
  });

  /* ── Inventory tab (Admin) ── */

  $(document).on('change', '#hostpn-mgmt-inventory-room', function () {
    var btn = $(this).closest('.hostpn-mgmt-room-selector').find('.hostpn-mgmt-generate-checklist');
    btn.prop('disabled', !$(this).val());
    $(this).closest('.hostpn-mgmt-inventory-admin').find('.hostpn-mgmt-inventory-checklist').empty();
  });

  $(document).on('click', '.hostpn-mgmt-generate-checklist', function () {
    var btn = $(this);
    var roomId = $('#hostpn-mgmt-inventory-room').val();
    var container = btn.closest('.hostpn-mgmt-inventory-admin').find('.hostpn-mgmt-inventory-checklist');

    if (!roomId) return;

    btn.prop('disabled', true);
    container.html('<div class="hostpn-mgmt-loading"><i class="material-icons-outlined hostpn-spin">sync</i> ' + (i18n.generating || 'Generating checklist...') + '</div>');

    $.ajax({
      url: ajaxUrl,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_inventory_checklist_load',
        hostpn_ajax_nonce: nonce,
        hostpn_accommodation_id: accommodationId,
        hostpn_room_id: roomId
      },
      success: function (data) {
        btn.prop('disabled', false);
        if (data && data.error_key === '' && data.items) {
          renderChecklist(container, roomId, data.items, data.contract_id || 0, data.existing || null);
        } else {
          container.html('<p class="hostpn-mgmt-empty">' + ((data && data.error_content) || i18n.noItems || 'No inventory items found.') + '</p>');
        }
      },
      error: function () {
        btn.prop('disabled', false);
        container.html('<p class="hostpn-mgmt-empty">' + (i18n.errorLoading || 'Error loading data.') + '</p>');
      }
    });
  });

  function renderChecklist(container, roomId, items, contractId, existing) {
    var existingItems = {};
    var overallNotes = '';

    if (existing && existing.items) {
      for (var i = 0; i < existing.items.length; i++) {
        var key = existing.items[i].category + '_' + existing.items[i].name;
        existingItems[key] = existing.items[i];
      }
      overallNotes = existing.overall_notes || '';
    }

    var html = '';
    var currentCat = '';

    for (var j = 0; j < items.length; j++) {
      var item = items[j];
      var itemKey = item.category + '_' + item.name;
      var prev = existingItems[itemKey] || {};
      var status = prev.status || 'ok';
      var comment = prev.comment || '';

      if (item.category_label !== currentCat) {
        if (currentCat !== '') html += '</div>';
        currentCat = item.category_label;
        html += '<div class="hostpn-mgmt-inv-checklist-category">';
        html += '<h4>' + escHtml(currentCat) + '</h4>';
      }

      html += '<div class="hostpn-mgmt-inv-checklist-item" data-category="' + escAttr(item.category) + '" data-name="' + escAttr(item.name) + '">';
      html += '<span class="hostpn-mgmt-inv-item-name">' + escHtml(item.name) + '</span>';
      html += '<div class="hostpn-mgmt-inv-status-group">';
      html += '<label><input type="radio" name="inv_' + j + '" value="ok"' + (status === 'ok' ? ' checked' : '') + '> OK</label>';
      html += '<label><input type="radio" name="inv_' + j + '" value="issue"' + (status === 'issue' ? ' checked' : '') + '> ' + (i18n.issue || 'Issue') + '</label>';
      html += '</div>';
      html += '<textarea class="hostpn-mgmt-inv-item-comment" placeholder="' + escAttr(i18n.comment || 'Comment') + '">' + escHtml(comment) + '</textarea>';
      html += '</div>';
    }

    if (currentCat !== '') html += '</div>';

    html += '<div class="hostpn-mgmt-inv-overall">';
    html += '<label>' + (i18n.overallNotes || 'General notes') + '</label>';
    html += '<textarea id="hostpn-mgmt-inv-overall-notes">' + escHtml(overallNotes) + '</textarea>';
    html += '</div>';

    html += '<div class="hostpn-mgmt-inv-actions">';
    html += '<button type="button" class="hostpn-mgmt-btn hostpn-mgmt-inv-save" data-room-id="' + roomId + '" data-contract-id="' + contractId + '">';
    html += '<i class="material-icons-outlined">save</i> ' + (i18n.saveInspection || 'Save inspection');
    html += '</button>';
    html += '<button type="button" class="hostpn-mgmt-btn hostpn-mgmt-btn-secondary hostpn-mgmt-inv-email" data-room-id="' + roomId + '" data-contract-id="' + contractId + '">';
    html += '<i class="material-icons-outlined">email</i> ' + (i18n.sendEmail || 'Send by email');
    html += '</button>';
    html += '</div>';

    container.html(html);
  }

  $(document).on('click', '.hostpn-mgmt-inv-save', function () {
    var btn = $(this);
    var roomId = btn.data('room-id');
    var contractId = btn.data('contract-id');
    var inspectionData = collectInspectionData(btn);

    btn.prop('disabled', true);

    $.ajax({
      url: ajaxUrl,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_inventory_inspection_save',
        hostpn_ajax_nonce: nonce,
        hostpn_room_id: roomId,
        hostpn_contract_id: contractId,
        hostpn_accommodation_id: accommodationId,
        inspection_data: JSON.stringify(inspectionData)
      },
      success: function (data) {
        btn.prop('disabled', false);
        if (data && data.error_key === '') {
          showMessage(btn.closest('.hostpn-mgmt-inventory-admin'), 'success', i18n.inspectionSaved || 'Inspection saved.');
        } else {
          showMessage(btn.closest('.hostpn-mgmt-inventory-admin'), 'error', (data && data.error_content) || i18n.errorSaving || 'Error saving.');
        }
      },
      error: function () {
        btn.prop('disabled', false);
        showMessage(btn.closest('.hostpn-mgmt-inventory-admin'), 'error', i18n.errorSaving || 'Error saving.');
      }
    });
  });

  $(document).on('click', '.hostpn-mgmt-inv-email', function () {
    var btn = $(this);
    var roomId = btn.data('room-id');
    var contractId = btn.data('contract-id');
    var inspectionData = collectInspectionData(btn);

    btn.prop('disabled', true);

    $.ajax({
      url: ajaxUrl,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_inventory_inspection_email',
        hostpn_ajax_nonce: nonce,
        hostpn_room_id: roomId,
        hostpn_contract_id: contractId,
        hostpn_accommodation_id: accommodationId,
        inspection_data: JSON.stringify(inspectionData)
      },
      success: function (data) {
        btn.prop('disabled', false);
        if (data && data.error_key === '') {
          showMessage(btn.closest('.hostpn-mgmt-inventory-admin'), 'success', i18n.emailSent || 'Email sent successfully.');
        } else {
          showMessage(btn.closest('.hostpn-mgmt-inventory-admin'), 'error', (data && data.error_content) || i18n.errorSending || 'Error sending email.');
        }
      },
      error: function () {
        btn.prop('disabled', false);
        showMessage(btn.closest('.hostpn-mgmt-inventory-admin'), 'error', i18n.errorSending || 'Error sending email.');
      }
    });
  });

  function collectInspectionData(contextEl) {
    var wrapper = contextEl.closest('.hostpn-mgmt-inventory-admin');
    var items = [];

    wrapper.find('.hostpn-mgmt-inv-checklist-item').each(function () {
      items.push({
        category: $(this).data('category'),
        name: $(this).data('name'),
        status: $(this).find('input[type="radio"]:checked').val() || 'ok',
        comment: $(this).find('.hostpn-mgmt-inv-item-comment').val() || ''
      });
    });

    return {
      items: items,
      overall_notes: wrapper.find('#hostpn-mgmt-inv-overall-notes').val() || ''
    };
  }

  /* ── Helpers ── */
  function showMessage(container, type, text) {
    container.find('.hostpn-mgmt-message').remove();
    var cls = type === 'success' ? 'hostpn-mgmt-message-success' : 'hostpn-mgmt-message-error';
    var icon = type === 'success' ? 'check_circle' : 'error';
    var msg = $('<div class="hostpn-mgmt-message ' + cls + '"><i class="material-icons-outlined">' + icon + '</i> ' + escHtml(text) + '</div>');
    container.append(msg);
    setTimeout(function () { msg.fadeOut(300, function () { msg.remove(); }); }, 4000);
  }

  function todayISO() {
    var d = new Date();
    return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
  }

  function pad(n) {
    return n < 10 ? '0' + n : '' + n;
  }

  function escHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  function escAttr(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
  }

})(jQuery);

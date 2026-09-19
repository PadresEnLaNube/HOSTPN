(function ($) {
  'use strict';

  function getNonce() {
    if (window.hostpn_ajax && window.hostpn_ajax.hostpn_ajax_nonce) {
      return window.hostpn_ajax.hostpn_ajax_nonce;
    }
    if (window.hostpnMgmtTabs && window.hostpnMgmtTabs.nonce) {
      return window.hostpnMgmtTabs.nonce;
    }
    if (window.hostpn && window.hostpn.nonce) {
      return window.hostpn.nonce;
    }
    if (window.hostpn_action && window.hostpn_action.hostpn_get_nonce) {
      return window.hostpn_action.hostpn_get_nonce;
    }
    return '';
  }

  function getAjaxUrl() {
    if (window.hostpn_ajax && window.hostpn_ajax.ajax_url) {
      return window.hostpn_ajax.ajax_url;
    }
    if (window.hostpnMgmtTabs && window.hostpnMgmtTabs.ajaxUrl) {
      return window.hostpnMgmtTabs.ajaxUrl;
    }
    return '/wp-admin/admin-ajax.php';
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

  function formatMoney(num) {
    var val = parseFloat(num) || 0;
    return val.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function initFinancialChart(chartMonths) {
    if (!window.Chart || !chartMonths || !chartMonths.length) return;
    var canvas = document.getElementById('hostpn-financial-chart-canvas');
    if (!canvas) return;

    var labels = [];
    var expectedData = [];
    var collectedData = [];
    for (var i = 0; i < chartMonths.length; i++) {
      labels.push(chartMonths[i].label);
      expectedData.push(chartMonths[i].expected || 0);
      collectedData.push(chartMonths[i].collected || 0);
    }

    if (window.hostpnFinChartInstance) {
      window.hostpnFinChartInstance.destroy();
    }

    window.hostpnFinChartInstance = new window.Chart(canvas, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          { label: 'Cobrado Real', data: collectedData, backgroundColor: '#38bdf8', borderRadius: 4 },
          { label: 'Previsto', data: expectedData, backgroundColor: '#334155', borderRadius: 4 }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { labels: { color: '#cbd5e1', font: { size: 11 } } },
          tooltip: {
            callbacks: {
              label: function(ctx) { return ctx.dataset.label + ': €' + ctx.raw.toLocaleString('es-ES', { minimumFractionDigits: 2 }); }
            }
          }
        },
        scales: {
          x: { ticks: { color: '#94a3b8', font: { size: 10 } }, grid: { color: '#1e293b' } },
          y: { ticks: { color: '#94a3b8', font: { size: 10 } }, grid: { color: '#1e293b' } }
        }
      }
    });
  }

  window.hostpnInitFinancialChart = initFinancialChart;

  function refreshFinancialUI(res) {
    var html = (res && res.html) || (res && res.summary && res.summary.html) || '';
    var summary = (res && res.summary) ? res.summary : res;

    var panels = $('.hostpn-admin-financial-panel, .hostpn-mgmt-panel, #hostpn-financial-dashboard');
    if (panels.length && html) {
      panels.each(function() {
        var $p = $(this);
        var inner = $p.find('.hostpn-mgmt-financial-content');
        var newInnerHtml = $(html).find('.hostpn-mgmt-financial-content').html();
        if (inner.length && newInnerHtml) {
          inner.html(newInnerHtml);
        } else {
          $p.replaceWith(html);
        }
      });

      if (summary && summary.chart_months) {
        initFinancialChart(summary.chart_months);
      }
      return;
    }

    if (panels.length && summary && window.hostpnRenderFinancialTab) {
      window.hostpnRenderFinancialTab(panels.find('.hostpn-mgmt-financial-content'), summary);
    }
  }

  function closeFinancialPopups() {
    if (window.HOSTPN_Popups) {
      window.HOSTPN_Popups.close();
    }
    $('#hostpn-payment-modal').remove();
    $('#hostpn-expense-modal').remove();
    $('#hostpn-csv-modal').remove();
    $('.hostpn-popup-overlay').fadeOut('fast', function() {
      $(this).remove();
    });
  }

  /* ── Room Payment Modal ── */
  function openPaymentModal(roomId, defaultAmount, defaultType, editRecord) {
    defaultType = defaultType || 'rent';
    defaultAmount = defaultAmount || 0;
    editRecord = editRecord || null;

    var payId = editRecord ? (editRecord.id || '') : '';
    var payAmount = editRecord ? editRecord.amount : defaultAmount;
    var payType = editRecord ? (editRecord.payment_type || defaultType) : defaultType;
    var payDate = editRecord ? (editRecord.payment_date || editRecord.date || todayISO()) : todayISO();
    var payNotes = editRecord ? (editRecord.notes || '') : '';

    var title = editRecord ? 'Editar Registro de Pago' : ('Registrar Pago (' + (defaultType === 'deposit' ? 'Fianza' : 'Renta Mensual') + ')');

    var modalHtml = '<div class="hostpn-popup-overlay hostpn-display-none-soft"></div>';
    modalHtml += '<div id="hostpn-payment-modal" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); z-index:999999; background:#ffffff; color:#0f172a; border:1px solid #e2e8f0; border-radius:10px; width:90%; max-width:440px; padding:20px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.05);">';
    modalHtml += '<div class="hostpn-popup-content">';
    modalHtml += '<div class="hostpn-popup-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">';
    modalHtml += '<h3 class="hostpn-popup-title" style="margin:0; font-size:16px; font-weight:700; color:#0f172a;">' + escHtml(title) + '</h3>';
    modalHtml += '<button type="button" class="hostpn-popup-close hostpn-popup-close-wrapper" style="padding:2px 6px; cursor:pointer;">&times;</button>';
    modalHtml += '</div>';

    modalHtml += '<div class="hostpn-popup-body">';
    modalHtml += '<input type="hidden" id="hostpn-pay-id" value="' + escHtml(payId) + '">';

    modalHtml += '<div class="hostpn-input-wrapper" style="margin-bottom:12px;">';
    modalHtml += '<label class="hostpn-label" style="display:block; font-size:12px; color:#475569; font-weight:600; margin-bottom:4px;">Tipo de Pago</label>';
    modalHtml += '<select id="hostpn-pay-type" class="hostpn-field hostpn-select" style="width:100%; padding:8px 12px; background:#ffffff; color:#0f172a; border:1px solid #cbd5e1; border-radius:6px; font-size:13px;">';
    modalHtml += '<option value="rent"' + (payType === 'rent' ? ' selected' : '') + '>Renta Mensual</option>';
    modalHtml += '<option value="deposit"' + (payType === 'deposit' ? ' selected' : '') + '>Fianza</option>';
    modalHtml += '<option value="custom"' + (payType === 'custom' ? ' selected' : '') + '>Otro Ingreso</option>';
    modalHtml += '</select></div>';

    modalHtml += '<div class="hostpn-input-wrapper" style="margin-bottom:12px;">';
    modalHtml += '<label class="hostpn-label" style="display:block; font-size:12px; color:#475569; font-weight:600; margin-bottom:4px;">Importe (€)</label>';
    modalHtml += '<input type="number" step="0.01" id="hostpn-pay-amount" class="hostpn-field hostpn-input" value="' + payAmount + '" style="width:100%; padding:8px 12px; background:#ffffff; color:#0f172a; border:1px solid #cbd5e1; border-radius:6px; font-size:14px; font-weight:600;">';
    modalHtml += '</div>';

    modalHtml += '<div class="hostpn-input-wrapper" style="margin-bottom:12px;">';
    modalHtml += '<label class="hostpn-label" style="display:block; font-size:12px; color:#475569; font-weight:600; margin-bottom:4px;">Fecha del Pago</label>';
    modalHtml += '<input type="date" id="hostpn-pay-date" class="hostpn-field hostpn-input" value="' + escHtml(payDate) + '" style="width:100%; padding:8px 12px; background:#ffffff; color:#0f172a; border:1px solid #cbd5e1; border-radius:6px; font-size:13px;">';
    modalHtml += '</div>';

    modalHtml += '<div class="hostpn-input-wrapper" style="margin-bottom:16px;">';
    modalHtml += '<label class="hostpn-label" style="display:block; font-size:12px; color:#475569; font-weight:600; margin-bottom:4px;">Observaciones / Referencia</label>';
    modalHtml += '<input type="text" id="hostpn-pay-notes" class="hostpn-field hostpn-input" value="' + escHtml(payNotes) + '" placeholder="Ej. Transferencia bancaria, Bizum, Efectivo" style="width:100%; padding:8px 12px; background:#ffffff; color:#0f172a; border:1px solid #cbd5e1; border-radius:6px; font-size:13px;">';
    modalHtml += '</div>';
    modalHtml += '</div>';

    modalHtml += '<div class="hostpn-popup-footer" style="display:flex; justify-content:flex-end; gap:8px;">';
    modalHtml += '<button type="button" class="hostpn-btn hostpn-btn-mini hostpn-btn-transparent hostpn-popup-close">Cancelar</button>';
    modalHtml += '<button type="button" id="hostpn-submit-payment" class="hostpn-btn hostpn-btn-mini" data-room-id="' + roomId + '">' + (editRecord ? 'Actualizar Pago' : 'Guardar Pago') + '</button>';
    modalHtml += '</div></div></div>';

    $('#hostpn-payment-modal').remove();
    $('.hostpn-popup-overlay').remove();
    $('body').append(modalHtml);

    if (window.HOSTPN_Popups) {
      window.HOSTPN_Popups.open('hostpn-payment-modal');
    } else {
      $('#hostpn-payment-modal').fadeIn('fast');
      $('.hostpn-popup-overlay').removeClass('hostpn-display-none-soft').fadeIn('fast');
    }
  }

  // Open modal handlers
  $(document).on('click', '.hostpn-add-rent-btn', function (e) {
    e.preventDefault();
    e.stopPropagation();
    var roomId = $(this).attr('data-room-id') || $(this).data('room-id');
    var amount = $(this).attr('data-rent') || $(this).data('rent') || 0;
    openPaymentModal(roomId, amount, 'rent');
  });

  $(document).on('click', '.hostpn-add-deposit-btn', function (e) {
    e.preventDefault();
    e.stopPropagation();
    var roomId = $(this).attr('data-room-id') || $(this).data('room-id');
    var amount = $(this).attr('data-deposit') || $(this).data('deposit') || 0;
    openPaymentModal(roomId, amount, 'deposit');
  });

  $(document).on('click', '.hostpn-add-payment-btn', function (e) {
    e.preventDefault();
    e.stopPropagation();
    var roomId = $(this).attr('data-room-id') || $(this).data('room-id');
    var amount = $(this).attr('data-rent') || $(this).attr('data-deposit') || 0;
    openPaymentModal(roomId, amount, 'rent');
  });

  $(document).on('click', '.hostpn-edit-payment-btn', function (e) {
    e.preventDefault();
    e.stopPropagation();
    var roomId = $(this).attr('data-room-id') || $(this).data('room-id');
    var raw = $(this).attr('data-payment');
    var pay = raw ? JSON.parse(raw) : null;
    if (pay) {
      openPaymentModal(roomId, pay.amount, pay.payment_type, pay);
    }
  });

  $(document).on('click', '.hostpn-modal-close, .hostpn-popup-close', function () {
    closeFinancialPopups();
  });

  // Toggle payments subrow
  $(document).on('click', '.hostpn-toggle-payments-btn', function (e) {
    e.preventDefault();
    e.stopPropagation();
    var roomId = $(this).attr('data-room-id') || $(this).data('room-id');
    var subrow = $('#hostpn-payments-subrow-' + roomId);
    if (subrow.length) {
      if (subrow.is(':visible')) {
        subrow.hide();
      } else {
        subrow.show();
      }
    }
  });

  // Submit payment handler (Add or Edit)
  $(document).on('click', '#hostpn-submit-payment', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var btn = $(this);
    if (btn.data('submitting')) return;

    var roomId = btn.data('room-id');
    var payId = $('#hostpn-pay-id').val();
    var type = $('#hostpn-pay-type').val();
    var amount = $('#hostpn-pay-amount').val();
    var date = $('#hostpn-pay-date').val();
    var notes = $('#hostpn-pay-notes').val();

    var ajaxType = payId ? 'hostpn_edit_room_payment' : 'hostpn_add_room_payment';

    btn.data('submitting', true).prop('disabled', true).text('Guardando...');

    $.ajax({
      url: getAjaxUrl(),
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'hostpn_ajax',
        hostpn_ajax_type: ajaxType,
        hostpn_ajax_nonce: getNonce(),
        room_id: roomId,
        payment_id: payId,
        amount: amount,
        payment_type: type,
        payment_date: date,
        notes: notes
      },
      success: function (res) {
        btn.data('submitting', false);
        closeFinancialPopups();
        if (res && res.error_key === '') {
          refreshFinancialUI(res);
        } else {
          alert((res && res.error_content) || 'Error al guardar el pago.');
        }
      },
      error: function () {
        btn.data('submitting', false);
        btn.prop('disabled', false).text('Guardar Pago');
        alert('Error procesando pago.');
      }
    });
  });

  // Delete payment handler
  $(document).on('click', '.hostpn-delete-payment-btn', function (e) {
    e.preventDefault();
    if (!confirm('¿Estás seguro de que deseas eliminar este registro de pago?')) return;

    var btn = $(this);
    var roomId = btn.data('room-id');
    var paymentId = btn.data('payment-id');

    btn.prop('disabled', true).text('...');

    $.ajax({
      url: getAjaxUrl(),
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_delete_room_payment',
        hostpn_ajax_nonce: getNonce(),
        room_id: roomId,
        payment_id: paymentId
      },
      success: function (res) {
        if (res && res.error_key === '') {
          refreshFinancialUI(res);
        } else {
          alert((res && res.error_content) || 'Error al eliminar el pago.');
        }
      },
      error: function () {
        alert('Error conectando con el servidor.');
      }
    });
  });

  /* ── Expenses Modal & Handlers ── */
  function openExpenseModal(accomId, editExpense) {
    editExpense = editExpense || null;

    var expId = editExpense ? (editExpense.id || '') : '';
    var expAmount = editExpense ? editExpense.amount : 0;
    var expDate = editExpense ? (editExpense.date || todayISO()) : todayISO();
    var expProvider = editExpense ? (editExpense.provider || '') : '';
    var expCategory = editExpense ? (editExpense.category || '') : '';
    var expNotes = editExpense ? (editExpense.notes || '') : '';

    var title = editExpense ? 'Editar Gasto' : 'Añadir Nuevo Gasto';

    var modalHtml = '<div class="hostpn-popup-overlay hostpn-display-none-soft"></div>';
    modalHtml += '<div id="hostpn-expense-modal" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); z-index:999999; background:#ffffff; color:#0f172a; border:1px solid #e2e8f0; border-radius:10px; width:90%; max-width:480px; padding:20px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.05);">';
    modalHtml += '<div class="hostpn-popup-content">';
    modalHtml += '<div class="hostpn-popup-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">';
    modalHtml += '<h3 class="hostpn-popup-title" style="margin:0; font-size:16px; font-weight:700; color:#0f172a;">' + escHtml(title) + '</h3>';
    modalHtml += '<button type="button" class="hostpn-popup-close hostpn-popup-close-wrapper" style="padding:2px 6px; cursor:pointer;">&times;</button>';
    modalHtml += '</div>';

    modalHtml += '<div class="hostpn-popup-body">';
    modalHtml += '<input type="hidden" id="hostpn-exp-id" value="' + escHtml(expId) + '">';

    modalHtml += '<div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">';
    modalHtml += '<div class="hostpn-input-wrapper">';
    modalHtml += '<label class="hostpn-label" style="display:block; font-size:12px; color:#475569; font-weight:600; margin-bottom:4px;">Importe (€)</label>';
    modalHtml += '<input type="number" step="0.01" id="hostpn-exp-amount" class="hostpn-field hostpn-input" value="' + expAmount + '" style="width:100%; padding:8px 12px; background:#ffffff; color:#0f172a; border:1px solid #cbd5e1; border-radius:6px; font-size:14px; font-weight:600;">';
    modalHtml += '</div>';
    modalHtml += '<div class="hostpn-input-wrapper">';
    modalHtml += '<label class="hostpn-label" style="display:block; font-size:12px; color:#475569; font-weight:600; margin-bottom:4px;">Fecha</label>';
    modalHtml += '<input type="date" id="hostpn-exp-date" class="hostpn-field hostpn-input" value="' + escHtml(expDate) + '" style="width:100%; padding:8px 12px; background:#ffffff; color:#0f172a; border:1px solid #cbd5e1; border-radius:6px; font-size:13px;">';
    modalHtml += '</div></div>';

    modalHtml += '<div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">';
    modalHtml += '<div class="hostpn-input-wrapper">';
    modalHtml += '<label class="hostpn-label" style="display:block; font-size:12px; color:#475569; font-weight:600; margin-bottom:4px;">Proveedor / Empresa</label>';
    modalHtml += '<input type="text" id="hostpn-exp-provider" class="hostpn-field hostpn-input" value="' + escHtml(expProvider) + '" placeholder="Ej. Endesa, Telefónica, Fontanero" style="width:100%; padding:8px 12px; background:#ffffff; color:#0f172a; border:1px solid #cbd5e1; border-radius:6px; font-size:13px;">';
    modalHtml += '</div>';
    modalHtml += '<div class="hostpn-input-wrapper">';
    modalHtml += '<label class="hostpn-label" style="display:block; font-size:12px; color:#475569; font-weight:600; margin-bottom:4px;">Categoría / Concepto</label>';
    modalHtml += '<select id="hostpn-exp-category" class="hostpn-field hostpn-select" style="width:100%; padding:8px 12px; background:#ffffff; color:#0f172a; border:1px solid #cbd5e1; border-radius:6px; font-size:13px;">';
    var cats = ['Suministros (Luz, Agua, Gas)', 'Internet / Wifi', 'Mantenimiento / Reparación', 'Limpieza', 'Impuestos / Seguro', 'Otro'];
    for (var c = 0; c < cats.length; c++) {
      var sel = (expCategory === cats[c]) ? ' selected' : '';
      modalHtml += '<option value="' + escHtml(cats[c]) + '"' + sel + '>' + escHtml(cats[c]) + '</option>';
    }
    modalHtml += '</select></div></div>';

    modalHtml += '<div class="hostpn-input-wrapper" style="margin-bottom:12px;">';
    modalHtml += '<label class="hostpn-label" style="display:block; font-size:12px; color:#475569; font-weight:600; margin-bottom:4px;">Fichero Adjunto (Factura / Recibo - Privado)</label>';
    modalHtml += '<input type="file" id="hostpn-exp-file" class="hostpn-field hostpn-input" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx" style="width:100%; padding:8px 12px; background:#f8fafc; color:#334155; border:1px dashed #cbd5e1; border-radius:6px; font-size:12px;">';
    if (editExpense && editExpense.attachment_original_name) {
      modalHtml += '<span style="display:block; font-size:11px; color:#0284c7; margin-top:4px; font-weight:500;">Adjunto actual: ' + escHtml(editExpense.attachment_original_name) + '</span>';
    }
    modalHtml += '</div>';

    modalHtml += '<div class="hostpn-input-wrapper" style="margin-bottom:16px;">';
    modalHtml += '<label class="hostpn-label" style="display:block; font-size:12px; color:#475569; font-weight:600; margin-bottom:4px;">Notas u Observaciones</label>';
    modalHtml += '<input type="text" id="hostpn-exp-notes" class="hostpn-field hostpn-input" value="' + escHtml(expNotes) + '" placeholder="Notas explicativas sobre este gasto" style="width:100%; padding:8px 12px; background:#ffffff; color:#0f172a; border:1px solid #cbd5e1; border-radius:6px; font-size:13px;">';
    modalHtml += '</div>';
    modalHtml += '</div>';

    modalHtml += '<div class="hostpn-popup-footer" style="display:flex; justify-content:flex-end; gap:8px;">';
    modalHtml += '<button type="button" class="hostpn-btn hostpn-btn-mini hostpn-btn-transparent hostpn-popup-close">Cancelar</button>';
    modalHtml += '<button type="button" id="hostpn-submit-expense" class="hostpn-btn hostpn-btn-mini" data-accom-id="' + accomId + '">' + (editExpense ? 'Actualizar Gasto' : 'Guardar Gasto') + '</button>';
    modalHtml += '</div></div></div>';

    $('#hostpn-expense-modal').remove();
    $('.hostpn-popup-overlay').remove();
    $('body').append(modalHtml);

    if (window.HOSTPN_Popups) {
      window.HOSTPN_Popups.open('hostpn-expense-modal');
    } else {
      $('#hostpn-expense-modal').fadeIn('fast');
      $('.hostpn-popup-overlay').removeClass('hostpn-display-none-soft').fadeIn('fast');
    }
  }

  $(document).on('click', '.hostpn-add-expense-btn', function (e) {
    e.preventDefault();
    var accomId = $(this).data('accom-id') || 0;
    openExpenseModal(accomId, null);
  });

  $(document).on('click', '.hostpn-edit-expense-btn', function (e) {
    e.preventDefault();
    var accomId = $(this).data('accom-id') || 0;
    var raw = $(this).attr('data-expense');
    var exp = raw ? JSON.parse(raw) : null;
    if (exp) {
      openExpenseModal(accomId, exp);
    }
  });

  // Submit expense handler
  $(document).on('click', '#hostpn-submit-expense', function (e) {
    e.preventDefault();
    var btn = $(this);
    if (btn.data('submitting')) return;

    var accomId = btn.data('accom-id');
    var expId = $('#hostpn-exp-id').val();
    var amount = $('#hostpn-exp-amount').val();
    var date = $('#hostpn-exp-date').val();
    var provider = $('#hostpn-exp-provider').val();
    var category = $('#hostpn-exp-category').val();
    var notes = $('#hostpn-exp-notes').val();

    var formData = new FormData();
    formData.append('action', 'hostpn_ajax');
    formData.append('hostpn_ajax_type', 'hostpn_save_expense');
    formData.append('hostpn_ajax_nonce', getNonce());
    formData.append('accommodation_id', accomId);
    formData.append('expense_id', expId);
    formData.append('amount', amount);
    formData.append('date', date);
    formData.append('provider', provider);
    formData.append('category', category);
    formData.append('notes', notes);

    var fileInput = $('#hostpn-exp-file')[0];
    if (fileInput && fileInput.files && fileInput.files[0]) {
      formData.append('attachment', fileInput.files[0]);
    }

    btn.data('submitting', true).prop('disabled', true).text('Guardando...');

    $.ajax({
      url: getAjaxUrl(),
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (res) {
        btn.data('submitting', false);
        $('#hostpn-expense-modal').remove();
        if (res && res.error_key === '') {
          refreshFinancialUI(res);
        } else {
          alert((res && res.error_content) || 'Error guardando gasto.');
        }
      },
      error: function () {
        btn.data('submitting', false);
        btn.prop('disabled', false).text('Guardar Gasto');
        alert('Error al conectar con el servidor.');
      }
    });
  });

  // Delete expense handler
  $(document).on('click', '.hostpn-delete-expense-btn', function (e) {
    e.preventDefault();
    if (!confirm('¿Estás seguro de que deseas eliminar este gasto?')) return;

    var btn = $(this);
    var accomId = btn.data('accom-id');
    var expenseId = btn.data('expense-id');

    btn.prop('disabled', true).text('...');

    $.ajax({
      url: getAjaxUrl(),
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_delete_expense',
        hostpn_ajax_nonce: getNonce(),
        accommodation_id: accomId,
        expense_id: expenseId
      },
      success: function (res) {
        if (res && res.error_key === '') {
          refreshFinancialUI(res);
        } else {
          alert((res && res.error_content) || 'Error eliminando gasto.');
        }
      },
      error: function () {
        alert('Error al conectar con el servidor.');
      }
    });
  });

  /* ── CSV Import Handlers ── */
  $(document).on('click', '.hostpn-financial-import-btn', function (e) {
    e.preventDefault();
    var btn = $(this);
    var accomId = btn.data('accommodation-id') || 0;

    var modalHtml = '<div id="hostpn-csv-modal" style="position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(2px); z-index:999999; display:flex; align-items:center; justify-content:center; padding:16px;">';
    modalHtml += '<div style="background:#ffffff; color:#0f172a; border:1px solid #e2e8f0; border-radius:10px; width:100%; max-width:560px; padding:22px; font-family:-apple-system,BlinkMacSystemFont,sans-serif; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">';
    modalHtml += '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">';
    modalHtml += '<h3 style="margin:0; font-size:16px; font-weight:700; color:#0f172a;">Importar Extracto CSV (Booking.com / Airbnb)</h3>';
    modalHtml += '<button type="button" class="hostpn-csv-modal-close" style="background:none; border:none; color:#64748b; font-size:22px; cursor:pointer;">&times;</button>';
    modalHtml += '</div>';

    modalHtml += '<div id="hostpn-csv-step-1">';
    modalHtml += '<p style="font-size:12px; color:#64748b; margin-bottom:14px;">Selecciona o arrastra el archivo CSV exportado desde Booking.com o Airbnb.</p>';
    modalHtml += '<input type="file" id="hostpn-csv-file-input" accept=".csv" style="display:block; width:100%; margin-bottom:14px; padding:10px; background:#f8fafc; color:#334155; border:1px dashed #cbd5e1; border-radius:6px; font-size:12px;">';
    modalHtml += '<div style="display:flex; justify-content:flex-end; gap:8px;">';
    modalHtml += '<button type="button" class="hostpn-csv-modal-close hostpn-btn hostpn-btn-mini hostpn-btn-transparent">Cancelar</button>';
    modalHtml += '<button type="button" id="hostpn-upload-csv-submit" class="hostpn-btn hostpn-btn-mini" data-accom-id="' + accomId + '">Analizar Archivo</button>';
    modalHtml += '</div></div>';

    modalHtml += '<div id="hostpn-csv-step-2" style="display:none;">';
    modalHtml += '<div id="hostpn-csv-preview-content"></div>';
    modalHtml += '<div style="display:flex; justify-content:flex-end; gap:8px; margin-top:16px;">';
    modalHtml += '<button type="button" class="hostpn-csv-modal-close hostpn-btn hostpn-btn-mini hostpn-btn-transparent">Cancelar</button>';
    modalHtml += '<button type="button" id="hostpn-confirm-csv-import" class="hostpn-btn hostpn-btn-mini" data-accom-id="' + accomId + '">Confirmar Importación</button>';
    modalHtml += '</div></div>';

    modalHtml += '</div></div>';

    $('#hostpn-csv-modal').remove();
    $('body').append(modalHtml);
  });

  $(document).on('click', '.hostpn-csv-modal-close', function () {
    $('#hostpn-csv-modal').remove();
  });

  var parsedCsvRecords = [];

  $(document).on('click', '#hostpn-upload-csv-submit', function (e) {
    e.preventDefault();
    var btn = $(this);
    var accomId = btn.data('accom-id');
    var fileInput = $('#hostpn-csv-file-input')[0];

    if (!fileInput.files || !fileInput.files[0]) {
      alert('Por favor selecciona un archivo CSV.');
      return;
    }

    var formData = new FormData();
    formData.append('action', 'hostpn_ajax');
    formData.append('hostpn_ajax_type', 'hostpn_financial_upload_csv');
    formData.append('hostpn_ajax_nonce', getNonce());
    formData.append('hostpn_accommodation_id', accomId);
    formData.append('hostpn_financial_csv_file', fileInput.files[0]);

    btn.prop('disabled', true).text('Analizando...');

    $.ajax({
      url: getAjaxUrl(),
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (res) {
        btn.prop('disabled', false).text('Analizar Archivo');
        if (res && res.records) {
          parsedCsvRecords = res.records;
          $('#hostpn-csv-step-1').hide();
          $('#hostpn-csv-step-2').show();

          var prevHtml = '<div style="background:#1e293b; padding:10px; border-radius:4px; margin-bottom:12px; font-size:12px;">';
          prevHtml += '<strong>Formato Detectado:</strong> ' + escHtml(res.detected_format.label) + '<br>';
          prevHtml += '<strong>Registros Encontrados:</strong> ' + res.total_records;
          prevHtml += '</div>';

          prevHtml += '<table style="width:100%; border-collapse:collapse; font-size:11px; color:#cbd5e1;">';
          prevHtml += '<tr style="background:#334155; text-align:left;"><th style="padding:6px;">Huésped</th><th style="padding:6px;">Habitación</th><th style="padding:6px;">Fecha</th><th style="padding:6px;">Importe</th></tr>';

          for (var i = 0; i < Math.min(10, res.records.length); i++) {
            var rec = res.records[i];
            prevHtml += '<tr style="border-bottom:1px solid #334155;">';
            prevHtml += '<td style="padding:6px;">' + escHtml(rec.guest_name) + '</td>';
            prevHtml += '<td style="padding:6px; color:#38bdf8;">' + escHtml(rec.matched_room_label) + '</td>';
            prevHtml += '<td style="padding:6px;">' + escHtml(rec.date) + '</td>';
            prevHtml += '<td style="padding:6px; color:#4ade80; font-weight:600;">€ ' + formatMoney(rec.amount) + '</td>';
            prevHtml += '</tr>';
          }
          prevHtml += '</table>';

          $('#hostpn-csv-preview-content').html(prevHtml);
        } else {
          alert((res && res.error_content) || 'Error analizando CSV.');
        }
      },
      error: function () {
        btn.prop('disabled', false).text('Analizar Archivo');
        alert('Error conectando con el servidor.');
      }
    });
  });

  $(document).on('click', '#hostpn-confirm-csv-import', function (e) {
    e.preventDefault();
    var btn = $(this);
    var accomId = btn.data('accom-id');

    if (!parsedCsvRecords || !parsedCsvRecords.length) {
      alert('No hay registros para importar.');
      return;
    }

    btn.prop('disabled', true).text('Importando...');

    $.ajax({
      url: getAjaxUrl(),
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_financial_process_import',
        hostpn_ajax_nonce: getNonce(),
        accommodation_id: accomId,
        records: JSON.stringify(parsedCsvRecords)
      },
      success: function (res) {
        $('#hostpn-csv-modal').remove();
        if (res && res.summary) {
          alert(res.message || 'Importación completada con éxito.');
          refreshFinancialUI(res);
        }
      },
      error: function () {
        btn.prop('disabled', false).text('Confirmar Importación');
        alert('Error en la importación.');
      }
    });
  });

})(jQuery);

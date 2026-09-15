(function ($) {
  'use strict';

  $(document).ready(function () {
    // Dynamic room filtering based on accommodation selection
    var $accommodationSelect = $('.hostpn-contract-accommodation-select');
    var $roomSelect = $('.hostpn-contract-room-select');

    if ($accommodationSelect.length && $roomSelect.length) {
      $accommodationSelect.on('change', function () {
        var accommodationId = $(this).val();
        if (!accommodationId) {
          $roomSelect.html(
            '<option value="">' +
              ($roomSelect.attr('placeholder') || 'Select room (optional)') +
              '</option>'
          );
          return;
        }

        // Fetch rooms via AJAX
        $.ajax({
          url: hostpnContractCpt.ajaxUrl,
          type: 'POST',
          data: {
            action: 'hostpn_room_list_by_accommodation',
            hostpn_ajax_nonce: hostpnContractCpt.nonce,
            hostpn_accommodation_id: accommodationId,
          },
          success: function (response) {
            try {
              var data =
                typeof response === 'string' ? JSON.parse(response) : response;
              if (data.rooms) {
                var currentVal = $roomSelect.val();
                var html =
                  '<option value="">' +
                  ($roomSelect.attr('placeholder') ||
                    'Select room (optional)') +
                  '</option>';
                $.each(data.rooms, function (id, label) {
                  var selected = id == currentVal ? ' selected' : '';
                  html +=
                    '<option value="' + id + '"' + selected + '>' + label + '</option>';
                });
                $roomSelect.html(html);
              }
            } catch (e) {
              // Silent fail
            }
          },
        });
      });
    }

    // Auto-fill tenant data from guest selection
    var $guestSelect = $('.hostpn-contract-guest-select');
    if ($guestSelect.length) {
      $guestSelect.on('change', function () {
        var guestId = $(this).val();
        if (!guestId) return;

        $.ajax({
          url: hostpnContractCpt.ajaxUrl,
          type: 'POST',
          data: {
            action: 'hostpn_ajax',
            hostpn_ajax_nonce: hostpnContractCpt.nonce,
            hostpn_ajax_type: 'hostpn_contract_get_guest_data',
            hostpn_guest_id: guestId,
          },
          success: function (response) {
            try {
              var data =
                typeof response === 'string' ? JSON.parse(response) : response;
              if (data.guest) {
                var $name = $('#hostpn_contract_tenant_name');
                var $nif = $('#hostpn_contract_tenant_nif');
                var $email = $('#hostpn_contract_tenant_email');

                if ($name.length && !$name.val()) $name.val(data.guest.name || '');
                if ($nif.length && !$nif.val()) $nif.val(data.guest.nif || '');
                if ($email.length && !$email.val()) $email.val(data.guest.email || '');
              }
            } catch (e) {
              // Silent fail
            }
          },
        });
      });
    }

    // Auto-fill landlord data from accommodation selection
    if ($accommodationSelect.length) {
      $accommodationSelect.on('change', function () {
        var accommodationId = $(this).val();
        if (!accommodationId) return;

        $.ajax({
          url: hostpnContractCpt.ajaxUrl,
          type: 'POST',
          data: {
            action: 'hostpn_ajax',
            hostpn_ajax_nonce: hostpnContractCpt.nonce,
            hostpn_ajax_type: 'hostpn_contract_get_accommodation_data',
            hostpn_accommodation_id: accommodationId,
          },
          success: function (response) {
            try {
              var data =
                typeof response === 'string' ? JSON.parse(response) : response;
              if (data.accommodation) {
                var $name = $('#hostpn_contract_landlord_name');
                var $nif = $('#hostpn_contract_landlord_nif');
                var $address = $('#hostpn_contract_landlord_address');
                var $type = $('#hostpn_contract_type');

                if ($name.length && !$name.val())
                  $name.val(data.accommodation.landlord_name || '');
                if ($nif.length && !$nif.val())
                  $nif.val(data.accommodation.landlord_nif || '');
                if ($address.length && !$address.val())
                  $address.val(data.accommodation.landlord_address || '');
                if ($type.length && !$type.val() && data.accommodation.contract_type)
                  $type.val(data.accommodation.contract_type);
              }
            } catch (e) {
              // Silent fail
            }
          },
        });
      });
    }

    // Live preview update on field changes (admin metabox only)
    var $previewContainer = $('#hostpn-contract-preview-container');
    if ($previewContainer.length) {
      var previewTimer;
      var $contractFields = $(
        '.hostpn-contract-fields-column input, .hostpn-contract-fields-column select'
      );

      $contractFields.on('change keyup', function () {
        clearTimeout(previewTimer);
        previewTimer = setTimeout(function () {
          updatePreview();
        }, 500);
      });

      function updatePreview() {
        var contractId = $previewContainer
          .closest('.hostpn-contract-metabox-wrapper')
          .find('input[name="post_ID"]')
          .val();
        var contractType = $('#hostpn_contract_type').val();
        var accommodationId = $('#hostpn_contract_accommodation_id').val();

        if (!contractType || !accommodationId) {
          return;
        }

        $.ajax({
          url: hostpnContractCpt.ajaxUrl,
          type: 'POST',
          data: {
            action: 'hostpn_ajax',
            hostpn_ajax_nonce: hostpnContractCpt.nonce,
            hostpn_ajax_type: 'hostpn_contract_preview',
            hostpn_contract_id: contractId || 0,
            hostpn_contract_type: contractType,
            hostpn_accommodation_id: accommodationId,
            // Send current field values for preview
            hostpn_contract_landlord_name: $(
              '#hostpn_contract_landlord_name'
            ).val(),
            hostpn_contract_landlord_nif: $(
              '#hostpn_contract_landlord_nif'
            ).val(),
            hostpn_contract_landlord_address: $(
              '#hostpn_contract_landlord_address'
            ).val(),
            hostpn_contract_tenant_name: $(
              '#hostpn_contract_tenant_name'
            ).val(),
            hostpn_contract_tenant_nif: $(
              '#hostpn_contract_tenant_nif'
            ).val(),
            hostpn_contract_tenant_email: $(
              '#hostpn_contract_tenant_email'
            ).val(),
            hostpn_contract_duration: $('#hostpn_contract_duration').val(),
            hostpn_contract_start_date: $(
              '#hostpn_contract_start_date'
            ).val(),
            hostpn_contract_end_date: $('#hostpn_contract_end_date').val(),
            hostpn_contract_rent_amount: $(
              '#hostpn_contract_rent_amount'
            ).val(),
            hostpn_contract_rent_words: $(
              '#hostpn_contract_rent_words'
            ).val(),
            hostpn_contract_deposit_amount: $(
              '#hostpn_contract_deposit_amount'
            ).val(),
            hostpn_contract_deposit_words: $(
              '#hostpn_contract_deposit_words'
            ).val(),
            hostpn_contract_room_id: $('#hostpn_contract_room_id').val(),
            hostpn_contract_payment_day: $(
              '#hostpn_contract_payment_day'
            ).val(),
            hostpn_contract_bank_name: $('#hostpn_contract_bank_name').val(),
            hostpn_contract_iban: $('#hostpn_contract_iban').val(),
            hostpn_contract_guest_count: $(
              '#hostpn_contract_guest_count'
            ).val(),
            hostpn_contract_checkin_time: $(
              '#hostpn_contract_checkin_time'
            ).val(),
            hostpn_contract_checkout_time: $(
              '#hostpn_contract_checkout_time'
            ).val(),
            hostpn_contract_total_price: $(
              '#hostpn_contract_total_price'
            ).val(),
            hostpn_contract_notice_days: $(
              '#hostpn_contract_notice_days'
            ).val(),
          },
          success: function (response) {
            try {
              var data =
                typeof response === 'string' ? JSON.parse(response) : response;
              if (data.html) {
                $previewContainer.html(data.html);
              }
            } catch (e) {
              // Silent fail
            }
          },
        });
      }
    }
  });
})(jQuery);

(function ($) {
  'use strict';

  $(document).ready(function () {
    // Preview toggle
    $(document).on('click', '.hostpn-cb-btn-preview', function (e) {
      e.preventDefault();
      var $btn = $(this);
      var contractId = $btn.data('contract-id');
      var $preview = $(
        '.hostpn-cb-card-preview[data-contract-id="' + contractId + '"]'
      );
      var $content = $preview.find('.hostpn-cb-preview-content');
      var $loading = $preview.find('.hostpn-cb-preview-loading');

      if ($preview.is(':visible')) {
        $preview.slideUp(200);
        $btn.removeClass('active');
        return;
      }

      $btn.addClass('active');
      $preview.slideDown(200);

      // Load preview if not already loaded
      if (!$content.html().trim()) {
        $loading.show();
        $content.hide();

        $.ajax({
          url: hostpnContractsBlock.ajaxUrl,
          type: 'POST',
          data: {
            action: 'hostpn_ajax',
            hostpn_ajax_nonce: hostpnContractsBlock.nonce,
            hostpn_ajax_type: 'hostpn_contract_frontend_preview',
            hostpn_contract_id: contractId,
          },
          success: function (response) {
            try {
              var data =
                typeof response === 'string' ? JSON.parse(response) : response;
              if (data.html) {
                $content.html(data.html);
              } else if (data.error_content) {
                $content.html(
                  '<p style="color:#c62828;">' + data.error_content + '</p>'
                );
              }
            } catch (err) {
              $content.html(
                '<p style="color:#c62828;">Error loading preview.</p>'
              );
            }
            $loading.hide();
            $content.show();
          },
          error: function () {
            $content.html(
              '<p style="color:#c62828;">Error loading preview.</p>'
            );
            $loading.hide();
            $content.show();
          },
        });
      }
    });

    // Upload signed copy - trigger file input
    $(document).on('click', '.hostpn-cb-btn-upload', function (e) {
      e.preventDefault();
      var contractId = $(this).data('contract-id');
      var $fileInput = $(
        '.hostpn-cb-file-input[data-contract-id="' + contractId + '"]'
      );
      $fileInput.trigger('click');
    });

    // Upload signed copy - handle file selection
    $(document).on('change', '.hostpn-cb-file-input', function () {
      var $input = $(this);
      var contractId = $input.data('contract-id');
      var file = $input[0].files[0];

      if (!file) return;

      // Validate PDF
      if (file.type !== 'application/pdf') {
        alert(hostpnContractsBlock.i18n.onlyPdf);
        $input.val('');
        return;
      }

      var $card = $input.closest('.hostpn-cb-card');
      var $btn = $card.find('.hostpn-cb-btn-upload');
      var originalText = $btn.html();
      $btn
        .addClass('uploading')
        .html(
          '<i class="material-icons-outlined hostpn-spin">sync</i> ' +
            hostpnContractsBlock.i18n.uploading
        );

      var formData = new FormData();
      formData.append('action', 'hostpn_contract_upload_signed');
      formData.append('hostpn_ajax_nonce', hostpnContractsBlock.nonce);
      formData.append('contract_id', contractId);
      formData.append('signed_pdf', file);

      $.ajax({
        url: hostpnContractsBlock.ajaxUrl,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          try {
            var data =
              typeof response === 'string' ? JSON.parse(response) : response;
            if (data.error_key) {
              alert(data.error_content || 'Upload failed.');
              $btn.removeClass('uploading').html(originalText);
            } else {
              // Update status badge
              $card
                .find('.hostpn-cb-badge')
                .removeClass(
                  'hostpn-cb-status-draft hostpn-cb-status-sent hostpn-cb-status-expired'
                )
                .addClass('hostpn-cb-status-signed')
                .text(hostpnContractsBlock.i18n.signed);

              // Replace upload button with success message
              $btn.replaceWith(
                '<div class="hostpn-cb-upload-success">' +
                  '<i class="material-icons-outlined">check_circle</i> ' +
                  (data.message || hostpnContractsBlock.i18n.uploadSuccess) +
                  '</div>'
              );
              $input.remove();
            }
          } catch (err) {
            alert('Upload failed.');
            $btn.removeClass('uploading').html(originalText);
          }
        },
        error: function () {
          alert('Upload failed.');
          $btn.removeClass('uploading').html(originalText);
        },
      });

      $input.val('');
    });
  });
})(jQuery);

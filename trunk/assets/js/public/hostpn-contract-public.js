(function ($) {
  'use strict';

  var signaturePadLandlord = null;
  var signaturePadTenant = null;

  $(document).ready(function () {
    // Initialize signature pads if SignaturePad is available
    if (typeof SignaturePad === 'undefined') return;

    var landlordCanvas = document.getElementById('hostpn-signature-landlord');
    var tenantCanvas = document.getElementById('hostpn-signature-tenant');

    if (landlordCanvas) {
      signaturePadLandlord = new SignaturePad(landlordCanvas, {
        backgroundColor: 'rgba(250, 250, 250, 1)',
        penColor: 'rgb(0, 0, 0)'
      });
      resizeCanvas(landlordCanvas, signaturePadLandlord);
    }

    if (tenantCanvas) {
      signaturePadTenant = new SignaturePad(tenantCanvas, {
        backgroundColor: 'rgba(250, 250, 250, 1)',
        penColor: 'rgb(0, 0, 0)'
      });
      resizeCanvas(tenantCanvas, signaturePadTenant);
    }

    // Handle window resize
    $(window).on('resize', function () {
      if (landlordCanvas && signaturePadLandlord) resizeCanvas(landlordCanvas, signaturePadLandlord);
      if (tenantCanvas && signaturePadTenant) resizeCanvas(tenantCanvas, signaturePadTenant);
    });

    // Clear signature buttons
    $(document).on('click', '.hostpn-signature-clear-btn', function (e) {
      e.preventDefault();
      var targetId = $(this).data('target');
      if (targetId === 'hostpn-signature-landlord' && signaturePadLandlord) {
        signaturePadLandlord.clear();
        $('#hostpn-signature-landlord-image').empty();
      }
      if (targetId === 'hostpn-signature-tenant' && signaturePadTenant) {
        signaturePadTenant.clear();
        $('#hostpn-signature-tenant-image').empty();
      }
    });

    // Print button
    $('#hostpn-contract-print-btn').on('click', function (e) {
      e.preventDefault();
      embedSignatureImages();
      window.print();
    });

    // PDF download button
    $('#hostpn-contract-pdf-btn').on('click', function (e) {
      e.preventDefault();
      var $btn = $(this);
      if ($btn.prop('disabled')) return;

      embedSignatureImages();

      $btn.prop('disabled', true).find('span').text('Generando PDF...');

      var element = document.getElementById('hostpn-contract-document');
      var opt = {
        margin:      [10, 10, 10, 10],
        filename:    'contrato_firmado.pdf',
        image:       { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true, letterRendering: true },
        jsPDF:       { unit: 'mm', format: 'a4', orientation: 'portrait' },
        pagebreak:   { mode: ['avoid-all', 'css', 'legacy'] }
      };

      html2pdf().set(opt).from(element).save().then(function () {
        $btn.prop('disabled', false).find('span').text($btn.data('original-text') || 'Download PDF');
      }).catch(function () {
        $btn.prop('disabled', false).find('span').text($btn.data('original-text') || 'Download PDF');
        alert('Error generating PDF. Please try again.');
      });
    });

    // Store original button text
    $('#hostpn-contract-pdf-btn').data('original-text', $('#hostpn-contract-pdf-btn').find('span').text());
  });

  /**
   * Resize canvas to fit its container while maintaining signature data.
   */
  function resizeCanvas(canvas, pad) {
    var wrapper = canvas.parentElement;
    var ratio = Math.max(window.devicePixelRatio || 1, 1);
    var width = wrapper.offsetWidth - 4; // Account for border
    if (width < 200) width = 200;
    if (width > 400) width = 400;

    canvas.width = width * ratio;
    canvas.height = 150 * ratio;
    canvas.style.width = width + 'px';
    canvas.style.height = '150px';

    var ctx = canvas.getContext('2d');
    ctx.scale(ratio, ratio);
    pad.clear();
  }

  /**
   * Embed signature pad images into the document for print/PDF.
   */
  function embedSignatureImages() {
    if (signaturePadLandlord && !signaturePadLandlord.isEmpty()) {
      var landlordImg = signaturePadLandlord.toDataURL('image/png');
      $('#hostpn-signature-landlord-image').html('<img src="' + landlordImg + '" alt="Landlord signature" />');
    }

    if (signaturePadTenant && !signaturePadTenant.isEmpty()) {
      var tenantImg = signaturePadTenant.toDataURL('image/png');
      $('#hostpn-signature-tenant-image').html('<img src="' + tenantImg + '" alt="Tenant signature" />');
    }
  }

})(jQuery);

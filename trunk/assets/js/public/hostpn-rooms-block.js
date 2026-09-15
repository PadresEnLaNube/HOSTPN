(function ($) {
  'use strict';

  console.log('[hostpn-rooms-block] Script loaded');
  console.log('[hostpn-rooms-block] hostpnRoomsBlock available:', typeof hostpnRoomsBlock !== 'undefined');
  if (typeof hostpnRoomsBlock !== 'undefined') {
    console.log('[hostpn-rooms-block] ajaxUrl:', hostpnRoomsBlock.ajaxUrl);
    console.log('[hostpn-rooms-block] nonce:', hostpnRoomsBlock.nonce ? 'SET' : 'MISSING');
  }
  console.log('[hostpn-rooms-block] .hostpn-room-notify-btn count:', $('.hostpn-room-notify-btn').length);

  // Toggle handler for .hostpn-toggle (hostpn-forms.js not loaded on frontend)
  $(document).on('click', '.hostpn-rooms-listing .hostpn-toggle, .hostpn-room-waitlist-admin .hostpn-toggle', function (e) {
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();

    var $toggle = $(this);

    var $indicator = $toggle.find('.hostpn-toggle-indicator');
    if ($indicator.length) {
      if ($toggle.siblings('.hostpn-toggle-content').is(':visible')) {
        $indicator.text('add');
      } else {
        $indicator.text('close');
      }
    }

    $toggle.siblings('.hostpn-toggle-content').fadeToggle();
  });

  // Notify me button
  $(document).on('click', '.hostpn-room-notify-btn', function (e) {
    e.preventDefault();
    e.stopPropagation();

    console.log('[hostpn-rooms-block] Notify button clicked');

    var $btn = $(this);
    var $form = $btn.closest('.hostpn-room-notify-form');
    var $input = $form.find('.hostpn-room-notify-email');
    var $feedback = $form.find('.hostpn-room-notify-feedback');
    var email = $.trim($input.val());
    var roomId = $form.data('room-id');

    console.log('[hostpn-rooms-block] email:', email, 'roomId:', roomId);

    $feedback.removeClass('hostpn-success hostpn-error').text('');

    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      console.log('[hostpn-rooms-block] Email validation failed');
      $feedback.addClass('hostpn-error').text(hostpnRoomsBlock.i18n.invalidEmail);
      return;
    }

    console.log('[hostpn-rooms-block] Sending subscribe AJAX...');
    $btn.prop('disabled', true).text(hostpnRoomsBlock.i18n.subscribing);

    $.ajax({
      url: hostpnRoomsBlock.ajaxUrl,
      type: 'POST',
      data: {
        action: 'hostpn_room_availability_subscribe',
        hostpn_room_id: roomId,
        hostpn_notify_email: email,
        hostpn_ajax_nonce: hostpnRoomsBlock.nonce,
      },
      dataType: 'json',
      success: function (response) {
        console.log('[hostpn-rooms-block] Subscribe response:', response);
        if (response.error_key === '') {
          $feedback.addClass('hostpn-success').text(hostpnRoomsBlock.i18n.subscribed);
          $input.val('');
        } else if (response.error_key === 'already_subscribed') {
          $feedback.addClass('hostpn-error').text(hostpnRoomsBlock.i18n.alreadySubscribed);
        } else {
          $feedback.addClass('hostpn-error').text(response.error_content || '');
        }
      },
      error: function (xhr, status, error) {
        console.error('[hostpn-rooms-block] Subscribe error:', status, error, xhr.responseText);
        $feedback.addClass('hostpn-error').text('Error');
      },
      complete: function () {
        $btn.prop('disabled', false).html(
          '<i class="material-icons-outlined">notifications</i> ' + hostpnRoomsBlock.i18n.notifyMe
        );
      },
    });
  });

  // Admin: remove email from waitlist
  $(document).on('click', '.hostpn-waitlist-remove-btn', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var $btn = $(this);
    var $item = $btn.closest('.hostpn-waitlist-email-item');
    var roomId = $btn.data('room-id');
    var email = $btn.data('email');

    console.log('[hostpn-rooms-block] Remove waitlist email:', email, 'roomId:', roomId);

    $btn.prop('disabled', true);

    $.ajax({
      url: hostpnRoomsBlock.ajaxUrl,
      type: 'POST',
      data: {
        action: 'hostpn_room_waitlist_remove',
        hostpn_room_id: roomId,
        hostpn_notify_email: email,
        hostpn_ajax_nonce: hostpnRoomsBlock.nonce,
      },
      dataType: 'json',
      success: function (response) {
        console.log('[hostpn-rooms-block] Remove response:', response);
        if (response.error_key === '') {
          $item.fadeOut(300, function () {
            var $ul = $item.closest('.hostpn-waitlist-emails');
            $item.remove();
            // Update count in toggle label
            var remaining = $ul.find('.hostpn-waitlist-email-item').length;
            var $label = $ul.closest('.hostpn-room-waitlist-admin').find('.hostpn-toggle label');
            if (remaining > 0) {
              $label.html('<i class="material-icons-outlined hostpn-icon-small">mail</i> ' + hostpnRoomsBlock.i18n.waitingList + ' (' + remaining + ')');
            } else {
              $ul.closest('.hostpn-room-waitlist-admin').fadeOut(300);
            }
          });
        }
      },
      error: function (xhr, status, error) {
        console.error('[hostpn-rooms-block] Remove error:', status, error, xhr.responseText);
        $btn.prop('disabled', false);
      },
    });
  });
})(jQuery);

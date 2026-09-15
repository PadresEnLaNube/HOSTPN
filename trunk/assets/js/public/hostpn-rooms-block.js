(function ($) {
  'use strict';

  $(document).on('click', '.hostpn-room-notify-btn', function (e) {
    e.preventDefault();
    e.stopPropagation();
    var $btn = $(this);
    var $form = $btn.closest('.hostpn-room-notify-form');
    var $input = $form.find('.hostpn-room-notify-email');
    var $feedback = $form.find('.hostpn-room-notify-feedback');
    var email = $.trim($input.val());
    var roomId = $form.data('room-id');

    $feedback.removeClass('hostpn-success hostpn-error').text('');

    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      $feedback.addClass('hostpn-error').text(hostpnRoomsBlock.i18n.invalidEmail);
      return;
    }

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
        if (response.error_key === '') {
          $feedback.addClass('hostpn-success').text(hostpnRoomsBlock.i18n.subscribed);
          $input.val('');
        } else if (response.error_key === 'already_subscribed') {
          $feedback.addClass('hostpn-error').text(hostpnRoomsBlock.i18n.alreadySubscribed);
        } else {
          $feedback.addClass('hostpn-error').text(response.error_content || '');
        }
      },
      error: function () {
        $feedback.addClass('hostpn-error').text('Error');
      },
      complete: function () {
        $btn.prop('disabled', false).html(
          '<i class="material-icons-outlined">notifications</i> ' + hostpnRoomsBlock.i18n.notifyMe
        );
      },
    });
  });
})(jQuery);

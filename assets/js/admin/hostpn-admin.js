(function($) {
	'use strict';

	$(document).ready(function() {
		// Handle guest notification resend button click
		$(document).on('click', '.hostpn-resend-notification:not([disabled])', function(e) {
			e.preventDefault();

			var $button = $(this);
			var postId = $button.data('post-id');
			var $icon = $button.find('i');
			var originalText = $button.contents().filter(function() {
				return this.nodeType === 3;
			}).text().trim();

			// Disable button and show loading state
			$button.prop('disabled', true);
			$icon.text('hourglass_empty').addClass('hostpn-icon-spin');

			$.ajax({
				url: hostpn_admin.ajaxurl,
				type: 'POST',
				data: {
					action: 'hostpn_guest_resend_notification',
					post_id: postId,
					nonce: hostpn_admin.nonce
				},
				success: function(response) {
					if (response.success) {
						// Show success state
						$icon.text('check_circle').removeClass('hostpn-icon-spin');
						setTimeout(function() {
							$icon.text('email');
							$button.prop('disabled', false);
						}, 2000);

						// Show success message
						if (typeof hostpn_show_notification === 'function') {
							hostpn_show_notification(response.data.message, 'success');
						}
					} else {
						// Show error state
						$icon.text('error').removeClass('hostpn-icon-spin');
						setTimeout(function() {
							$icon.text('email');
							$button.prop('disabled', false);
						}, 2000);

						// Show error message
						if (typeof hostpn_show_notification === 'function') {
							hostpn_show_notification(response.data.message, 'error');
						} else {
							alert(response.data.message);
						}
					}
				},
				error: function() {
					// Show error state
					$icon.text('error').removeClass('hostpn-icon-spin');
					setTimeout(function() {
						$icon.text('email');
						$button.prop('disabled', false);
					}, 2000);

					var errorMsg = 'Error al reenviar la notificación';
					if (typeof hostpn_show_notification === 'function') {
						hostpn_show_notification(errorMsg, 'error');
					} else {
						alert(errorMsg);
					}
				}
			});
		});
	});

})(jQuery);
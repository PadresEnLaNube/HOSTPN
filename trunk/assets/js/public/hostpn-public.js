(function($) {
	'use strict';

	function hostpn_timer(step) {
		var step_timer = $('.hostpn-player-step[data-hostpn-step="' + step + '"] .hostpn-player-timer');
		var step_icon = $('.hostpn-player-step[data-hostpn-step="' + step + '"] .hostpn-player-timer-icon');
		
		if (!step_timer.hasClass('timing')) {
			step_timer.addClass('timing');

      setInterval(function() {
      	step_icon.fadeOut('fast').fadeIn('slow').fadeOut('fast').fadeIn('slow');
      }, 5000);

      setInterval(function() {
      	step_timer.text(Math.max(0, parseInt(step_timer.text()) - 1)).fadeOut('fast').fadeIn('slow').fadeOut('fast').fadeIn('slow');
      }, 60000);
		}
	}

	$(document).on('click', '.hostpn-popup-player-btn', function(e){
  	hostpn_timer(1);
	});

  $(document).on('click', '.hostpn-steps-prev', function(e){
    e.preventDefault();

    var steps_count = $('#hostpn-recipe-wrapper').attr('data-hostpn-steps-count');
    var current_step = $('#hostpn-popup-steps').attr('data-hostpn-current-step');
    var next_step = Math.max(0, (parseInt(current_step) - 1));
    
    $('.hostpn-player-step').addClass('hostpn-display-none-soft');
    $('#hostpn-popup-steps').attr('data-hostpn-current-step', next_step);
    $('.hostpn-player-step[data-hostpn-step=' + next_step + ']').removeClass('hostpn-display-none-soft');

    if (current_step <= steps_count) {
    	$('.hostpn-steps-next').removeClass('hostpn-display-none');
    }

    if (current_step <= 2) {
    	$(this).addClass('hostpn-display-none');
    }

    hostpn_timer(next_step);
	});

	$(document).on('click', '.hostpn-steps-next', function(e){
    e.preventDefault();

    var steps_count = $('#hostpn-recipe-wrapper').attr('data-hostpn-steps-count');
    var current_step = $('#hostpn-popup-steps').attr('data-hostpn-current-step');
    var next_step = Math.min(steps_count, (parseInt(current_step) + 1));

    $('.hostpn-player-step').addClass('hostpn-display-none-soft');
    $('#hostpn-popup-steps').attr('data-hostpn-current-step', next_step);
    $('.hostpn-player-step[data-hostpn-step=' + next_step + ']').removeClass('hostpn-display-none-soft');

    if (current_step >= 1) {
    	$('.hostpn-steps-prev').removeClass('hostpn-display-none');
    }

    if (current_step >= (steps_count - 1)) {
    	$(this).addClass('hostpn-display-none');
    }

    hostpn_timer(next_step);
	});

	$(document).on('click', '.hostpn-ingredient-checkbox', function(e){
    e.preventDefault();

    if ($(this).text() == 'radio_button_unchecked') {
    	$(this).text('task_alt');
    }else{
    	$(this).text('radio_button_unchecked');
    }
	});

	// Initialize HOSTPN Carousel for main images
	if ($('.hostpn-carousel-main-images').length) {
		$('.hostpn-carousel-main-images').hostpnCarousel({
			autoplay: true,
			autoplaySpeed: 5000,
			speed: 2000,
			loop: true,
			dots: true,
			nav: false,
			itemsDesktop: 4,
			itemsMobile: 2
		});
	}

	// Financial Management - Public View (Admin Only)
	var HOSTPN_Public_Financial = {

		init: function() {
			if ($('.hostpn-public-financial-dashboard').length > 0) {
				this.bindEvents();
			}
		},

		bindEvents: function() {
			var self = this;

			// Toggle financial management section
			$(document).on('click', '.hostpn-financial-toggle-wrapper .hostpn-toggle-header', function() {
				$(this).toggleClass('active');
				$(this).siblings('.hostpn-toggle-content').toggleClass('active');
			});

			// Add record button
			$(document).on('click', '.hostpn-financial-add-record-btn', function(e) {
				e.preventDefault();
				self.openRecordModal();
			});

			// Edit record button
			$(document).on('click', '.hostpn-financial-record-edit', function(e) {
				e.preventDefault();
				var recordId = $(this).data('record-id');
				self.openRecordModal(recordId);
			});

			// Delete record button
			$(document).on('click', '.hostpn-financial-record-delete', function(e) {
				e.preventDefault();
				var recordId = $(this).data('record-id');
				if (confirm(hostpn_ajax.translations.confirm_delete || 'Are you sure you want to delete this record?')) {
					self.deleteRecord(recordId);
				}
			});

			// Filter change
			$(document).on('change', '.hostpn-financial-filter', function() {
				self.loadDashboard();
			});

			// Filter reset
			$(document).on('click', '.hostpn-financial-filter-reset', function(e) {
				e.preventDefault();
				$('.hostpn-financial-filter').val('');
				self.loadDashboard();
			});

			// Pagination
			$(document).on('click', '.hostpn-financial-pagination-btn', function(e) {
				e.preventDefault();
				var page = $(this).data('page');
				self.loadDashboard(page);
			});

			// Export CSV
			$(document).on('click', '.hostpn-financial-export-btn', function(e) {
				e.preventDefault();
				self.exportToCSV();
			});

			// Submit record form
			$(document).on('submit', '#hostpn-financial-record-form', function(e) {
				e.preventDefault();
				self.saveRecord();
			});

			// Close modal
			$(document).on('click', '.hostpn-modal-close, #hostpn-financial-record-modal .hostpn-modal-backdrop', function(e) {
				e.preventDefault();
				self.closeModal();
			});
		},

		openRecordModal: function(recordId) {
			var $modal = $('#hostpn-financial-record-modal');
			var $form = $('#hostpn-financial-record-form');

			// Reset form
			$form[0].reset();
			$('#hostpn_finance_id').val('');

			if (recordId) {
				// Edit mode - load record data
				$('#hostpn-financial-modal-title').text('Edit Financial Record');
				this.loadRecordData(recordId);
			} else {
				// Add mode
				$('#hostpn-financial-modal-title').text('Add Financial Record');
				$('#hostpn_financial_date').val(new Date().toISOString().split('T')[0]);
			}

			// Show modal
			$modal.fadeIn(300);
			$('body').css('overflow', 'hidden');
		},

		loadRecordData: function(recordId) {
			var self = this;

			$.ajax({
				url: hostpn_ajax.ajax_url,
				type: 'POST',
				data: {
					action: 'hostpn_get_financial_record',
					nonce: hostpn_ajax.hostpn_ajax_nonce,
					record_id: recordId
				},
				success: function(response) {
					if (response.success && response.data) {
						var record = response.data;
						$('#hostpn_finance_id').val(record.id);
						$('#hostpn_financial_date').val(record.date);
						$('#hostpn_finance_type').val(record.type);
						$('#hostpn_financial_amount').val(record.amount);
						$('#hostpn_financial_currency').val(record.currency);
						$('#hostpn_financial_platform').val(record.platform);
						$('#hostpn_financial_description').val(record.description);
						$('#hostpn_financial_notes').val(record.notes);
					}
				}
			});
		},

		saveRecord: function() {
			var self = this;
			var $form = $('#hostpn-financial-record-form');
			var $submitBtn = $form.find('button[type="submit"]');
			var originalText = $submitBtn.html();

			// Disable submit button
			$submitBtn.prop('disabled', true).html('<i class="material-icons-outlined">hourglass_empty</i> Saving...');

			var formData = {
				action: 'hostpn_save_financial_record',
				nonce: hostpn_ajax.hostpn_ajax_nonce,
				record_id: $('#hostpn_finance_id').val(),
				accommodation_id: $('#hostpn_financial_accommodation_id').val(),
				date: $('#hostpn_financial_date').val(),
				type: $('#hostpn_finance_type').val(),
				amount: $('#hostpn_financial_amount').val(),
				currency: $('#hostpn_financial_currency').val(),
				platform: $('#hostpn_financial_platform').val(),
				description: $('#hostpn_financial_description').val(),
				notes: $('#hostpn_financial_notes').val()
			};

			$.ajax({
				url: hostpn_ajax.ajax_url,
				type: 'POST',
				data: formData,
				success: function(response) {
					if (response.success) {
						self.closeModal();
						self.loadDashboard();
						// Show success message
						if (typeof hostpn_i18n !== 'undefined' && hostpn_i18n.saved_successfully) {
							alert(hostpn_i18n.saved_successfully);
						}
					} else {
						alert(response.data.message || 'Error saving record');
					}
				},
				error: function() {
					alert('Error saving record. Please try again.');
				},
				complete: function() {
					$submitBtn.prop('disabled', false).html(originalText);
				}
			});
		},

		deleteRecord: function(recordId) {
			var self = this;

			$.ajax({
				url: hostpn_ajax.ajax_url,
				type: 'POST',
				data: {
					action: 'hostpn_delete_financial_record',
					nonce: hostpn_ajax.hostpn_ajax_nonce,
					record_id: recordId
				},
				success: function(response) {
					if (response.success) {
						self.loadDashboard();
						// Show success message
						if (typeof hostpn_i18n !== 'undefined' && hostpn_i18n.removed_successfully) {
							alert(hostpn_i18n.removed_successfully);
						}
					} else {
						alert(response.data.message || 'Error deleting record');
					}
				},
				error: function() {
					alert('Error deleting record. Please try again.');
				}
			});
		},

		loadDashboard: function(page) {
			var self = this;
			var $dashboard = $('.hostpn-public-financial-dashboard');
			var accommodationId = $dashboard.data('accommodation-id');

			page = page || 1;

			var filters = {
				year: $('.hostpn-financial-filter[data-filter="year"]').val(),
				quarter: $('.hostpn-financial-filter[data-filter="quarter"]').val(),
				platform: $('.hostpn-financial-filter[data-filter="platform"]').val(),
				type: $('.hostpn-financial-filter[data-filter="type"]').val(),
				page: page
			};

			// Show loading state
			$dashboard.css('opacity', '0.5');

			$.ajax({
				url: hostpn_ajax.ajax_url,
				type: 'POST',
				data: {
					action: 'hostpn_load_public_financial_dashboard',
					nonce: hostpn_ajax.hostpn_ajax_nonce,
					accommodation_id: accommodationId,
					filters: filters
				},
				success: function(response) {
					if (response.success && response.data.html) {
						// Replace dashboard content
						var $newContent = $(response.data.html);
						$dashboard.html($newContent.html());
					}
				},
				error: function() {
					alert('Error loading dashboard. Please refresh the page.');
				},
				complete: function() {
					$dashboard.css('opacity', '1');
				}
			});
		},

		exportToCSV: function() {
			var $dashboard = $('.hostpn-public-financial-dashboard');
			var accommodationId = $dashboard.data('accommodation-id');

			var filters = {
				year: $('.hostpn-financial-filter[data-filter="year"]').val(),
				quarter: $('.hostpn-financial-filter[data-filter="quarter"]').val(),
				platform: $('.hostpn-financial-filter[data-filter="platform"]').val(),
				type: $('.hostpn-financial-filter[data-filter="type"]').val()
			};

			// Build download URL
			var downloadUrl = hostpn_ajax.ajax_url + '?action=hostpn_export_financial_csv&nonce=' +
				hostpn_ajax.hostpn_ajax_nonce + '&accommodation_id=' + accommodationId;

			// Add filters to URL
			$.each(filters, function(key, value) {
				if (value) {
					downloadUrl += '&' + key + '=' + encodeURIComponent(value);
				}
			});

			// Trigger download
			window.location.href = downloadUrl;
		},

		closeModal: function() {
			$('#hostpn-financial-record-modal').fadeOut(300);
			$('body').css('overflow', '');
		}
	};

	// Initialize on document ready
	$(document).ready(function() {
		HOSTPN_Public_Financial.init();
	});

})(jQuery);

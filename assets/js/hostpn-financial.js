/**
 * HOSTPN Financial Management JavaScript
 *
 * Handles CSV upload, import, dashboard loading, filters, and CRUD operations.
 *
 * @package    hostpn
 * @subpackage hostpn/assets/js
 * @since      1.0.36
 */

(function($) {
  'use strict';

  var HOSTPN_Financial = {

    /**
     * Initialize financial management
     */
    init: function() {
      this.bindEvents();
      this.loadDashboardOnPageLoad();
    },

    /**
     * Bind event handlers
     */
    bindEvents: function() {
      var self = this;

      // Import button click
      $(document).on('click', '.hostpn-financial-import-btn', function(e) {
        e.preventDefault();
        self.openImportModal();
      });

      // File input change
      $(document).on('change', '#hostpn_financial_csv_file', function(e) {
        self.handleFileSelect(e);
      });

      // Drag and drop
      $(document).on('dragover', '#hostpn-financial-drop-zone', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('hostpn-drag-over');
      });

      $(document).on('dragleave', '#hostpn-financial-drop-zone', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('hostpn-drag-over');
      });

      $(document).on('drop', '#hostpn-financial-drop-zone', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('hostpn-drag-over');

        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
          $('#hostpn_financial_csv_file')[0].files = files;
          self.handleFileSelect({ target: { files: files } });
        }
      });

      // Import confirm
      $(document).on('click', '.hostpn-financial-import-confirm', function(e) {
        e.preventDefault();
        self.confirmImport();
      });

      // Import cancel/retry
      $(document).on('click', '.hostpn-financial-import-cancel, .hostpn-financial-import-retry', function(e) {
        e.preventDefault();
        self.resetImportModal();
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

      // Record edit
      $(document).on('click', '.hostpn-financial-record-edit', function(e) {
        e.preventDefault();
        var recordId = $(this).data('record-id');
        self.openEditModal(recordId);
      });

      // Record save
      $(document).on('submit', '#hostpn-financial-record-edit-form', function(e) {
        e.preventDefault();
        self.saveRecord();
      });

      // Record delete
      $(document).on('click', '.hostpn-financial-record-delete', function(e) {
        e.preventDefault();
        var recordId = $(this).data('record-id');
        if (confirm(hostpn_ajax.translations.confirm_delete || 'Are you sure you want to delete this record?')) {
          self.deleteRecord(recordId);
        }
      });

      // Batch delete
      $(document).on('click', '.hostpn-financial-batch-delete', function(e) {
        e.preventDefault();
        var batchId = $(this).data('batch-id');
        if (confirm(hostpn_ajax.translations.confirm_batch_delete || 'Are you sure you want to delete all records from this import batch?')) {
          self.deleteBatch(batchId);
        }
      });

      // Export
      $(document).on('click', '.hostpn-financial-export-btn', function(e) {
        e.preventDefault();
        self.exportToCSV();
      });
    },

    /**
     * Load dashboard on page load if we're on accommodation edit page
     */
    loadDashboardOnPageLoad: function() {
      var $dashboard = $('#hostpn-financial-dashboard');
      if ($dashboard.length > 0 && $dashboard.data('accommodation-id')) {
        this.loadDashboard();
      }
    },

    /**
     * Load financial dashboard via AJAX
     */
    loadDashboard: function(page) {
      var self = this;
      var $dashboard = $('#hostpn-financial-dashboard');
      var accommodationId = $dashboard.data('accommodation-id');

      if (!accommodationId) {
        return;
      }

      // Collect filters
      var filters = {
        year: $('.hostpn-financial-filter[data-filter="year"]').val(),
        quarter: $('.hostpn-financial-filter[data-filter="quarter"]').val(),
        platform: $('.hostpn-financial-filter[data-filter="platform"]').val(),
        type: $('.hostpn-financial-filter[data-filter="type"]').val(),
        page: page || 1
      };

      // Show loading
      $dashboard.html(hostpn_ajax.popup_loader);

      $.ajax({
        url: hostpn_ajax.ajax_url,
        type: 'POST',
        data: {
          action: 'hostpn_ajax',
          hostpn_ajax_type: 'hostpn_financial_dashboard_load',
          hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
          hostpn_accommodation_id: accommodationId,
          year: filters.year,
          quarter: filters.quarter,
          platform: filters.platform,
          type: filters.type,
          page: filters.page
        },
        success: function(response) {
          var data = typeof response === 'string' ? JSON.parse(response) : response;

          if (data.error_key) {
            self.showError($dashboard, data.error_content || 'Error loading dashboard');
          } else {
            $dashboard.html(data.html);
          }
        },
        error: function() {
          self.showError($dashboard, 'Failed to load financial dashboard');
        }
      });
    },

    /**
     * Open import modal
     */
    openImportModal: function() {
      var self = this;
      var $modal = $('#hostpn-popup-financial-import');
      var accommodationId = $('.hostpn-financial-import-btn').data('accommodation-id');

      // Load modal content
      $.ajax({
        url: hostpn_ajax.ajax_url,
        type: 'POST',
        data: {
          action: 'hostpn_ajax',
          hostpn_ajax_type: 'hostpn_financial_import_form',
          hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
          hostpn_accommodation_id: accommodationId
        },
        success: function(response) {
          // Load template directly
          $.get(hostpn_ajax.plugin_url + '/templates/admin/financial/hostpn-financial-import.php', function(html) {
            $modal.html(html).fadeIn();
          }).fail(function() {
            // Fallback: create simple modal
            $modal.html(self.getImportModalHTML()).fadeIn();
          });
        }
      });
    },

    /**
     * Get import modal HTML (fallback)
     */
    getImportModalHTML: function() {
      return '<div class="hostpn-popup-content">' +
        '<div class="hostpn-popup-header">' +
        '<h2>Import Financial CSV</h2>' +
        '<button type="button" class="hostpn-popup-close"><i class="material-icons-outlined">close</i></button>' +
        '</div>' +
        '<div class="hostpn-popup-body">' +
        '<div class="hostpn-financial-import-step hostpn-financial-import-step-1">' +
        '<p>Upload a CSV file from Airbnb or Booking.com.</p>' +
        '<input type="file" id="hostpn_financial_csv_file" accept=".csv" />' +
        '</div>' +
        '<div class="hostpn-financial-import-step hostpn-financial-import-step-2" style="display:none;"></div>' +
        '<div class="hostpn-financial-import-step hostpn-financial-import-step-3" style="display:none;"></div>' +
        '<div class="hostpn-financial-import-step hostpn-financial-import-step-4" style="display:none;"></div>' +
        '<div class="hostpn-financial-import-error" style="display:none;"></div>' +
        '</div>' +
        '</div>';
    },

    /**
     * Handle file selection
     */
    handleFileSelect: function(e) {
      var self = this;
      var file = e.target.files[0];

      if (!file) {
        return;
      }

      // Validate file type
      if (!file.name.toLowerCase().endsWith('.csv')) {
        alert('Please select a CSV file');
        return;
      }

      // Validate file size (5MB max)
      if (file.size > 5 * 1024 * 1024) {
        alert('File size exceeds 5MB limit');
        return;
      }

      // Show progress
      $('.hostpn-financial-import-step-1').hide();
      $('.hostpn-financial-upload-progress').show();

      // Upload file
      var formData = new FormData();
      formData.append('action', 'hostpn_ajax');
      formData.append('hostpn_ajax_type', 'hostpn_financial_upload');
      formData.append('hostpn_ajax_nonce', hostpn_ajax.hostpn_ajax_nonce);
      formData.append('hostpn_financial_csv_file', file);

      $.ajax({
        url: hostpn_ajax.ajax_url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          var data = typeof response === 'string' ? JSON.parse(response) : response;

          if (data.error_key) {
            self.showImportError(data.error_content || 'Upload failed');
          } else {
            self.showPreview(data);
          }
        },
        error: function() {
          self.showImportError('Upload failed');
        }
      });
    },

    /**
     * Show preview of uploaded file
     */
    showPreview: function(data) {
      $('.hostpn-financial-upload-progress').hide();
      $('.hostpn-financial-import-step-2').show();

      // Display detected format
      $('#hostpn-financial-detected-format-text')
        .text(data.detected_format.replace('_', ' ').toUpperCase())
        .addClass('hostpn-financial-badge-' + data.detected_format);

      // Display total count
      $('#hostpn-financial-detected-count-text').text(data.total_rows);

      // Display preview table
      var previewHTML = '<table class="hostpn-financial-table"><tbody>';
      if (data.preview_rows && data.preview_rows.length > 0) {
        data.preview_rows.forEach(function(row) {
          previewHTML += '<tr>';
          Object.values(row).slice(0, 5).forEach(function(value) {
            previewHTML += '<td>' + (value || '-') + '</td>';
          });
          previewHTML += '</tr>';
        });
      }
      previewHTML += '</tbody></table>';
      $('#hostpn-financial-preview-table').html(previewHTML);

      // Store temp file info
      $('#hostpn-financial-upload-form').data('temp-file', data.temp_file);
    },

    /**
     * Confirm and start import
     */
    confirmImport: function() {
      var self = this;
      var tempFile = $('#hostpn-financial-upload-form').data('temp-file');
      var accommodationId = $('.hostpn-financial-import-btn').data('accommodation-id');

      if (!tempFile) {
        self.showImportError('No file to import');
        return;
      }

      // Show progress
      $('.hostpn-financial-import-step-2').hide();
      $('.hostpn-financial-import-step-3').show();

      $.ajax({
        url: hostpn_ajax.ajax_url,
        type: 'POST',
        data: {
          action: 'hostpn_ajax',
          hostpn_ajax_type: 'hostpn_financial_import_confirmed',
          hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
          hostpn_accommodation_id: accommodationId,
          temp_file: tempFile
        },
        success: function(response) {
          var data = typeof response === 'string' ? JSON.parse(response) : response;

          if (data.error_key) {
            self.showImportError(data.error_content || 'Import failed');
          } else {
            self.showImportResults(data);
          }
        },
        error: function() {
          self.showImportError('Import failed');
        }
      });
    },

    /**
     * Show import results
     */
    showImportResults: function(data) {
      $('.hostpn-financial-import-step-3').hide();
      $('.hostpn-financial-import-step-4').show();

      $('#hostpn-financial-imported-count').text(data.imported);
      $('#hostpn-financial-skipped-count').text(data.skipped);

      if (data.errors && data.errors.length > 0) {
        var errorHTML = '';
        data.errors.forEach(function(error) {
          errorHTML += '<li>' + error + '</li>';
        });
        $('#hostpn-financial-error-list').html(errorHTML);
        $('#hostpn-financial-import-errors').show();
      }

      // Reload dashboard after successful import
      this.loadDashboard();
    },

    /**
     * Show import error
     */
    showImportError: function(message) {
      $('.hostpn-financial-import-step').hide();
      $('.hostpn-financial-upload-progress').hide();
      $('.hostpn-financial-import-error').show();
      $('#hostpn-financial-error-message').text(message);
    },

    /**
     * Reset import modal to initial state
     */
    resetImportModal: function() {
      $('.hostpn-financial-import-step').hide();
      $('.hostpn-financial-upload-progress').hide();
      $('.hostpn-financial-import-error').hide();
      $('.hostpn-financial-import-step-1').show();
      $('#hostpn_financial_csv_file').val('');
      $('#hostpn-financial-upload-form').removeData('temp-file');
    },

    /**
     * Open edit modal for a record
     */
    openEditModal: function(recordId) {
      var self = this;
      var $modal = $('#hostpn-popup-financial-edit');

      // Show loading
      $modal.html(hostpn_ajax.popup_loader).fadeIn();

      $.ajax({
        url: hostpn_ajax.ajax_url,
        type: 'POST',
        data: {
          action: 'hostpn_ajax',
          hostpn_ajax_type: 'hostpn_financial_record_edit',
          hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
          record_id: recordId
        },
        success: function(response) {
          var data = typeof response === 'string' ? JSON.parse(response) : response;

          if (data.error_key) {
            $modal.html('<p>' + (data.error_content || 'Error loading record') + '</p>');
          } else {
            $modal.html(data.html);
          }
        },
        error: function() {
          $modal.html('<p>Failed to load record</p>');
        }
      });
    },

    /**
     * Save edited record
     */
    saveRecord: function() {
      var self = this;
      var $form = $('#hostpn-financial-record-edit-form');
      var formData = $form.serializeArray();

      // Add AJAX parameters
      formData.push(
        { name: 'action', value: 'hostpn_ajax' },
        { name: 'hostpn_ajax_type', value: 'hostpn_financial_record_save' },
        { name: 'hostpn_ajax_nonce', value: hostpn_ajax.hostpn_ajax_nonce }
      );

      // Show loading
      $form.hide();
      $('.hostpn-financial-edit-loading').show();

      $.ajax({
        url: hostpn_ajax.ajax_url,
        type: 'POST',
        data: $.param(formData),
        success: function(response) {
          var data = typeof response === 'string' ? JSON.parse(response) : response;

          if (data.error_key) {
            alert(data.error_content || 'Failed to save record');
            $form.show();
            $('.hostpn-financial-edit-loading').hide();
          } else {
            // Close modal and reload dashboard
            $('#hostpn-popup-financial-edit').fadeOut();
            self.loadDashboard();
          }
        },
        error: function() {
          alert('Failed to save record');
          $form.show();
          $('.hostpn-financial-edit-loading').hide();
        }
      });
    },

    /**
     * Delete a record
     */
    deleteRecord: function(recordId) {
      var self = this;

      $.ajax({
        url: hostpn_ajax.ajax_url,
        type: 'POST',
        data: {
          action: 'hostpn_ajax',
          hostpn_ajax_type: 'hostpn_financial_record_delete',
          hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
          record_id: recordId
        },
        success: function(response) {
          var data = typeof response === 'string' ? JSON.parse(response) : response;

          if (data.error_key) {
            alert(data.error_content || 'Failed to delete record');
          } else {
            self.loadDashboard();
          }
        },
        error: function() {
          alert('Failed to delete record');
        }
      });
    },

    /**
     * Delete an import batch
     */
    deleteBatch: function(batchId) {
      var self = this;
      var accommodationId = $('#hostpn-financial-dashboard').data('accommodation-id');

      $.ajax({
        url: hostpn_ajax.ajax_url,
        type: 'POST',
        data: {
          action: 'hostpn_ajax',
          hostpn_ajax_type: 'hostpn_financial_batch_delete',
          hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
          hostpn_accommodation_id: accommodationId,
          batch_id: batchId
        },
        success: function(response) {
          var data = typeof response === 'string' ? JSON.parse(response) : response;

          if (data.error_key) {
            alert(data.error_content || 'Failed to delete batch');
          } else {
            alert(data.message || 'Batch deleted successfully');
            self.loadDashboard();
          }
        },
        error: function() {
          alert('Failed to delete batch');
        }
      });
    },

    /**
     * Export records to CSV
     */
    exportToCSV: function() {
      var accommodationId = $('#hostpn-financial-dashboard').data('accommodation-id');

      // Collect filters
      var filters = {
        year: $('.hostpn-financial-filter[data-filter="year"]').val(),
        quarter: $('.hostpn-financial-filter[data-filter="quarter"]').val(),
        platform: $('.hostpn-financial-filter[data-filter="platform"]').val(),
        type: $('.hostpn-financial-filter[data-filter="type"]').val()
      };

      // Create form and submit
      var $form = $('<form>', {
        method: 'POST',
        action: hostpn_ajax.ajax_url
      });

      $form.append($('<input>', { type: 'hidden', name: 'action', value: 'hostpn_ajax' }));
      $form.append($('<input>', { type: 'hidden', name: 'hostpn_ajax_type', value: 'hostpn_financial_export' }));
      $form.append($('<input>', { type: 'hidden', name: 'hostpn_ajax_nonce', value: hostpn_ajax.hostpn_ajax_nonce }));
      $form.append($('<input>', { type: 'hidden', name: 'hostpn_accommodation_id', value: accommodationId }));
      $form.append($('<input>', { type: 'hidden', name: 'year', value: filters.year }));
      $form.append($('<input>', { type: 'hidden', name: 'quarter', value: filters.quarter }));
      $form.append($('<input>', { type: 'hidden', name: 'platform', value: filters.platform }));
      $form.append($('<input>', { type: 'hidden', name: 'type', value: filters.type }));

      $form.appendTo('body').submit().remove();
    },

    /**
     * Show error message
     */
    showError: function($container, message) {
      $container.html(
        '<div class="hostpn-alert hostpn-alert-error hostpn-mt-20">' +
        '<i class="material-icons-outlined">error</i> ' +
        '<span>' + message + '</span>' +
        '</div>'
      );
    }
  };

  // Initialize when document is ready
  $(document).ready(function() {
    HOSTPN_Financial.init();
  });

})(jQuery);

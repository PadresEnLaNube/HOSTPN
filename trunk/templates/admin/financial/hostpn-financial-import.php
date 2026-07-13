<?php
/**
 * Financial CSV Import Modal Template
 *
 * Modal for uploading and importing CSV files.
 *
 * @package    hostpn
 * @subpackage hostpn/templates/admin/financial
 * @since      1.0.36
 */

// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

$accommodation_id = !empty($hostpn_accommodation_id) ? $hostpn_accommodation_id : 0;
?>

<div class="hostpn-popup-content hostpn-financial-import-modal">
  <div class="hostpn-popup-header">
    <h2><?php esc_html_e('Import Financial CSV', 'hostpn'); ?></h2>
    <button type="button" class="hostpn-popup-close">
      <i class="material-icons-outlined">close</i>
    </button>
  </div>

  <div class="hostpn-popup-body">
    <!-- Step 1: File Upload -->
    <div class="hostpn-financial-import-step hostpn-financial-import-step-1">
      <p><?php esc_html_e('Upload a CSV file from Airbnb or Booking.com. The file format will be automatically detected.', 'hostpn'); ?></p>

      <div class="hostpn-financial-import-supported">
        <strong><?php esc_html_e('Supported formats:', 'hostpn'); ?></strong>
        <ul>
          <li><?php esc_html_e('Airbnb Fiscal Documents (quarterly data)', 'hostpn'); ?></li>
          <li><?php esc_html_e('Airbnb Invoices (individual reservations)', 'hostpn'); ?></li>
          <li><?php esc_html_e('Booking.com Reservations', 'hostpn'); ?></li>
        </ul>
      </div>

      <form id="hostpn-financial-upload-form" class="hostpn-mt-20">
        <div class="hostpn-financial-file-drop-zone" id="hostpn-financial-drop-zone">
          <i class="material-icons-outlined hostpn-financial-drop-icon">cloud_upload</i>
          <p class="hostpn-financial-drop-text">
            <?php esc_html_e('Drag and drop CSV file here or', 'hostpn'); ?>
            <label for="hostpn_financial_csv_file" class="hostpn-financial-file-label">
              <?php esc_html_e('browse', 'hostpn'); ?>
            </label>
          </p>
          <input type="file"
                 id="hostpn_financial_csv_file"
                 name="hostpn_financial_csv_file"
                 accept=".csv"
                 class="hostpn-financial-file-input"
                 style="display: none;" />
          <p class="hostpn-financial-file-limits">
            <?php esc_html_e('Maximum file size: 5MB', 'hostpn'); ?>
          </p>
        </div>

        <input type="hidden" name="hostpn_accommodation_id" value="<?php echo esc_attr($accommodation_id); ?>" />
        <input type="hidden" name="hostpn_ajax_nonce" value="<?php echo esc_attr(wp_create_nonce('hostpn-nonce')); ?>" />
      </form>

      <div class="hostpn-financial-upload-progress" style="display: none;">
        <?php HOSTPN_Data::hostpn_popup_loader(); ?>
      </div>
    </div>

    <!-- Step 2: Preview and Confirmation -->
    <div class="hostpn-financial-import-step hostpn-financial-import-step-2" style="display: none;">
      <div class="hostpn-financial-import-detected">
        <div class="hostpn-financial-detected-format">
          <strong><?php esc_html_e('Detected format:', 'hostpn'); ?></strong>
          <span id="hostpn-financial-detected-format-text" class="hostpn-financial-badge"></span>
        </div>
        <div class="hostpn-financial-detected-count">
          <strong><?php esc_html_e('Total records:', 'hostpn'); ?></strong>
          <span id="hostpn-financial-detected-count-text"></span>
        </div>
      </div>

      <div class="hostpn-financial-import-preview hostpn-mt-20">
        <h4><?php esc_html_e('Preview (first 5 rows):', 'hostpn'); ?></h4>
        <div id="hostpn-financial-preview-table" class="hostpn-financial-preview-wrapper"></div>
      </div>

      <div class="hostpn-financial-import-actions hostpn-mt-20 hostpn-text-align-right">
        <button type="button" class="hostpn-btn hostpn-financial-import-cancel">
          <?php esc_html_e('Cancel', 'hostpn'); ?>
        </button>
        <button type="button" class="hostpn-btn hostpn-financial-import-confirm">
          <?php esc_html_e('Import Records', 'hostpn'); ?>
        </button>
      </div>
    </div>

    <!-- Step 3: Import Progress -->
    <div class="hostpn-financial-import-step hostpn-financial-import-step-3" style="display: none;">
      <?php HOSTPN_Data::hostpn_popup_loader(); ?>
    </div>

    <!-- Step 4: Import Results -->
    <div class="hostpn-financial-import-step hostpn-financial-import-step-4" style="display: none;">
      <div id="hostpn-financial-import-results">
        <div class="hostpn-financial-import-success">
          <i class="material-icons-outlined hostpn-financial-success-icon">check_circle</i>
          <h3><?php esc_html_e('Import Completed!', 'hostpn'); ?></h3>
          <div class="hostpn-financial-import-stats">
            <p>
              <strong><?php esc_html_e('Successfully imported:', 'hostpn'); ?></strong>
              <span id="hostpn-financial-imported-count"></span>
            </p>
            <p>
              <strong><?php esc_html_e('Skipped:', 'hostpn'); ?></strong>
              <span id="hostpn-financial-skipped-count"></span>
            </p>
          </div>
          <div id="hostpn-financial-import-errors" class="hostpn-mt-20" style="display: none;">
            <h4><?php esc_html_e('Errors:', 'hostpn'); ?></h4>
            <ul id="hostpn-financial-error-list"></ul>
          </div>
        </div>

        <div class="hostpn-financial-import-actions hostpn-mt-20 hostpn-text-align-right">
          <button type="button" class="hostpn-btn hostpn-popup-close">
            <?php esc_html_e('Close', 'hostpn'); ?>
          </button>
        </div>
      </div>
    </div>

    <!-- Error Display -->
    <div class="hostpn-financial-import-error" style="display: none;">
      <div class="hostpn-alert hostpn-alert-error">
        <i class="material-icons-outlined">error</i>
        <span id="hostpn-financial-error-message"></span>
      </div>
      <div class="hostpn-text-align-right hostpn-mt-20">
        <button type="button" class="hostpn-btn hostpn-financial-import-retry">
          <?php esc_html_e('Try Again', 'hostpn'); ?>
        </button>
      </div>
    </div>
  </div>
</div>

<?php
/**
 * Financial Record Edit Form Template
 *
 * Form for editing a financial record.
 *
 * @package    hostpn
 * @subpackage hostpn/templates/admin/financial
 * @since      1.0.36
 */

// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

// Variables should be set before including this template:
// $record_data - array with record details

if (empty($record_data)) {
  echo '<p>' . esc_html(__('Record not found.', 'hostpn')) . '</p>';
  return;
}

$record_id = $record_data['id'];
$meta = $record_data['meta'];
?>

<div class="hostpn-popup-content hostpn-financial-edit-modal">
  <div class="hostpn-popup-header">
    <h2><?php esc_html_e('Edit Financial Record', 'hostpn'); ?></h2>
    <button type="button" class="hostpn-popup-close">
      <i class="material-icons-outlined">close</i>
    </button>
  </div>

  <div class="hostpn-popup-body">
    <form id="hostpn-financial-record-edit-form" data-record-id="<?php echo esc_attr($record_id); ?>">

      <div class="hostpn-financial-edit-row">
        <div class="hostpn-financial-edit-col">
          <label for="hostpn_financial_record_type"><?php esc_html_e('Record Type', 'hostpn'); ?> *</label>
          <select id="hostpn_financial_record_type" name="hostpn_financial_record_type" class="hostpn-select hostpn-width-100-percent" required>
            <option value="income" <?php selected(!empty($meta['hostpn_financial_record_type']) ? $meta['hostpn_financial_record_type'] : '', 'income'); ?>>
              <?php esc_html_e('Income', 'hostpn'); ?>
            </option>
            <option value="expense" <?php selected(!empty($meta['hostpn_financial_record_type']) ? $meta['hostpn_financial_record_type'] : '', 'expense'); ?>>
              <?php esc_html_e('Expense', 'hostpn'); ?>
            </option>
            <option value="tax" <?php selected(!empty($meta['hostpn_financial_record_type']) ? $meta['hostpn_financial_record_type'] : '', 'tax'); ?>>
              <?php esc_html_e('Tax', 'hostpn'); ?>
            </option>
            <option value="fee" <?php selected(!empty($meta['hostpn_financial_record_type']) ? $meta['hostpn_financial_record_type'] : '', 'fee'); ?>>
              <?php esc_html_e('Fee', 'hostpn'); ?>
            </option>
          </select>
        </div>

        <div class="hostpn-financial-edit-col">
          <label for="hostpn_financial_platform"><?php esc_html_e('Platform', 'hostpn'); ?> *</label>
          <select id="hostpn_financial_platform" name="hostpn_financial_platform" class="hostpn-select hostpn-width-100-percent" required>
            <option value="airbnb" <?php selected(!empty($meta['hostpn_financial_platform']) ? $meta['hostpn_financial_platform'] : '', 'airbnb'); ?>>
              <?php esc_html_e('Airbnb', 'hostpn'); ?>
            </option>
            <option value="booking" <?php selected(!empty($meta['hostpn_financial_platform']) ? $meta['hostpn_financial_platform'] : '', 'booking'); ?>>
              <?php esc_html_e('Booking.com', 'hostpn'); ?>
            </option>
            <option value="manual" <?php selected(!empty($meta['hostpn_financial_platform']) ? $meta['hostpn_financial_platform'] : '', 'manual'); ?>>
              <?php esc_html_e('Manual', 'hostpn'); ?>
            </option>
          </select>
        </div>
      </div>

      <div class="hostpn-financial-edit-row">
        <div class="hostpn-financial-edit-col">
          <label for="hostpn_financial_date"><?php esc_html_e('Transaction Date', 'hostpn'); ?></label>
          <input type="date"
                 id="hostpn_financial_date"
                 name="hostpn_financial_date"
                 class="hostpn-input hostpn-width-100-percent"
                 value="<?php echo esc_attr(!empty($meta['hostpn_financial_date']) ? $meta['hostpn_financial_date'] : ''); ?>" />
        </div>

        <div class="hostpn-financial-edit-col">
          <label for="hostpn_financial_currency"><?php esc_html_e('Currency', 'hostpn'); ?></label>
          <input type="text"
                 id="hostpn_financial_currency"
                 name="hostpn_financial_currency"
                 class="hostpn-input hostpn-width-100-percent"
                 value="<?php echo esc_attr(!empty($meta['hostpn_financial_currency']) ? $meta['hostpn_financial_currency'] : 'EUR'); ?>"
                 maxlength="3" />
        </div>
      </div>

      <div class="hostpn-financial-edit-row">
        <div class="hostpn-financial-edit-col">
          <label for="hostpn_financial_amount"><?php esc_html_e('Total Amount', 'hostpn'); ?> *</label>
          <input type="number"
                 id="hostpn_financial_amount"
                 name="hostpn_financial_amount"
                 class="hostpn-input hostpn-width-100-percent"
                 value="<?php echo esc_attr(!empty($meta['hostpn_financial_amount']) ? $meta['hostpn_financial_amount'] : ''); ?>"
                 step="0.01"
                 required />
        </div>

        <div class="hostpn-financial-edit-col">
          <label for="hostpn_financial_net_amount"><?php esc_html_e('Net Amount', 'hostpn'); ?></label>
          <input type="number"
                 id="hostpn_financial_net_amount"
                 name="hostpn_financial_net_amount"
                 class="hostpn-input hostpn-width-100-percent"
                 value="<?php echo esc_attr(!empty($meta['hostpn_financial_net_amount']) ? $meta['hostpn_financial_net_amount'] : ''); ?>"
                 step="0.01" />
        </div>
      </div>

      <div class="hostpn-financial-edit-row">
        <div class="hostpn-financial-edit-col">
          <label for="hostpn_financial_tax_amount"><?php esc_html_e('Tax Amount', 'hostpn'); ?></label>
          <input type="number"
                 id="hostpn_financial_tax_amount"
                 name="hostpn_financial_tax_amount"
                 class="hostpn-input hostpn-width-100-percent"
                 value="<?php echo esc_attr(!empty($meta['hostpn_financial_tax_amount']) ? $meta['hostpn_financial_tax_amount'] : ''); ?>"
                 step="0.01" />
        </div>

        <div class="hostpn-financial-edit-col">
          <label for="hostpn_financial_fee_amount"><?php esc_html_e('Fee Amount', 'hostpn'); ?></label>
          <input type="number"
                 id="hostpn_financial_fee_amount"
                 name="hostpn_financial_fee_amount"
                 class="hostpn-input hostpn-width-100-percent"
                 value="<?php echo esc_attr(!empty($meta['hostpn_financial_fee_amount']) ? $meta['hostpn_financial_fee_amount'] : ''); ?>"
                 step="0.01" />
        </div>
      </div>

      <div class="hostpn-financial-edit-row">
        <div class="hostpn-financial-edit-col">
          <label for="hostpn_financial_year"><?php esc_html_e('Year', 'hostpn'); ?></label>
          <input type="number"
                 id="hostpn_financial_year"
                 name="hostpn_financial_year"
                 class="hostpn-input hostpn-width-100-percent"
                 value="<?php echo esc_attr(!empty($meta['hostpn_financial_year']) ? $meta['hostpn_financial_year'] : date('Y')); ?>"
                 min="2000"
                 max="2099" />
        </div>

        <div class="hostpn-financial-edit-col">
          <label for="hostpn_financial_quarter"><?php esc_html_e('Quarter', 'hostpn'); ?></label>
          <select id="hostpn_financial_quarter" name="hostpn_financial_quarter" class="hostpn-select hostpn-width-100-percent">
            <option value=""><?php esc_html_e('- Select -', 'hostpn'); ?></option>
            <option value="Q1" <?php selected(!empty($meta['hostpn_financial_quarter']) ? $meta['hostpn_financial_quarter'] : '', 'Q1'); ?>>Q1</option>
            <option value="Q2" <?php selected(!empty($meta['hostpn_financial_quarter']) ? $meta['hostpn_financial_quarter'] : '', 'Q2'); ?>>Q2</option>
            <option value="Q3" <?php selected(!empty($meta['hostpn_financial_quarter']) ? $meta['hostpn_financial_quarter'] : '', 'Q3'); ?>>Q3</option>
            <option value="Q4" <?php selected(!empty($meta['hostpn_financial_quarter']) ? $meta['hostpn_financial_quarter'] : '', 'Q4'); ?>>Q4</option>
          </select>
        </div>
      </div>

      <input type="hidden" name="record_id" value="<?php echo esc_attr($record_id); ?>" />
      <input type="hidden" name="hostpn_ajax_nonce" value="<?php echo esc_attr(wp_create_nonce('hostpn-nonce')); ?>" />

      <div class="hostpn-financial-edit-actions hostpn-mt-20 hostpn-text-align-right">
        <button type="button" class="hostpn-btn hostpn-popup-close">
          <?php esc_html_e('Cancel', 'hostpn'); ?>
        </button>
        <button type="submit" class="hostpn-btn hostpn-financial-record-save-btn">
          <?php esc_html_e('Save Changes', 'hostpn'); ?>
        </button>
      </div>
    </form>

    <div class="hostpn-financial-edit-loading" style="display: none;">
      <?php HOSTPN_Data::hostpn_popup_loader(); ?>
    </div>
  </div>
</div>

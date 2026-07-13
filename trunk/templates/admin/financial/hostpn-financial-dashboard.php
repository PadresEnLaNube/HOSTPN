<?php
/**
 * Financial Dashboard Template
 *
 * Displays summary cards, filters, data table, and quarterly breakdown.
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
// $dashboard_data - array with 'totals', 'records', 'pagination', 'quarterly', 'batches'
// $read_only - boolean (optional, defaults to false)

$read_only = isset($read_only) ? $read_only : false;
$accommodation_id = !empty($hostpn_accommodation_id) ? $hostpn_accommodation_id : 0;

if (empty($dashboard_data)) {
  echo '<p>' . esc_html(__('No financial data available yet. Import a CSV file to get started.', 'hostpn')) . '</p>';
  return;
}

$totals = $dashboard_data['totals'];
$records = $dashboard_data['records'];
$pagination = $dashboard_data['pagination'];
$quarterly = $dashboard_data['quarterly'];
$batches = $dashboard_data['batches'];
$currency = !empty($totals['currency']) ? $totals['currency'] : 'EUR';

// Get available years for filter
$available_years = HOSTPN_Financial::get_available_years($accommodation_id);
?>

<div class="hostpn-financial-dashboard-wrapper">

  <!-- Summary Cards -->
  <div class="hostpn-financial-summary-cards hostpn-mb-20">
    <div class="hostpn-financial-card hostpn-financial-card-income">
      <div class="hostpn-financial-card-icon">
        <i class="material-icons-outlined">trending_up</i>
      </div>
      <div class="hostpn-financial-card-content">
        <div class="hostpn-financial-card-label"><?php esc_html_e('Total Income', 'hostpn'); ?></div>
        <div class="hostpn-financial-card-value">
          <?php echo esc_html(number_format($totals['income'], 2, ',', '.') . ' ' . $currency); ?>
        </div>
      </div>
    </div>

    <div class="hostpn-financial-card hostpn-financial-card-expenses">
      <div class="hostpn-financial-card-icon">
        <i class="material-icons-outlined">trending_down</i>
      </div>
      <div class="hostpn-financial-card-content">
        <div class="hostpn-financial-card-label"><?php esc_html_e('Total Expenses', 'hostpn'); ?></div>
        <div class="hostpn-financial-card-value">
          <?php echo esc_html(number_format($totals['expenses'] + $totals['tax'] + $totals['fees'], 2, ',', '.') . ' ' . $currency); ?>
        </div>
      </div>
    </div>

    <div class="hostpn-financial-card hostpn-financial-card-net">
      <div class="hostpn-financial-card-icon">
        <i class="material-icons-outlined">account_balance_wallet</i>
      </div>
      <div class="hostpn-financial-card-content">
        <div class="hostpn-financial-card-label"><?php esc_html_e('Net Amount', 'hostpn'); ?></div>
        <div class="hostpn-financial-card-value">
          <?php echo esc_html(number_format($totals['net'], 2, ',', '.') . ' ' . $currency); ?>
        </div>
      </div>
    </div>

    <div class="hostpn-financial-card hostpn-financial-card-records">
      <div class="hostpn-financial-card-icon">
        <i class="material-icons-outlined">receipt_long</i>
      </div>
      <div class="hostpn-financial-card-content">
        <div class="hostpn-financial-card-label"><?php esc_html_e('Total Records', 'hostpn'); ?></div>
        <div class="hostpn-financial-card-value">
          <?php echo esc_html($totals['record_count']); ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Filters -->
  <div class="hostpn-financial-filters hostpn-mb-20">
    <div class="hostpn-financial-filters-row">
      <div class="hostpn-financial-filter-item">
        <label><?php esc_html_e('Year', 'hostpn'); ?></label>
        <select class="hostpn-select hostpn-financial-filter" data-filter="year">
          <option value=""><?php esc_html_e('All Years', 'hostpn'); ?></option>
          <?php foreach ($available_years as $year) : ?>
            <option value="<?php echo esc_attr($year); ?>"><?php echo esc_html($year); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="hostpn-financial-filter-item">
        <label><?php esc_html_e('Quarter', 'hostpn'); ?></label>
        <select class="hostpn-select hostpn-financial-filter" data-filter="quarter">
          <option value=""><?php esc_html_e('All Quarters', 'hostpn'); ?></option>
          <option value="Q1"><?php esc_html_e('Q1', 'hostpn'); ?></option>
          <option value="Q2"><?php esc_html_e('Q2', 'hostpn'); ?></option>
          <option value="Q3"><?php esc_html_e('Q3', 'hostpn'); ?></option>
          <option value="Q4"><?php esc_html_e('Q4', 'hostpn'); ?></option>
        </select>
      </div>

      <div class="hostpn-financial-filter-item">
        <label><?php esc_html_e('Platform', 'hostpn'); ?></label>
        <select class="hostpn-select hostpn-financial-filter" data-filter="platform">
          <option value=""><?php esc_html_e('All Platforms', 'hostpn'); ?></option>
          <option value="airbnb"><?php esc_html_e('Airbnb', 'hostpn'); ?></option>
          <option value="booking"><?php esc_html_e('Booking.com', 'hostpn'); ?></option>
          <option value="manual"><?php esc_html_e('Manual', 'hostpn'); ?></option>
        </select>
      </div>

      <div class="hostpn-financial-filter-item">
        <label><?php esc_html_e('Type', 'hostpn'); ?></label>
        <select class="hostpn-select hostpn-financial-filter" data-filter="type">
          <option value=""><?php esc_html_e('All Types', 'hostpn'); ?></option>
          <option value="income"><?php esc_html_e('Income', 'hostpn'); ?></option>
          <option value="expense"><?php esc_html_e('Expense', 'hostpn'); ?></option>
          <option value="tax"><?php esc_html_e('Tax', 'hostpn'); ?></option>
          <option value="fee"><?php esc_html_e('Fee', 'hostpn'); ?></option>
        </select>
      </div>

      <div class="hostpn-financial-filter-item">
        <button type="button" class="hostpn-btn hostpn-btn-mini hostpn-financial-filter-reset">
          <?php esc_html_e('Reset Filters', 'hostpn'); ?>
        </button>
      </div>
    </div>
  </div>

  <!-- Data Table -->
  <div class="hostpn-financial-table-wrapper hostpn-mb-20">
    <?php if (!empty($records)) : ?>
      <table class="hostpn-financial-table">
        <thead>
          <tr>
            <th class="hostpn-financial-th-date"><?php esc_html_e('Date', 'hostpn'); ?></th>
            <th class="hostpn-financial-th-platform"><?php esc_html_e('Platform', 'hostpn'); ?></th>
            <th class="hostpn-financial-th-type"><?php esc_html_e('Type', 'hostpn'); ?></th>
            <th class="hostpn-financial-th-description"><?php esc_html_e('Description', 'hostpn'); ?></th>
            <th class="hostpn-financial-th-amount hostpn-text-align-right"><?php esc_html_e('Amount', 'hostpn'); ?></th>
            <?php if (!$read_only) : ?>
              <th class="hostpn-financial-th-actions hostpn-text-align-center"><?php esc_html_e('Actions', 'hostpn'); ?></th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($records as $record) : ?>
            <tr data-record-id="<?php echo esc_attr($record['id']); ?>">
              <td class="hostpn-financial-td-date">
                <?php echo esc_html($record['date'] ? date('d/m/Y', strtotime($record['date'])) : '-'); ?>
              </td>
              <td class="hostpn-financial-td-platform">
                <span class="hostpn-financial-badge hostpn-financial-badge-<?php echo esc_attr($record['platform']); ?>">
                  <?php echo esc_html(ucfirst($record['platform'])); ?>
                </span>
              </td>
              <td class="hostpn-financial-td-type">
                <span class="hostpn-financial-badge hostpn-financial-badge-<?php echo esc_attr($record['type']); ?>">
                  <?php echo esc_html(ucfirst($record['type'])); ?>
                </span>
              </td>
              <td class="hostpn-financial-td-description">
                <?php echo esc_html($record['title']); ?>
              </td>
              <td class="hostpn-financial-td-amount hostpn-text-align-right">
                <?php echo esc_html(number_format($record['amount'], 2, ',', '.') . ' ' . $record['currency']); ?>
              </td>
              <?php if (!$read_only) : ?>
                <td class="hostpn-financial-td-actions hostpn-text-align-center">
                  <button type="button" class="hostpn-btn-icon hostpn-financial-record-edit"
                          data-record-id="<?php echo esc_attr($record['id']); ?>"
                          title="<?php esc_attr_e('Edit', 'hostpn'); ?>">
                    <i class="material-icons-outlined">edit</i>
                  </button>
                  <button type="button" class="hostpn-btn-icon hostpn-financial-record-delete"
                          data-record-id="<?php echo esc_attr($record['id']); ?>"
                          title="<?php esc_attr_e('Delete', 'hostpn'); ?>">
                    <i class="material-icons-outlined">delete</i>
                  </button>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <?php if ($pagination['pages'] > 1) : ?>
        <div class="hostpn-financial-pagination hostpn-mt-20">
          <?php for ($i = 1; $i <= $pagination['pages']; $i++) : ?>
            <button type="button"
                    class="hostpn-btn-mini hostpn-financial-pagination-btn <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>"
                    data-page="<?php echo esc_attr($i); ?>">
              <?php echo esc_html($i); ?>
            </button>
          <?php endfor; ?>
        </div>
      <?php endif; ?>

      <!-- Export Button -->
      <div class="hostpn-financial-export-wrapper hostpn-mt-20 hostpn-text-align-right">
        <button type="button" class="hostpn-btn hostpn-btn-mini hostpn-financial-export-btn">
          <i class="material-icons-outlined hostpn-vertical-align-middle">file_download</i>
          <span class="hostpn-vertical-align-middle"><?php esc_html_e('Export to CSV', 'hostpn'); ?></span>
        </button>
      </div>
    <?php else : ?>
      <p class="hostpn-text-align-center hostpn-p-30">
        <?php esc_html_e('No records found. Try adjusting the filters or import new data.', 'hostpn'); ?>
      </p>
    <?php endif; ?>
  </div>

  <!-- Import History -->
  <?php if (!$read_only && !empty($batches)) : ?>
    <div class="hostpn-financial-import-history hostpn-mb-20">
      <h3><?php esc_html_e('Import History', 'hostpn'); ?></h3>
      <table class="hostpn-financial-table">
        <thead>
          <tr>
            <th><?php esc_html_e('Date', 'hostpn'); ?></th>
            <th><?php esc_html_e('Filename', 'hostpn'); ?></th>
            <th class="hostpn-text-align-right"><?php esc_html_e('Records', 'hostpn'); ?></th>
            <th class="hostpn-text-align-center"><?php esc_html_e('Actions', 'hostpn'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($batches as $batch) : ?>
            <tr data-batch-id="<?php echo esc_attr($batch['batch_id']); ?>">
              <td><?php echo esc_html(date('d/m/Y H:i', strtotime($batch['timestamp']))); ?></td>
              <td><?php echo esc_html($batch['filename']); ?></td>
              <td class="hostpn-text-align-right"><?php echo esc_html($batch['record_count']); ?></td>
              <td class="hostpn-text-align-center">
                <button type="button" class="hostpn-btn-icon hostpn-financial-batch-delete"
                        data-batch-id="<?php echo esc_attr($batch['batch_id']); ?>"
                        title="<?php esc_attr_e('Delete Batch', 'hostpn'); ?>">
                  <i class="material-icons-outlined">delete</i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

</div>

<!-- Hidden popup containers (will be populated via AJAX) -->
<div id="hostpn-popup-financial-import" class="hostpn-popup" style="display: none;"></div>
<div id="hostpn-popup-financial-edit" class="hostpn-popup" style="display: none;"></div>

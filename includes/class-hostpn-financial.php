<?php
/**
 * Financial Management System.
 *
 * Main class for managing financial data, dashboard, and operations.
 *
 * @link       padresenlanube.com/
 * @since      1.0.36
 * @package    hostpn
 * @subpackage hostpn/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Financial {

  /**
   * Get dashboard data for an accommodation.
   *
   * @since    1.0.36
   * @param    int      $accommodation_id    Accommodation ID
   * @param    array    $filters             Filters (year, quarter, platform, type)
   * @return   array                         Dashboard data
   */
  public static function get_dashboard_data($accommodation_id, $filters = []) {
    // Get summary totals
    $totals = self::get_totals($accommodation_id, $filters);

    // Get records with pagination
    $page = !empty($filters['page']) ? (int) $filters['page'] : 1;
    $per_page = !empty($filters['per_page']) ? (int) $filters['per_page'] : 20;
    $records_data = HOSTPN_Financial_Importer::get_records($accommodation_id, $filters, $page, $per_page);

    // Get quarterly breakdown
    $quarterly = self::get_quarterly_breakdown($accommodation_id, $filters);

    // Get import history
    $batches = HOSTPN_Financial_Importer::get_import_batches($accommodation_id);

    return [
      'totals' => $totals,
      'records' => $records_data['records'],
      'pagination' => [
        'total' => $records_data['total'],
        'pages' => $records_data['pages'],
        'current_page' => $records_data['current_page'],
        'per_page' => $per_page,
      ],
      'quarterly' => $quarterly,
      'batches' => $batches,
    ];
  }

  /**
   * Get financial totals for an accommodation.
   *
   * @since    1.0.36
   * @param    int      $accommodation_id    Accommodation ID
   * @param    array    $filters             Filters (year, quarter, platform, type)
   * @return   array                         Totals array
   */
  public static function get_totals($accommodation_id, $filters = []) {
    // Build query args
    $args = [
      'post_type' => 'hostpn_financial_record',
      'post_parent' => $accommodation_id,
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'fields' => 'ids',
    ];

    // Build meta query for filters
    $meta_query = ['relation' => 'AND'];

    if (!empty($filters['year'])) {
      $meta_query[] = [
        'key' => 'hostpn_financial_year',
        'value' => (int) $filters['year'],
        'compare' => '=',
      ];
    }

    if (!empty($filters['quarter'])) {
      $meta_query[] = [
        'key' => 'hostpn_financial_quarter',
        'value' => $filters['quarter'],
        'compare' => '=',
      ];
    }

    if (!empty($filters['platform'])) {
      $meta_query[] = [
        'key' => 'hostpn_financial_platform',
        'value' => $filters['platform'],
        'compare' => '=',
      ];
    }

    if (!empty($filters['type'])) {
      $meta_query[] = [
        'key' => 'hostpn_financial_record_type',
        'value' => $filters['type'],
        'compare' => '=',
      ];
    }

    if (count($meta_query) > 1) {
      $args['meta_query'] = $meta_query;
    }

    // Get records
    $record_ids = get_posts($args);

    // Calculate totals
    $totals = [
      'income' => 0,
      'expenses' => 0,
      'tax' => 0,
      'fees' => 0,
      'net' => 0,
      'record_count' => count($record_ids),
      'currency' => 'EUR', // Default currency
    ];

    foreach ($record_ids as $record_id) {
      $type = get_post_meta($record_id, 'hostpn_financial_record_type', true);
      $amount = (float) get_post_meta($record_id, 'hostpn_financial_amount', true);
      $currency = get_post_meta($record_id, 'hostpn_financial_currency', true);

      // Use first currency found
      if (!empty($currency) && $totals['currency'] === 'EUR') {
        $totals['currency'] = $currency;
      }

      switch ($type) {
        case 'income':
          $totals['income'] += $amount;
          break;
        case 'expense':
          $totals['expenses'] += abs($amount);
          break;
        case 'tax':
          $totals['tax'] += abs($amount);
          break;
        case 'fee':
          $totals['fees'] += abs($amount);
          break;
      }
    }

    // Calculate net
    $totals['net'] = $totals['income'] - $totals['expenses'] - $totals['tax'] - $totals['fees'];

    return $totals;
  }

  /**
   * Get quarterly breakdown for an accommodation.
   *
   * @since    1.0.36
   * @param    int      $accommodation_id    Accommodation ID
   * @param    array    $filters             Filters (year, platform)
   * @return   array                         Quarterly data
   */
  public static function get_quarterly_breakdown($accommodation_id, $filters = []) {
    $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
    $breakdown = [];

    foreach ($quarters as $quarter) {
      $quarter_filters = array_merge($filters, ['quarter' => $quarter]);
      unset($quarter_filters['page']);
      unset($quarter_filters['per_page']);

      $totals = self::get_totals($accommodation_id, $quarter_filters);

      $breakdown[$quarter] = [
        'income' => $totals['income'],
        'expenses' => $totals['expenses'],
        'tax' => $totals['tax'],
        'fees' => $totals['fees'],
        'net' => $totals['net'],
      ];
    }

    return $breakdown;
  }

  /**
   * Get available years for filtering.
   *
   * @since    1.0.36
   * @param    int      $accommodation_id    Accommodation ID
   * @return   array                         Array of years
   */
  public static function get_available_years($accommodation_id) {
    global $wpdb;

    $query = $wpdb->prepare("
      SELECT DISTINCT pm.meta_value as year
      FROM {$wpdb->posts} p
      INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
      WHERE p.post_type = 'hostpn_financial_record'
        AND p.post_parent = %d
        AND p.post_status = 'publish'
        AND pm.meta_key = 'hostpn_financial_year'
        AND pm.meta_value IS NOT NULL
      ORDER BY pm.meta_value DESC
    ", $accommodation_id);

    $results = $wpdb->get_col($query);

    // Ensure results are integers
    return array_map('intval', array_filter($results));
  }

  /**
   * Export financial records to CSV.
   *
   * @since    1.0.36
   * @param    int      $accommodation_id    Accommodation ID
   * @param    array    $filters             Filters (year, quarter, platform, type)
   */
  public static function export_to_csv($accommodation_id, $filters = []) {
    // Get all records matching filters (no pagination)
    $args = [
      'post_type' => 'hostpn_financial_record',
      'post_parent' => $accommodation_id,
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'orderby' => 'date',
      'order' => 'DESC',
    ];

    // Build meta query for filters
    $meta_query = ['relation' => 'AND'];

    if (!empty($filters['year'])) {
      $meta_query[] = [
        'key' => 'hostpn_financial_year',
        'value' => (int) $filters['year'],
        'compare' => '=',
      ];
    }

    if (!empty($filters['quarter'])) {
      $meta_query[] = [
        'key' => 'hostpn_financial_quarter',
        'value' => $filters['quarter'],
        'compare' => '=',
      ];
    }

    if (!empty($filters['platform'])) {
      $meta_query[] = [
        'key' => 'hostpn_financial_platform',
        'value' => $filters['platform'],
        'compare' => '=',
      ];
    }

    if (!empty($filters['type'])) {
      $meta_query[] = [
        'key' => 'hostpn_financial_record_type',
        'value' => $filters['type'],
        'compare' => '=',
      ];
    }

    if (count($meta_query) > 1) {
      $args['meta_query'] = $meta_query;
    }

    $records = get_posts($args);

    if (empty($records)) {
      return false;
    }

    // Get accommodation name for filename
    $accommodation = get_post($accommodation_id);
    $filename = 'financial-records-' . sanitize_title($accommodation->post_title) . '-' . date('Y-m-d') . '.csv';

    // Set headers
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

    // UTF-8 BOM for Excel
    echo "\xEF\xBB\xBF";

    // Open output stream
    $output = fopen('php://output', 'w');

    // Write headers
    $headers = [
      __('Date', 'hostpn'),
      __('Platform', 'hostpn'),
      __('Type', 'hostpn'),
      __('Description', 'hostpn'),
      __('Amount', 'hostpn'),
      __('Net Amount', 'hostpn'),
      __('Tax', 'hostpn'),
      __('Fees', 'hostpn'),
      __('Currency', 'hostpn'),
      __('Year', 'hostpn'),
      __('Quarter', 'hostpn'),
      __('Batch ID', 'hostpn'),
      __('Import Date', 'hostpn'),
    ];
    fputcsv($output, $headers, ';');

    // Write records
    foreach ($records as $record) {
      $row = [
        get_post_meta($record->ID, 'hostpn_financial_date', true),
        get_post_meta($record->ID, 'hostpn_financial_platform', true),
        get_post_meta($record->ID, 'hostpn_financial_record_type', true),
        $record->post_title,
        get_post_meta($record->ID, 'hostpn_financial_amount', true),
        get_post_meta($record->ID, 'hostpn_financial_net_amount', true),
        get_post_meta($record->ID, 'hostpn_financial_tax_amount', true),
        get_post_meta($record->ID, 'hostpn_financial_fee_amount', true),
        get_post_meta($record->ID, 'hostpn_financial_currency', true),
        get_post_meta($record->ID, 'hostpn_financial_year', true),
        get_post_meta($record->ID, 'hostpn_financial_quarter', true),
        get_post_meta($record->ID, 'hostpn_financial_import_batch_id', true),
        get_post_meta($record->ID, 'hostpn_financial_import_timestamp', true),
      ];

      fputcsv($output, $row, ';');
    }

    fclose($output);
    exit;
  }

  /**
   * Get record details for editing.
   *
   * @since    1.0.36
   * @param    int      $record_id    Record ID
   * @return   array|WP_Error         Record data or error
   */
  public static function get_record_details($record_id) {
    $record = get_post($record_id);

    if (!$record || $record->post_type !== 'hostpn_financial_record') {
      return new WP_Error('invalid_record', __('Invalid financial record ID', 'hostpn'));
    }

    // Get all meta
    $meta = get_post_meta($record_id);

    // Flatten meta (remove arrays)
    $flattened_meta = [];
    foreach ($meta as $key => $value) {
      $flattened_meta[$key] = is_array($value) ? $value[0] : $value;
    }

    return [
      'id' => $record->ID,
      'title' => $record->post_title,
      'content' => $record->post_content,
      'date' => $record->post_date,
      'accommodation_id' => $record->post_parent,
      'meta' => $flattened_meta,
    ];
  }

  /**
   * Update a financial record.
   *
   * @since    1.0.36
   * @param    int      $record_id    Record ID
   * @param    array    $data         Record data to update
   * @return   bool|WP_Error          True on success, error otherwise
   */
  public static function update_record($record_id, $data) {
    $record = get_post($record_id);

    if (!$record || $record->post_type !== 'hostpn_financial_record') {
      return new WP_Error('invalid_record', __('Invalid financial record ID', 'hostpn'));
    }

    // Update post if title or content provided
    $post_data = ['ID' => $record_id];

    if (isset($data['title'])) {
      $post_data['post_title'] = sanitize_text_field($data['title']);
    }

    if (isset($data['content'])) {
      $post_data['post_content'] = wp_kses_post($data['content']);
    }

    if (count($post_data) > 1) {
      wp_update_post($post_data);
    }

    // Update meta fields
    if (isset($data['meta']) && is_array($data['meta'])) {
      foreach ($data['meta'] as $key => $value) {
        // Sanitize based on field type
        if (strpos($key, 'amount') !== false) {
          $value = round(floatval($value), 2);
        } elseif (strpos($key, 'date') !== false) {
          $value = sanitize_text_field($value);
        } elseif (strpos($key, 'year') !== false) {
          $value = (int) $value;
        } else {
          $value = sanitize_text_field($value);
        }

        update_post_meta($record_id, $key, $value);
      }
    }

    // Recalculate accommodation totals
    if ($record->post_parent) {
      HOSTPN_Financial_Importer::update_accommodation_totals($record->post_parent);
    }

    return true;
  }

  /**
   * Create a new financial record (manual entry).
   *
   * @since    1.0.36
   * @param    int      $accommodation_id    Accommodation ID
   * @param    array    $data                Record data
   * @return   int|WP_Error                  Post ID or error
   */
  public static function create_record($accommodation_id, $data) {
    // Validate accommodation
    $accommodation = get_post($accommodation_id);
    if (!$accommodation || $accommodation->post_type !== 'hostpn_accommodation') {
      return new WP_Error('invalid_accommodation', __('Invalid accommodation ID', 'hostpn'));
    }

    // Create post
    $post_data = [
      'post_type' => 'hostpn_financial_record',
      'post_title' => !empty($data['title']) ? sanitize_text_field($data['title']) : __('Manual Entry', 'hostpn'),
      'post_content' => !empty($data['content']) ? wp_kses_post($data['content']) : '',
      'post_status' => 'publish',
      'post_parent' => $accommodation_id,
    ];

    $post_id = wp_insert_post($post_data, true);

    if (is_wp_error($post_id)) {
      return $post_id;
    }

    // Save meta fields
    if (isset($data['meta']) && is_array($data['meta'])) {
      foreach ($data['meta'] as $key => $value) {
        // Sanitize based on field type
        if (strpos($key, 'amount') !== false) {
          $value = round(floatval($value), 2);
        } elseif (strpos($key, 'date') !== false) {
          $value = sanitize_text_field($value);
        } elseif (strpos($key, 'year') !== false) {
          $value = (int) $value;
        } else {
          $value = sanitize_text_field($value);
        }

        update_post_meta($post_id, $key, $value);
      }
    }

    // Mark as manual entry
    update_post_meta($post_id, 'hostpn_financial_platform', 'manual');
    update_post_meta($post_id, 'hostpn_financial_accommodation_id', $accommodation_id);

    // Update accommodation totals
    HOSTPN_Financial_Importer::update_accommodation_totals($accommodation_id);

    return $post_id;
  }
}

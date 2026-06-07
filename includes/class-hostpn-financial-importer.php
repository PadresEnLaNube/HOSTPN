<?php
/**
 * Financial CSV Importer.
 *
 * This class handles batch import of financial records from CSV files.
 *
 * @link       padresenlanube.com/
 * @since      1.0.36
 * @package    hostpn
 * @subpackage hostpn/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Financial_Importer {

  /**
   * Import financial records from CSV file.
   *
   * @since    1.0.36
   * @param    string    $file_path         Path to CSV file
   * @param    int       $accommodation_id  Accommodation ID to link records to
   * @param    string    $format            CSV format (optional, will auto-detect)
   * @return   array|WP_Error               Import result or error
   */
  public static function import_csv($file_path, $accommodation_id, $format = null) {
    // Validate accommodation exists
    $accommodation = get_post($accommodation_id);
    if (!$accommodation || $accommodation->post_type !== 'hostpn_accommodation') {
      return new WP_Error('invalid_accommodation', __('Invalid accommodation ID', 'hostpn'));
    }

    // Parse CSV
    $parsed = HOSTPN_Financial_CSV_Parser::parse_csv($file_path, $format);
    if (is_wp_error($parsed)) {
      return $parsed;
    }

    // Generate batch ID
    $batch_id = wp_generate_uuid4();
    $timestamp = current_time('mysql');
    $filename = basename($file_path);

    // Track import stats
    $stats = [
      'batch_id' => $batch_id,
      'total_rows' => count($parsed['rows']),
      'imported' => 0,
      'skipped' => 0,
      'errors' => [],
    ];

    // Suspend cache invalidation for performance
    wp_suspend_cache_invalidation(true);

    // Import each row
    foreach ($parsed['rows'] as $index => $row) {
      $result = self::import_row(
        $row,
        $parsed['format'],
        $accommodation_id,
        $batch_id,
        $timestamp,
        $filename
      );

      if (is_wp_error($result)) {
        $stats['skipped']++;
        $stats['errors'][] = sprintf(
          __('Row %d: %s', 'hostpn'),
          $index + 2, // +2 because: +1 for header row, +1 for 1-based indexing
          $result->get_error_message()
        );
      } else {
        $stats['imported']++;
      }

      // Yield to prevent timeouts on large imports
      if ($index % 50 === 0) {
        wp_cache_flush();
      }
    }

    // Re-enable cache invalidation
    wp_suspend_cache_invalidation(false);
    wp_cache_flush();

    // Update accommodation totals
    self::update_accommodation_totals($accommodation_id);

    return $stats;
  }

  /**
   * Import single CSV row as financial record.
   *
   * @since    1.0.36
   * @param    array     $row               CSV row data
   * @param    string    $format            CSV format
   * @param    int       $accommodation_id  Accommodation ID
   * @param    string    $batch_id          Import batch ID
   * @param    string    $timestamp         Import timestamp
   * @param    string    $filename          Source filename
   * @return   int|WP_Error                 Post ID or error
   */
  private static function import_row($row, $format, $accommodation_id, $batch_id, $timestamp, $filename) {
    // Map row to meta fields
    $meta = HOSTPN_Financial_CSV_Parser::map_row_to_meta($row, $format);

    if (empty($meta)) {
      return new WP_Error('mapping_failed', __('Failed to map CSV row to meta fields', 'hostpn'));
    }

    // Generate post title based on format
    $post_title = self::generate_post_title($meta, $format);

    // Create post
    $post_data = [
      'post_type' => 'hostpn_financial_record',
      'post_title' => $post_title,
      'post_content' => '', // Can add notes if needed
      'post_status' => 'publish',
      'post_parent' => $accommodation_id,
      'post_date' => !empty($meta['hostpn_financial_date']) ? $meta['hostpn_financial_date'] : current_time('mysql'),
    ];

    $post_id = wp_insert_post($post_data, true);

    if (is_wp_error($post_id)) {
      return $post_id;
    }

    // Add import tracking meta
    $meta['hostpn_financial_accommodation_id'] = $accommodation_id;
    $meta['hostpn_financial_import_batch_id'] = $batch_id;
    $meta['hostpn_financial_import_timestamp'] = $timestamp;
    $meta['hostpn_financial_csv_source'] = $filename;

    // Save all meta fields
    foreach ($meta as $key => $value) {
      if ($value !== '' && $value !== null) {
        update_post_meta($post_id, $key, $value);
      }
    }

    return $post_id;
  }

  /**
   * Generate post title for financial record.
   *
   * @since    1.0.36
   * @param    array     $meta     Meta fields
   * @param    string    $format   Format type
   * @return   string              Post title
   */
  private static function generate_post_title($meta, $format) {
    $platform = !empty($meta['hostpn_financial_platform']) ? ucfirst($meta['hostpn_financial_platform']) : 'Unknown';
    $date = !empty($meta['hostpn_financial_date']) ? date('Y-m-d', strtotime($meta['hostpn_financial_date'])) : date('Y-m-d');

    switch ($format) {
      case 'airbnb_fiscal':
        $listing = !empty($meta['hostpn_airbnb_listing_title']) ? $meta['hostpn_airbnb_listing_title'] : 'Fiscal Data';
        $year = !empty($meta['hostpn_financial_year']) ? $meta['hostpn_financial_year'] : date('Y');
        return sprintf('%s - %s %s', $platform, $listing, $year);

      case 'airbnb_invoice':
        $invoice_num = !empty($meta['hostpn_airbnb_invoice_number']) ? $meta['hostpn_airbnb_invoice_number'] : 'N/A';
        return sprintf('%s Invoice %s', $platform, $invoice_num);

      case 'booking':
        $ref_num = !empty($meta['hostpn_booking_reference_number']) ? $meta['hostpn_booking_reference_number'] : 'N/A';
        return sprintf('%s Reservation %s', $platform, $ref_num);

      default:
        return sprintf('%s - %s', $platform, $date);
    }
  }

  /**
   * Update accommodation financial totals.
   *
   * @since    1.0.36
   * @param    int      $accommodation_id    Accommodation ID
   */
  public static function update_accommodation_totals($accommodation_id) {
    // Query all financial records for this accommodation
    $records = get_posts([
      'post_type' => 'hostpn_financial_record',
      'post_parent' => $accommodation_id,
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'fields' => 'ids',
    ]);

    $totals = [
      'total_income' => 0,
      'total_expenses' => 0,
      'total_tax' => 0,
      'total_fees' => 0,
      'record_count' => count($records),
    ];

    foreach ($records as $record_id) {
      $type = get_post_meta($record_id, 'hostpn_financial_record_type', true);
      $amount = (float) get_post_meta($record_id, 'hostpn_financial_amount', true);

      switch ($type) {
        case 'income':
          $totals['total_income'] += $amount;
          break;
        case 'expense':
          $totals['total_expenses'] += abs($amount);
          break;
        case 'tax':
          $totals['total_tax'] += abs($amount);
          break;
        case 'fee':
          $totals['total_fees'] += abs($amount);
          break;
      }
    }

    // Calculate net
    $totals['net_amount'] = $totals['total_income'] - $totals['total_expenses'] - $totals['total_tax'] - $totals['total_fees'];

    // Save totals to accommodation meta
    foreach ($totals as $key => $value) {
      update_post_meta($accommodation_id, 'hostpn_financial_' . $key, $value);
    }

    // Invalidate any cached data
    wp_cache_delete('hostpn_financial_totals_' . $accommodation_id);
  }

  /**
   * Delete all records from a batch.
   *
   * @since    1.0.36
   * @param    string    $batch_id           Batch ID to delete
   * @param    int       $accommodation_id   Accommodation ID (for security check)
   * @return   int|WP_Error                  Number of deleted records or error
   */
  public static function delete_batch($batch_id, $accommodation_id = 0) {
    // Find all records with this batch ID
    $args = [
      'post_type' => 'hostpn_financial_record',
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'fields' => 'ids',
      'meta_query' => [
        [
          'key' => 'hostpn_financial_import_batch_id',
          'value' => $batch_id,
          'compare' => '=',
        ],
      ],
    ];

    // Add accommodation filter if provided
    if ($accommodation_id > 0) {
      $args['post_parent'] = $accommodation_id;
    }

    $records = get_posts($args);

    if (empty($records)) {
      return new WP_Error('batch_not_found', __('No records found for this batch ID', 'hostpn'));
    }

    $deleted_count = 0;

    foreach ($records as $record_id) {
      if (wp_trash_post($record_id)) {
        $deleted_count++;
      }
    }

    // Update accommodation totals if accommodation specified
    if ($accommodation_id > 0) {
      self::update_accommodation_totals($accommodation_id);
    }

    return $deleted_count;
  }

  /**
   * Get import batches for an accommodation.
   *
   * @since    1.0.36
   * @param    int      $accommodation_id    Accommodation ID
   * @return   array                         Array of batch info
   */
  public static function get_import_batches($accommodation_id) {
    global $wpdb;

    // Query distinct batch IDs with metadata
    $query = $wpdb->prepare("
      SELECT
        pm1.meta_value as batch_id,
        pm2.meta_value as timestamp,
        pm3.meta_value as filename,
        COUNT(p.ID) as record_count
      FROM {$wpdb->posts} p
      LEFT JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = 'hostpn_financial_import_batch_id'
      LEFT JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = 'hostpn_financial_import_timestamp'
      LEFT JOIN {$wpdb->postmeta} pm3 ON p.ID = pm3.post_id AND pm3.meta_key = 'hostpn_financial_csv_source'
      WHERE p.post_type = 'hostpn_financial_record'
        AND p.post_parent = %d
        AND p.post_status = 'publish'
        AND pm1.meta_value IS NOT NULL
      GROUP BY pm1.meta_value
      ORDER BY pm2.meta_value DESC
    ", $accommodation_id);

    $results = $wpdb->get_results($query);

    $batches = [];
    foreach ($results as $row) {
      $batches[] = [
        'batch_id' => $row->batch_id,
        'timestamp' => $row->timestamp,
        'filename' => $row->filename,
        'record_count' => (int) $row->record_count,
      ];
    }

    return $batches;
  }

  /**
   * Delete a single financial record.
   *
   * @since    1.0.36
   * @param    int      $record_id           Record ID to delete
   * @param    int      $accommodation_id    Accommodation ID (for security)
   * @return   bool|WP_Error                 True on success, error otherwise
   */
  public static function delete_record($record_id, $accommodation_id = 0) {
    $record = get_post($record_id);

    if (!$record || $record->post_type !== 'hostpn_financial_record') {
      return new WP_Error('invalid_record', __('Invalid financial record ID', 'hostpn'));
    }

    // Security check: verify accommodation ownership
    if ($accommodation_id > 0 && $record->post_parent != $accommodation_id) {
      return new WP_Error('permission_denied', __('You do not have permission to delete this record', 'hostpn'));
    }

    $result = wp_trash_post($record_id);

    if (!$result) {
      return new WP_Error('delete_failed', __('Failed to delete financial record', 'hostpn'));
    }

    // Update totals
    if ($record->post_parent) {
      self::update_accommodation_totals($record->post_parent);
    }

    return true;
  }

  /**
   * Get financial records for an accommodation with filters.
   *
   * @since    1.0.36
   * @param    int      $accommodation_id    Accommodation ID
   * @param    array    $filters             Filters (year, quarter, platform, type)
   * @param    int      $page                Page number (for pagination)
   * @param    int      $per_page            Records per page
   * @return   array                         Records and pagination info
   */
  public static function get_records($accommodation_id, $filters = [], $page = 1, $per_page = 20) {
    $args = [
      'post_type' => 'hostpn_financial_record',
      'post_parent' => $accommodation_id,
      'post_status' => 'publish',
      'posts_per_page' => $per_page,
      'paged' => $page,
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

    $query = new WP_Query($args);

    $records = [];
    foreach ($query->posts as $post) {
      $records[] = [
        'id' => $post->ID,
        'title' => $post->post_title,
        'date' => get_post_meta($post->ID, 'hostpn_financial_date', true),
        'platform' => get_post_meta($post->ID, 'hostpn_financial_platform', true),
        'type' => get_post_meta($post->ID, 'hostpn_financial_record_type', true),
        'amount' => (float) get_post_meta($post->ID, 'hostpn_financial_amount', true),
        'currency' => get_post_meta($post->ID, 'hostpn_financial_currency', true),
        'batch_id' => get_post_meta($post->ID, 'hostpn_financial_import_batch_id', true),
      ];
    }

    return [
      'records' => $records,
      'total' => $query->found_posts,
      'pages' => $query->max_num_pages,
      'current_page' => $page,
    ];
  }
}

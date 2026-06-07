<?php
/**
 * Financial CSV Parser.
 *
 * This class handles CSV file parsing and format detection for
 * Airbnb and Booking.com financial data imports.
 *
 * @link       padresenlanube.com/
 * @since      1.0.36
 * @package    hostpn
 * @subpackage hostpn/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Financial_CSV_Parser {

  /**
   * Detect CSV format by analyzing headers.
   *
   * @since    1.0.36
   * @param    string    $file_path    Path to CSV file
   * @return   string|WP_Error         Format type or error
   */
  public static function detect_format($file_path) {
    if (!file_exists($file_path)) {
      return new WP_Error('file_not_found', __('CSV file not found', 'hostpn'));
    }

    // Read first line (headers)
    $handle = fopen($file_path, 'r');
    if (!$handle) {
      return new WP_Error('file_read_error', __('Could not read CSV file', 'hostpn'));
    }

    // Handle BOM
    $bom = fread($handle, 3);
    if ($bom !== "\xEF\xBB\xBF") {
      rewind($handle);
    }

    $headers = fgetcsv($handle, 0, ',');
    fclose($handle);

    if (!$headers || empty($headers)) {
      return new WP_Error('invalid_csv', __('Invalid CSV format: no headers found', 'hostpn'));
    }

    // Normalize headers (lowercase, trim)
    $headers = array_map('strtolower', array_map('trim', $headers));

    // Detection keywords for each format
    $formats = [
      'airbnb_fiscal' => [
        'keywords' => ['listingid', 'q1payouts', 'q4payouts', 'renteddays'],
        'threshold' => 0.8,
      ],
      'airbnb_invoice' => [
        'keywords' => ['número de factura', 'codigo de confirmacion', 'importe neto', 'importe del iva'],
        'threshold' => 0.75,
      ],
      'booking' => [
        'keywords' => ['número de referencia', 'estado de la reserva', 'cargo por servicio de los pagos'],
        'threshold' => 0.67,
      ],
    ];

    // Check each format
    foreach ($formats as $format_type => $format_config) {
      $matches = 0;
      $total_keywords = count($format_config['keywords']);

      foreach ($format_config['keywords'] as $keyword) {
        foreach ($headers as $header) {
          // Remove accents for comparison
          $normalized_header = self::remove_accents($header);
          $normalized_keyword = self::remove_accents($keyword);

          if (strpos($normalized_header, $normalized_keyword) !== false) {
            $matches++;
            break;
          }
        }
      }

      $match_percentage = $matches / $total_keywords;

      if ($match_percentage >= $format_config['threshold']) {
        return $format_type;
      }
    }

    return new WP_Error('unknown_format', __('Could not detect CSV format. Supported: Airbnb fiscal docs, Airbnb invoices, Booking.com', 'hostpn'));
  }

  /**
   * Parse CSV file based on detected format.
   *
   * @since    1.0.36
   * @param    string    $file_path    Path to CSV file
   * @param    string    $format       Format type (optional, will auto-detect if not provided)
   * @return   array|WP_Error          Parsed data or error
   */
  public static function parse_csv($file_path, $format = null) {
    // Detect format if not provided
    if (!$format) {
      $format = self::detect_format($file_path);
      if (is_wp_error($format)) {
        return $format;
      }
    }

    // Read CSV file
    $handle = fopen($file_path, 'r');
    if (!$handle) {
      return new WP_Error('file_read_error', __('Could not read CSV file', 'hostpn'));
    }

    // Handle BOM
    $bom = fread($handle, 3);
    if ($bom !== "\xEF\xBB\xBF") {
      rewind($handle);
    }

    // Read headers
    $headers = fgetcsv($handle, 0, ',');
    if (!$headers) {
      fclose($handle);
      return new WP_Error('invalid_csv', __('Invalid CSV format: no headers found', 'hostpn'));
    }

    // Normalize headers
    $headers = array_map('trim', $headers);

    // Parse rows
    $rows = [];
    $row_number = 1;

    while (($data = fgetcsv($handle, 0, ',')) !== false) {
      $row_number++;

      // Skip empty rows
      if (empty(array_filter($data))) {
        continue;
      }

      // Validate row has same number of columns as headers
      if (count($data) !== count($headers)) {
        error_log(sprintf(
          'HOSTPN Financial CSV Parser: Row %d has %d columns, expected %d. Skipping row.',
          $row_number,
          count($data),
          count($headers)
        ));
        continue;
      }

      // Combine headers with data
      $row = array_combine($headers, $data);
      $rows[] = $row;
    }

    fclose($handle);

    if (empty($rows)) {
      return new WP_Error('empty_csv', __('CSV file contains no data rows', 'hostpn'));
    }

    return [
      'format' => $format,
      'headers' => $headers,
      'rows' => $rows,
      'row_count' => count($rows),
    ];
  }

  /**
   * Get preview of CSV data (first N rows).
   *
   * @since    1.0.36
   * @param    string    $file_path    Path to CSV file
   * @param    int       $limit        Number of rows to preview (default: 5)
   * @return   array|WP_Error          Preview data or error
   */
  public static function get_preview($file_path, $limit = 5) {
    $parsed = self::parse_csv($file_path);

    if (is_wp_error($parsed)) {
      return $parsed;
    }

    return [
      'format' => $parsed['format'],
      'headers' => $parsed['headers'],
      'preview_rows' => array_slice($parsed['rows'], 0, $limit),
      'total_rows' => $parsed['row_count'],
    ];
  }

  /**
   * Validate CSV file.
   *
   * @since    1.0.36
   * @param    array     $file    $_FILES array element
   * @return   true|WP_Error      True if valid, error otherwise
   */
  public static function validate_file($file) {
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
      return new WP_Error('upload_error', __('File upload failed', 'hostpn'));
    }

    // Check file size (5MB max)
    $max_size = 5 * 1024 * 1024;
    if ($file['size'] > $max_size) {
      return new WP_Error('file_too_large', __('File size exceeds 5MB limit', 'hostpn'));
    }

    // Check file type
    $file_type = wp_check_filetype($file['name']);
    $allowed_types = ['text/csv', 'application/csv', 'text/plain', 'application/vnd.ms-excel'];

    if (!in_array($file_type['type'], $allowed_types) && !in_array($file['type'], $allowed_types)) {
      // Also check by extension
      $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
      if ($extension !== 'csv') {
        return new WP_Error('invalid_file_type', __('Only CSV files are allowed', 'hostpn'));
      }
    }

    return true;
  }

  /**
   * Remove accents from string for comparison.
   *
   * @since    1.0.36
   * @param    string    $string    String with accents
   * @return   string               String without accents
   */
  private static function remove_accents($string) {
    if (!function_exists('remove_accents')) {
      // Fallback if WordPress function not available
      $unwanted_array = [
        'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u',
        'Á'=>'A', 'É'=>'E', 'Í'=>'I', 'Ó'=>'O', 'Ú'=>'U',
        'ñ'=>'n', 'Ñ'=>'N', 'ü'=>'u', 'Ü'=>'U',
      ];
      return strtr($string, $unwanted_array);
    }

    return remove_accents($string);
  }

  /**
   * Map CSV row to financial record meta fields based on format.
   *
   * @since    1.0.36
   * @param    array     $row       CSV row data
   * @param    string    $format    Format type
   * @return   array                Mapped meta fields
   */
  public static function map_row_to_meta($row, $format) {
    $meta = [
      'hostpn_financial_platform' => '',
      'hostpn_financial_record_type' => '',
      'hostpn_financial_date' => '',
      'hostpn_financial_amount' => 0,
      'hostpn_financial_currency' => 'EUR',
    ];

    switch ($format) {
      case 'airbnb_fiscal':
        $meta['hostpn_financial_platform'] = 'airbnb';
        $meta['hostpn_financial_record_type'] = 'income';

        // Map Airbnb fiscal fields
        if (isset($row['listingId'])) $meta['hostpn_airbnb_listing_id'] = $row['listingId'];
        if (isset($row['listingTitle'])) $meta['hostpn_airbnb_listing_title'] = $row['listingTitle'];
        if (isset($row['rentedDays'])) $meta['hostpn_airbnb_rented_days'] = (int) $row['rentedDays'];
        if (isset($row['reportingCurrency'])) $meta['hostpn_financial_currency'] = $row['reportingCurrency'];

        // Quarterly data
        foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter) {
          if (isset($row[$quarter . 'Payouts'])) {
            $meta['hostpn_airbnb_' . strtolower($quarter) . '_payouts'] = self::parse_amount($row[$quarter . 'Payouts']);
          }
          if (isset($row[$quarter . 'FeesPaid'])) {
            $meta['hostpn_airbnb_' . strtolower($quarter) . '_fees'] = self::parse_amount($row[$quarter . 'FeesPaid']);
          }
        }

        // Calculate total amount (sum of all quarterly payouts)
        $total = 0;
        foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter) {
          $key = 'hostpn_airbnb_' . strtolower($quarter) . '_payouts';
          if (isset($meta[$key])) {
            $total += $meta[$key];
          }
        }
        $meta['hostpn_financial_amount'] = $total;

        // Use current year for fiscal documents
        $meta['hostpn_financial_year'] = date('Y');
        $meta['hostpn_financial_date'] = date('Y-12-31'); // End of year

        break;

      case 'airbnb_invoice':
        $meta['hostpn_financial_platform'] = 'airbnb';
        $meta['hostpn_financial_record_type'] = 'income';

        // Map Airbnb invoice fields
        if (isset($row['Número de factura'])) $meta['hostpn_airbnb_invoice_number'] = $row['Número de factura'];
        if (isset($row['Código de confirmación'])) $meta['hostpn_airbnb_confirmation_code'] = $row['Código de confirmación'];
        if (isset($row['Número de IVA'])) $meta['hostpn_airbnb_vat_number'] = $row['Número de IVA'];
        if (isset($row['Moneda'])) $meta['hostpn_financial_currency'] = $row['Moneda'];

        // Amounts
        if (isset($row['Importe neto'])) {
          $net = self::parse_amount($row['Importe neto']);
          $meta['hostpn_airbnb_net_amount'] = $net;
          $meta['hostpn_financial_net_amount'] = $net;
        }

        if (isset($row['Importe del IVA'])) {
          $vat = self::parse_amount($row['Importe del IVA']);
          $meta['hostpn_airbnb_vat_amount'] = $vat;
          $meta['hostpn_financial_tax_amount'] = $vat;
        }

        if (isset($row['Importe bruto'])) {
          $gross = self::parse_amount($row['Importe bruto']);
          $meta['hostpn_airbnb_gross_amount'] = $gross;
          $meta['hostpn_financial_amount'] = $gross;
        }

        // Date
        if (isset($row['Fecha del servicio'])) {
          $date = self::parse_date($row['Fecha del servicio']);
          if ($date) {
            $meta['hostpn_financial_date'] = $date;
            $meta['hostpn_financial_year'] = date('Y', strtotime($date));
            $meta['hostpn_financial_quarter'] = self::get_quarter_from_date($date);
          }
        }

        break;

      case 'booking':
        $meta['hostpn_financial_platform'] = 'booking';
        $meta['hostpn_financial_record_type'] = 'income';

        // Map Booking.com fields
        if (isset($row['Número de referencia'])) $meta['hostpn_booking_reference_number'] = $row['Número de referencia'];
        if (isset($row['Estado de la reserva'])) $meta['hostpn_booking_reservation_status'] = $row['Estado de la reserva'];
        if (isset($row['Estado del pago'])) $meta['hostpn_booking_payment_status'] = $row['Estado del pago'];
        if (isset($row['Moneda'])) $meta['hostpn_financial_currency'] = $row['Moneda'];

        // Amounts
        if (isset($row['Importe'])) {
          $amount = self::parse_amount($row['Importe']);
          $meta['hostpn_booking_amount'] = $amount;
          $meta['hostpn_financial_amount'] = $amount;
        }

        if (isset($row['Comisión'])) {
          $meta['hostpn_booking_commission'] = self::parse_amount($row['Comisión']);
        }

        if (isset($row['Cargo por servicio de los pagos'])) {
          $meta['hostpn_booking_service_charge'] = self::parse_amount($row['Cargo por servicio de los pagos']);
        }

        if (isset($row['IVA por los servicios de la plataforma online'])) {
          $meta['hostpn_booking_vat_platform'] = self::parse_amount($row['IVA por los servicios de la plataforma online']);
        }

        if (isset($row['Neto'])) {
          $net = self::parse_amount($row['Neto']);
          $meta['hostpn_booking_net_amount'] = $net;
          $meta['hostpn_financial_net_amount'] = $net;
        }

        if (isset($row['ID del pago'])) $meta['hostpn_booking_payment_id'] = $row['ID del pago'];

        // Dates
        if (isset($row['Check-in'])) {
          $date = self::parse_date($row['Check-in']);
          if ($date) {
            $meta['hostpn_financial_date'] = $date;
            $meta['hostpn_financial_year'] = date('Y', strtotime($date));
            $meta['hostpn_financial_quarter'] = self::get_quarter_from_date($date);
          }
        }

        if (isset($row['Fecha de emisión del pago'])) {
          $payment_date = self::parse_date($row['Fecha de emisión del pago']);
          if ($payment_date) {
            $meta['hostpn_booking_payment_date'] = $payment_date;
          }
        }

        break;
    }

    return $meta;
  }

  /**
   * Parse amount from string (handles negative, decimals, etc).
   *
   * @since    1.0.36
   * @param    string    $amount    Amount string
   * @return   float                Parsed amount
   */
  private static function parse_amount($amount) {
    // Remove currency symbols and spaces
    $amount = preg_replace('/[^0-9,.\-]/', '', $amount);

    // Handle European format (comma as decimal separator)
    if (strpos($amount, ',') !== false && strpos($amount, '.') !== false) {
      // Both comma and dot present, assume European format (dot = thousands, comma = decimal)
      $amount = str_replace('.', '', $amount);
      $amount = str_replace(',', '.', $amount);
    } elseif (strpos($amount, ',') !== false) {
      // Only comma present, assume it's decimal separator
      $amount = str_replace(',', '.', $amount);
    }

    return round(floatval($amount), 2);
  }

  /**
   * Parse date from various formats.
   *
   * @since    1.0.36
   * @param    string    $date_string    Date string
   * @return   string|false              Date in Y-m-d format or false
   */
  private static function parse_date($date_string) {
    $date_string = trim($date_string);

    if (empty($date_string)) {
      return false;
    }

    // Try to parse various date formats
    $formats = [
      'Y-m-d',
      'd/m/Y',
      'd-m-Y',
      'm/d/Y',
      'd M Y',
      'j M Y',
    ];

    foreach ($formats as $format) {
      $date = DateTime::createFromFormat($format, $date_string);
      if ($date && $date->format($format) === $date_string) {
        return $date->format('Y-m-d');
      }
    }

    // Try strtotime as fallback
    $timestamp = strtotime($date_string);
    if ($timestamp !== false) {
      return date('Y-m-d', $timestamp);
    }

    return false;
  }

  /**
   * Get quarter from date.
   *
   * @since    1.0.36
   * @param    string    $date    Date in Y-m-d format
   * @return   string             Quarter (Q1-Q4)
   */
  private static function get_quarter_from_date($date) {
    $month = (int) date('n', strtotime($date));

    if ($month >= 1 && $month <= 3) return 'Q1';
    if ($month >= 4 && $month <= 6) return 'Q2';
    if ($month >= 7 && $month <= 9) return 'Q3';
    return 'Q4';
  }
}

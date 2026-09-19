<?php
/**
 * Class HOSTPN_Financial_Importer
 *
 * Handles CSV parsing, format detection, and automated payment imports for Booking.com and Airbnb CSV files.
 *
 * @package hostpn
 * @subpackage hostpn/includes
 * @since 1.0.36
 */

if (!defined('ABSPATH')) {
  exit;
}

class HOSTPN_Financial_Importer {

  /**
   * Detect CSV format based on header signature.
   *
   * @param string $file_path Absolute path to uploaded CSV file.
   * @return array Array with format key ('airbnb_payout', 'airbnb_fiscal', 'booking_reservations', 'generic') and label.
   */
  public static function detect_format($file_path) {
    if (!file_exists($file_path) || !is_readable($file_path)) {
      return [
        'format' => 'unknown',
        'label'  => __('Unknown / Unreadable File', 'hostpn'),
      ];
    }

    $handle = fopen($file_path, 'r');
    if (!$handle) {
      return [
        'format' => 'unknown',
        'label'  => __('Could not open CSV file', 'hostpn'),
      ];
    }

    // Read first 5 lines to detect encoding / delimiter / headers
    $first_lines = [];
    for ($i = 0; $i < 5; $i++) {
      $line = fgets($handle);
      if ($line !== false) {
        $first_lines[] = mb_convert_encoding($line, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
      }
    }
    fclose($handle);

    $header_str = strtolower(implode(' ', $first_lines));

    // Airbnb Payout / Reservation report signatures
    if (
      strpos($header_str, 'confirmation code') !== false ||
      strpos($header_str, 'código de confirmación') !== false ||
      strpos($header_str, 'gross earnings') !== false ||
      strpos($header_str, 'ingresos brutos') !== false ||
      (strpos($header_str, 'airbnb') !== false && strpos($header_str, 'payout') !== false)
    ) {
      return [
        'format' => 'airbnb_payout',
        'label'  => __('Airbnb (Informe de Pagos / Reservas)', 'hostpn'),
      ];
    }

    // Airbnb Fiscal Summary signature
    if (
      strpos($header_str, 'documento fiscal') !== false ||
      strpos($header_str, 'fiscal document') !== false ||
      strpos($header_str, 'resumen fiscal') !== false
    ) {
      return [
        'format' => 'airbnb_fiscal',
        'label'  => __('Airbnb (Resumen Fiscal)', 'hostpn'),
      ];
    }

    // Booking.com signature
    if (
      strpos($header_str, 'booking number') !== false ||
      strpos($header_str, 'número de reserva') !== false ||
      strpos($header_str, 'book number') !== false ||
      strpos($header_str, 'booking.com') !== false ||
      strpos($header_str, 'commission') !== false ||
      strpos($header_str, 'comisión') !== false
    ) {
      return [
        'format' => 'booking_reservations',
        'label'  => __('Booking.com (Informe de Reservas)', 'hostpn'),
      ];
    }

    return [
      'format' => 'generic',
      'label'  => __('CSV Genérico de Ingresos', 'hostpn'),
    ];
  }

  /**
   * Parse CSV file and match rows to accommodation rooms and guests.
   *
   * @param string $file_path
   * @param int    $accommodation_id
   * @return array Parsed records list and statistics
   */
  public static function parse_file($file_path, $accommodation_id) {
    $detection = self::detect_format($file_path);
    $format = $detection['format'];

    $handle = fopen($file_path, 'r');
    if (!$handle) {
      return [
        'success' => false,
        'error'   => __('Unable to open CSV file for reading.', 'hostpn'),
      ];
    }

    // Auto-detect delimiter
    $sample = fgets($handle);
    rewind($handle);
    $delimiter = ',';
    if (strpos($sample, ';') !== false && substr_count($sample, ';') > substr_count($sample, ',')) {
      $delimiter = ';';
    } elseif (strpos($sample, "\t") !== false && substr_count($sample, "\t") > substr_count($sample, ',')) {
      $delimiter = "\t";
    }

    $raw_headers = fgetcsv($handle, 0, $delimiter);
    if (!$raw_headers) {
      fclose($handle);
      return [
        'success' => false,
        'error'   => __('CSV file appears to be empty.', 'hostpn'),
      ];
    }

    // Standardize headers
    $headers = array_map(function($h) {
      return strtolower(trim(preg_replace('/[\x00-\x1F\x7F-\x9F\xEF\xBB\xBF]/', '', $h)));
    }, $raw_headers);

    // Get rooms in this accommodation for auto-matching
    $rooms = HOSTPN_Post_Type_Room::hostpn_get_rooms_by_accommodation($accommodation_id);
    $room_maps = [];
    foreach ($rooms as $r_id) {
      $r_num = get_post_meta($r_id, 'hostpn_room_number', true);
      $r_title = get_the_title($r_id);
      $g_id = get_post_meta($r_id, 'hostpn_room_guest_id', true);
      $g_name = '';
      if (!empty($g_id)) {
        $g_name = trim(get_post_meta($g_id, 'hostpn_name', true) . ' ' . get_post_meta($g_id, 'hostpn_surname', true));
        if (empty($g_name)) {
          $g_name = get_the_title($g_id);
        }
      }

      $room_maps[] = [
        'id'         => $r_id,
        'number'     => strtolower(trim($r_num)),
        'title'      => strtolower(trim($r_title)),
        'guest_id'   => $g_id,
        'guest_name' => strtolower(trim($g_name)),
      ];
    }

    $records = [];
    $row_index = 1;

    while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
      $row_index++;
      if (empty(array_filter($data))) {
        continue;
      }

      $row = [];
      foreach ($headers as $idx => $header_name) {
        $row[$header_name] = isset($data[$idx]) ? trim($data[$idx]) : '';
      }

      $extracted = self::extract_row_data($row, $format, $headers);
      if (!$extracted || $extracted['amount'] <= 0) {
        continue;
      }

      // Attempt to match room
      $matched_room_id = 0;
      $matched_room_label = '';

      foreach ($room_maps as $rm) {
        if (!empty($extracted['room_name'])) {
          $r_search = strtolower($extracted['room_name']);
          if (!empty($rm['number']) && (strpos($r_search, $rm['number']) !== false || $r_search === $rm['number'])) {
            $matched_room_id = $rm['id'];
            $matched_room_label = sprintf(__('Habitación %s', 'hostpn'), strtoupper($rm['number']));
            break;
          }
          if (strpos($r_search, $rm['title']) !== false || strpos($rm['title'], $r_search) !== false) {
            $matched_room_id = $rm['id'];
            $matched_room_label = sprintf(__('Habitación %s', 'hostpn'), strtoupper($rm['number'] ? $rm['number'] : $rm['title']));
            break;
          }
        }

        if (!$matched_room_id && !empty($extracted['guest_name']) && !empty($rm['guest_name'])) {
          $g_search = strtolower($extracted['guest_name']);
          if (strpos($g_search, $rm['guest_name']) !== false || strpos($rm['guest_name'], $g_search) !== false) {
            $matched_room_id = $rm['id'];
            $matched_room_label = sprintf(__('Habitación %s', 'hostpn'), strtoupper($rm['number'] ? $rm['number'] : $rm['title']));
            break;
          }
        }
      }

      // Default to first room if unmatched
      if (!$matched_room_id && !empty($rooms)) {
        $matched_room_id = $rooms[0];
        $r_num = get_post_meta($matched_room_id, 'hostpn_room_number', true);
        $matched_room_label = sprintf(__('Habitación %s', 'hostpn'), strtoupper($r_num ? $r_num : get_the_title($matched_room_id)));
      }

      $records[] = [
        'row'                => $row_index,
        'guest_name'         => !empty($extracted['guest_name']) ? $extracted['guest_name'] : __('Huésped CSV', 'hostpn'),
        'room_name'          => !empty($extracted['room_name']) ? $extracted['room_name'] : '',
        'matched_room_id'    => $matched_room_id,
        'matched_room_label' => $matched_room_label,
        'amount'             => $extracted['amount'],
        'date'               => $extracted['date'],
        'month_key'          => date('Y_m', strtotime($extracted['date'])),
        'payment_type'       => $extracted['payment_type'],
        'source'             => $extracted['source'],
        'reference'          => $extracted['reference'],
      ];
    }

    fclose($handle);

    return [
      'success'        => true,
      'detected_format'=> $detection,
      'total_records'  => count($records),
      'records'        => $records,
    ];
  }

  /**
   * Extract key payment fields from raw CSV row depending on format.
   *
   * @param array  $row Normalized CSV row.
   * @param string $format Detected CSV format.
   * @param array  $headers Header names.
   * @return array Extracted dataset.
   */
  private static function extract_row_data($row, $format, $headers) {
    $guest_name = '';
    $room_name = '';
    $amount = 0.0;
    $date_str = date('Y-m-d');
    $source = 'manual';
    $reference = '';
    $payment_type = 'rent';

    if ($format === 'airbnb_payout' || $format === 'airbnb_fiscal') {
      $source = 'airbnb';
      $guest_name = self::get_first_val($row, ['guest', 'huésped', 'huesped', 'guest name', 'nombre del huésped']);
      $room_name  = self::get_first_val($row, ['listing', 'anuncio', 'propiedad', 'accommodation']);
      $raw_amt    = self::get_first_val($row, ['amount', 'importe', 'net amount', 'payout', 'ingresos brutos', 'gross earnings', 'paid out']);
      $raw_date   = self::get_first_val($row, ['date', 'fecha', 'start date', 'fecha de inicio', 'paid out date']);
      $reference  = self::get_first_val($row, ['confirmation code', 'código de confirmación', 'reference']);

      $amount = self::clean_amount($raw_amt);
      if (!empty($raw_date)) {
        $ts = strtotime($raw_date);
        if ($ts) {
          $date_str = date('Y-m-d', $ts);
        }
      }
    } elseif ($format === 'booking_reservations') {
      $source = 'booking';
      $guest_name = self::get_first_val($row, ['guest name', 'nombre del huésped', 'guest', 'huésped', 'huesped']);
      $room_name  = self::get_first_val($row, ['room name', 'habitación', 'habitacion', 'unit', 'property']);
      $raw_amt    = self::get_first_val($row, ['price', 'precio', 'total price', 'amount', 'payout', 'importe']);
      $raw_date   = self::get_first_val($row, ['check-in', 'llegada', 'fecha de llegada', 'booked on', 'fecha de reserva']);
      $reference  = self::get_first_val($row, ['booking number', 'número de reserva', 'numero de reserva', 'book number']);

      $amount = self::clean_amount($raw_amt);
      if (!empty($raw_date)) {
        $ts = strtotime($raw_date);
        if ($ts) {
          $date_str = date('Y-m-d', $ts);
        }
      }
    } else {
      // Generic format
      foreach ($row as $k => $v) {
        if (empty($guest_name) && (strpos($k, 'guest') !== false || strpos($k, 'nombre') !== false || strpos($k, 'name') !== false)) {
          $guest_name = $v;
        }
        if (empty($room_name) && (strpos($k, 'room') !== false || strpos($k, 'habita') !== false || strpos($k, 'prop') !== false)) {
          $room_name = $v;
        }
        if ($amount <= 0 && (strpos($k, 'amount') !== false || strpos($k, 'price') !== false || strpos($k, 'precio') !== false || strpos($k, 'importe') !== false || strpos($k, 'total') !== false)) {
          $amount = self::clean_amount($v);
        }
        if (empty($raw_date) && (strpos($k, 'date') !== false || strpos($k, 'fecha') !== false)) {
          $ts = strtotime($v);
          if ($ts) {
            $date_str = date('Y-m-d', $ts);
          }
        }
      }
    }

    return [
      'guest_name'   => $guest_name,
      'room_name'    => $room_name,
      'amount'       => $amount,
      'date'         => $date_str,
      'payment_type' => $payment_type,
      'source'       => $source,
      'reference'    => $reference,
    ];
  }

  /**
   * Helper to fetch first existing key value from row array.
   */
  private static function get_first_val($row, $keys) {
    foreach ($keys as $k) {
      if (isset($row[$k]) && $row[$k] !== '') {
        return $row[$k];
      }
    }
    return '';
  }

  /**
   * Clean numeric float from currency string (e.g. € 1.250,50 -> 1250.50).
   */
  private static function clean_amount($val) {
    if (is_numeric($val)) {
      return floatval($val);
    }
    $clean = preg_replace('/[^\d\,\.]/', '', (string)$val);
    if (strpos($clean, ',') !== false && strpos($clean, '.') !== false) {
      if (strrpos($clean, ',') > strrpos($clean, '.')) {
        $clean = str_replace('.', '', $clean);
        $clean = str_replace(',', '.', $clean);
      } else {
        $clean = str_replace(',', '', $clean);
      }
    } elseif (strpos($clean, ',') !== false) {
      $clean = str_replace(',', '.', $clean);
    }
    return is_numeric($clean) ? floatval($clean) : 0.0;
  }

  /**
   * Execute import of selected records into room payment meta.
   *
   * @param int   $accommodation_id
   * @param array $records Array of record items to import.
   * @return array Result count and logs.
   */
  public static function execute_import($accommodation_id, $records) {
    if (empty($records) || !is_array($records)) {
      return [
        'success'        => false,
        'imported_count' => 0,
        'message'        => __('No records selected to import.', 'hostpn'),
      ];
    }

    $imported_count = 0;

    foreach ($records as $r) {
      $room_id = isset($r['matched_room_id']) ? intval($r['matched_room_id']) : 0;
      if (!$room_id) {
        continue;
      }

      $amount       = floatval($r['amount']);
      $payment_type = !empty($r['payment_type']) ? sanitize_key($r['payment_type']) : 'rent';
      $payment_date = !empty($r['date']) ? sanitize_text_field($r['date']) : date('Y-m-d');
      $month_key    = !empty($r['month_key']) ? sanitize_key($r['month_key']) : date('Y_m', strtotime($payment_date));
      $source       = !empty($r['source']) ? sanitize_key($r['source']) : 'csv';
      $reference    = !empty($r['reference']) ? sanitize_text_field($r['reference']) : '';
      $guest_name   = !empty($r['guest_name']) ? sanitize_text_field($r['guest_name']) : '';

      HOSTPN_Post_Type_Accommodation::hostpn_record_room_payment(
        $room_id,
        $amount,
        $payment_type,
        $month_key,
        $payment_date,
        $source,
        sprintf('%s %s', $guest_name, $reference ? "($reference)" : '')
      );

      $imported_count++;
    }

    return [
      'success'        => true,
      'imported_count' => $imported_count,
      'message'        => sprintf(__('Successfully imported %d payment records.', 'hostpn'), $imported_count),
    ];
  }
}

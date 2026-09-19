<?php
/**
 * accommodation creator.
 *
 * This class defines accommodation options, menus and templates.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    hostpn
 * @subpackage hostpn/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Post_Type_Accommodation {
  public function hostpn_accommodation_get_fields($accommodation_id = 0) {
    $hostpn_fields = [];
      $hostpn_fields['hostpn_accommodation_title'] = [
        'id' => 'hostpn_accommodation_title',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'value' => !empty($accommodation_id) ? get_the_title($accommodation_id) : '',
        'label' => __('Accommodation title', 'hostpn'),
        'placeholder' => __('Accommodation title', 'hostpn'),
      ];
      $hostpn_fields['hostpn_description'] = [
        'id' => 'hostpn_description',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'value' => !empty($accommodation_id) ? (str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($accommodation_id)->post_content))) : '',
        'input' => 'textarea',
        'label' => __('Accommodation description', 'hostpn'),
      ];
      
    return $hostpn_fields;
  }

  public function hostpn_accommodation_get_fields_meta() {
    $hostpn_fields_meta = [];
      $hostpn_fields_meta['hostpn_accommodation_code'] = [
        'id' => 'hostpn_accommodation_code',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'required' => true,
        'xml' => 'codigoEstablecimiento',
        'label' => __('Accommodation code', 'hostpn'),
        'placeholder' => __('Accommodation code', 'hostpn'),
      ];
      // NRUA genérico del alojamiento para exportaciones CSV
      $hostpn_fields_meta['hostpn_nrua'] = [
        'id' => 'hostpn_nrua',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'label' => __('NRUA (generic stay number)', 'hostpn'),
        'placeholder' => __('NRUA used in CSV exports', 'hostpn'),
        'description' => __('Generic NRUA number stored in the Accommodation metadata and used in police CSV exports.', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_accommodation_type'] = [
        'id' => 'hostpn_accommodation_type',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => HOSTPN_Data::hostpn_accommodation_types(),
        'xml' => 'codigoEstablecimiento',
        'label' => __('Accommodation type', 'hostpn'),
        'placeholder' => __('Accommodation type', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_accommodation_address'] = [
        'id' => 'hostpn_accommodation_address',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'direccion',
        'label' => esc_html(__('Address', 'hostpn')),
        'placeholder' => esc_html(__('Address', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_accommodation_address_alt'] = [
        'id' => 'hostpn_accommodation_address_alt',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'direccionComplementaria',
        'label' => esc_html(__('Address complementary information', 'hostpn')),
        'placeholder' => esc_html(__('Address complementary information', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_accommodation_country'] = [
        'id' => 'hostpn_accommodation_country',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => HOSTPN_Data::hostpn_countries(),
        'parent' => 'this',
        'xml' => 'pais',
        'label' => esc_html(__('Accommodation country', 'hostpn')),
        'placeholder' => esc_html(__('Accommodation country', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_accommodation_postal_code'] = [
        'id' => 'hostpn_accommodation_postal_code',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'parent' => 'hostpn_accommodation_country',
        'parent_option' => 'es',
        'xml' => 'codigoMunicipio',
        'label' => esc_html(__('Accommodation postal code', 'hostpn')),
        'placeholder' => esc_html(__('Accommodation postal code', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_accommodation_city'] = [
        'id' => 'hostpn_accommodation_city',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'nombreMunicipio',
        'label' => esc_html(__('Accommodation city', 'hostpn')),
        'placeholder' => esc_html(__('Accommodation city', 'hostpn')),
      ];

      $hostpn_fields_meta['hostpn_accommodation_gallery'] = [
        'id' => 'hostpn_accommodation_gallery',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'attachment',
        'type' => 'image',
        'multiple' => true,
        'label' => esc_html(__('Photo Gallery', 'hostpn')),
        'placeholder' => esc_html(__('Select images', 'hostpn')),
        'description' => esc_html(__('Upload multiple images to create a photo gallery for this accommodation.', 'hostpn')),
      ];

      $hostpn_fields_meta['hostpn_accommodation_form'] = [
        'id' => 'hostpn_accommodation_form',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'hidden',
        'value' => 'hostpn_accommodation_form',
      ];
      $hostpn_fields_meta['hostpn_ajax_nonce'] = [
        'id' => 'hostpn_ajax_nonce',
        'input' => 'input',
        'type' => 'nonce',
      ];

      // New editor fields for rules and conditions
      $hostpn_fields_meta['hostpn_accommodation_rules'] = [
        'id' => 'hostpn_accommodation_rules',
        'input' => 'editor',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'label' => esc_html(__('Accommodation Rules', 'hostpn')),
        'description' => esc_html(__('Enter the accommodation rules and policies', 'hostpn')),
      ];

      $hostpn_fields_meta['hostpn_checkin_conditions'] = [
        'id' => 'hostpn_checkin_conditions',
        'input' => 'editor',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'label' => esc_html(__('Check-in Conditions', 'hostpn')),
        'description' => esc_html(__('Enter the check-in conditions and requirements', 'hostpn')),
      ];

      $hostpn_fields_meta['hostpn_checkout_conditions'] = [
        'id' => 'hostpn_checkout_conditions',
        'input' => 'editor',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'label' => esc_html(__('Check-out Conditions', 'hostpn')),
        'description' => esc_html(__('Enter the check-out conditions and requirements', 'hostpn')),
      ];

      // Accommodation features - Parking
      $hostpn_fields_meta['hostpn_parking_description'] = [
        'id' => 'hostpn_parking_description',
        'input' => 'textarea',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'label' => esc_html(__('Parking Description', 'hostpn')),
        'description' => esc_html(__('Describe parking availability and conditions', 'hostpn')),
      ];

      // Accommodation features - Internet
      $hostpn_fields_meta['hostpn_internet_description'] = [
        'id' => 'hostpn_internet_description',
        'input' => 'textarea',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'label' => esc_html(__('Internet Description', 'hostpn')),
        'description' => esc_html(__('Describe internet speed and features', 'hostpn')),
      ];

      // Cleaning system options
      $hostpn_fields_meta['hostpn_cleaning_system'] = [
        'id'          => 'hostpn_cleaning_system',
        'class'       => 'hostpn-select hostpn-width-100-percent',
        'input'       => 'select',
        'options'     => [
          'punctual' => __('Punctual per room', 'hostpn'),
          'shared'   => __('Shared periodic rotation', 'hostpn'),
        ],
        'label'       => esc_html(__('Cleaning System', 'hostpn')),
        'description' => esc_html(__('Choose between punctual room cleaning or shared periodic rotation between occupied rooms.', 'hostpn')),
      ];

      $hostpn_fields_meta['hostpn_shared_cleaning_frequency_days'] = [
        'id'          => 'hostpn_shared_cleaning_frequency_days',
        'class'       => 'hostpn-input hostpn-width-100-percent',
        'input'       => 'input',
        'type'        => 'number',
        'label'       => esc_html(__('Shared Cleaning Frequency (days)', 'hostpn')),
        'description' => esc_html(__('Interval in days between periodic cleanings (e.g. 7 for weekly).', 'hostpn')),
      ];

      $hostpn_fields_meta['hostpn_shared_cleaning_notice_days'] = [
        'id'          => 'hostpn_shared_cleaning_notice_days',
        'class'       => 'hostpn-input hostpn-width-100-percent',
        'input'       => 'input',
        'type'        => 'number',
        'label'       => esc_html(__('Notice Days Prior to Cleaning', 'hostpn')),
        'description' => esc_html(__('How many days before the cleaning date to send email notifications.', 'hostpn')),
      ];

      $hostpn_fields_meta['hostpn_shared_cleaning_stays'] = [
        'id'          => 'hostpn_shared_cleaning_stays',
        'class'       => 'hostpn-input hostpn-width-100-percent',
        'input'       => 'textarea',
        'label'       => esc_html(__('Stays / Areas to Clean', 'hostpn')),
        'description' => esc_html(__('Comma separated list of rooms or areas to clean during shared cleaning.', 'hostpn')),
      ];

      // Accommodation features - Kitchen Section Start
      $hostpn_fields_meta['hostpn_kitchen_section_start'] = [
        'id' => 'hostpn_kitchen_section_start',
        'section' => 'start',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-kitchen-section',
        'label' => esc_html(__('Kitchen Features', 'hostpn')),
      ];

      // Generate kitchen features using translated features
      $accommodation_features = HOSTPN_i18n::hostpn_get_accommodation_features();
      foreach ($accommodation_features['kitchen']['features'] as $meta_key => $feature_label) {
        $feature_id = str_replace('hostpn_', '', $meta_key);
        $hostpn_fields_meta[$meta_key] = [
          'id' => $meta_key,
          'input' => 'input',
          'type' => 'checkbox',
          'class' => 'hostpn-input hostpn-width-100-percent hostpn-kitchen-feature',
          'label' => esc_html($feature_label),
        ];
      }

      // Accommodation features - Kitchen additional items (html_multi)
      $hostpn_fields_meta['hostpn_kitchen_additional_features'] = [
        'id' => 'hostpn_kitchen_additional_features',
        'input' => 'html_multi',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-kitchen-additional-features',
        'html_multi_fields' => [
          [
            'id' => 'hostpn_kitchen_custom_name', 
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text', 
            'multiple' => true,
            'label' => esc_html(__('Custom kitchen feature name', 'hostpn')),
            'placeholder' => esc_html(__('Enter the name of the custom kitchen feature', 'hostpn')),
          ],
        ],
        'label' => esc_html(__('Additional Kitchen Features', 'hostpn')),
        'description' => esc_html(__('Add custom kitchen features not listed above', 'hostpn')),
      ];

      // Accommodation features - Kitchen Section End
      $hostpn_fields_meta['hostpn_kitchen_section_end'] = [
        'id' => 'hostpn_kitchen_section_end',
        'section' => 'end',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-kitchen-section',
        'label' => esc_html(__('Kitchen Features', 'hostpn')),
      ];

      // Accommodation features - Room Section Start
      $hostpn_fields_meta['hostpn_room_section_start'] = [
        'id' => 'hostpn_room_section_start',
        'section' => 'start',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-room-section',
        'label' => esc_html(__('Room Features', 'hostpn')),
      ];



      // Generate room features using translated features
      foreach ($accommodation_features['room']['features'] as $meta_key => $feature_label) {
        $feature_id = str_replace('hostpn_', '', $meta_key);
        $hostpn_fields_meta[$meta_key] = [
          'id' => $meta_key,
          'input' => 'input',
          'type' => 'checkbox',
          'class' => 'hostpn-input hostpn-width-100-percent hostpn-room-feature',
          'label' => esc_html($feature_label),
        ];
      }

      // Accommodation features - Room additional items (html_multi)
      $hostpn_fields_meta['hostpn_room_additional_features'] = [
        'id' => 'hostpn_room_additional_features',
        'input' => 'html_multi',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-room-additional-features',
        'html_multi_fields' => [
          [
            'id' => 'hostpn_room_custom_name', 
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text', 
            'multiple' => true,
            'label' => esc_html(__('Custom room feature name', 'hostpn')),
            'placeholder' => esc_html(__('Enter the name of the custom room feature', 'hostpn')),
          ],
        ],
        'label' => esc_html(__('Additional Room Features', 'hostpn')),
        'description' => esc_html(__('Add custom room features not listed above', 'hostpn')),
      ];

      // Accommodation features - Room Section End
      $hostpn_fields_meta['hostpn_room_section_end'] = [
        'id' => 'hostpn_room_section_end',
        'section' => 'end',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-room-section',
        'label' => esc_html(__('Room Features', 'hostpn')),
      ];

      // Accommodation features - Bathroom Section Start
      $hostpn_fields_meta['hostpn_bathroom_section_start'] = [
        'id' => 'hostpn_bathroom_section_start',
        'section' => 'start',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-bathroom-section',
        'label' => esc_html(__('Bathroom Features', 'hostpn')),
      ];



      // Generate bathroom features using translated features
      foreach ($accommodation_features['bathroom']['features'] as $meta_key => $feature_label) {
        $feature_id = str_replace('hostpn_', '', $meta_key);
        $hostpn_fields_meta[$meta_key] = [
          'id' => $meta_key,
          'input' => 'input',
          'type' => 'checkbox',
          'class' => 'hostpn-input hostpn-width-100-percent hostpn-bathroom-feature',
          'label' => esc_html($feature_label),
        ];
      }

      // Accommodation features - Bathroom additional items (html_multi)
      $hostpn_fields_meta['hostpn_bathroom_additional_features'] = [
        'id' => 'hostpn_bathroom_additional_features',
        'input' => 'html_multi',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-bathroom-additional-features',
        'html_multi_fields' => [
          [
            'id' => 'hostpn_bathroom_custom_name',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text', 
            'multiple' => true,
            'label' => esc_html(__('Custom bathroom feature name', 'hostpn')),
            'placeholder' => esc_html(__('Enter the name of the custom bathroom feature', 'hostpn')),
          ],
        ],
        'label' => esc_html(__('Additional Bathroom Features', 'hostpn')),
        'description' => esc_html(__('Add custom bathroom features not listed above', 'hostpn')),
      ];

      // Accommodation features - Bathroom Section End
      $hostpn_fields_meta['hostpn_bathroom_section_end'] = [
        'id' => 'hostpn_bathroom_section_end',
        'section' => 'end',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-bathroom-section',
        'label' => esc_html(__('Bathroom Features', 'hostpn')),
      ];

      // Accommodation features - Living Area Section Start
      $hostpn_fields_meta['hostpn_living_area_section_start'] = [
        'id' => 'hostpn_living_area_section_start',
        'section' => 'start',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-living-area-section',
        'label' => esc_html(__('Living Area Features', 'hostpn')),
      ];



      // Generate living area features using translated features
      foreach ($accommodation_features['living_area']['features'] as $meta_key => $feature_label) {
        $feature_id = str_replace('hostpn_', '', $meta_key);
        $hostpn_fields_meta[$meta_key] = [
          'id' => $meta_key,
          'input' => 'input',
          'type' => 'checkbox',
          'class' => 'hostpn-input hostpn-width-100-percent hostpn-living-area-feature',
          'label' => esc_html($feature_label),
        ];
      }

      // Accommodation features - Living Area additional items (html_multi)
      $hostpn_fields_meta['hostpn_living_area_additional_features'] = [
        'id' => 'hostpn_living_area_additional_features',
        'input' => 'html_multi',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-living-area-additional-features',
        'html_multi_fields' => [
          [
            'id' => 'hostpn_living_area_custom_name', 
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text', 
            'multiple' => true,
            'label' => esc_html(__('Custom living area feature name', 'hostpn')),
            'placeholder' => esc_html(__('Enter the name of the custom living area feature', 'hostpn')),
          ],
        ],
        'label' => esc_html(__('Additional Living Area Features', 'hostpn')),
        'description' => esc_html(__('Add custom living area features not listed above', 'hostpn')),
      ];

      // Accommodation features - Living Area Section End
      $hostpn_fields_meta['hostpn_living_area_section_end'] = [
        'id' => 'hostpn_living_area_section_end',
        'section' => 'end',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-living-area-section',
        'label' => esc_html(__('Living Area Features', 'hostpn')),
      ];

      // Accommodation features - Audiovisual Equipment Section Start
      $hostpn_fields_meta['hostpn_audiovisual_section_start'] = [
        'id' => 'hostpn_audiovisual_section_start',
        'section' => 'start',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-audiovisual-section',
        'label' => esc_html(__('Audiovisual Equipment', 'hostpn')),
      ];



      // Generate audiovisual features using translated features
      foreach ($accommodation_features['audiovisual']['features'] as $meta_key => $feature_label) {
        $feature_id = str_replace('hostpn_', '', $meta_key);
        $hostpn_fields_meta[$meta_key] = [
          'id' => $meta_key,
          'input' => 'input',
          'type' => 'checkbox',
          'class' => 'hostpn-input hostpn-width-100-percent hostpn-audiovisual-feature',
          'label' => esc_html($feature_label),
        ];
      }

      // Accommodation features - Audiovisual additional items (html_multi)
      $hostpn_fields_meta['hostpn_audiovisual_additional_features'] = [
        'id' => 'hostpn_audiovisual_additional_features',
        'input' => 'html_multi',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-audiovisual-additional-features',
        'html_multi_fields' => [
          [
            'id' => 'hostpn_audiovisual_custom_name', 
            'input' => 'input',
            'type' => 'text', 
            'multiple' => true,
            'label' => esc_html(__('Custom audiovisual feature name', 'hostpn')),
            'placeholder' => esc_html(__('Enter the name of the custom audiovisual feature', 'hostpn')),
          ],
        ],
        'label' => esc_html(__('Additional Audiovisual Features', 'hostpn')),
        'description' => esc_html(__('Add custom audiovisual features not listed above', 'hostpn')),
      ];

      // Accommodation features - Audiovisual Section End
      $hostpn_fields_meta['hostpn_audiovisual_section_end'] = [
        'id' => 'hostpn_audiovisual_section_end',
        'section' => 'end',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-audiovisual-section',
        'label' => esc_html(__('Audiovisual Equipment', 'hostpn')),
      ];

    return $hostpn_fields_meta;
  }

  /**
   * Get financial management fields for separate metabox.
   */  /**
   * Helper to record a payment for a room (Monthly Rent, Deposit, Custom).
   *
   * @param int    $room_id
   * @param float  $amount
   * @param string $payment_type 'rent' | 'deposit' | 'custom'
   * @param string $month_key    'YYYY_MM'
   * @param string $payment_date 'YYYY-MM-DD'
   * @param string $source       'manual' | 'airbnb' | 'booking' | 'csv'
   * @param string $notes
   * @return array
   */
  /**
   * Recalculate room payment status for rent or deposit based on accumulated payments.
   */
  public static function hostpn_recalculate_room_payment_status($room_id, $payment_type = 'rent', $month_key = '') {
    if (!$room_id) {
      return;
    }

    $history = get_post_meta($room_id, 'hostpn_room_payment_history', true);
    if (!is_array($history)) {
      $history = get_post_meta($room_id, 'hostpn_room_payments_log', true);
    }
    if (!is_array($history)) {
      $history = [];
    }

    // Always recalculate deposit
    $deposit_expected = 0.0;
    $r_dep = get_post_meta($room_id, 'hostpn_room_contract_deposit_amount', true);
    if (empty($r_dep) || !is_numeric($r_dep)) $r_dep = get_post_meta($room_id, 'hostpn_room_deposit', true);
    if (is_numeric($r_dep)) $deposit_expected = floatval($r_dep);

    $total_deposit_paid = 0.0;
    foreach ($history as $rec) {
      $p_type = isset($rec['payment_type']) ? $rec['payment_type'] : '';
      if ($p_type === 'deposit') {
        $total_deposit_paid += floatval(isset($rec['amount']) ? $rec['amount'] : 0);
      }
    }
    update_post_meta($room_id, 'hostpn_room_deposit_paid_amount', $total_deposit_paid);
    if ($deposit_expected > 0 && $total_deposit_paid >= $deposit_expected) {
      update_post_meta($room_id, 'hostpn_room_deposit_paid', '1');
    } else {
      update_post_meta($room_id, 'hostpn_room_deposit_paid', '0');
    }

    // Recalculate rent for month_key
    if (empty($month_key)) {
      $month_key = date('Y_m');
    }

    $rent_expected = 0.0;
    $r_rent = get_post_meta($room_id, 'hostpn_room_contract_rent_amount', true);
    if (empty($r_rent) || !is_numeric($r_rent)) $r_rent = get_post_meta($room_id, 'hostpn_room_rent', true);
    if (empty($r_rent) || !is_numeric($r_rent)) $r_rent = get_post_meta($room_id, 'hostpn_room_price', true);
    if (is_numeric($r_rent)) $rent_expected = floatval($r_rent);

    $total_rent_paid = 0.0;
    foreach ($history as $rec) {
      $p_type = isset($rec['payment_type']) ? $rec['payment_type'] : 'rent';
      $p_mkey = isset($rec['month_key']) ? $rec['month_key'] : (isset($rec['payment_date']) ? date('Y_m', strtotime($rec['payment_date'])) : '');
      if ($p_type === 'rent' && $p_mkey === $month_key) {
        $total_rent_paid += floatval(isset($rec['amount']) ? $rec['amount'] : 0);
      }
    }

    update_post_meta($room_id, 'hostpn_room_rent_paid_amount_' . $month_key, $total_rent_paid);
    if ($rent_expected > 0 && $total_rent_paid >= $rent_expected) {
      update_post_meta($room_id, 'hostpn_room_rent_paid_' . $month_key, '1');
    } else {
      update_post_meta($room_id, 'hostpn_room_rent_paid_' . $month_key, '0');
    }
  }

  public static function hostpn_record_room_payment($room_id, $amount, $payment_type = 'rent', $month_key = '', $payment_date = '', $source = 'manual', $notes = '') {
    if (!$room_id || $amount <= 0) {
      return ['success' => false, 'message' => __('Invalid payment data', 'hostpn')];
    }

    if (empty($payment_date)) {
      $payment_date = current_time('Y-m-d');
    }
    if (empty($month_key)) {
      $month_key = date('Y_m', strtotime($payment_date));
    }

    $history = get_post_meta($room_id, 'hostpn_room_payment_history', true);
    if (!is_array($history)) {
      $history = [];
    }

    $payment_record = [
      'id'           => uniqid('pay_'),
      'amount'       => floatval($amount),
      'payment_type' => sanitize_key($payment_type),
      'month_key'    => sanitize_key($month_key),
      'payment_date' => sanitize_text_field($payment_date),
      'source'       => sanitize_key($source),
      'notes'        => sanitize_textarea_field($notes),
      'created_at'   => current_time('mysql'),
    ];

    array_unshift($history, $payment_record);
    update_post_meta($room_id, 'hostpn_room_payment_history', $history);

    // Recalculate payment statuses dynamically
    self::hostpn_recalculate_room_payment_status($room_id, $payment_type, $month_key);

    return [
      'success' => true,
      'record'  => $payment_record,
    ];
  }

  /**
   * Render financial dashboard content for metabox.
   *
   * @param int $accommodation_id
   * @return string HTML
   */
  public static function hostpn_render_admin_financial_dashboard_content($accommodation_id) {
    if (!$accommodation_id) {
      return '<p class="hostpn-mgmt-empty">' . esc_html__('Please save the accommodation post to view financial management.', 'hostpn') . '</p>';
    }

    $data = self::hostpn_get_financial_summary($accommodation_id);
    $rooms = isset($data['rooms']) ? $data['rooms'] : [];
    $expenses = isset($data['expenses']) ? $data['expenses'] : [];
    $chart_months = isset($data['chart_months']) ? $data['chart_months'] : [];

    $expected_rent  = floatval($data['total_monthly_rent_expected']);
    $collected_rent = floatval($data['total_monthly_rent_collected']);
    $expected_dep   = floatval($data['total_deposits_expected']);
    $collected_dep  = floatval($data['total_deposits_collected']);
    $total_expenses = floatval($data['total_expenses']);
    $net_income     = floatval($data['net_income']);

    $nonce = wp_create_nonce('hostpn-nonce');
    $ajax_url = admin_url('admin-ajax.php');

    ob_start();
    ?>
    <div class="hostpn-mgmt-panel hostpn-admin-financial-panel" data-accommodation-id="<?php echo esc_attr($accommodation_id); ?>">
      <div class="hostpn-mgmt-financial-content">
        <!-- 5 Metric Cards Grid -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:14px; margin-bottom:24px;">
          <div style="background:#ffffff; color:#0f172a; border:1px solid #e2e8f0; border-radius:8px; padding:16px; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
            <div style="font-size:11px; text-transform:uppercase; color:#64748b; font-weight:600; letter-spacing:0.5px;"><?php esc_html_e('Tasa de Ocupación', 'hostpn'); ?></div>
            <div style="font-size:20px; font-weight:700; color:#0f172a; margin-top:4px;"><?php echo esc_html($data['occupied_rooms']); ?> / <?php echo esc_html($data['total_rooms']); ?> (<?php echo esc_html($data['occupancy_rate']); ?>%)</div>
          </div>

          <div style="background:#ffffff; color:#0f172a; border:1px solid #e2e8f0; border-radius:8px; padding:16px; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
            <div style="font-size:11px; text-transform:uppercase; color:#64748b; font-weight:600; letter-spacing:0.5px;"><?php esc_html_e('Cobrado este Mes', 'hostpn'); ?></div>
            <div style="font-size:20px; font-weight:700; color:#0284c7; margin-top:4px;">€ <?php echo esc_html(number_format($collected_rent, 2, ',', '.')); ?></div>
            <div style="font-size:11px; color:#64748b; margin-top:4px; font-weight:500;"><?php esc_html_e('Previsto:', 'hostpn'); ?> € <?php echo esc_html(number_format($expected_rent, 2, ',', '.')); ?></div>
          </div>

          <div style="background:#ffffff; color:#0f172a; border:1px solid #e2e8f0; border-radius:8px; padding:16px; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
            <div style="font-size:11px; text-transform:uppercase; color:#64748b; font-weight:600; letter-spacing:0.5px;"><?php esc_html_e('Fianzas Custodiadas', 'hostpn'); ?></div>
            <div style="font-size:20px; font-weight:700; color:#2563eb; margin-top:4px;">€ <?php echo esc_html(number_format($collected_dep, 2, ',', '.')); ?></div>
            <div style="font-size:11px; color:#64748b; margin-top:4px; font-weight:500;"><?php esc_html_e('Previsto:', 'hostpn'); ?> € <?php echo esc_html(number_format($expected_dep, 2, ',', '.')); ?></div>
          </div>

          <div style="background:#ffffff; color:#0f172a; border:1px solid #e2e8f0; border-radius:8px; padding:16px; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
            <div style="font-size:11px; text-transform:uppercase; color:#64748b; font-weight:600; letter-spacing:0.5px;"><?php esc_html_e('Gastos Totales', 'hostpn'); ?></div>
            <div style="font-size:20px; font-weight:700; color:#dc2626; margin-top:4px;">€ <?php echo esc_html(number_format($total_expenses, 2, ',', '.')); ?></div>
          </div>

          <div style="background:#ffffff; color:#0f172a; border:1px solid #e2e8f0; border-radius:8px; padding:16px; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
            <div style="font-size:11px; text-transform:uppercase; color:#64748b; font-weight:600; letter-spacing:0.5px;"><?php esc_html_e('Beneficio Neto', 'hostpn'); ?></div>
            <div style="font-size:20px; font-weight:700; color:<?php echo ($net_income >= 0) ? '#16a34a' : '#dc2626'; ?>; margin-top:4px;">€ <?php echo esc_html(number_format($net_income, 2, ',', '.')); ?></div>
          </div>
        </div>

        <!-- Historical Evolution Chart Canvas -->
        <?php if (!empty($chart_months)): ?>
          <div class="hostpn-fin-chart-wrapper" style="margin-bottom:24px; padding:20px; background:#ffffff; border:1px solid #e2e8f0; border-radius:8px; color:#0f172a; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
            <div style="font-size:12px; font-weight:700; color:#334155; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:14px;">
              <span><?php esc_html_e('Evolución de Ingresos Mensuales', 'hostpn'); ?></span>
            </div>
            <div style="position:relative; height:200px; width:100%;">
              <canvas id="hostpn-financial-chart-canvas"></canvas>
            </div>
          </div>
        <?php endif; ?>

        <!-- Room Financial Details Table -->
        <div style="margin-bottom:28px;">
          <h4 style="margin:0 0 14px; font-size:14px; font-weight:700; color:#0f172a; text-transform:uppercase; letter-spacing:0.5px;">
            <?php esc_html_e('Estado Financiero de Habitaciones', 'hostpn'); ?>
          </h4>
          <?php if (empty($rooms)): ?>
            <p class="hostpn-mgmt-empty"><?php esc_html_e('No financial data available.', 'hostpn'); ?></p>
          <?php else: ?>
            <table class="hostpn-mgmt-inv-table" style="width:100%; border-collapse:collapse; font-size:13px; background:#ffffff; color:#0f172a; border:1px solid #e2e8f0; border-radius:8px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.03);">
              <thead>
                <tr style="background:#f8fafc; color:#475569; border-bottom:1px solid #e2e8f0; font-size:12px; text-transform:uppercase;">
                  <th style="padding:12px 14px; text-align:left; font-weight:600;"><?php esc_html_e('Habitación', 'hostpn'); ?></th>
                  <th style="padding:12px 14px; text-align:left; font-weight:600;"><?php esc_html_e('Huésped Actual', 'hostpn'); ?></th>
                  <th style="padding:12px 14px; text-align:left; font-weight:600;"><?php esc_html_e('Fianza', 'hostpn'); ?></th>
                  <th style="padding:12px 14px; text-align:left; font-weight:600;"><?php esc_html_e('Renta Mensual', 'hostpn'); ?></th>
                  <th style="padding:12px 14px; text-align:center; font-weight:600;"><?php esc_html_e('Acciones / Historial', 'hostpn'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rooms as $r): ?>
                  <?php
                  $room_name = sprintf(__('Habitación %s', 'hostpn'), $r['room_number']);
                  $payments = isset($r['payments_log']) ? $r['payments_log'] : [];

                  $dep_paid = isset($r['deposit_paid_amount']) ? floatval($r['deposit_paid_amount']) : 0.0;
                  $dep_exp  = floatval($r['deposit_amount']);

                  $rent_paid = isset($r['rent_paid_amount']) ? floatval($r['rent_paid_amount']) : 0.0;
                  $rent_exp  = floatval($r['rent_amount']);
                  ?>
                  <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:12px 14px; font-weight:600; color:#0f172a; vertical-align:top;"><?php echo esc_html($room_name); ?></td>
                    <td style="padding:12px 14px; vertical-align:top; color:#0f172a;">
                      <?php if ($r['is_occupied']): ?>
                        <strong style="color:#0f172a;"><?php echo esc_html($r['guest_name']); ?></strong>
                      <?php else: ?>
                        <span style="color:#94a3b8; font-style:italic;"><?php esc_html_e('Disponible', 'hostpn'); ?></span>
                      <?php endif; ?>
                    </td>
                    <td class="hostpn-fin-deposit-cell">
                      <?php
                      // Get deposit payment dates
                      $dep_payment_dates = [];
                      foreach ($payments as $pay) {
                        if (isset($pay['payment_type']) && $pay['payment_type'] === 'deposit') {
                          $dep_payment_dates[] = isset($pay['payment_date']) ? $pay['payment_date'] : (isset($pay['date']) ? $pay['date'] : '');
                        }
                      }
                      $dep_last_payment_date = !empty($dep_payment_dates) ? max($dep_payment_dates) : '';
                      $dep_pending = $dep_exp - $dep_paid;

                      ob_start();
                      if ($dep_exp > 0 && $dep_paid >= $dep_exp) {
                        $dep_title = sprintf('Fianza custodiada completada: € %s', number_format($dep_paid, 2, ',', '.'));
                        if (!empty($dep_last_payment_date)) {
                          $dep_title .= sprintf(' | Fecha de pago: %s', $dep_last_payment_date);
                        }
                        ?>
                        <div class="hostpn-fin-status-wrapper">
                          <strong class="hostpn-fin-amount">€ <?php echo esc_html(number_format($dep_exp, 2, ',', '.')); ?></strong>
                          <i class="material-icons-outlined hostpn-tooltip hostpn-fin-icon-paid" title="<?php echo esc_attr($dep_title); ?>">check_circle</i>
                        </div>
                        <?php
                      } elseif ($dep_paid > 0) {
                        $dep_title = sprintf('Fianza parcial: € %s de € %s', number_format($dep_paid, 2, ',', '.'), number_format($dep_exp, 2, ',', '.'));
                        $dep_title .= sprintf(' | Pendiente: € %s', number_format($dep_pending, 2, ',', '.'));
                        if (!empty($dep_last_payment_date)) {
                          $dep_title .= sprintf(' | Último pago: %s', $dep_last_payment_date);
                        }
                        ?>
                        <div class="hostpn-fin-status-wrapper">
                          <strong class="hostpn-fin-amount">€ <?php echo esc_html(number_format($dep_exp, 2, ',', '.')); ?></strong>
                          <i class="material-icons-outlined hostpn-tooltip hostpn-fin-icon-partial" title="<?php echo esc_attr($dep_title); ?>">timelapse</i>
                        </div>
                        <?php
                      } else {
                        $dep_title = sprintf('Fianza pendiente: € %s', number_format($dep_pending, 2, ',', '.'));
                        ?>
                        <div class="hostpn-fin-status-wrapper">
                          <strong class="hostpn-fin-amount">€ <?php echo esc_html(number_format($dep_exp, 2, ',', '.')); ?></strong>
                          <i class="material-icons-outlined hostpn-tooltip hostpn-fin-icon-pending" title="<?php echo esc_attr($dep_title); ?>">error_outline</i>
                        </div>
                        <?php
                      }
                      echo ob_get_clean();
                      ?>
                    </td>
                    <td class="hostpn-fin-rent-cell">
                      <?php
                      // Get current month key and rent payment info
                      $curr_month_key = date('Y_m');
                      $rent_payment_dates = [];
                      foreach ($payments as $pay) {
                        if (isset($pay['payment_type']) && $pay['payment_type'] === 'rent') {
                          $p_mkey = isset($pay['month_key']) ? $pay['month_key'] : (isset($pay['payment_date']) ? date('Y_m', strtotime($pay['payment_date'])) : '');
                          if ($p_mkey === $curr_month_key) {
                            $rent_payment_dates[] = isset($pay['payment_date']) ? $pay['payment_date'] : (isset($pay['date']) ? $pay['date'] : '');
                          }
                        }
                      }
                      $rent_last_payment_date = !empty($rent_payment_dates) ? max($rent_payment_dates) : '';
                      $rent_pending = $rent_exp - $rent_paid;

                      // Check for overdue months
                      $start_date = !empty($r['start_date']) ? $r['start_date'] : '';
                      $overdue_months = [];
                      if (!empty($start_date) && $r['is_occupied']) {
                        $start_month = new DateTime($start_date);
                        $start_month->modify('first day of this month');
                        $current_month = new DateTime();
                        $current_month->modify('first day of this month');

                        while ($start_month < $current_month) {
                          $check_month_key = $start_month->format('Y_m');
                          $month_paid = 0.0;
                          foreach ($payments as $pay) {
                            if (isset($pay['payment_type']) && $pay['payment_type'] === 'rent') {
                              $p_mkey = isset($pay['month_key']) ? $pay['month_key'] : (isset($pay['payment_date']) ? date('Y_m', strtotime($pay['payment_date'])) : '');
                              if ($p_mkey === $check_month_key) {
                                $month_paid += floatval(isset($pay['amount']) ? $pay['amount'] : 0);
                              }
                            }
                          }
                          if ($month_paid < $rent_exp) {
                            $overdue_months[] = [
                              'month' => $start_month->format('Y-m'),
                              'pending' => $rent_exp - $month_paid
                            ];
                          }
                          $start_month->modify('+1 month');
                        }
                      }

                      ob_start();
                      if ($rent_exp > 0 && $rent_paid >= $rent_exp) {
                        $rent_title = sprintf('Cobrado este mes: € %s', number_format($rent_paid, 2, ',', '.'));
                        if (!empty($rent_last_payment_date)) {
                          $rent_title .= sprintf(' | Fecha de pago: %s', $rent_last_payment_date);
                        }
                        if (!empty($overdue_months)) {
                          $rent_title .= ' | Meses atrasados: ';
                          $overdue_list = [];
                          foreach ($overdue_months as $om) {
                            $overdue_list[] = sprintf('%s (€ %s)', $om['month'], number_format($om['pending'], 2, ',', '.'));
                          }
                          $rent_title .= implode(', ', $overdue_list);
                        }
                        ?>
                        <div class="hostpn-fin-status-wrapper">
                          <strong class="hostpn-fin-amount">€ <?php echo esc_html(number_format($rent_exp, 2, ',', '.')); ?></strong>
                          <i class="material-icons-outlined hostpn-tooltip hostpn-fin-icon-paid" title="<?php echo esc_attr($rent_title); ?>">check_circle</i>
                        </div>
                        <?php
                      } elseif ($rent_paid > 0) {
                        $rent_title = sprintf('Pago parcial este mes: € %s de € %s', number_format($rent_paid, 2, ',', '.'), number_format($rent_exp, 2, ',', '.'));
                        $rent_title .= sprintf(' | Pendiente: € %s', number_format($rent_pending, 2, ',', '.'));
                        if (!empty($rent_last_payment_date)) {
                          $rent_title .= sprintf(' | Último pago: %s', $rent_last_payment_date);
                        }
                        if (!empty($overdue_months)) {
                          $rent_title .= ' | Meses atrasados: ';
                          $overdue_list = [];
                          foreach ($overdue_months as $om) {
                            $overdue_list[] = sprintf('%s (€ %s)', $om['month'], number_format($om['pending'], 2, ',', '.'));
                          }
                          $rent_title .= implode(', ', $overdue_list);
                        }
                        ?>
                        <div class="hostpn-fin-status-wrapper">
                          <strong class="hostpn-fin-amount">€ <?php echo esc_html(number_format($rent_exp, 2, ',', '.')); ?></strong>
                          <i class="material-icons-outlined hostpn-tooltip hostpn-fin-icon-partial" title="<?php echo esc_attr($rent_title); ?>">timelapse</i>
                        </div>
                        <?php
                      } else {
                        $rent_title = sprintf('Pendiente este mes: € %s', number_format($rent_pending, 2, ',', '.'));
                        if (!empty($overdue_months)) {
                          $rent_title .= ' | Meses atrasados: ';
                          $overdue_list = [];
                          foreach ($overdue_months as $om) {
                            $overdue_list[] = sprintf('%s (€ %s)', $om['month'], number_format($om['pending'], 2, ',', '.'));
                          }
                          $rent_title .= implode(', ', $overdue_list);
                        }
                        ?>
                        <div class="hostpn-fin-status-wrapper">
                          <strong class="hostpn-fin-amount">€ <?php echo esc_html(number_format($rent_exp, 2, ',', '.')); ?></strong>
                          <i class="material-icons-outlined hostpn-tooltip hostpn-fin-icon-pending" title="<?php echo esc_attr($rent_title); ?>">error_outline</i>
                        </div>
                        <?php
                      }
                      echo ob_get_clean();
                      ?>
                    </td>
                    <td style="padding:12px 14px; text-align:center; vertical-align:top;">
                      <?php if ($r['is_occupied']): ?>
                        <div style="display:flex; flex-wrap:wrap; gap:10px; justify-content:center; align-items:center; margin-bottom:6px;">
                          <a href="#" class="hostpn-add-rent-btn" data-room-id="<?php echo esc_attr($r['room_id']); ?>" data-rent="<?php echo esc_attr($r['rent_amount']); ?>" style="color:#0284c7; font-weight:600; font-size:12px; text-decoration:none;">
                            + <?php esc_html_e('Pago Mensual', 'hostpn'); ?>
                          </a>
                          <a href="#" class="hostpn-add-deposit-btn" data-room-id="<?php echo esc_attr($r['room_id']); ?>" data-deposit="<?php echo esc_attr($r['deposit_amount']); ?>" style="color:#2563eb; font-weight:600; font-size:12px; text-decoration:none;">
                            + <?php esc_html_e('Pago Fianza', 'hostpn'); ?>
                          </a>
                        </div>
                      <?php endif; ?>
                      <a href="#" class="hostpn-toggle-payments-btn" data-room-id="<?php echo esc_attr($r['room_id']); ?>" style="color:#475569; font-weight:600; font-size:12px; text-decoration:none; display:inline-flex; align-items:center; gap:2px;">
                        <i class="material-icons-outlined" style="font-size:16px; vertical-align:middle;">expand_more</i> <?php echo esc_html(sprintf(__('Historial (%d)', 'hostpn'), count($payments))); ?>
                      </a>
                    </td>
                  </tr>

                  <!-- Collapsible payment history subrow -->
                  <tr id="hostpn-payments-subrow-<?php echo esc_attr($r['room_id']); ?>" class="hostpn-payments-subrow" style="display:none; background:#f8fafc; border-bottom:1px solid #e2e8f0;">
                    <td colspan="5" style="padding:14px 18px;">
                      <div style="font-size:12px; font-weight:700; color:#334155; margin-bottom:10px; display:flex; justify-content:space-between; align-items:center;">
                        <span><?php echo esc_html(sprintf(__('Historial de Pagos Registrados - %s', 'hostpn'), $room_name)); ?></span>
                        <?php if ($r['is_occupied']): ?>
                          <a href="#" class="hostpn-add-payment-btn" data-room-id="<?php echo esc_attr($r['room_id']); ?>" style="color:#0284c7; font-weight:600; font-size:12px; text-decoration:none;">
                            + <?php esc_html_e('Añadir Otro Pago', 'hostpn'); ?>
                          </a>
                        <?php endif; ?>
                      </div>

                      <?php if (empty($payments)): ?>
                        <div style="font-size:12px; color:#64748b; font-style:italic;"><?php esc_html_e('No hay pagos registrados para esta habitación.', 'hostpn'); ?></div>
                      <?php else: ?>
                        <table style="width:100%; border-collapse:collapse; font-size:12px; color:#334155; background:#ffffff; border:1px solid #e2e8f0; border-radius:6px; overflow:hidden;">
                          <thead>
                            <tr style="border-bottom:1px solid #e2e8f0; background:#f1f5f9; color:#475569; text-align:left;">
                              <th style="padding:8px 10px; font-weight:600;"><?php esc_html_e('Fecha', 'hostpn'); ?></th>
                              <th style="padding:8px 10px; font-weight:600;"><?php esc_html_e('Tipo', 'hostpn'); ?></th>
                              <th style="padding:8px 10px; font-weight:600;"><?php esc_html_e('Mes', 'hostpn'); ?></th>
                              <th style="padding:8px 10px; font-weight:600;"><?php esc_html_e('Importe', 'hostpn'); ?></th>
                              <th style="padding:8px 10px; font-weight:600;"><?php esc_html_e('Notas', 'hostpn'); ?></th>
                              <th style="padding:8px 10px; text-align:center; font-weight:600;"><?php esc_html_e('Acciones', 'hostpn'); ?></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($payments as $pay): ?>
                              <?php
                              $type_label = ($pay['payment_type'] === 'deposit') ? __('Fianza', 'hostpn') : (($pay['payment_type'] === 'rent') ? __('Renta Mensual', 'hostpn') : __('Otro', 'hostpn'));
                              $pay_json = wp_json_encode($pay);
                              ?>
                              <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:8px 10px; color:#0f172a; font-weight:500;"><?php echo esc_html(isset($pay['payment_date']) ? $pay['payment_date'] : (isset($pay['date']) ? $pay['date'] : '--')); ?></td>
                                <td style="padding:8px 10px;"><span style="background:#f1f5f9; color:#334155; padding:2px 8px; border-radius:4px; font-size:11px; border:1px solid #e2e8f0; font-weight:500;"><?php echo esc_html($type_label); ?></span></td>
                                <td style="padding:8px 10px; color:#475569;"><?php echo esc_html(isset($pay['month_key']) ? $pay['month_key'] : '--'); ?></td>
                                <td style="padding:8px 10px; color:#0284c7; font-weight:700;">€ <?php echo esc_html(number_format(floatval($pay['amount']), 2, ',', '.')); ?></td>
                                <td style="padding:8px 10px; font-style:italic; color:#64748b;"><?php echo esc_html(isset($pay['notes']) ? $pay['notes'] : '--'); ?></td>
                                <td style="padding:8px 10px; text-align:center;">
                                  <a href="#" class="hostpn-edit-payment-btn hostpn-tooltip" title="<?php esc_attr_e('Editar pago', 'hostpn'); ?>" data-room-id="<?php echo esc_attr($r['room_id']); ?>" data-payment="<?php echo esc_attr($pay_json); ?>" style="text-decoration:none; margin-right:8px; display:inline-block;">
                                    <i class="material-icons-outlined" style="font-size:16px; color:#475569; vertical-align:middle;">edit</i>
                                  </a>
                                  <a href="#" class="hostpn-delete-payment-btn hostpn-tooltip" title="<?php esc_attr_e('Eliminar pago', 'hostpn'); ?>" data-room-id="<?php echo esc_attr($r['room_id']); ?>" data-payment-id="<?php echo esc_attr($pay['id']); ?>" style="text-decoration:none; display:inline-block;">
                                    <i class="material-icons-outlined" style="font-size:16px; color:#dc2626; vertical-align:middle;">delete</i>
                                  </a>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>

        <!-- Expenses ("Gastos del Alojamiento") Section -->
        <div style="margin-top:28px; background:#ffffff; border:1px solid #e2e8f0; border-radius:8px; padding:20px; color:#0f172a; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:10px;">
            <div>
              <h4 style="margin:0; font-size:14px; font-weight:700; color:#0f172a; text-transform:uppercase; letter-spacing:0.5px;">
                <?php esc_html_e('Gastos del Alojamiento', 'hostpn'); ?>
              </h4>
              <span style="font-size:12px; color:#64748b;"><?php esc_html_e('Registro de suministros, mantenimientos, reparaciones y otros costes.', 'hostpn'); ?></span>
            </div>
            <a href="#" class="hostpn-add-expense-btn" data-accom-id="<?php echo esc_attr($accommodation_id); ?>" style="color:#0284c7; font-weight:600; font-size:12px; text-decoration:none;">
              + <?php esc_html_e('Añadir Gasto', 'hostpn'); ?>
            </a>
          </div>

          <?php if (empty($expenses)): ?>
            <div style="font-size:13px; color:#64748b; font-style:italic; padding:12px 0;"><?php esc_html_e('No hay gastos registrados en este alojamiento.', 'hostpn'); ?></div>
          <?php else: ?>
            <table style="width:100%; border-collapse:collapse; font-size:12px; color:#334155; border:1px solid #e2e8f0; border-radius:6px; overflow:hidden;">
              <thead>
                <tr style="background:#f8fafc; color:#475569; text-align:left; border-bottom:1px solid #e2e8f0; font-size:11px; text-transform:uppercase;">
                  <th style="padding:10px 12px; font-weight:600;"><?php esc_html_e('Fecha', 'hostpn'); ?></th>
                  <th style="padding:10px 12px; font-weight:600;"><?php esc_html_e('Proveedor', 'hostpn'); ?></th>
                  <th style="padding:10px 12px; font-weight:600;"><?php esc_html_e('Categoría / Concepto', 'hostpn'); ?></th>
                  <th style="padding:10px 12px; font-weight:600;"><?php esc_html_e('Importe', 'hostpn'); ?></th>
                  <th style="padding:10px 12px; font-weight:600;"><?php esc_html_e('Fichero Adjunto', 'hostpn'); ?></th>
                  <th style="padding:10px 12px; font-weight:600;"><?php esc_html_e('Notas', 'hostpn'); ?></th>
                  <th style="padding:10px 12px; text-align:center; font-weight:600;"><?php esc_html_e('Acciones', 'hostpn'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($expenses as $exp): ?>
                  <?php $exp_json = wp_json_encode($exp); ?>
                  <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:10px 12px; font-weight:600; color:#0f172a;"><?php echo esc_html(isset($exp['date']) ? $exp['date'] : '--'); ?></td>
                    <td style="padding:10px 12px; color:#0f172a;"><?php echo esc_html(isset($exp['provider']) ? $exp['provider'] : '--'); ?></td>
                    <td style="padding:10px 12px;"><span style="background:#f1f5f9; color:#334155; padding:2px 8px; border-radius:4px; font-size:11px; border:1px solid #e2e8f0; font-weight:500;"><?php echo esc_html(isset($exp['category']) ? $exp['category'] : 'General'); ?></span></td>
                    <td style="padding:10px 12px; color:#dc2626; font-weight:700;">€ <?php echo esc_html(number_format(floatval($exp['amount']), 2, ',', '.')); ?></td>
                    <td style="padding:10px 12px;">
                      <?php if (!empty($exp['attachment_filename'])): ?>
                        <?php $file_url = add_query_arg(['action' => 'hostpn_expense_download_attachment', 'accommodation_id' => $accommodation_id, 'filename' => urlencode($exp['attachment_filename']), 'hostpn_ajax_nonce' => $nonce], $ajax_url); ?>
                        <a href="<?php echo esc_url($file_url); ?>" target="_blank" style="color:#0284c7; text-decoration:none; font-weight:600;">
                          <i class="material-icons-outlined" style="font-size:14px; vertical-align:middle;">attach_file</i> <?php echo esc_html(!empty($exp['attachment_original_name']) ? $exp['attachment_original_name'] : $exp['attachment_filename']); ?>
                        </a>
                      <?php else: ?>
                        <span style="color:#94a3b8; font-style:italic;"><?php esc_html_e('Sin adjunto', 'hostpn'); ?></span>
                      <?php endif; ?>
                    </td>
                    <td style="padding:10px 12px; font-style:italic; color:#64748b;"><?php echo esc_html(isset($exp['notes']) ? $exp['notes'] : '--'); ?></td>
                    <td style="padding:10px 12px; text-align:center;">
                      <a href="#" class="hostpn-edit-expense-btn hostpn-tooltip" title="<?php esc_attr_e('Editar gasto', 'hostpn'); ?>" data-accom-id="<?php echo esc_attr($accommodation_id); ?>" data-expense="<?php echo esc_attr($exp_json); ?>" style="text-decoration:none; margin-right:8px; display:inline-block;">
                        <i class="material-icons-outlined" style="font-size:16px; color:#475569; vertical-align:middle;">edit</i>
                      </a>
                      <a href="#" class="hostpn-delete-expense-btn hostpn-tooltip" title="<?php esc_attr_e('Eliminar gasto', 'hostpn'); ?>" data-accom-id="<?php echo esc_attr($accommodation_id); ?>" data-expense-id="<?php echo esc_attr($exp['id']); ?>" style="text-decoration:none; display:inline-block;">
                        <i class="material-icons-outlined" style="font-size:16px; color:#dc2626; vertical-align:middle;">delete</i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>

        <!-- Bottom CSV Import Section -->
        <div style="margin-top:24px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:16px; box-shadow:0 1px 3px rgba(0,0,0,0.03);">
          <div>
            <strong style="display:block; font-size:13px; color:#0f172a;"><?php esc_html_e('Importación masiva desde CSV (Booking.com / Airbnb)', 'hostpn'); ?></strong>
            <span style="font-size:11px; color:#64748b;"><?php esc_html_e('Sube extractos o facturas de Booking o Airbnb para automatizar el registro de cobranzas.', 'hostpn'); ?></span>
          </div>
          <a href="#" class="hostpn-financial-import-btn" data-accommodation-id="<?php echo esc_attr($accommodation_id); ?>" style="color:#0284c7; font-weight:600; font-size:12px; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
            <i class="material-icons-outlined hostpn-vertical-align-middle" style="font-size:16px;">upload_file</i>
            <span class="hostpn-vertical-align-middle"><?php esc_html_e('Importar Extracto CSV', 'hostpn'); ?></span>
          </a>
        </div>

      </div>
    </div>

    <!-- Script to render Chart.js on page load in Admin if chart_months present -->
    <script>
    (function($) {
      $(document).ready(function() {
        if (window.Chart && <?php echo wp_json_encode(!empty($chart_months)); ?>) {
          var months = <?php echo wp_json_encode($chart_months); ?>;
          var labels = [];
          var expectedData = [];
          var collectedData = [];
          for (var i = 0; i < months.length; i++) {
            labels.push(months[i].label);
            expectedData.push(months[i].expected || 0);
            collectedData.push(months[i].collected || 0);
          }
          var canvas = document.getElementById('hostpn-financial-chart-canvas');
          if (canvas) {
            if (window.hostpnFinChartInstance) {
              window.hostpnFinChartInstance.destroy();
            }
            window.hostpnFinChartInstance = new window.Chart(canvas, {
              type: 'bar',
              data: {
                labels: labels,
                datasets: [
                  { label: 'Cobrado Real', data: collectedData, backgroundColor: '#0284c7', borderRadius: 4 },
                  { label: 'Previsto', data: expectedData, backgroundColor: '#cbd5e1', borderRadius: 4 }
                ]
              },
              options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                  legend: { labels: { color: '#334155', font: { size: 11, weight: '600' } } },
                  tooltip: {
                    callbacks: {
                      label: function(ctx) { return ctx.dataset.label + ': €' + ctx.raw.toLocaleString('es-ES', { minimumFractionDigits: 2 }); }
                    }
                  }
                },
                scales: {
                  x: { ticks: { color: '#64748b', font: { size: 10 } }, grid: { color: '#f1f5f9' } },
                  y: { ticks: { color: '#64748b', font: { size: 10 } }, grid: { color: '#f1f5f9' } }
                }
              }
            });
          }
        }
      });
    })(jQuery);
    </script>
    <?php
    return ob_get_clean();
  }

  /**
   * Get financial management fields for separate metabox.
   */
  public static function hostpn_financial_get_fields() {
    $hostpn_fields = [];
    $accommodation_id = get_the_ID();

    $html_content = self::hostpn_render_admin_financial_dashboard_content($accommodation_id);

    $hostpn_fields['hostpn_financial_dashboard'] = [
      'id' => 'hostpn_financial_dashboard',
      'input' => 'html',
      'html_content' => '<div id="hostpn-financial-dashboard" data-accommodation-id="' . esc_attr($accommodation_id) . '">' . $html_content . '</div>',
    ];

    return $hostpn_fields;
  }

  /**
   * Get contract generation fields for separate metabox.
   * Returns fields dynamically based on accommodation type.
   *
   * @param int $accommodation_id Post ID (0 for new posts).
   * @return array
   */
  public static function hostpn_contract_get_fields($accommodation_id = 0) {
    $hostpn_fields = [];

    if (empty($accommodation_id)) {
      $accommodation_id = get_the_ID();
    }

    $accommodation_type = get_post_meta($accommodation_id, 'hostpn_accommodation_type', true);
    $contract_type = HOSTPN_Contract_Templates::hostpn_get_type_for_accommodation($accommodation_type);

    // --- Common: Landlord fields ---
    $hostpn_fields['hostpn_contract_landlord_name'] = [
      'id' => 'hostpn_contract_landlord_name',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => esc_html(__('Landlord full name', 'hostpn')),
      'placeholder' => esc_html(__('Full name of the landlord', 'hostpn')),
    ];
    $hostpn_fields['hostpn_contract_landlord_nif'] = [
      'id' => 'hostpn_contract_landlord_nif',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => esc_html(__('Landlord NIF/NIE', 'hostpn')),
      'placeholder' => esc_html(__('NIF or NIE of the landlord', 'hostpn')),
    ];
    $hostpn_fields['hostpn_contract_landlord_address'] = [
      'id' => 'hostpn_contract_landlord_address',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => esc_html(__('Landlord address', 'hostpn')),
      'placeholder' => esc_html(__('Domicile address of the landlord', 'hostpn')),
    ];
    $hostpn_fields['hostpn_contract_landlord_email'] = [
      'id' => 'hostpn_contract_landlord_email',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'email',
      'label' => esc_html(__('Landlord email', 'hostpn')),
      'placeholder' => esc_html(__('Email of the landlord', 'hostpn')),
    ];

    // --- Room-only fields (before tenant so room selection auto-fills tenant data) ---
    if ($contract_type === 'habitacion') {
      $hostpn_fields['hostpn_contract_room_id'] = [
        'id' => 'hostpn_contract_room_id',
        'class' => 'hostpn-select hostpn-width-100-percent hostpn-contract-room-select',
        'input' => 'select',
        'options' => HOSTPN_Post_Type_Room::hostpn_get_rooms_options($accommodation_id),
        'label' => esc_html(__('Room', 'hostpn')),
        'placeholder' => esc_html(__('Select room', 'hostpn')),
      ];

      // Room summary placeholder — JS will populate with guest/contract data from Room/Guest CPTs
      $hostpn_fields['hostpn_contract_room_summary'] = [
        'id' => 'hostpn_contract_room_summary',
        'input' => 'html',
        'html_content' => '<div id="hostpn-contract-room-summary"></div>',
      ];
    }

    // --- Tenant fields (non-habitacion only — habitacion reads from Guest CPT) ---
    if ($contract_type !== 'habitacion') {
      $hostpn_fields['hostpn_contract_tenant_name'] = [
        'id' => 'hostpn_contract_tenant_name',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'label' => esc_html(__('Tenant full name', 'hostpn')),
        'placeholder' => esc_html(__('Full name of the tenant', 'hostpn')),
      ];
      $hostpn_fields['hostpn_contract_tenant_nif'] = [
        'id' => 'hostpn_contract_tenant_nif',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'label' => esc_html(__('Tenant NIF/NIE', 'hostpn')),
        'placeholder' => esc_html(__('NIF or NIE of the tenant', 'hostpn')),
      ];
      $hostpn_fields['hostpn_contract_tenant_address'] = [
        'id' => 'hostpn_contract_tenant_address',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'label' => esc_html(__('Tenant address', 'hostpn')),
        'placeholder' => esc_html(__('Domicile address of the tenant', 'hostpn')),
      ];
      $hostpn_fields['hostpn_contract_tenant_email'] = [
        'id' => 'hostpn_contract_tenant_email',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'email',
        'label' => esc_html(__('Tenant email', 'hostpn')),
        'placeholder' => esc_html(__('Email of the tenant', 'hostpn')),
      ];
    }

    // --- Tourist-only fields ---
    if ($contract_type === 'turistico') {
      $hostpn_fields['hostpn_contract_guest_count'] = [
        'id' => 'hostpn_contract_guest_count',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'label' => esc_html(__('Number of guests', 'hostpn')),
        'placeholder' => esc_html(__('Maximum number of guests', 'hostpn')),
      ];
      $hostpn_fields['hostpn_contract_checkin_time'] = [
        'id' => 'hostpn_contract_checkin_time',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'time',
        'label' => esc_html(__('Check-in time', 'hostpn')),
      ];
      $hostpn_fields['hostpn_contract_checkout_time'] = [
        'id' => 'hostpn_contract_checkout_time',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'time',
        'label' => esc_html(__('Check-out time', 'hostpn')),
      ];
    }

    // --- Contract dates (non-habitacion only — habitacion reads from Room CPT) ---
    if ($contract_type !== 'habitacion') {
      $hostpn_fields['hostpn_contract_duration'] = [
        'id' => 'hostpn_contract_duration',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'label' => esc_html(__('Contract duration', 'hostpn')),
        'placeholder' => esc_html(__('e.g. 11 months', 'hostpn')),
      ];
      $hostpn_fields['hostpn_contract_start_date'] = [
        'id' => 'hostpn_contract_start_date',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'label' => esc_html(__('Start date', 'hostpn')),
      ];
      $hostpn_fields['hostpn_contract_end_date'] = [
        'id' => 'hostpn_contract_end_date',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'date',
        'label' => esc_html(__('End date', 'hostpn')),
      ];
    }

    // --- Long-stay only: Notice days (habitacion reads from Room CPT) ---
    if ($contract_type === 'lau') {
      $hostpn_fields['hostpn_contract_notice_days'] = [
        'id' => 'hostpn_contract_notice_days',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'label' => esc_html(__('Notice days', 'hostpn')),
        'placeholder' => esc_html(__('Days of advance notice', 'hostpn')),
      ];
    }

    // --- Financial details ---
    if ($contract_type === 'turistico') {
      $hostpn_fields['hostpn_contract_total_price'] = [
        'id' => 'hostpn_contract_total_price',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'label' => esc_html(__('Total price (EUR)', 'hostpn')),
        'placeholder' => esc_html(__('Total price for the stay', 'hostpn')),
      ];
    } elseif ($contract_type !== 'habitacion') {
      // LAU — habitacion reads rent from Room CPT
      $hostpn_fields['hostpn_contract_rent_amount'] = [
        'id' => 'hostpn_contract_rent_amount',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'label' => esc_html(__('Monthly rent (EUR)', 'hostpn')),
        'placeholder' => esc_html(__('Monthly rent amount', 'hostpn')),
      ];
      $hostpn_fields['hostpn_contract_rent_words'] = [
        'id' => 'hostpn_contract_rent_words',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'label' => esc_html(__('Rent in words', 'hostpn')),
        'placeholder' => esc_html(__('e.g. trescientos cincuenta', 'hostpn')),
      ];
      $hostpn_fields['hostpn_contract_payment_day'] = [
        'id' => 'hostpn_contract_payment_day',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'label' => esc_html(__('Payment day', 'hostpn')),
        'placeholder' => esc_html(__('Day of the month for payment', 'hostpn')),
      ];
    }

    // --- Room and long-stay: Bank details ---
    if ($contract_type === 'habitacion' || $contract_type === 'lau') {
      $hostpn_fields['hostpn_contract_bank_name'] = [
        'id' => 'hostpn_contract_bank_name',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'label' => esc_html(__('Bank name', 'hostpn')),
        'placeholder' => esc_html(__('Name of the bank', 'hostpn')),
      ];
      $hostpn_fields['hostpn_contract_iban'] = [
        'id' => 'hostpn_contract_iban',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'label' => esc_html(__('IBAN', 'hostpn')),
        'placeholder' => esc_html(__('Bank account IBAN', 'hostpn')),
      ];
    }

    // --- Supplies and deposit (non-habitacion only — habitacion reads from Room CPT) ---
    if ($contract_type !== 'habitacion') {
      $hostpn_fields['hostpn_contract_deposit_amount'] = [
        'id' => 'hostpn_contract_deposit_amount',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'label' => esc_html(__('Deposit amount (EUR)', 'hostpn')),
        'placeholder' => esc_html(__('Deposit amount', 'hostpn')),
      ];
      $hostpn_fields['hostpn_contract_deposit_words'] = [
        'id' => 'hostpn_contract_deposit_words',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'label' => esc_html(__('Deposit in words', 'hostpn')),
        'placeholder' => esc_html(__('e.g. trescientos cincuenta', 'hostpn')),
      ];
      $hostpn_fields['hostpn_contract_deposit_months'] = [
        'id' => 'hostpn_contract_deposit_months',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => [
          '1' => '1 ' . esc_html(__('month', 'hostpn')),
          '2' => '2 ' . esc_html(__('months', 'hostpn')),
        ],
        'label' => esc_html(__('Deposit months', 'hostpn')),
      ];
    }

    // --- Inventory annex ---
    $hostpn_fields['hostpn_contract_inventory_enabled'] = [
      'id' => 'hostpn_contract_inventory_enabled',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'checkbox',
      'label' => esc_html(__('Add inventory annex to contract', 'hostpn')),
    ];
    $inventory_categories = [
      'mobiliario'              => __('Furniture', 'hostpn'),
      'equipamiento_individual' => __('Individual equipment', 'hostpn'),
      'menaje_individual'       => __('Individual kitchenware', 'hostpn'),
      'equipamiento_comunitario' => __('Community equipment', 'hostpn'),
      'otros_enseres'           => __('Other items', 'hostpn'),
    ];

    foreach ($inventory_categories as $cat_key => $cat_label) {
      $hostpn_fields['hostpn_contract_inv_' . $cat_key] = [
        'id' => 'hostpn_contract_inv_' . $cat_key,
        'input' => 'html_multi',
        'class' => 'hostpn-input hostpn-width-100-percent hostpn-contract-inventory-items',
        'parent' => 'hostpn_contract_inventory_enabled',
        'parent_option' => 'on',
        'html_multi_fields' => [
          [
            'id' => 'hostpn_contract_inv_' . $cat_key . '_name',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'multiple' => true,
            'label' => esc_html(__('Item name', 'hostpn')),
            'placeholder' => esc_html(__('Name of the item', 'hostpn')),
          ],
          [
            'id' => 'hostpn_contract_inv_' . $cat_key . '_url',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'url',
            'multiple' => true,
            'label' => esc_html(__('Item URL', 'hostpn')),
            'placeholder' => esc_html(__('Link to the product', 'hostpn')),
          ],
        ],
        'label' => $cat_label,
      ];
    }

    // --- Share link ---
    $post_id = $accommodation_id ? $accommodation_id : get_the_ID();
    $token = get_post_meta($post_id, 'hostpn_contract_token', true);
    if (empty($token)) {
      $token = bin2hex(random_bytes(16));
      if ($post_id) {
        update_post_meta($post_id, 'hostpn_contract_token', $token);
      }
    }

    $share_html = '<div class="hostpn-contract-share-wrapper" style="margin-top:15px;">'
      . '<label class="hostpn-label">' . esc_html(__('Contract shared link', 'hostpn')) . '</label>';

    if ($contract_type === 'habitacion') {
      // Per-room URLs
      $rooms = HOSTPN_Post_Type_Room::hostpn_get_rooms_options($post_id);
      if (!empty($rooms)) {
        $share_html .= '<div class="hostpn-contract-share-rooms" style="display:flex;flex-direction:column;gap:6px;">';
        foreach ($rooms as $rid => $rlabel) {
          $room_url = add_query_arg([
            'hostpn_contract'      => $token,
            'hostpn_contract_room' => $rid,
          ], home_url('/'));
          $code_id = 'hostpn-contract-share-url-' . intval($rid);
          $share_html .= '<div style="display:flex;align-items:center;gap:8px;">'
            . '<span class="hostpn-label" style="min-width:100px;font-weight:600;">' . esc_html($rlabel) . '</span>'
            . '<code id="' . esc_attr($code_id) . '" style="flex:1;word-break:break-all;padding:8px;background:#f5f5f5;border-radius:4px;font-size:12px;">' . esc_url($room_url) . '</code>'
            . '<a href="' . esc_url($room_url) . '" target="_blank" class="hostpn-cursor-pointer hostpn-tooltip" title="' . esc_attr(__('Open', 'hostpn')) . '" style="font-size:20px;text-decoration:none;color:inherit;"><i class="material-icons-outlined">open_in_new</i></a>'
            . '<i class="material-icons-outlined hostpn-btn-copy hostpn-cursor-pointer hostpn-tooltip" title="' . esc_attr(__('Copy URL', 'hostpn')) . '" data-hostpn-copy-content="#' . esc_attr($code_id) . '" style="font-size:20px;">content_copy</i>'
            . '</div>';
        }
        $share_html .= '</div>';
      } else {
        $share_html .= '<p class="description">' . esc_html(__('No rooms found. Add rooms to generate per-room contract links.', 'hostpn')) . '</p>';
      }
    } else {
      // Single URL for non-habitacion types
      $share_url = add_query_arg('hostpn_contract', $token, home_url('/'));
      $share_html .= '<div style="display:flex;align-items:center;gap:8px;">'
        . '<code id="hostpn-contract-share-url" style="flex:1;word-break:break-all;padding:8px;background:#f5f5f5;border-radius:4px;font-size:12px;">' . esc_url($share_url) . '</code>'
        . '<a href="' . esc_url($share_url) . '" target="_blank" class="hostpn-cursor-pointer hostpn-tooltip" title="' . esc_attr(__('Open', 'hostpn')) . '" style="font-size:20px;text-decoration:none;color:inherit;"><i class="material-icons-outlined">open_in_new</i></a>'
        . '<i class="material-icons-outlined hostpn-btn-copy hostpn-cursor-pointer hostpn-tooltip" title="' . esc_attr(__('Copy URL', 'hostpn')) . '" data-hostpn-copy-content="#hostpn-contract-share-url" style="font-size:20px;">content_copy</i>'
        . '</div>';
    }

    $share_html .= '<p class="description" style="margin-top:5px;">' . esc_html(__('Share this link with the tenant to view, sign, and download the contract.', 'hostpn')) . '</p>'
      . '</div>';

    $hostpn_fields['hostpn_contract_share_link'] = [
      'id' => 'hostpn_contract_share_link',
      'input' => 'html',
      'html_content' => $share_html,
    ];

    return $hostpn_fields;
  }

  /**
   * Get all possible contract meta keys for saving (all types combined).
   *
   * @return array
   */
  public static function hostpn_contract_get_all_field_keys() {
    $all_keys = [
      'hostpn_contract_landlord_name', 'hostpn_contract_landlord_nif', 'hostpn_contract_landlord_address', 'hostpn_contract_landlord_email',
      'hostpn_contract_tenant_name', 'hostpn_contract_tenant_nif', 'hostpn_contract_tenant_address', 'hostpn_contract_tenant_email',
      'hostpn_contract_room_id', 'hostpn_contract_duration', 'hostpn_contract_start_date', 'hostpn_contract_end_date',
      'hostpn_contract_notice_days', 'hostpn_contract_rent_amount', 'hostpn_contract_rent_words',
      'hostpn_contract_payment_day', 'hostpn_contract_bank_name', 'hostpn_contract_iban',
      'hostpn_contract_supplies_option', 'hostpn_contract_supplies_limit',
      'hostpn_contract_deposit_amount', 'hostpn_contract_deposit_words', 'hostpn_contract_deposit_months',
      'hostpn_contract_guest_count', 'hostpn_contract_checkin_time', 'hostpn_contract_checkout_time',
      'hostpn_contract_total_price',
      'hostpn_contract_inventory_enabled',
    ];
    return $all_keys;
  }

  /**
   * Register accommodation.
   *
   * @since    1.0.0
   */
  public function hostpn_accommodation_register_post_type() {
    $labels = [
      'name'                => _x('Accommodation', 'Post Type general name', 'hostpn'),
      'singular_name'       => _x('Accommodation', 'Post Type singular name', 'hostpn'),
      'menu_name'           => esc_html(__('Accommodations', 'hostpn')),
      'parent_item_colon'   => esc_html(__('Parent accommodation', 'hostpn')),
      'all_items'           => esc_html(__('All accommodations', 'hostpn')),
      'view_item'           => esc_html(__('View accommodation', 'hostpn')),
      'add_new_item'        => esc_html(__('Add new accommodation', 'hostpn')),
      'add_new'             => esc_html(__('Add new accommodation', 'hostpn')),
      'edit_item'           => esc_html(__('Edit accommodation', 'hostpn')),
      'update_item'         => esc_html(__('Update accommodation', 'hostpn')),
      'search_items'        => esc_html(__('Search accommodations', 'hostpn')),
      'not_found'           => esc_html(__('Not accommodation found', 'hostpn')),
      'not_found_in_trash'  => esc_html(__('Not accommodation found in Trash', 'hostpn')),
    ];

    $args = [
      'labels'              => $labels,
      'rewrite'             => ['slug' => 'accommodations', 'with_front' => false],
      'label'               => esc_html(__('Accommodation', 'hostpn')),
      'description'         => esc_html(__('Accommodation description', 'hostpn')),
      'supports'            => ['title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'page-attributes', ],
      'hierarchical'        => true,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => false,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 5,
      'can_export'          => true,
      'has_archive'         => true,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'capability_type'     => 'page',
      'taxonomies'          => HOSTPN_ROLE_CAPABILITIES,
      'show_in_rest'        => true, /* REST API */
    ];

    register_post_type('hostpn_accommodation', $args);
    add_theme_support('post-thumbnails', ['page', 'hostpn_accommodation']);

    // Flush rewrite rules only once after registration
    if (get_option('hostpn_accommodation_rewrite_flushed') != 'yes') {
      flush_rewrite_rules();
      update_option('hostpn_accommodation_rewrite_flushed', 'yes');
    }

    add_action('pre_get_posts', [$this, 'hostpn_accommodation_admin_order']);
  }

  /**
   * Add accommodation dashboard metabox.
   *
   * @since    1.0.0
   */
  public function hostpn_accommodation_add_meta_box() {
    add_meta_box('hostpn_meta_box', esc_html(__('Accommodation details', 'hostpn')), [$this, 'hostpn_accommodation_meta_box_function'], 'hostpn_accommodation', 'normal', 'high', ['__block_editor_compatible_meta_box' => true,]);
    add_meta_box('hostpn_financial_meta_box', esc_html(__('Financial Management', 'hostpn')), [$this, 'hostpn_financial_meta_box_function'], 'hostpn_accommodation', 'normal', 'default', ['__block_editor_compatible_meta_box' => true,]);
    add_meta_box('hostpn_contract_meta_box', esc_html(__('Contract Generation', 'hostpn')), [$this, 'hostpn_contract_meta_box_function'], 'hostpn_accommodation', 'normal', 'default', ['__block_editor_compatible_meta_box' => true,]);
  }

  /**
   * Defines accommodation dashboard contents.
   *
   * @since    1.0.0
   */
  public function hostpn_accommodation_meta_box_function($post) {
    self::hostpn_render_accommodation_rooms_summary_block($post->ID);
    foreach (self::hostpn_accommodation_get_fields_meta() as $hostpn_field) {
      if (!is_null(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $post->ID))) {
        echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $post->ID), HOSTPN_KSES);
      }
    }
  }

  /**
   * Renders Financial Management metabox contents.
   */
  public function hostpn_financial_meta_box_function($post) {
    echo self::hostpn_render_admin_financial_dashboard_content($post->ID);
  }

  /**
   * Renders Contract Generation metabox contents with two-column layout.
   */
  public function hostpn_contract_meta_box_function($post) {
    $accommodation_type = get_post_meta($post->ID, 'hostpn_accommodation_type', true);
    $contract_type = HOSTPN_Contract_Templates::hostpn_get_type_for_accommodation($accommodation_type);
    $contract_types = HOSTPN_Contract_Templates::hostpn_get_contract_types();
    $type_label = isset($contract_types[$contract_type]) ? $contract_types[$contract_type] : '';
    $settings_url = admin_url('admin.php?page=hostpn');

    echo '<div class="hostpn-contract-top-bar">';
    echo '<span class="hostpn-contract-type-badge" data-contract-type="' . esc_attr($contract_type) . '">' . esc_html($type_label) . '</span>';
    echo '<div class="hostpn-contract-top-actions">';
    echo '<a href="' . esc_url($settings_url) . '#hostpn-contracts-editor" target="_blank" class="hostpn-btn hostpn-btn-mini hostpn-btn-transparent" title="' . esc_attr__('Edit templates', 'hostpn') . '"><span class="material-icons-outlined">edit_note</span> ' . esc_html__('Edit templates', 'hostpn') . '</a>';
    echo '<button type="button" class="hostpn-btn hostpn-btn-mini hostpn-btn-transparent hostpn-contract-refresh-btn" title="' . esc_attr__('Refresh preview', 'hostpn') . '"><span class="material-icons-outlined">refresh</span></button>';
    echo '</div>';
    echo '</div>';

    echo '<div class="hostpn-contract-columns" data-accommodation-id="' . esc_attr($post->ID) . '" data-contract-type="' . esc_attr($contract_type) . '">';

    // Left column: fields
    echo '<div class="hostpn-contract-fields-column">';
    echo '<div class="hostpn-contract-metabox-fields">';
    foreach (self::hostpn_contract_get_fields($post->ID) as $hostpn_field) {
      if (!is_null(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $post->ID))) {
        echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $post->ID), HOSTPN_KSES);
      }
    }
    echo '</div>';
    echo '</div>';

    // Right column: live preview
    echo '<div class="hostpn-contract-preview-column">';
    echo '<div class="hostpn-contract-live-preview" id="hostpn-contract-live-preview">';
    $template = HOSTPN_Contract_Templates::hostpn_get_saved_template($contract_type);
    $preview_html = HOSTPN_Contract_Templates::hostpn_render_contract($contract_type, $template, $post->ID);
    $preview_html .= HOSTPN_Contract_Templates::hostpn_render_inventory($post->ID);
    echo wp_kses_post($preview_html);
    echo '</div>';
    echo '</div>';

    echo '</div>'; // .hostpn-contract-columns
  }

  /**
   * Defines single template for accommodation.
   *
   * @since    1.0.0
   */
  public function hostpn_accommodation_single_template($single) {
    global $post;
    
    // Check if we're on a single accommodation page
    if (is_singular('hostpn_accommodation') || (isset($post) && $post->post_type == 'hostpn_accommodation')) {
      // First, check if theme has a template for this post type (theme override)
      $theme_template = locate_template(['single-hostpn_accommodation.php', 'single.php']);
      if ($theme_template) {
        return $theme_template;
      }
      
      // If no theme template, use plugin template
      // Try multiple path strategies
      $template_path = false;
      
      // Strategy 1: Use HOSTPN_DIR if defined
      if (defined('HOSTPN_DIR')) {
        $template_path = HOSTPN_DIR . 'templates/public/single-hostpn_accommodation.php';
      }
      
      // Strategy 2: Use plugin_dir_path from current file
      if (!$template_path || !file_exists($template_path)) {
        $template_path = plugin_dir_path(dirname(__FILE__)) . 'templates/public/single-hostpn_accommodation.php';
      }
      
      // Strategy 3: Use WP_PLUGIN_DIR with relative path
      if (!$template_path || !file_exists($template_path)) {
        $template_path = WP_PLUGIN_DIR . '/hostpn/templates/public/single-hostpn_accommodation.php';
      }
      
      if ($template_path && file_exists($template_path)) {
        return $template_path;
      }
    }

    return $single;
  }

  /**
   * Defines archive template for accommodation.
   *
   * @since    1.0.0
   */
  public function hostpn_accommodation_archive_template($archive) {
    global $post;
    
    // Check if we're on an accommodation archive page
    if (is_post_type_archive('hostpn_accommodation') || (isset($post) && $post->post_type == 'hostpn_accommodation')) {
      // First, check if theme has a template for this post type (theme override)
      $theme_template = locate_template(['archive-hostpn_accommodation.php', 'archive.php']);
      if ($theme_template) {
        return $theme_template;
      }
      
      // If no theme template, use plugin template
      // Try multiple path strategies
      $template_path = false;
      
      // Strategy 1: Use HOSTPN_DIR if defined
      if (defined('HOSTPN_DIR')) {
        $template_path = HOSTPN_DIR . 'templates/public/archive-hostpn_accommodation.php';
      }
      
      // Strategy 2: Use plugin_dir_path from current file
      if (!$template_path || !file_exists($template_path)) {
        $template_path = plugin_dir_path(dirname(__FILE__)) . 'templates/public/archive-hostpn_accommodation.php';
      }
      
      // Strategy 3: Use WP_PLUGIN_DIR with relative path
      if (!$template_path || !file_exists($template_path)) {
        $template_path = WP_PLUGIN_DIR . '/hostpn/templates/public/archive-hostpn_accommodation.php';
      }
      
      if ($template_path && file_exists($template_path)) {
        return $template_path;
      }
    }

    return $archive;
  }

  public function hostpn_accommodation_save_post($post_id, $cpt, $update) {
    if($cpt->post_type == 'hostpn_accommodation' && array_key_exists('hostpn_accommodation_form', $_POST)){
      // Always require nonce verification
      if (!array_key_exists('hostpn_ajax_nonce', $_POST)) {
        echo wp_json_encode([
          'error_key' => 'hostpn_nonce_error_required',
          'error_content' => esc_html(__('Security check failed: Nonce is required.', 'hostpn')),
        ]);

        exit;
      }

      if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hostpn_ajax_nonce'])), 'hostpn-nonce')) {
        echo wp_json_encode([
          'error_key' => 'hostpn_nonce_error_invalid',
          'error_content' => esc_html(__('Security check failed: Invalid nonce.', 'hostpn')),
        ]);

        exit;
      }

      if (!array_key_exists('hostpn_duplicate', $_POST)) {
        // Build contract fields for all types + any extra keys not in current type
        $contract_fields = self::hostpn_contract_get_fields($post_id);
        // Also save extra contract keys from other types that might be in POST
        $all_contract_keys = self::hostpn_contract_get_all_field_keys();
        foreach ($all_contract_keys as $ckey) {
          if (!isset($contract_fields[$ckey]) && array_key_exists($ckey, $_POST)) {
            $contract_fields[$ckey] = [
              'id' => $ckey,
              'input' => 'input',
              'type' => 'text',
            ];
          }
        }
        foreach (array_merge(self::hostpn_accommodation_get_fields(), self::hostpn_accommodation_get_fields_meta(), self::hostpn_financial_get_fields(), $contract_fields) as $hostpn_field) {
          $hostpn_input = array_key_exists('input', $hostpn_field) ? $hostpn_field['input'] : '';

          if (array_key_exists($hostpn_field['id'], $_POST) || $hostpn_input == 'html_multi') {
            $hostpn_value = array_key_exists($hostpn_field['id'], $_POST) ? 
              HOSTPN_Forms::hostpn_sanitizer(
                wp_unslash($_POST[$hostpn_field['id']]),
                $hostpn_field['input'], 
                !empty($hostpn_field['type']) ? $hostpn_field['type'] : '',
                $hostpn_field // Pass the entire field config
              ) : '';

            if (!empty($hostpn_input)) {
              switch ($hostpn_input) {
                case 'input':
                  if (array_key_exists('type', $hostpn_field) && $hostpn_field['type'] == 'checkbox') {
                    if (isset($_POST[$hostpn_field['id']])) {
                      update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                    } else {
                      update_post_meta($post_id, $hostpn_field['id'], '');
                    }
                  } else {
                    update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                  }

                  break;
                case 'select':
                  if (array_key_exists('multiple', $hostpn_field) && $hostpn_field['multiple']) {
                    $multi_array = [];
                    $empty = true;

                    foreach (wp_unslash($_POST[$hostpn_field['id']]) as $multi_value) {
                      $multi_array[] = HOSTPN_Forms::hostpn_sanitizer(
                        $multi_value, 
                        $hostpn_field['input'], 
                        !empty($hostpn_field['type']) ? $hostpn_field['type'] : '',
                        $hostpn_field // Pass the entire field config
                      );
                    }

                    update_post_meta($post_id, $hostpn_field['id'], $multi_array);
                  } else {
                    update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                  }
                  
                  break;
                case 'html_multi':
                  foreach ($hostpn_field['html_multi_fields'] as $hostpn_multi_field) {
                    if (array_key_exists($hostpn_multi_field['id'], $_POST)) {
                      $multi_array = [];
                      $empty = true;

                      // Sanitize the POST data before using it
                      $sanitized_post_data = isset($_POST[$hostpn_multi_field['id']]) ? 
                        array_map(function($value) {
                            return sanitize_text_field(wp_unslash($value));
                        }, (array)$_POST[$hostpn_multi_field['id']]) : [];
                      
                      foreach ($sanitized_post_data as $multi_value) {
                        if (!empty($multi_value)) {
                          $empty = false;
                        }

                        $multi_array[] = HOSTPN_Forms::hostpn_sanitizer(
                          $multi_value, 
                          $hostpn_multi_field['input'], 
                          !empty($hostpn_multi_field['type']) ? $hostpn_multi_field['type'] : '',
                          $hostpn_multi_field // Pass the entire field config
                        );
                      }

                      if (!$empty) {
                        update_post_meta($post_id, $hostpn_multi_field['id'], $multi_array);
                      } else {
                        update_post_meta($post_id, $hostpn_multi_field['id'], '');
                      }
                    }
                  }

                  break;
                default:
                  update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                  break;
              }
            }
          } else {
            update_post_meta($post_id, $hostpn_field['id'], '');
          }
        }
      }
    }
  }

  public function hostpn_accommodation_form_save($element_id, $key_value, $hostpn_form_type, $hostpn_form_subtype, $post_type = '') {
    // Determine post type from parameter or existing post
    if (empty($post_type) && !empty($element_id)) {
      $post_type = get_post_type($element_id);
    }

    if ($post_type == 'hostpn_accommodation') {
      switch ($hostpn_form_type) {
        case 'post':
          switch ($hostpn_form_subtype) {
            case 'post_new':
              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  if (strpos($key, 'hostpn_') !== false) {
                    ${$key} = $value;
                  }
                }
              }

              // Use existing post if already created by AJAX handler, otherwise create new
              if (!empty($element_id) && get_post($element_id)) {
                $accommodation_id = $element_id;
                $hostpn_accommodation_title = !empty($hostpn_accommodation_title) ? $hostpn_accommodation_title : get_the_title($accommodation_id);
                $hostpn_accommodation_description = !empty($hostpn_accommodation_description) ? $hostpn_accommodation_description : get_post_field('post_content', $accommodation_id);
                wp_update_post(['ID' => $accommodation_id, 'post_title' => esc_html($hostpn_accommodation_title), 'post_content' => $hostpn_accommodation_description]);
              } else {
                $post_functions = new HOSTPN_Functions_Post();
                $hostpn_accommodation_title = !empty($hostpn_accommodation_title) ? $hostpn_accommodation_title : gmdate('Y-m-d H:i:s', current_time('timestamp')) . ' - ' . bin2hex(openssl_random_pseudo_bytes(4));
                $hostpn_accommodation_description = !empty($hostpn_accommodation_description) ? $hostpn_accommodation_description : __('Accommodation description...', 'hostpn');
                $accommodation_id = $post_functions->hostpn_insert_post(esc_html($hostpn_accommodation_title), $hostpn_accommodation_description, '', sanitize_title(esc_html($hostpn_accommodation_title)), 'hostpn_accommodation', 'publish', get_current_user_id());
              }

              // Set language for new accommodation if Polylang is active
              if (class_exists('Polylang') && function_exists('pll_set_post_language')) {
                $current_lang = function_exists('pll_current_language') ? pll_current_language('slug') : (function_exists('pll_default_language') ? pll_default_language() : '');
                if (!empty($current_lang)) {
                  pll_set_post_language($accommodation_id, $current_lang);
                }
              }

              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_post_meta($accommodation_id, $key, $value);
                }
              }

              break;
            case 'post_edit':
              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  if (strpos($key, 'hostpn_') !== false) {
                    ${$key} = $value;
                    delete_post_meta($element_id, $key);
                  }
                }
              }

              $accommodation_id = $element_id;
              wp_update_post(['ID' => $accommodation_id, 'post_title' => $hostpn_accommodation_title, 'post_content' => $hostpn_accommodation_description,]);

              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_post_meta($accommodation_id, $key, $value);
                }
              }

              break;
            case 'post_check':
              self::hostpn_history_add($element_id);
              break;
            case 'post_uncheck':
              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  if (strpos($key, 'hostpn_') !== false) {
                    ${$key} = $value;
                    delete_post_meta($element_id, $key);
                  }
                }
              }

              break;
          }
      }
    }
  }

  public function hostpn_accommodation_register_scripts() {
    if (!wp_script_is('hostpn-aux', 'registered')) {
      wp_register_script('hostpn-aux', HOSTPN_URL . 'assets/js/hostpn-aux.js', [], HOSTPN_VERSION, true);
    }

    if (!wp_script_is('hostpn-forms', 'registered')) {
      wp_register_script('hostpn-forms', HOSTPN_URL . 'assets/js/hostpn-forms.js', [], HOSTPN_VERSION, true);
    }

    if (!wp_script_is('hostpn-selector', 'registered')) {
      wp_register_script('hostpn-selector', HOSTPN_URL . 'assets/js/hostpn-selector.js', [], HOSTPN_VERSION, true);
    }
  }
  
  /**
   * Register template filters after plugin initialization
   */
  public function hostpn_accommodation_register_template_filters() {
    // Only register filters if the plugin is fully loaded
    if (defined('HOSTPN_DIR') || defined('HOSTPN_URL')) {
      add_filter('single_template', [$this, 'hostpn_accommodation_single_template']);
      add_filter('archive_template', [$this, 'hostpn_accommodation_archive_template']);
      
      // Register block templates for Site Editor (block themes)
      if (function_exists('wp_is_block_theme') && wp_is_block_theme()) {
        add_filter('get_block_templates', [$this, 'hostpn_accommodation_register_block_templates'], 10, 3);
        // Also handle get_block_template to prevent undefined array key warnings
        add_filter('get_block_template', [$this, 'hostpn_accommodation_get_block_template'], 10, 3);
      }
    }
  }
  
  /**
   * Register accommodation templates as block templates for Site Editor
   *
   * @param array $query_result Array of template objects.
   * @param array $query Optional. Arguments to retrieve templates.
   * @param string $template_type Optional. The template type (wp_template or wp_template_part).
   * @return array Modified array of template objects.
   */
  public function hostpn_accommodation_register_block_templates($query_result, $query, $template_type) {
    // Only add templates when querying for wp_template type
    if ($template_type !== 'wp_template') {
      return $query_result;
    }
    
    // Check if we should filter by slug (to avoid adding templates unnecessarily)
    // But always include our templates to prevent undefined array key warnings
    $should_add_templates = true;
    if (isset($query['slug__in']) && is_array($query['slug__in']) && !empty($query['slug__in'])) {
      $requested_slugs = $query['slug__in'];
      $needed_slugs = ['single-hostpn_accommodation', 'archive-hostpn_accommodation'];
      // Only skip if none of our templates are requested
      if (!array_intersect($requested_slugs, $needed_slugs)) {
        $should_add_templates = false;
      }
    }
    
    // Always add templates when query is empty or when our templates are needed
    // This ensures WordPress can build its internal cache/index properly
    if (!$should_add_templates && !empty($query)) {
      return $query_result;
    }
    
    // Check if templates are already in the result to avoid duplicates
    $existing_slugs = [];
    foreach ($query_result as $existing_template) {
      if (isset($existing_template->slug)) {
        $existing_slugs[] = $existing_template->slug;
      }
    }
    
    // Get plugin block template paths
    $single_template_path = false;
    $archive_template_path = false;
    
    if (defined('HOSTPN_DIR')) {
      $single_template_path = HOSTPN_DIR . 'templates/block-templates/single-hostpn_accommodation.html';
      $archive_template_path = HOSTPN_DIR . 'templates/block-templates/archive-hostpn_accommodation.html';
    }

    if (!$single_template_path || !file_exists($single_template_path)) {
      $single_template_path = plugin_dir_path(dirname(__FILE__)) . 'templates/block-templates/single-hostpn_accommodation.html';
    }

    if (!$archive_template_path || !file_exists($archive_template_path)) {
      $archive_template_path = plugin_dir_path(dirname(__FILE__)) . 'templates/block-templates/archive-hostpn_accommodation.html';
    }
    
    // Create template objects for Site Editor
    $templates = [];
    
    // Single accommodation template
    if ($single_template_path && file_exists($single_template_path) && !in_array('single-hostpn_accommodation', $existing_slugs)) {
      $template_content = file_get_contents($single_template_path);
      $templates[] = (object) [
        'id' => 'hostpn//single-hostpn_accommodation',
        'theme' => 'hostpn',
        'content' => $template_content,
        'slug' => 'single-hostpn_accommodation',
        'source' => 'plugin',
        'type' => 'wp_template',
        'title' => __('Single Accommodation', 'hostpn'),
        'description' => __('Template for displaying a single accommodation post.', 'hostpn'),
        'status' => 'publish',
        'wp_id' => 0,
        'has_theme_file' => true,
        'is_custom' => false,
        'author' => 0,
        'post_types' => ['hostpn_accommodation'],
      ];
    }
    
    // Archive accommodation template
    if ($archive_template_path && file_exists($archive_template_path) && !in_array('archive-hostpn_accommodation', $existing_slugs)) {
      $template_content = file_get_contents($archive_template_path);
      $templates[] = (object) [
        'id' => 'hostpn//archive-hostpn_accommodation',
        'theme' => 'hostpn',
        'content' => $template_content,
        'slug' => 'archive-hostpn_accommodation',
        'source' => 'plugin',
        'type' => 'wp_template',
        'title' => __('Accommodation Archive', 'hostpn'),
        'description' => __('Template for displaying the accommodation archive page.', 'hostpn'),
        'status' => 'publish',
        'wp_id' => 0,
        'has_theme_file' => true,
        'is_custom' => false,
        'author' => 0,
        'post_types' => ['hostpn_accommodation'],
      ];
    }
    
    // Merge with existing templates
    return array_merge($query_result, $templates);
  }
  
  /**
   * Get a specific block template to prevent undefined array key warnings
   *
   * @param WP_Block_Template|null $template The resolved template.
   * @param string $id The template ID (e.g., 'hostpn//single-hostpn_accommodation').
   * @param string $template_type The template type.
   * @return WP_Block_Template|null The resolved template or null.
   */
  public function hostpn_accommodation_get_block_template($template, $id, $template_type) {
    // Only handle wp_template type
    if ($template_type !== 'wp_template') {
      return $template;
    }
    
    // Check if this is one of our templates
    if (strpos($id, 'hostpn//') === 0) {
      $slug = str_replace('hostpn//', '', $id);
      
      // Only handle our specific templates
      if (!in_array($slug, ['single-hostpn_accommodation', 'archive-hostpn_accommodation'])) {
        return $template;
      }
      
      // Get the appropriate template path
      $template_path = false;
      if (defined('HOSTPN_DIR')) {
        $template_path = HOSTPN_DIR . 'templates/block-templates/' . $slug . '.html';
      }

      if (!$template_path || !file_exists($template_path)) {
        $template_path = plugin_dir_path(dirname(__FILE__)) . 'templates/block-templates/' . $slug . '.html';
      }
      
      if ($template_path && file_exists($template_path)) {
        $template_content = file_get_contents($template_path);
        
        // Create template object
        $template = (object) [
          'id' => $id,
          'theme' => 'hostpn',
          'content' => $template_content,
          'slug' => $slug,
          'source' => 'plugin',
          'type' => 'wp_template',
          'title' => $slug === 'single-hostpn_accommodation' ? __('Single Accommodation', 'hostpn') : __('Accommodation Archive', 'hostpn'),
          'description' => $slug === 'single-hostpn_accommodation' ? __('Template for displaying a single accommodation post.', 'hostpn') : __('Template for displaying the accommodation archive page.', 'hostpn'),
          'status' => 'publish',
          'wp_id' => 0,
          'has_theme_file' => true,
          'is_custom' => false,
          'author' => 0,
          'post_types' => ['hostpn_accommodation'],
        ];
      }
    }
    
    return $template;
  }
  
  public function hostpn_accommodation_admin_order($query) {
    if (!is_admin() || !$query->is_main_query()) {
      return;
    }

    if (isset($_GET['post_type']) && $_GET['post_type'] === 'hostpn_accommodation') {
      if (!isset($_GET['orderby'])) {
        $query->set('orderby', 'date');
        $query->set('order', 'DESC');
      }
    }
  }

  public function hostpn_accommodation_print_scripts() {
    wp_print_scripts(['hostpn-aux', 'hostpn-forms', 'hostpn-selector']);
  }

  public function hostpn_accommodation_list_wrapper() {
    ob_start();

    if(HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
      ?>
        <div class="hostpn-hostpn_accommodation-list hostpn-mb-50">
          <div class="hostpn-accommodation-search-container hostpn-mb-20 hostpn-text-align-right">
            <div class="hostpn-accommodation-search-wrapper">
              <input type="text" class="hostpn-accommodation-search-input hostpn-input hostpn-display-none" placeholder="<?php esc_attr_e('Filter...', 'hostpn'); ?>" />
              <i class="material-icons-outlined hostpn-accommodation-search-toggle hostpn-cursor-pointer hostpn-font-size-30 hostpn-vertical-align-middle hostpn-tooltip" title="<?php esc_attr_e('Search Accommodations', 'hostpn'); ?>">search</i>

              <div class="hostpn-display-inline-block hostpn-position-relative">
                <i class="material-icons-outlined hostpn-sort-toggle hostpn-cursor-pointer hostpn-font-size-30 hostpn-vertical-align-middle hostpn-tooltip" title="<?php esc_attr_e('Sort', 'hostpn'); ?>">sort</i>
                <div class="hostpn-sort-menu hostpn-display-none-soft">
                  <ul class="hostpn-list-style-none">
                    <li><a href="#" class="hostpn-sort-option hostpn-sort-active hostpn-text-decoration-none" data-hostpn-sort="date-desc"><?php esc_html_e('Newest first', 'hostpn'); ?></a></li>
                    <li><a href="#" class="hostpn-sort-option hostpn-text-decoration-none" data-hostpn-sort="date-asc"><?php esc_html_e('Oldest first', 'hostpn'); ?></a></li>
                    <li><a href="#" class="hostpn-sort-option hostpn-text-decoration-none" data-hostpn-sort="name-asc"><?php esc_html_e('Name A-Z', 'hostpn'); ?></a></li>
                    <li><a href="#" class="hostpn-sort-option hostpn-text-decoration-none" data-hostpn-sort="name-desc"><?php esc_html_e('Name Z-A', 'hostpn'); ?></a></li>
                  </ul>
                </div>
              </div>

              <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-add" data-hostpn-ajax-type="hostpn_accommodation_new">
                <i class="material-icons-outlined hostpn-cursor-pointer hostpn-font-size-30 hostpn-vertical-align-middle hostpn-tooltip" title="<?php esc_attr_e('Add new Accommodation', 'hostpn'); ?>">add</i>
              </a>
            </div>
          </div>

          <div class="hostpn-hostpn_accommodation-list-wrapper">
            <?php echo wp_kses(self::hostpn_accommodation_list(), HOSTPN_KSES); ?>
          </div>
        </div>
      <?php
    }else{
      echo do_shortcode('[hostpn-call-to-action hostpn_call_to_action_icon="account_circle" hostpn_call_to_action_title="' . __('Account needed', 'hostpn') . '" hostpn_call_to_action_content="' . __('You need a valid account to see this content. Please', 'hostpn') . ' ' . '<a href=\'#\' class=\'userspn-profile-popup-btn\'>' . __('login', 'hostpn') . '</a>' . ' ' . __('or', 'hostpn') . ' ' . '<a href=\'#\' class=\'userspn-profile-popup-btn\' data-userspn-action=\'register\'>' . __('register', 'hostpn') . '</a>' . ' ' . __('to go ahead', 'hostpn') . '" hostpn_call_to_action_button_link="#" hostpn_call_to_action_button_text="' . __('Login', 'hostpn') . '" hostpn_call_to_action_button_class="userspn-profile-popup-btn" hostpn_call_to_action_class="hostpn-mb-100"]');
    }
    
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_accommodation_list() {
    $accommodation_atts = [
      'fields' => 'ids',
      'numberposts' => -1,
      'post_type' => 'hostpn_accommodation',
      'post_status' => 'any', 
      'orderby' => 'date', 
      'order' => 'DESC', 
    ];
    
    if (class_exists('Polylang')) {
      $accommodation_atts['lang'] = pll_current_language('slug');
    }

    $accommodation = get_posts($accommodation_atts);

    ob_start();
    ?>
      <ul class="hostpn-accommodations hostpn-list-style-none hostpn-margin-auto">
        <?php if (!empty($accommodation)): ?>
          <?php foreach ($accommodation as $accommodation_id): ?>
            <?php
              $hostpn_accommodation_period = get_post_meta($accommodation_id, 'hostpn_accommodation_period', true);
              $hostpn_accommodation_timed_checkbox = get_post_meta($accommodation_id, 'hostpn_accommodation_timed_checkbox', true);
              $accommodation_title = get_post_meta($accommodation_id, 'hostpn_accommodation_title', true);
            ?>

            <li class="hostpn-accommodation hostpn-mb-10" data-hostpn_accommodation-id="<?php echo esc_attr($accommodation_id); ?>"
                data-hostpn-sort-name="<?php echo esc_attr(strtolower(get_post_meta($accommodation_id, 'hostpn_accommodation_title', true))); ?>"
                data-hostpn-sort-date="<?php echo esc_attr(get_post_field('post_date', $accommodation_id)); ?>">
              <div class="hostpn-display-table hostpn-width-100-percent">
                <div class="hostpn-display-inline-table hostpn-width-60-percent">
                  <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-view" data-hostpn-ajax-type="hostpn_accommodation_view">
                    <span><?php echo esc_html($accommodation_title); ?></span>
                      
                    <?php if ($hostpn_accommodation_timed_checkbox == 'on'): ?>
                      <i class="material-icons-outlined hostpn-timed hostpn-cursor-pointer hostpn-vertical-align-super hostpn-p-5 hostpn-font-size-15 hostpn-tooltip" title="<?php esc_html_e('This Accommodation is timed', 'hostpn'); ?>">access_time</i>
                    <?php endif ?>

                    <?php if ($hostpn_accommodation_period == 'on'): ?>
                      <i class="material-icons-outlined hostpn-timed hostpn-cursor-pointer hostpn-vertical-align-super hostpn-p-5 hostpn-font-size-15 hostpn-tooltip" title="<?php esc_html_e('This Accommodation is periodic', 'hostpn'); ?>">replay</i>
                    <?php endif ?>
                  </a>
                </div>

                <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right hostpn-position-relative">
                  <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-share" data-hostpn-ajax-type="hostpn_accommodation_share">
                    <i class="material-icons-outlined hostpn-share-btn hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-mr-10 hostpn-tooltip" title="<?php esc_html_e('Share link to allow guests fill their information out directly in the platform.', 'hostpn'); ?>">share</i>
                  </a>

                  <i class="material-icons-outlined hostpn-menu-more-btn hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30">more_vert</i>

                  <div class="hostpn-menu-more hostpn-z-index-99 hostpn-display-none-soft">
                    <ul class="hostpn-list-style-none">
                      <li>
                        <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-view" data-hostpn-ajax-type="hostpn_accommodation_view">
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('View Accommodation', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">visibility</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-edit" data-hostpn-ajax-type="hostpn_accommodation_edit"> 
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Edit Accommodation', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">edit</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-share" data-hostpn-ajax-type="hostpn_accommodation_share">
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Share accommodation', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">share</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-accommodation-duplicate-post">
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Duplicate Accommodation', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">copy</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <?php if (current_user_can('manage_options')) : ?>
                        <li>
                          <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none"
                             data-hostpn-popup-id="hostpn-popup-financial-view"
                             data-hostpn-ajax-type="hostpn_financial_view">
                            <div class="hostpn-display-table hostpn-width-100-percent">
                              <div class="hostpn-display-inline-table hostpn-width-70-percent">
                                <p><?php esc_html_e('Financial Data', 'hostpn'); ?></p>
                              </div>
                              <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                                <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">account_balance</i>
                              </div>
                            </div>
                          </a>
                        </li>
                      <?php endif; ?>
                      <li>
                        <a href="#" class="hostpn-popup-open" data-hostpn-popup-id="hostpn-popup-accommodation-remove">
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Remove Accommodation', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">delete</i>
                            </div>
                          </div>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </li>
          <?php endforeach ?>
        <?php endif ?>

        <li class="hostpn-mt-50 hostpn-accommodation" data-hostpn_accommodation-id="0">
          <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-add" data-hostpn-ajax-type="hostpn_accommodation_new">
            <div class="hostpn-display-table hostpn-width-100-percent">
              <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-tablet-display-block hostpn-tablet-width-100-percent hostpn-text-align-center">
                <i class="material-icons-outlined hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30 hostpn-width-25">add</i>
              </div>
              <div class="hostpn-display-inline-table hostpn-width-80-percent hostpn-tablet-display-block hostpn-tablet-width-100-percent">
                <?php esc_html_e('Add new Accommodation', 'hostpn'); ?>
              </div>
            </div>
          </a>
        </li>
      </ul>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_accommodation_view($accommodation_id) {  
    ob_start();
    self::hostpn_accommodation_register_scripts();
    self::hostpn_accommodation_print_scripts();
    ?>
      <div class="accommodation-view hostpn-p-30" data-hostpn_accommodation-id="<?php echo esc_attr($accommodation_id); ?>">
        <h4 class="hostpn-text-align-center"><?php echo esc_html(get_the_title($accommodation_id)); ?></h4>
        
        <div class="hostpn-word-wrap-break-word">
          <p><?php echo wp_kses(str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($accommodation_id)->post_content)), HOSTPN_KSES); ?></p>
        </div>

        <div class="accommodation-view">
          <?php foreach (array_merge(self::hostpn_accommodation_get_fields(), self::hostpn_accommodation_get_fields_meta()) as $hostpn_field): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_display_wrapper($hostpn_field, 'post', $accommodation_id), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right hostpn-accommodation" data-hostpn_accommodation-id="<?php echo esc_attr($accommodation_id); ?>">
            <a href="#" class="hostpn-btn hostpn-btn-mini hostpn-popup-open-ajax" data-hostpn-popup-id="hostpn-popup-accommodation-edit" data-hostpn-ajax-type="hostpn_accommodation_edit"><?php esc_html_e('Edit Accommodation', 'hostpn'); ?></a>
          </div>
        </div>
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_accommodation_new() {
    ob_start();
    self::hostpn_accommodation_register_scripts();
    self::hostpn_accommodation_print_scripts();
    ?>
      <div class="accommodation-new hostpn-p-30">
        <h4 class="hostpn-mb-30"><?php esc_html_e('Add new Accommodation', 'hostpn'); ?></h4>

        <form action="" method="post" id="hostpn-form" class="hostpn-form">      
          <?php foreach (self::hostpn_accommodation_get_fields() as $hostpn_field): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post'), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <?php foreach (self::hostpn_accommodation_get_fields_meta() as $hostpn_field_meta): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field_meta, 'post'), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right">
            <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-subtype="post_new" data-hostpn-post-type="hostpn_accommodation" type="submit" value="<?php esc_attr_e('Create Accommodation', 'hostpn'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_accommodation_edit($accommodation_id) {
    ob_start();
    self::hostpn_accommodation_register_scripts();
    self::hostpn_accommodation_print_scripts();
    ?>
      <div class="accommodation-edit hostpn-p-30">
        <p class="hostpn-text-align-center hostpn-mb-0"><?php esc_html_e('Editing', 'hostpn'); ?></p>
        <h4 class="hostpn-text-align-center hostpn-mb-30"><?php echo esc_html(get_the_title($accommodation_id)); ?></h4>

        <form action="" method="post" id="hostpn-form" class="hostpn-form">      
          <?php foreach (self::hostpn_accommodation_get_fields($accommodation_id) as $hostpn_field): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $accommodation_id), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <?php foreach (self::hostpn_accommodation_get_fields_meta() as $hostpn_field_meta): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field_meta, 'post', $accommodation_id), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right">
            <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-subtype="post_edit" data-hostpn-post-type="hostpn_accommodation" type="submit" data-hostpn-post-id="<?php echo esc_attr($accommodation_id); ?>" value="<?php esc_attr_e('Save Accommodation', 'hostpn'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }
  
  public function hostpn_accommodation_share() {
    ob_start();
    ?>
      <div class="hostpn-p-30">
        <h3 class="hostpn-text-align-center"><?php esc_html_e('Accommodation share link', 'hostpn'); ?></h3>
        <p class="hostpn-text-align-center"><?php esc_html_e('You can share accommodation link to allow companions to fill out their guests forms directly in the platform.', 'hostpn'); ?></p>

        <?php if (class_exists('USERSPN')): ?>
          <div class="hostpn-display-table hostpn-width-100-percent">
            <div class="hostpn-display-inline-table hostpn-width-90-percent">
              <code id="hostpn-share-url"><?php 
                $share_url = add_query_arg([
                  'hostpn_action' => 'popup_open',
                  'hostpn_popup' => 'userspn-profile-popup',
                  'hostpn_tab' => 'register',
                  'hostpn_get_nonce' => wp_create_nonce('hostpn-get-nonce')
                ], home_url('guests'));
                echo esc_url($share_url); 
              ?></code>
            </div>
            <div class="hostpn-display-inline-table hostpn-width-10-percent hostpn-text-align-center hostpn-copy-disabled">
              <i class="material-icons-outlined hostpn-btn-copy hostpn-vertical-align-middle hostpn-cursor-pointer hostpn-tooltip" title="<?php esc_html_e('Copy url', 'hostpn'); ?>" data-hostpn-copy-content="#hostpn-share-url">content_copy</i>
            </div>
          </div>
        <?php else: ?>
          <p class="hostpn-text-align-center"><?php esc_html_e('Please install Users Manager - PN plugin to allow user creation and management.', 'hostpn'); ?></p>

          <div class="hostpn-text-align-center">
            <a href="<?php echo esc_url(self::hostpn_share_link()); ?>" class="hostpn-btn hostpn-btn-mini"><?php esc_html_e('Install plugin', 'hostpn'); ?></a>
          </div>
        <?php endif ?>
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_share_link() {
    return esc_url(admin_url('/plugin-install.php?s=userspn&tab=search&type=term'));
  }

  /**
   * Register hostpn_contract as a recognized query variable.
   */
  public function hostpn_contract_query_vars($vars) {
    $vars[] = 'hostpn_contract';
    $vars[] = 'hostpn_contract_room';
    return $vars;
  }

  /**
   * Enqueue contract public scripts if hostpn_contract parameter is present.
   * Runs during wp_enqueue_scripts so styles/scripts are properly queued.
   */
  public function hostpn_contract_enqueue_scripts() {
    if (empty($_GET['hostpn_contract'])) {
      return;
    }

    wp_enqueue_style('hostpn-contract-public', HOSTPN_URL . 'assets/css/public/hostpn-contract-public.css', [], HOSTPN_VERSION, 'all');
    wp_enqueue_style('hostpn-material-icons-outlined', HOSTPN_URL . 'assets/css/material-icons-outlined.min.css', [], HOSTPN_VERSION, 'all');
    wp_enqueue_script('jquery');
    wp_enqueue_script('hostpn-signature-pad', HOSTPN_URL . 'assets/js/vendor/signature_pad.umd.min.js', [], '4.1.7', true);
    wp_enqueue_script('hostpn-html2pdf', HOSTPN_URL . 'assets/js/vendor/html2pdf.bundle.min.js', [], '0.10.1', true);
    wp_enqueue_script('hostpn-contract-public', HOSTPN_URL . 'assets/js/public/hostpn-contract-public.js', ['jquery', 'hostpn-signature-pad', 'hostpn-html2pdf'], HOSTPN_VERSION, true);
  }

  /**
   * Intercept requests with hostpn_contract parameter and load the contract template.
   */
  public function hostpn_contract_template_redirect() {
    // DEBUG: Log to footer so we can see in browser console
    add_action('wp_footer', function() {
      $debug = [];
      $debug['hook_fired'] = true;
      $debug['GET_params'] = array_keys($_GET);
      $debug['hostpn_contract_present'] = isset($_GET['hostpn_contract']);
      $debug['hostpn_contract_value'] = isset($_GET['hostpn_contract']) ? sanitize_text_field(wp_unslash($_GET['hostpn_contract'])) : 'NOT SET';
      $debug['HOSTPN_DIR'] = defined('HOSTPN_DIR') ? HOSTPN_DIR : 'NOT DEFINED';
      $debug['template_exists'] = defined('HOSTPN_DIR') ? file_exists(HOSTPN_DIR . 'templates/public/hostpn-contract-view.php') : false;

      if (!empty($_GET['hostpn_contract'])) {
        $token = sanitize_text_field(wp_unslash($_GET['hostpn_contract']));
        $debug['token'] = $token;
        $debug['token_length'] = strlen($token);

        global $wpdb;
        $meta_row = $wpdb->get_row($wpdb->prepare(
          "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = 'hostpn_contract_token' AND meta_value = %s LIMIT 1",
          $token
        ));
        $debug['db_direct_query'] = $meta_row ? ['post_id' => $meta_row->post_id, 'meta_value' => $meta_row->meta_value] : 'NO MATCH IN DB';

        $posts = get_posts([
          'post_type'      => 'hostpn_accommodation',
          'post_status'    => 'any',
          'numberposts'    => 1,
          'meta_query'     => [
            [
              'key'   => 'hostpn_contract_token',
              'value' => $token,
            ],
          ],
        ]);
        $debug['get_posts_result'] = !empty($posts) ? 'FOUND (ID: ' . $posts[0]->ID . ')' : 'EMPTY';
      }

      echo '<script>console.log("[HOSTPN Contract Debug]", ' . wp_json_encode($debug) . ');</script>';
    });

    if (empty($_GET['hostpn_contract'])) {
      return;
    }

    $token = sanitize_text_field(wp_unslash($_GET['hostpn_contract']));
    if (empty($token) || strlen($token) < 16) {
      return;
    }

    // Direct DB query for maximum reliability (bypasses WP_Query filters)
    global $wpdb;
    $accommodation_id = $wpdb->get_var($wpdb->prepare(
      "SELECT p.ID FROM {$wpdb->posts} p
       INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
       WHERE pm.meta_key = 'hostpn_contract_token'
       AND pm.meta_value = %s
       AND p.post_type = 'hostpn_accommodation'
       LIMIT 1",
      $token
    ));

    if (empty($accommodation_id)) {
      return;
    }

    // Enqueue styles/scripts directly since wp_enqueue_scripts may have already fired
    wp_enqueue_style('hostpn-contract-public', HOSTPN_URL . 'assets/css/public/hostpn-contract-public.css', [], HOSTPN_VERSION, 'all');
    wp_enqueue_style('hostpn-material-icons-outlined', HOSTPN_URL . 'assets/css/material-icons-outlined.min.css', [], HOSTPN_VERSION, 'all');
    wp_enqueue_script('jquery');
    wp_enqueue_script('hostpn-signature-pad', HOSTPN_URL . 'assets/js/vendor/signature_pad.umd.min.js', [], '4.1.7', true);
    wp_enqueue_script('hostpn-html2pdf', HOSTPN_URL . 'assets/js/vendor/html2pdf.bundle.min.js', [], '0.10.1', true);
    wp_enqueue_script('hostpn-contract-public', HOSTPN_URL . 'assets/js/public/hostpn-contract-public.js', ['jquery', 'hostpn-signature-pad', 'hostpn-html2pdf'], HOSTPN_VERSION, true);

    // Load the contract template
    $template_path = HOSTPN_DIR . 'templates/public/hostpn-contract-view.php';
    if (file_exists($template_path)) {
      include $template_path;
      exit;
    }
  }

  /**
   * AJAX handler: switch contract locale and return re-rendered HTML.
   */
  public static function hostpn_contract_switch_locale() {
    check_ajax_referer('hostpn-contract-public', 'hostpn_contract_nonce');

    $token   = isset($_POST['hostpn_contract_token']) ? sanitize_text_field(wp_unslash($_POST['hostpn_contract_token'])) : '';
    $room_id = isset($_POST['hostpn_contract_room']) ? absint($_POST['hostpn_contract_room']) : 0;
    $locale  = isset($_POST['hostpn_locale']) ? sanitize_text_field(wp_unslash($_POST['hostpn_locale'])) : '';

    if (empty($token) || empty($locale)) {
      echo wp_json_encode(['error_key' => 'invalid_request']);
      wp_die();
    }

    // Validate locale
    $available = HOSTPN_Contract_Templates::hostpn_get_available_contract_languages();
    if (!isset($available[$locale])) {
      echo wp_json_encode(['error_key' => 'invalid_locale']);
      wp_die();
    }

    // Find accommodation by token
    $posts = get_posts([
      'post_type'   => 'hostpn_accommodation',
      'post_status' => 'any',
      'numberposts' => 1,
      'meta_key'    => 'hostpn_contract_token',
      'meta_value'  => $token,
    ]);

    if (empty($posts)) {
      echo wp_json_encode(['error_key' => 'not_found']);
      wp_die();
    }

    $accommodation_id = $posts[0]->ID;

    // Validate room belongs to accommodation
    if ($room_id) {
      $room_acc = get_post_meta($room_id, 'hostpn_room_accommodation_id', true);
      if (intval($room_acc) !== $accommodation_id) {
        $room_id = 0;
      }
    }

    // Get contract type
    $accommodation_type = get_post_meta($accommodation_id, 'hostpn_accommodation_type', true);
    $contract_type = HOSTPN_Contract_Templates::hostpn_get_type_for_accommodation($accommodation_type);

    // ── DEBUG: collect diagnostic info ──
    $debug = [];
    $debug['requested_locale'] = $locale;
    $debug['get_locale_before'] = get_locale();

    $mo_file = HOSTPN_DIR . 'languages/hostpn-' . $locale . '.mo';
    $l10n_file = HOSTPN_DIR . 'languages/hostpn-' . $locale . '.l10n.php';
    $debug['mo_file_exists'] = file_exists($mo_file);
    $debug['l10n_file_exists'] = file_exists($l10n_file);

    // ── Switch locale ──
    // Bypass WP's translation loading entirely: WP 7.x's WP_Translation_Controller
    // caches failed loads and checks x-domain headers in .l10n.php files, making
    // load_textdomain unreliable for runtime locale switching.
    // Instead, read the .l10n.php file directly and use the gettext filter.
    $locale_switched = false;

    if ($locale !== 'en_US') {
      $locale_switched = switch_to_locale($locale);
      $debug['switch_to_locale_result'] = $locale_switched;

      // Load translations directly from .l10n.php file
      $hostpn_messages = [];
      if (file_exists($l10n_file)) {
        $l10n_data = @include $l10n_file;
        if (is_array($l10n_data) && !empty($l10n_data['messages'])) {
          $hostpn_messages = $l10n_data['messages'];
          $debug['direct_load'] = true;
          $debug['messages_count'] = count($hostpn_messages);
        }
      }

      // Override __() via gettext filter for the hostpn domain
      if (!empty($hostpn_messages)) {
        add_filter('gettext', function ($translation, $text, $domain) use ($hostpn_messages) {
          if ($domain === 'hostpn' && isset($hostpn_messages[$text]) && $hostpn_messages[$text] !== '') {
            return $hostpn_messages[$text];
          }
          return $translation;
        }, 1, 3);
      }
    }

    // Test translations
    $debug['test___PARTIES'] = __('PARTIES', 'hostpn');
    $debug['test___THE_LANDLORD'] = __('THE LANDLORD', 'hostpn');

    // Always use the default template (translatable __() calls)
    $template = HOSTPN_Contract_Templates::hostpn_get_default_template($contract_type);

    // Check first section content (snippet)
    $first_key = array_key_first($template);
    if ($first_key) {
      $debug['first_section_snippet'] = mb_substr(strip_tags($template[$first_key]), 0, 120);
    }

    // Render
    $contract_html  = HOSTPN_Contract_Templates::hostpn_render_contract($contract_type, $template, $accommodation_id, $room_id);
    $inventory_html = HOSTPN_Contract_Templates::hostpn_render_inventory($accommodation_id, $room_id);

    // Translated signature labels
    $signature_labels = [
      'landlord' => $contract_type === 'turistico' ? __('THE OWNER', 'hostpn') : __('THE LANDLORD', 'hostpn'),
      'tenant'   => $contract_type === 'turistico' ? __('THE GUEST', 'hostpn') : __('THE TENANT', 'hostpn'),
      'clear'    => __('Clear', 'hostpn'),
      'download' => __('Download PDF', 'hostpn'),
    ];

    // Restore locale and remove our gettext override
    remove_all_filters('gettext');
    if ($locale_switched) {
      restore_current_locale();
    }

    echo wp_json_encode([
      'error_key'        => '',
      'contract_html'    => $contract_html,
      'inventory_html'   => $inventory_html,
      'signature_labels' => $signature_labels,
      'debug'            => $debug,
    ]);
    wp_die();
  }

  /**
   * Get list of occupied rooms for an accommodation (rooms with assigned guest).
   *
   * @param int $accommodation_id
   * @return array
   */
  public static function hostpn_get_occupied_rooms($accommodation_id) {
    $rooms = HOSTPN_Post_Type_Room::hostpn_get_rooms_by_accommodation($accommodation_id);
    $occupied_rooms = [];

    foreach ($rooms as $room_id) {
      $guest_id = get_post_meta($room_id, 'hostpn_room_guest_id', true);
      if (!empty($guest_id)) {
        $room_number = get_post_meta($room_id, 'hostpn_room_number', true);
        $room_title = !empty($room_number) ? sprintf(__('Room %s', 'hostpn'), $room_number) : get_the_title($room_id);
        
        $guest_name = get_the_title($guest_id);
        $guest_user_id = get_post_meta($guest_id, 'hostpn_guest_user_id', true);

        $occupied_rooms[] = [
          'room_id'       => $room_id,
          'room_label'    => $room_title,
          'room_number'   => $room_number,
          'guest_id'      => $guest_id,
          'guest_name'    => $guest_name,
          'guest_user_id' => intval($guest_user_id),
        ];
      }
    }

    $custom_order = get_post_meta($accommodation_id, 'hostpn_shared_cleaning_room_order', true);
    if (is_array($custom_order) && !empty($custom_order)) {
      usort($occupied_rooms, function($a, $b) use ($custom_order) {
        $pos_a = array_search($a['room_id'], $custom_order);
        $pos_b = array_search($b['room_id'], $custom_order);
        if ($pos_a === false) $pos_a = 9999;
        if ($pos_b === false) $pos_b = 9999;
        return $pos_a - $pos_b;
      });
    }

    return $occupied_rooms;
  }

  /**
   * Save custom room rotation order for shared cleaning and reset turn index to 0.
   *
   * @param int   $accommodation_id
   * @param array $room_order Array of room IDs in desired rotation order.
   * @return bool
   */
  public static function hostpn_save_shared_cleaning_queue_order($accommodation_id, $room_order = []) {
    if (!is_array($room_order)) {
      $room_order = [];
    }
    $clean_order = array_map('intval', $room_order);
    update_post_meta($accommodation_id, 'hostpn_shared_cleaning_room_order', $clean_order);
    update_post_meta($accommodation_id, 'hostpn_shared_cleaning_turn_index', 0);
    return true;
  }

  /**
   * Get shared cleaning configuration, rotation status, comments, and history.
   *
   * @param int $accommodation_id
   * @return array
   */
  public static function hostpn_get_shared_cleaning_info($accommodation_id) {
    $system = get_post_meta($accommodation_id, 'hostpn_cleaning_system', true);
    if (empty($system)) {
      $system = 'punctual';
    }
    $frequency_days = intval(get_post_meta($accommodation_id, 'hostpn_shared_cleaning_frequency_days', true));
    if ($frequency_days <= 0) {
      $frequency_days = 7;
    }
    $notice_days = intval(get_post_meta($accommodation_id, 'hostpn_shared_cleaning_notice_days', true));
    if ($notice_days <= 0) {
      $notice_days = 2;
    }
    $next_date = get_post_meta($accommodation_id, 'hostpn_shared_cleaning_next_date', true);
    if (empty($next_date)) {
      $next_date = date('Y-m-d', strtotime('+' . $frequency_days . ' days'));
    }
    $stays = get_post_meta($accommodation_id, 'hostpn_shared_cleaning_stays', true);
    if (empty($stays)) {
      $stays = __('Kitchen, Shared Bathroom, Living Room, Hallway', 'hostpn');
    }
    $turn_index = intval(get_post_meta($accommodation_id, 'hostpn_shared_cleaning_turn_index', true));

    $occupied_rooms = self::hostpn_get_occupied_rooms($accommodation_id);
    $total_occ = count($occupied_rooms);
    if (!empty($occupied_rooms) && !empty($next_date)) {
      $next_ts = strtotime($next_date);
      foreach ($occupied_rooms as $idx => &$room_info) {
        $steps = ($idx - $turn_index + $total_occ) % $total_occ;
        $est_ts = strtotime('+' . ($steps * $frequency_days) . ' days', $next_ts);
        $room_info['estimated_next_date'] = date('Y-m-d', $est_ts);
      }
      unset($room_info);
    }

    if (empty($occupied_rooms)) {
      $current_turn = null;
    } else {
      if ($turn_index >= count($occupied_rooms)) {
        $turn_index = 0;
      }
      $current_turn = $occupied_rooms[$turn_index];
    }

    $comments = get_post_meta($accommodation_id, 'hostpn_shared_cleaning_comments', true);
    if (!is_array($comments)) {
      $comments = [];
    }

    $history = get_post_meta($accommodation_id, 'hostpn_shared_cleaning_history', true);
    if (!is_array($history)) {
      $history = [];
    }

    $instructions = get_post_meta($accommodation_id, 'hostpn_shared_cleaning_instructions', true);

    return [
      'system'          => $system,
      'frequency_days'  => $frequency_days,
      'notice_days'     => $notice_days,
      'next_date'       => $next_date,
      'stays'           => $stays,
      'turn_index'      => $turn_index,
      'occupied_rooms'  => $occupied_rooms,
      'current_turn'    => $current_turn,
      'instructions'    => $instructions,
      'comments'        => $comments,
      'history'         => $history,
    ];
  }

  /**
   * Complete shared cleaning turn, record history, advance turn and next date.
   *
   * @param int    $accommodation_id
   * @param string $notes
   * @param int    $user_id
   * @return array
   */
  public static function hostpn_complete_shared_cleaning_turn($accommodation_id, $notes = '', $user_id = 0) {
    $info = self::hostpn_get_shared_cleaning_info($accommodation_id);
    $occupied_rooms = $info['occupied_rooms'];
    $current_turn = $info['current_turn'];

    $history_item = [
      'date'         => current_time('mysql'),
      'room_id'      => $current_turn ? $current_turn['room_id'] : 0,
      'room_label'   => $current_turn ? $current_turn['room_label'] : __('General', 'hostpn'),
      'guest_name'   => $current_turn ? $current_turn['guest_name'] : '',
      'completed_by' => $user_id ? get_userdata($user_id)->display_name : __('System', 'hostpn'),
      'notes'        => sanitize_textarea_field($notes),
    ];

    $history = get_post_meta($accommodation_id, 'hostpn_shared_cleaning_history', true);
    if (!is_array($history)) {
      $history = [];
    }
    array_unshift($history, $history_item);
    if (count($history) > 50) {
      $history = array_slice($history, 0, 50);
    }
    update_post_meta($accommodation_id, 'hostpn_shared_cleaning_history', $history);

    $turn_index = $info['turn_index'];
    if (!empty($occupied_rooms)) {
      $turn_index = ($turn_index + 1) % count($occupied_rooms);
    } else {
      $turn_index = 0;
    }
    update_post_meta($accommodation_id, 'hostpn_shared_cleaning_turn_index', $turn_index);

    $frequency_days = $info['frequency_days'];
    $next_date_ts = strtotime($info['next_date']);
    if ($next_date_ts < time()) {
      $next_date_ts = time();
    }
    $new_next_date = date('Y-m-d', $next_date_ts + ($frequency_days * DAY_IN_SECONDS));
    update_post_meta($accommodation_id, 'hostpn_shared_cleaning_next_date', $new_next_date);

    delete_post_meta($accommodation_id, 'hostpn_shared_cleaning_reminder_sent_for_date');

    return [
      'success'        => true,
      'new_next_date'  => $new_next_date,
      'new_turn_index' => $turn_index,
    ];
  }

  /**
   * Add a comment to the group shared cleaning timeline.
   *
   * @param int    $accommodation_id
   * @param int    $user_id
   * @param string $comment_text
   * @return array|false
   */
  public static function hostpn_add_shared_cleaning_comment($accommodation_id, $user_id, $comment_text) {
    $comment_text = sanitize_textarea_field($comment_text);
    if (empty($comment_text)) {
      return false;
    }

    $user = get_userdata($user_id);
    $author_name = $user ? $user->display_name : __('Guest', 'hostpn');

    $guest_id = HOSTPN_Post_Type_Contract::hostpn_get_guest_id_for_user($user_id);
    $room_label = '';
    if ($guest_id) {
      $rooms = HOSTPN_Post_Type_Room::hostpn_get_rooms_by_accommodation($accommodation_id);
      foreach ($rooms as $r_id) {
        if (intval(get_post_meta($r_id, 'hostpn_room_guest_id', true)) === intval($guest_id)) {
          $num = get_post_meta($r_id, 'hostpn_room_number', true);
          $room_label = !empty($num) ? sprintf(__('Room %s', 'hostpn'), $num) : get_the_title($r_id);
          break;
        }
      }
    }

    $new_comment = [
      'id'          => uniqid('cm_'),
      'user_id'     => $user_id,
      'author_name' => $author_name,
      'room_label'  => $room_label,
      'text'        => $comment_text,
      'date'        => current_time('mysql'),
    ];

    $comments = get_post_meta($accommodation_id, 'hostpn_shared_cleaning_comments', true);
    if (!is_array($comments)) {
      $comments = [];
    }
    array_unshift($comments, $new_comment);
    if (count($comments) > 100) {
      $comments = array_slice($comments, 0, 100);
    }

    update_post_meta($accommodation_id, 'hostpn_shared_cleaning_comments', $comments);
    return $new_comment;
  }

  /**
   * Send shared cleaning email reminder to the current turn room's occupant.
   * Uses MailPN if installed, otherwise wp_mail().
   *
   * @param int $accommodation_id
   * @return bool
   */
  public static function hostpn_send_shared_cleaning_reminder($accommodation_id) {
    $info = self::hostpn_get_shared_cleaning_info($accommodation_id);
    if ($info['system'] !== 'shared') {
      return false;
    }

    $current_turn = $info['current_turn'];
    if (!$current_turn || empty($current_turn['guest_user_id'])) {
      return false;
    }

    $accommodation_title = get_the_title($accommodation_id);
    $guest_user_id = $current_turn['guest_user_id'];
    $guest_name    = $current_turn['guest_name'];
    $room_label    = $current_turn['room_label'];
    $next_date     = date_i18n(get_option('date_format'), strtotime($info['next_date']));
    $stays         = $info['stays'];

    $subject = sprintf(__('Shared Cleaning Reminder — %s', 'hostpn'), $accommodation_title);

    $content  = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333; line-height: 1.6;">';
    $content .= '<h2 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 8px;">' . sprintf(__('Shared Cleaning Reminder — %s', 'hostpn'), esc_html($accommodation_title)) . '</h2>';
    $content .= '<p>' . sprintf(__('Hello %s,', 'hostpn'), esc_html($guest_name)) . '</p>';
    $content .= '<p>' . sprintf(__('This is a friendly reminder that your room (%s) is assigned for the upcoming shared cleaning cycle scheduled for <strong>%s</strong>.', 'hostpn'), esc_html($room_label), esc_html($next_date)) . '</p>';
    $content .= '<div style="background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin: 15px 0;">';
    $content .= '<strong>' . __('Stays to clean:', 'hostpn') . '</strong><br>' . esc_html($stays);
    $content .= '</div>';
    $content .= '<p>' . __('Por favor, recuerda marcar la limpieza como completada en el panel de gestión del alojamiento una vez finalizada.', 'hostpn') . '</p>';
    $content .= '<p style="color: #7f8c8d; font-size: 13px; margin-top: 30px;">' . __('Mensaje automatizado desde la plataforma de gestión de alojamientos.', 'hostpn') . '</p>';
    $content .= '</div>';

    $sent = HOSTPN_Notifications::send_notification_to_user($guest_user_id, $subject, $content);
    if ($sent) {
      update_post_meta($accommodation_id, 'hostpn_shared_cleaning_reminder_sent_for_date', $info['next_date']);
    }
    return $sent;
  }

  /**
   * Process daily shared cleaning reminders via WP-Cron.
   */
  public static function hostpn_process_shared_cleaning_reminders() {
    $args = [
      'post_type'      => 'hostpn_accommodation',
      'post_status'    => 'publish',
      'posts_per_page' => -1,
      'meta_query'     => [
        [
          'key'     => 'hostpn_cleaning_system',
          'value'   => 'shared',
          'compare' => '=',
        ],
      ],
    ];

    $accommodations = get_posts($args);
    $today = date('Y-m-d');

    foreach ($accommodations as $accom) {
      $accom_id = $accom->ID;
      $info = self::hostpn_get_shared_cleaning_info($accom_id);
      if (empty($info['next_date'])) {
        continue;
      }

      $notice_days = $info['notice_days'];
      $target_notice_date = date('Y-m-d', strtotime($info['next_date'] . ' -' . $notice_days . ' days'));

      $last_sent_date = get_post_meta($accom_id, 'hostpn_shared_cleaning_reminder_sent_for_date', true);

      if ($today >= $target_notice_date && $today <= $info['next_date'] && $last_sent_date !== $info['next_date']) {
        self::hostpn_send_shared_cleaning_reminder($accom_id);
      }
    }
  }

  /**
   * Get guest stay duration info in an accommodation.
   *
   * @param int $accommodation_id
   * @param int $guest_id
   * @return array
   */
  public static function hostpn_get_guest_stay_info($accommodation_id, $guest_id) {
    if (!$guest_id || !$accommodation_id) {
      return [
        'days'               => 0,
        'formatted_duration' => '0 ' . __('days', 'hostpn'),
        'start_date'         => '',
      ];
    }

    $start_date_str = '';

    // 1. Check contracts for this guest in this accommodation
    if (class_exists('HOSTPN_Post_Type_Contract')) {
      $contracts = HOSTPN_Post_Type_Contract::hostpn_get_contracts($guest_id, $accommodation_id);
      if (!empty($contracts)) {
        foreach ($contracts as $cid) {
          $c_start = get_post_meta($cid, 'hostpn_contract_start_date', true);
          if (!empty($c_start)) {
            if (empty($start_date_str) || strtotime($c_start) < strtotime($start_date_str)) {
              $start_date_str = $c_start;
            }
          }
        }
      }
    }

    // 2. Check rooms assigned to guest in this accommodation
    if (empty($start_date_str)) {
      $rooms = HOSTPN_Post_Type_Room::hostpn_get_rooms_by_accommodation($accommodation_id);
      foreach ($rooms as $rid) {
        $r_guest = get_post_meta($rid, 'hostpn_room_guest_id', true);
        if (intval($r_guest) === intval($guest_id)) {
          $r_start = get_post_meta($rid, 'hostpn_room_contract_start_date', true);
          if (!empty($r_start)) {
            $start_date_str = $r_start;
            break;
          }
        }
      }
    }

    // 3. Fallback to guest post meta or post date
    if (empty($start_date_str)) {
      $entry = get_post_meta($guest_id, 'hostpn_entry_date', true);
      if (!empty($entry)) {
        $start_date_str = $entry;
      } else {
        $start_date_str = get_the_date('Y-m-d', $guest_id);
      }
    }

    $start_time = !empty($start_date_str) ? strtotime($start_date_str) : time();
    $now = current_time('timestamp');
    $diff_seconds = max(0, $now - $start_time);
    $days = max(1, floor($diff_seconds / DAY_IN_SECONDS));

    $years = floor($days / 365);
    $rem_days = $days % 365;
    $months = floor($rem_days / 30);
    $days_only = $rem_days % 30;

    $parts = [];
    if ($years > 0) {
      $parts[] = sprintf(_n('%d year', '%d years', $years, 'hostpn'), $years);
    }
    if ($months > 0) {
      $parts[] = sprintf(_n('%d month', '%d months', $months, 'hostpn'), $months);
    }
    if ($days_only > 0 || empty($parts)) {
      $parts[] = sprintf(_n('%d day', '%d days', $days_only, 'hostpn'), $days_only);
    }
    $formatted = implode(', ', $parts);

    return [
      'days'               => intval($days),
      'formatted_duration' => $formatted,
      'start_date'         => $start_date_str,
    ];
  }

  /**
   * Get active promotions list from options.
   *
   * @param int $accommodation_id Optional filter for source accommodation.
   * @return array
   */
  public static function hostpn_get_promotions($accommodation_id = 0) {
    $enabled = get_option('hostpn_promotions_enabled', 'on');
    if ($enabled !== 'on') {
      return [];
    }

    $promos = get_option('hostpn_promotions_data');
    if (!is_array($promos) || empty($promos)) {
      // Default 2-year loyalty promotion
      $promos = [
        [
          'id'                      => 'promo_2_years_default',
          'title'                   => __('2-Year Loyalty Promotion', 'hostpn'),
          'source_accommodation_id' => 0,
          'required_days'           => 730,
          'target_accommodation_name'=> __('Any other accommodation in our group', 'hostpn'),
          'reward_desc'             => __('1 week free stay', 'hostpn'),
          'conditions'              => __('Valid upon completing 24 continuous months of stay. Subject to availability with 30 days prior booking notice.', 'hostpn'),
          'active'                  => '1',
        ],
      ];
    }

    $filtered = [];
    foreach ($promos as $promo) {
      $p_active = isset($promo['active']) ? (string)$promo['active'] : '1';
      if ($p_active !== '1') {
        continue;
      }

      $source_id = isset($promo['source_accommodation_id']) ? intval($promo['source_accommodation_id']) : 0;
      if ($accommodation_id > 0 && $source_id > 0 && $source_id !== intval($accommodation_id)) {
        continue;
      }

      $filtered[] = $promo;
    }

    return $filtered;
  }

  /**
   * Get promotions summary for a specific guest and accommodation.
   *
   * @param int $accommodation_id
   * @param int $guest_id
   * @return array
   */
  public static function hostpn_get_guest_promotions_summary($accommodation_id, $guest_id) {
    $stay_info = self::hostpn_get_guest_stay_info($accommodation_id, $guest_id);
    $promos = self::hostpn_get_promotions($accommodation_id);

    $processed = [];
    $primary = null;

    foreach ($promos as $p) {
      $req_days = max(1, intval(isset($p['required_days']) ? $p['required_days'] : 730));
      $days_stayed = $stay_info['days'];
      $rem_days = max(0, $req_days - $days_stayed);
      $progress = min(100, round(($days_stayed / $req_days) * 100));
      $unlocked = ($days_stayed >= $req_days);

      // Human-readable remaining time
      $rem_years = floor($rem_days / 365);
      $rem_months = floor(($rem_days % 365) / 30);
      $rem_d_only = ($rem_days % 365) % 30;

      $rem_parts = [];
      if ($rem_years > 0) {
        $rem_parts[] = sprintf(_n('%d year', '%d years', $rem_years, 'hostpn'), $rem_years);
      }
      if ($rem_months > 0) {
        $rem_parts[] = sprintf(_n('%d month', '%d months', $rem_months, 'hostpn'), $rem_months);
      }
      if ($rem_d_only > 0 || empty($rem_parts)) {
        $rem_parts[] = sprintf(_n('%d day', '%d days', $rem_d_only, 'hostpn'), $rem_d_only);
      }
      $rem_formatted = implode(', ', $rem_parts);

      $item = array_merge($p, [
        'days_stayed'        => $days_stayed,
        'required_days'      => $req_days,
        'days_remaining'     => $rem_days,
        'rem_formatted'      => $rem_formatted,
        'progress_percent'   => $progress,
        'unlocked'           => $unlocked,
      ]);

      $processed[] = $item;

      if (!$primary || ($unlocked && !$primary['unlocked']) || ($rem_days < $primary['days_remaining'])) {
        $primary = $item;
      }
    }

    return [
      'stay_info'  => $stay_info,
      'promotions' => $processed,
      'primary'    => $primary,
    ];
  }

  /**
   * Get financial summary metrics and room-by-room details for an accommodation.
   *
   * @param int $accommodation_id
   * @return array
   */
  public static function hostpn_get_financial_summary($accommodation_id) {
    $rooms = HOSTPN_Post_Type_Room::hostpn_get_rooms_by_accommodation($accommodation_id);
    $total_rooms = count($rooms);
    $occupied_count = 0;
    
    $total_monthly_rent_expected  = 0.0;
    $total_monthly_rent_collected = 0.0;
    $total_deposits_expected      = 0.0;
    $total_deposits_collected     = 0.0;

    $curr_month_key = date('Y_m');
    $curr_month_label = date_i18n('F Y');

    $rooms_financial = [];
    $all_contracts = HOSTPN_Post_Type_Contract::hostpn_get_contracts(0, $accommodation_id);

    foreach ($rooms as $room_id) {
      $room_number = get_post_meta($room_id, 'hostpn_room_number', true);
      if (empty($room_number)) {
        $room_number = get_the_title($room_id);
      }
      $guest_id = get_post_meta($room_id, 'hostpn_room_guest_id', true);
      $is_occupied = (!empty($guest_id) && intval($guest_id) > 0);

      $guest_name = '--';
      $guest_email = '';
      $guest_phone = '';
      $guest_dni = '';
      $stay_days = 0;
      $stay_duration = '--';
      $start_date = '';
      $end_date = '';

      if ($is_occupied) {
        $occupied_count++;
        $name_str = trim(get_post_meta($guest_id, 'hostpn_name', true) . ' ' . get_post_meta($guest_id, 'hostpn_surname', true));
        $guest_name = !empty($name_str) ? $name_str : get_the_title($guest_id);
        $guest_email = get_post_meta($guest_id, 'hostpn_email', true);
        $guest_phone = get_post_meta($guest_id, 'hostpn_phone', true);
        $guest_dni = get_post_meta($guest_id, 'hostpn_dni', true);

        $stay_info = self::hostpn_get_guest_stay_info($accommodation_id, $guest_id);
        $stay_days = $stay_info['days'];
        $stay_duration = $stay_info['formatted_duration'];
        $start_date = $stay_info['start_date'];
      }

      // Find active or linked contract for this room/guest
      $room_contract = null;
      if (!empty($all_contracts)) {
        foreach ($all_contracts as $cid) {
          $c_room = get_post_meta($cid, 'hostpn_contract_room_id', true);
          $c_guest = get_post_meta($cid, 'hostpn_contract_guest_id', true);
          if (intval($c_room) === intval($room_id) || ($is_occupied && intval($c_guest) === intval($guest_id))) {
            $room_contract = $cid;
            break;
          }
        }
      }

      $rent_amount = 0.0;
      $deposit_amount = 0.0;
      $payment_day = '';
      $contract_status = 'available';

      if ($room_contract) {
        $rent_val = get_post_meta($room_contract, 'hostpn_contract_rent_amount', true);
        $dep_val  = get_post_meta($room_contract, 'hostpn_contract_deposit_amount', true);
        $rent_amount = is_numeric($rent_val) ? floatval($rent_val) : 0.0;
        $deposit_amount = is_numeric($dep_val) ? floatval($dep_val) : 0.0;
        $payment_day = get_post_meta($room_contract, 'hostpn_contract_payment_day', true);
        $contract_status = get_post_meta($room_contract, 'hostpn_contract_status', true);
        $c_end = get_post_meta($room_contract, 'hostpn_contract_end_date', true);
        if (!empty($c_end)) {
          $end_date = $c_end;
        }
      }

      // Check room metadata if contract value is empty or 0
      if ($rent_amount <= 0) {
        $r_rent = get_post_meta($room_id, 'hostpn_room_contract_rent_amount', true);
        if (empty($r_rent) || !is_numeric($r_rent)) $r_rent = get_post_meta($room_id, 'hostpn_room_rent', true);
        if (empty($r_rent) || !is_numeric($r_rent)) $r_rent = get_post_meta($room_id, 'hostpn_room_price', true);
        if (is_numeric($r_rent)) $rent_amount = floatval($r_rent);
      }

      if ($deposit_amount <= 0) {
        $r_dep = get_post_meta($room_id, 'hostpn_room_contract_deposit_amount', true);
        if (empty($r_dep) || !is_numeric($r_dep)) $r_dep = get_post_meta($room_id, 'hostpn_room_deposit', true);
        if (is_numeric($r_dep)) $deposit_amount = floatval($r_dep);
      }

      if (empty($payment_day)) {
        $r_pday = get_post_meta($room_id, 'hostpn_room_contract_payment_day', true);
        if (empty($r_pday)) $r_pday = get_post_meta($room_id, 'hostpn_room_payment_day', true);
        if (!empty($r_pday)) $payment_day = $r_pday;
      }

      // Fetch payment history logs
      $room_payments_history = get_post_meta($room_id, 'hostpn_room_payment_history', true);
      if (!is_array($room_payments_history) || empty($room_payments_history)) {
        $room_payments_history = get_post_meta($room_id, 'hostpn_room_payments_log', true);
      }
      if (!is_array($room_payments_history)) {
        $room_payments_history = [];
      }

      $dep_paid_amount = 0.0;
      $rent_paid_amount = 0.0;
      foreach ($room_payments_history as $rec) {
        $p_type = isset($rec['payment_type']) ? $rec['payment_type'] : 'rent';
        $p_mkey = isset($rec['month_key']) ? $rec['month_key'] : (isset($rec['payment_date']) ? date('Y_m', strtotime($rec['payment_date'])) : '');
        $p_amt  = floatval(isset($rec['amount']) ? $rec['amount'] : 0);
        if ($p_type === 'deposit') {
          $dep_paid_amount += $p_amt;
        } elseif ($p_type === 'rent' && $p_mkey === $curr_month_key) {
          $rent_paid_amount += $p_amt;
        }
      }

      $deposit_paid = ($deposit_amount > 0 && $dep_paid_amount >= $deposit_amount);
      $rent_paid_current = ($rent_amount > 0 && $rent_paid_amount >= $rent_amount);

      if ($is_occupied) {
        $total_monthly_rent_expected  += $rent_amount;
        $total_monthly_rent_collected += $rent_paid_amount;

        $total_deposits_expected  += $deposit_amount;
        $total_deposits_collected += $dep_paid_amount;
      }

      $rooms_financial[] = [
        'room_id'             => $room_id,
        'room_number'         => $room_number,
        'is_occupied'         => $is_occupied,
        'guest_id'            => $guest_id,
        'guest_name'          => $guest_name,
        'guest_email'         => $guest_email,
        'guest_phone'         => $guest_phone,
        'guest_dni'           => $guest_dni,
        'stay_days'           => $stay_days,
        'stay_duration'       => $stay_duration,
        'start_date'          => $start_date,
        'end_date'            => $end_date,
        'rent_amount'         => $rent_amount,
        'deposit_amount'      => $deposit_amount,
        'deposit_paid_amount' => $dep_paid_amount,
        'rent_paid_amount'    => $rent_paid_amount,
        'deposit_paid'        => $deposit_paid,
        'rent_paid_current'   => $rent_paid_current,
        'payment_day'         => $payment_day,
        'contract_status'     => $contract_status,
        'contract_id'         => $room_contract ? $room_contract : 0,
        'payments_log'        => $room_payments_history,
      ];
    }

    $occupancy_rate = $total_rooms > 0 ? round(($occupied_count / $total_rooms) * 100) : 0;

    // Expenses calculation
    $expenses = self::hostpn_get_accommodation_expenses($accommodation_id);
    $total_expenses = 0.0;
    foreach ($expenses as $exp) {
      $total_expenses += floatval(isset($exp['amount']) ? $exp['amount'] : 0);
    }
    $net_income = $total_monthly_rent_collected - $total_expenses;

    // Build 6-month historical chart dataset with REAL logged payments
    $chart_months = [];
    for ($i = 5; $i >= 0; $i--) {
      $ts = strtotime("-$i months");
      $m_key = date('Y_m', $ts);
      $m_start = date('Y-m-01', $ts);
      $m_end = date('Y-m-t', $ts);
      $m_label = date_i18n('M Y', $ts);
      
      $m_expected = 0.0;
      $m_collected = 0.0;
      
      foreach ($rooms_financial as $r) {
        if ($r['is_occupied']) {
          $m_expected += $r['rent_amount'];
        }

        // Real collected amounts for this month from payments log
        $payments_log = isset($r['payments_log']) ? $r['payments_log'] : [];
        if (is_array($payments_log) && !empty($payments_log)) {
          foreach ($payments_log as $rec) {
            $p_date = isset($rec['payment_date']) ? $rec['payment_date'] : (isset($rec['date']) ? $rec['date'] : '');
            if (!empty($p_date) && $p_date >= $m_start && $p_date <= $m_end) {
              $m_collected += floatval(isset($rec['amount']) ? $rec['amount'] : 0);
            }
          }
        } else {
          // Fallback if no payment log records exist yet
          $paid_m = get_post_meta($r['room_id'], 'hostpn_room_rent_paid_' . $m_key, true);
          if ($paid_m === '1') {
            $m_collected += $r['rent_amount'];
          }
        }
      }
      
      $chart_months[] = [
        'month_key' => $m_key,
        'label'     => ucfirst($m_label),
        'expected'  => $m_expected,
        'collected' => $m_collected,
      ];
    }

    return [
      'total_rooms'                   => $total_rooms,
      'occupied_rooms'                => $occupied_count,
      'occupancy_rate'                => $occupancy_rate,
      'total_monthly_rent_expected'   => $total_monthly_rent_expected,
      'total_monthly_rent_collected'  => $total_monthly_rent_collected,
      'total_deposits_expected'      => $total_deposits_expected,
      'total_deposits_collected'     => $total_deposits_collected,
      'total_expenses'                => $total_expenses,
      'net_income'                    => $net_income,
      'current_month_label'           => ucfirst($curr_month_label),
      'rooms'                         => $rooms_financial,
      'expenses'                      => $expenses,
      'chart_months'                  => $chart_months,
    ];
  }

  /**
   * Edit an existing payment record in a room's history.
   */
  public static function hostpn_edit_room_payment_record($room_id, $payment_id, $data) {
    if (!$room_id || empty($payment_id)) {
      return ['success' => false, 'message' => __('Invalid parameters', 'hostpn')];
    }
    $history = get_post_meta($room_id, 'hostpn_room_payment_history', true);
    if (!is_array($history)) {
      $history = [];
    }

    $found = false;
    foreach ($history as $idx => $rec) {
      if (isset($rec['id']) && $rec['id'] === $payment_id) {
        $found = true;
        if (isset($data['amount'])) $history[$idx]['amount'] = floatval($data['amount']);
        if (isset($data['payment_type'])) $history[$idx]['payment_type'] = sanitize_key($data['payment_type']);
        if (isset($data['payment_date'])) {
          $history[$idx]['payment_date'] = sanitize_text_field($data['payment_date']);
          $history[$idx]['month_key'] = date('Y_m', strtotime($data['payment_date']));
        }
        if (isset($data['notes'])) $history[$idx]['notes'] = sanitize_textarea_field($data['notes']);
        break;
      }
    }

    if (!$found) {
      return ['success' => false, 'message' => __('Payment record not found', 'hostpn')];
    }

    update_post_meta($room_id, 'hostpn_room_payment_history', $history);
    self::hostpn_recalculate_room_payment_status($room_id, 'rent');
    self::hostpn_recalculate_room_payment_status($room_id, 'deposit');
    return ['success' => true];
  }

  /**
   * Delete a payment record from a room's history.
   */
  public static function hostpn_delete_room_payment_record($room_id, $payment_id) {
    if (!$room_id || empty($payment_id)) {
      return ['success' => false, 'message' => __('Invalid parameters', 'hostpn')];
    }
    $history = get_post_meta($room_id, 'hostpn_room_payment_history', true);
    if (!is_array($history)) {
      return ['success' => false, 'message' => __('No payments history found', 'hostpn')];
    }

    $new_history = [];
    $removed = false;
    foreach ($history as $rec) {
      if (isset($rec['id']) && $rec['id'] === $payment_id) {
        $removed = true;
        continue;
      }
      $new_history[] = $rec;
    }

    if (!$removed) {
      return ['success' => false, 'message' => __('Payment record not found', 'hostpn')];
    }

    update_post_meta($room_id, 'hostpn_room_payment_history', $new_history);
    self::hostpn_recalculate_room_payment_status($room_id, 'rent');
    self::hostpn_recalculate_room_payment_status($room_id, 'deposit');
    return ['success' => true];
  }

  /**
   * Get list of accommodation expenses.
   */
  public static function hostpn_get_accommodation_expenses($accommodation_id) {
    $expenses = get_post_meta($accommodation_id, 'hostpn_accommodation_expenses', true);
    return is_array($expenses) ? $expenses : [];
  }

  /**
   * Save (add or update) an accommodation expense.
   */
  public static function hostpn_save_accommodation_expense($accommodation_id, $expense_data) {
    if (!$accommodation_id) {
      return ['success' => false, 'message' => __('Invalid accommodation', 'hostpn')];
    }

    $expenses = self::hostpn_get_accommodation_expenses($accommodation_id);
    $expense_id = !empty($expense_data['id']) ? sanitize_text_field($expense_data['id']) : uniqid('exp_');

    $amount = isset($expense_data['amount']) ? floatval($expense_data['amount']) : 0.0;
    $date = !empty($expense_data['date']) ? sanitize_text_field($expense_data['date']) : current_time('Y-m-d');
    $provider = !empty($expense_data['provider']) ? sanitize_text_field($expense_data['provider']) : '';
    $category = !empty($expense_data['category']) ? sanitize_text_field($expense_data['category']) : '';
    $notes = !empty($expense_data['notes']) ? sanitize_textarea_field($expense_data['notes']) : '';

    $record = [
      'id'                      => $expense_id,
      'amount'                  => $amount,
      'date'                    => $date,
      'provider'                => $provider,
      'category'                => $category,
      'notes'                   => $notes,
      'attachment_filename'     => !empty($expense_data['attachment_filename']) ? sanitize_text_field($expense_data['attachment_filename']) : '',
      'attachment_original_name' => !empty($expense_data['attachment_original_name']) ? sanitize_text_field($expense_data['attachment_original_name']) : '',
      'created_at'              => current_time('mysql'),
    ];

    $updated = false;
    foreach ($expenses as $idx => $exp) {
      if (isset($exp['id']) && $exp['id'] === $expense_id) {
        if (empty($record['attachment_filename']) && !empty($exp['attachment_filename'])) {
          $record['attachment_filename'] = $exp['attachment_filename'];
          $record['attachment_original_name'] = isset($exp['attachment_original_name']) ? $exp['attachment_original_name'] : '';
        }
        $expenses[$idx] = array_merge($exp, $record);
        $updated = true;
        break;
      }
    }

    if (!$updated) {
      array_unshift($expenses, $record);
    }

    update_post_meta($accommodation_id, 'hostpn_accommodation_expenses', $expenses);
    return ['success' => true, 'expense' => $record];
  }

  /**
   * Delete an accommodation expense.
   */
  public static function hostpn_delete_accommodation_expense($accommodation_id, $expense_id) {
    if (!$accommodation_id || empty($expense_id)) {
      return ['success' => false, 'message' => __('Invalid parameters', 'hostpn')];
    }
    $expenses = self::hostpn_get_accommodation_expenses($accommodation_id);
    $new_expenses = [];
    $found = false;

    foreach ($expenses as $exp) {
      if (isset($exp['id']) && $exp['id'] === $expense_id) {
        $found = true;
        if (!empty($exp['attachment_filename'])) {
          $dir = HOSTPN_Private_Storage::hostpn_get_expense_dir($accommodation_id);
          $file_path = $dir . DIRECTORY_SEPARATOR . $exp['attachment_filename'];
          if (file_exists($file_path)) {
            @unlink($file_path);
          }
        }
        continue;
      }
      $new_expenses[] = $exp;
    }

    if (!$found) {
      return ['success' => false, 'message' => __('Expense not found', 'hostpn')];
    }

    update_post_meta($accommodation_id, 'hostpn_accommodation_expenses', $new_expenses);
    return ['success' => true];
  }

  /**
   * Render top summary block in admin accommodation details metabox.
   *
   * @param int $accommodation_id
   */
  public static function hostpn_render_accommodation_rooms_summary_block($accommodation_id) {
    $data = self::hostpn_get_financial_summary($accommodation_id);
    if (empty($data['rooms'])) {
      return;
    }
    ?>
    <div class="hostpn-admin-rooms-summary-box" style="margin-bottom:20px; background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:16px; color:#0f172a;">
      <h3 style="margin:0 0 12px; font-size:15px; font-weight:700; color:#0f172a;">
        <?php esc_html_e('Rooms, Guests & Financial Summary', 'hostpn'); ?>
      </h3>
      <div style="display:flex; gap:16px; margin-bottom:14px; flex-wrap:wrap;">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:6px; padding:10px 14px; min-width:140px;">
          <span style="display:block; font-size:11px; text-transform:uppercase; color:#64748b; font-weight:600;"><?php esc_html_e('Occupancy', 'hostpn'); ?></span>
          <strong style="font-size:16px; color:#0f172a;"><?php echo esc_html($data['occupied_rooms']); ?> / <?php echo esc_html($data['total_rooms']); ?> (<?php echo esc_html($data['occupancy_rate']); ?>%)</strong>
        </div>
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:6px; padding:10px 14px; min-width:140px;">
          <span style="display:block; font-size:11px; text-transform:uppercase; color:#64748b; font-weight:600;"><?php esc_html_e('Monthly Rent (Collected / Expected)', 'hostpn'); ?></span>
          <strong style="font-size:16px; color:#0f172a;">€ <?php echo esc_html(number_format($data['total_monthly_rent_collected'], 2, ',', '.')); ?> / € <?php echo esc_html(number_format($data['total_monthly_rent_expected'], 2, ',', '.')); ?></strong>
        </div>
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:6px; padding:10px 14px; min-width:140px;">
          <span style="display:block; font-size:11px; text-transform:uppercase; color:#64748b; font-weight:600;"><?php esc_html_e('Total Deposits Held', 'hostpn'); ?></span>
          <strong style="font-size:16px; color:#0f172a;">€ <?php echo esc_html(number_format($data['total_deposits_collected'], 2, ',', '.')); ?> / € <?php echo esc_html(number_format($data['total_deposits_expected'], 2, ',', '.')); ?></strong>
        </div>
      </div>
    </div>
    <?php
  }
}
<?php
/**
 * Financial Record Post Type.
 *
 * This class defines the financial record custom post type for tracking
 * income, expenses, taxes, and fees from booking platforms.
 *
 * @link       padresenlanube.com/
 * @since      1.0.36
 * @package    hostpn
 * @subpackage hostpn/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Post_Type_Financial_Record {

  /**
   * Register financial record custom post type.
   *
   * @since    1.0.36
   */
  public function hostpn_financial_record_register_post_type() {
    $labels = [
      'name'                => _x('Financial Records', 'Post Type general name', 'hostpn'),
      'singular_name'       => _x('Financial Record', 'Post Type singular name', 'hostpn'),
      'menu_name'           => esc_html(__('Financial Records', 'hostpn')),
      'parent_item_colon'   => esc_html(__('Parent Record', 'hostpn')),
      'all_items'           => esc_html(__('All Financial Records', 'hostpn')),
      'view_item'           => esc_html(__('View Financial Record', 'hostpn')),
      'add_new_item'        => esc_html(__('Add New Financial Record', 'hostpn')),
      'add_new'             => esc_html(__('Add New', 'hostpn')),
      'edit_item'           => esc_html(__('Edit Financial Record', 'hostpn')),
      'update_item'         => esc_html(__('Update Financial Record', 'hostpn')),
      'search_items'        => esc_html(__('Search Financial Records', 'hostpn')),
      'not_found'           => esc_html(__('No financial records found', 'hostpn')),
      'not_found_in_trash'  => esc_html(__('No financial records found in Trash', 'hostpn')),
    ];

    $args = [
      'labels'              => $labels,
      'rewrite'             => ['slug' => 'financial-records', 'with_front' => false],
      'label'               => esc_html(__('Financial Record', 'hostpn')),
      'description'         => esc_html(__('Financial records from booking platforms', 'hostpn')),
      'supports'            => ['title', 'editor'],
      'hierarchical'        => false,
      'public'              => false, // Not publicly visible
      'show_ui'             => true,
      'show_in_menu'        => false, // Will show under accommodations menu
      'show_in_nav_menus'   => false,
      'show_in_admin_bar'   => false,
      'menu_position'       => 5,
      'can_export'          => true,
      'has_archive'         => false,
      'exclude_from_search' => true,
      'publicly_queryable'  => false,
      'capability_type'     => 'page',
      'show_in_rest'        => true,
    ];

    register_post_type('hostpn_financial_record', $args);

    // Flush rewrite rules only once after registration
    if (get_option('hostpn_financial_record_rewrite_flushed') != 'yes') {
      flush_rewrite_rules();
      update_option('hostpn_financial_record_rewrite_flushed', 'yes');
    }
  }

  /**
   * Get meta field definitions for financial records.
   *
   * @since    1.0.36
   * @return   array    Array of field definitions
   */
  public function hostpn_financial_record_get_meta_fields() {
    $fields = [];

    // Common fields
    $fields['hostpn_financial_record_type'] = [
      'id' => 'hostpn_financial_record_type',
      'class' => 'hostpn-select hostpn-width-100-percent',
      'input' => 'select',
      'options' => [
        'income' => __('Income', 'hostpn'),
        'expense' => __('Expense', 'hostpn'),
        'tax' => __('Tax', 'hostpn'),
        'fee' => __('Fee', 'hostpn'),
      ],
      'label' => __('Record Type', 'hostpn'),
      'required' => true,
    ];

    $fields['hostpn_financial_platform'] = [
      'id' => 'hostpn_financial_platform',
      'class' => 'hostpn-select hostpn-width-100-percent',
      'input' => 'select',
      'options' => [
        'airbnb' => __('Airbnb', 'hostpn'),
        'booking' => __('Booking.com', 'hostpn'),
        'manual' => __('Manual Entry', 'hostpn'),
      ],
      'label' => __('Platform', 'hostpn'),
      'required' => true,
    ];

    $fields['hostpn_financial_accommodation_id'] = [
      'id' => 'hostpn_financial_accommodation_id',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('Accommodation ID', 'hostpn'),
      'required' => true,
    ];

    $fields['hostpn_financial_import_batch_id'] = [
      'id' => 'hostpn_financial_import_batch_id',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('Import Batch ID', 'hostpn'),
      'description' => __('UUID for tracking import batches', 'hostpn'),
    ];

    $fields['hostpn_financial_import_timestamp'] = [
      'id' => 'hostpn_financial_import_timestamp',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('Import Timestamp', 'hostpn'),
    ];

    $fields['hostpn_financial_csv_source'] = [
      'id' => 'hostpn_financial_csv_source',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('CSV Source File', 'hostpn'),
    ];

    $fields['hostpn_financial_currency'] = [
      'id' => 'hostpn_financial_currency',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('Currency', 'hostpn'),
      'placeholder' => 'EUR',
    ];

    $fields['hostpn_financial_amount'] = [
      'id' => 'hostpn_financial_amount',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('Total Amount', 'hostpn'),
      'required' => true,
    ];

    $fields['hostpn_financial_net_amount'] = [
      'id' => 'hostpn_financial_net_amount',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('Net Amount', 'hostpn'),
    ];

    $fields['hostpn_financial_tax_amount'] = [
      'id' => 'hostpn_financial_tax_amount',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('Tax Amount', 'hostpn'),
    ];

    $fields['hostpn_financial_fee_amount'] = [
      'id' => 'hostpn_financial_fee_amount',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('Fee Amount', 'hostpn'),
    ];

    $fields['hostpn_financial_quarter'] = [
      'id' => 'hostpn_financial_quarter',
      'class' => 'hostpn-select hostpn-width-100-percent',
      'input' => 'select',
      'options' => [
        'Q1' => __('Q1', 'hostpn'),
        'Q2' => __('Q2', 'hostpn'),
        'Q3' => __('Q3', 'hostpn'),
        'Q4' => __('Q4', 'hostpn'),
      ],
      'label' => __('Quarter', 'hostpn'),
    ];

    $fields['hostpn_financial_year'] = [
      'id' => 'hostpn_financial_year',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('Year', 'hostpn'),
    ];

    $fields['hostpn_financial_date'] = [
      'id' => 'hostpn_financial_date',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'date',
      'label' => __('Transaction Date', 'hostpn'),
    ];

    // Airbnb Fiscal fields
    $fields['hostpn_airbnb_listing_id'] = [
      'id' => 'hostpn_airbnb_listing_id',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('Airbnb Listing ID', 'hostpn'),
    ];

    $fields['hostpn_airbnb_listing_title'] = [
      'id' => 'hostpn_airbnb_listing_title',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('Airbnb Listing Title', 'hostpn'),
    ];

    $fields['hostpn_airbnb_rented_days'] = [
      'id' => 'hostpn_airbnb_rented_days',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('Rented Days', 'hostpn'),
    ];

    // Quarterly payouts
    foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter) {
      $fields['hostpn_airbnb_' . strtolower($quarter) . '_payouts'] = [
        'id' => 'hostpn_airbnb_' . strtolower($quarter) . '_payouts',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'label' => sprintf(__('Airbnb %s Payouts', 'hostpn'), $quarter),
      ];

      $fields['hostpn_airbnb_' . strtolower($quarter) . '_fees'] = [
        'id' => 'hostpn_airbnb_' . strtolower($quarter) . '_fees',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'label' => sprintf(__('Airbnb %s Fees', 'hostpn'), $quarter),
      ];
    }

    // Airbnb Invoice fields
    $fields['hostpn_airbnb_invoice_number'] = [
      'id' => 'hostpn_airbnb_invoice_number',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('Invoice Number', 'hostpn'),
    ];

    $fields['hostpn_airbnb_confirmation_code'] = [
      'id' => 'hostpn_airbnb_confirmation_code',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('Confirmation Code', 'hostpn'),
    ];

    $fields['hostpn_airbnb_vat_number'] = [
      'id' => 'hostpn_airbnb_vat_number',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('VAT Number', 'hostpn'),
    ];

    $fields['hostpn_airbnb_net_amount'] = [
      'id' => 'hostpn_airbnb_net_amount',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('Airbnb Net Amount', 'hostpn'),
    ];

    $fields['hostpn_airbnb_vat_amount'] = [
      'id' => 'hostpn_airbnb_vat_amount',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('Airbnb VAT Amount', 'hostpn'),
    ];

    $fields['hostpn_airbnb_gross_amount'] = [
      'id' => 'hostpn_airbnb_gross_amount',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('Airbnb Gross Amount', 'hostpn'),
    ];

    // Booking.com fields
    $fields['hostpn_booking_reference_number'] = [
      'id' => 'hostpn_booking_reference_number',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('Booking Reference Number', 'hostpn'),
    ];

    $fields['hostpn_booking_reservation_status'] = [
      'id' => 'hostpn_booking_reservation_status',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('Reservation Status', 'hostpn'),
    ];

    $fields['hostpn_booking_payment_status'] = [
      'id' => 'hostpn_booking_payment_status',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('Payment Status', 'hostpn'),
    ];

    $fields['hostpn_booking_amount'] = [
      'id' => 'hostpn_booking_amount',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('Booking Amount', 'hostpn'),
    ];

    $fields['hostpn_booking_commission'] = [
      'id' => 'hostpn_booking_commission',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('Commission', 'hostpn'),
    ];

    $fields['hostpn_booking_service_charge'] = [
      'id' => 'hostpn_booking_service_charge',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('Service Charge', 'hostpn'),
    ];

    $fields['hostpn_booking_vat_platform'] = [
      'id' => 'hostpn_booking_vat_platform',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('VAT Platform', 'hostpn'),
    ];

    $fields['hostpn_booking_net_amount'] = [
      'id' => 'hostpn_booking_net_amount',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'number',
      'label' => __('Booking Net Amount', 'hostpn'),
    ];

    $fields['hostpn_booking_payment_id'] = [
      'id' => 'hostpn_booking_payment_id',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'text',
      'label' => __('Payment ID', 'hostpn'),
    ];

    $fields['hostpn_booking_payment_date'] = [
      'id' => 'hostpn_booking_payment_date',
      'class' => 'hostpn-input hostpn-width-100-percent',
      'input' => 'input',
      'type' => 'date',
      'label' => __('Payment Date', 'hostpn'),
    ];

    return $fields;
  }

  /**
   * Add meta boxes to financial record edit screen.
   *
   * @since    1.0.36
   */
  public function hostpn_financial_record_add_meta_boxes() {
    add_meta_box(
      'hostpn_financial_record_details',
      __('Financial Record Details', 'hostpn'),
      [$this, 'hostpn_financial_record_meta_box_callback'],
      'hostpn_financial_record',
      'normal',
      'high'
    );
  }

  /**
   * Meta box callback for financial record details.
   *
   * @since    1.0.36
   * @param    WP_Post    $post    The post object
   */
  public function hostpn_financial_record_meta_box_callback($post) {
    // Add nonce field
    wp_nonce_field('hostpn_financial_record_meta_box', 'hostpn_financial_record_meta_box_nonce');

    // Get all meta fields
    $fields = $this->hostpn_financial_record_get_meta_fields();

    // Display fields using HOSTPN_Forms
    echo '<div class="hostpn-financial-record-meta-fields">';
    foreach ($fields as $field) {
      // Get current value
      $field['value'] = get_post_meta($post->ID, $field['id'], true);

      // Display field using Forms builder
      if (class_exists('HOSTPN_Forms')) {
        echo HOSTPN_Forms::hostpn_input_wrapper_builder($field, 'post', $post->ID);
      }
    }
    echo '</div>';
  }

  /**
   * Save financial record meta data.
   *
   * @since    1.0.36
   * @param    int       $post_id    The post ID
   * @param    WP_Post   $post       The post object
   * @param    bool      $update     Whether this is an update
   */
  public function hostpn_financial_record_save_post($post_id, $post, $update) {
    // Check if this is a financial record
    if ($post->post_type != 'hostpn_financial_record') {
      return;
    }

    // Check nonce
    if (!isset($_POST['hostpn_financial_record_meta_box_nonce'])) {
      return;
    }

    if (!wp_verify_nonce($_POST['hostpn_financial_record_meta_box_nonce'], 'hostpn_financial_record_meta_box')) {
      return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
      return;
    }

    // Get all meta fields
    $fields = $this->hostpn_financial_record_get_meta_fields();

    // Save each field
    foreach ($fields as $field) {
      if (isset($_POST[$field['id']])) {
        $value = $_POST[$field['id']];

        // Sanitize using HOSTPN_Forms sanitizer if available
        if (class_exists('HOSTPN_Forms')) {
          $value = HOSTPN_Forms::hostpn_sanitizer(
            wp_unslash($value),
            $field['input'],
            !empty($field['type']) ? $field['type'] : '',
            $field
          );
        } else {
          $value = sanitize_text_field(wp_unslash($value));
        }

        update_post_meta($post_id, $field['id'], $value);
      }
    }
  }
}

<?php
/**
 * Contract creator.
 *
 * This class defines Contract options, menus and templates.
 * Contracts are independent entities linked to accommodations, guests, and rooms.
 *
 * @link       padresenlanube.com/
 * @since      1.0.83
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Post_Type_Contract
{
    public function hostpn_contract_get_fields($contract_id = 0)
    {
        $hostpn_fields = [];
        $hostpn_fields['hostpn_contract_title'] = [
            'id' => 'hostpn_contract_title',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'hidden',
            'value' => __('Contract', 'hostpn') . ' - ' . gmdate('Y-m-d H:i:s', current_time('timestamp')) . ' - ' . bin2hex(openssl_random_pseudo_bytes(4)),
        ];
        $hostpn_fields['hostpn_contract_description'] = [
            'id' => 'hostpn_contract_description',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'hidden',
            'value' => '',
        ];
        return $hostpn_fields;
    }

    public function hostpn_contract_get_fields_meta($contract_id = 0)
    {
        $hostpn_fields_meta = [];

        // Get accommodations for select
        $accommodations = get_posts([
            'post_type' => 'hostpn_accommodation',
            'post_status' => 'any',
            'numberposts' => -1,
            'fields' => 'ids',
        ]);
        $accommodation_options = [];
        foreach ($accommodations as $acc_id) {
            $accommodation_options[$acc_id] = get_the_title($acc_id);
        }

        // Get guests for select
        $guest_atts = [
            'post_type' => 'hostpn_guest',
            'post_status' => 'any',
            'numberposts' => -1,
            'fields' => 'ids',
        ];
        if (!HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
            $guest_atts['author'] = get_current_user_id();
        }
        $guests = get_posts($guest_atts);
        $guest_options = [];
        foreach ($guests as $guest_id) {
            $name = get_post_meta($guest_id, 'hostpn_name', true) . ' ' . get_post_meta($guest_id, 'hostpn_surname', true);
            $guest_options[$guest_id] = trim($name);
        }

        // Get rooms - will be filtered by JS based on accommodation selection
        $room_options = HOSTPN_Post_Type_Room::hostpn_get_rooms_options();

        // ── Relations ──────────────────────────────────────────────
        $hostpn_fields_meta['hostpn_contract_accommodation_id'] = [
            'id' => 'hostpn_contract_accommodation_id',
            'class' => 'hostpn-select hostpn-width-100-percent hostpn-contract-accommodation-select',
            'input' => 'select',
            'required' => true,
            'options' => $accommodation_options,
            'label' => __('Accommodation', 'hostpn'),
            'placeholder' => __('Select accommodation', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_guest_id'] = [
            'id' => 'hostpn_contract_guest_id',
            'class' => 'hostpn-select hostpn-width-100-percent hostpn-contract-guest-select',
            'input' => 'select',
            'required' => true,
            'options' => $guest_options,
            'label' => __('Guest', 'hostpn'),
            'placeholder' => __('Select guest', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_room_id'] = [
            'id' => 'hostpn_contract_room_id',
            'class' => 'hostpn-select hostpn-width-100-percent hostpn-contract-room-select',
            'input' => 'select',
            'options' => $room_options,
            'label' => __('Room', 'hostpn'),
            'placeholder' => __('Select room (optional)', 'hostpn'),
            'description' => __('Rooms are filtered by the selected accommodation.', 'hostpn'),
        ];

        // ── Type and Status ────────────────────────────────────────
        $hostpn_fields_meta['hostpn_contract_type'] = [
            'id' => 'hostpn_contract_type',
            'class' => 'hostpn-select hostpn-width-100-percent',
            'input' => 'select',
            'required' => true,
            'options' => HOSTPN_Contract_Templates::hostpn_get_contract_types(),
            'label' => __('Contract type', 'hostpn'),
            'placeholder' => __('Select contract type', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_status'] = [
            'id' => 'hostpn_contract_status',
            'class' => 'hostpn-select hostpn-width-100-percent',
            'input' => 'select',
            'required' => true,
            'options' => self::hostpn_get_contract_statuses(),
            'label' => __('Status', 'hostpn'),
            'placeholder' => __('Select status', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_version'] = [
            'id' => 'hostpn_contract_version',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'number',
            'min' => 1,
            'value' => 1,
            'label' => __('Version', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_parent_id'] = [
            'id' => 'hostpn_contract_parent_id',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'hidden',
            'value' => '',
        ];
        $hostpn_fields_meta['hostpn_contract_token'] = [
            'id' => 'hostpn_contract_token',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'hidden',
            'value' => bin2hex(openssl_random_pseudo_bytes(16)),
        ];

        // ── Landlord data ──────────────────────────────────────────
        $hostpn_fields_meta['hostpn_contract_landlord_name'] = [
            'id' => 'hostpn_contract_landlord_name',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Landlord name', 'hostpn'),
            'placeholder' => __('Full name of the landlord', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_landlord_nif'] = [
            'id' => 'hostpn_contract_landlord_nif',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Landlord NIF/NIE', 'hostpn'),
            'placeholder' => __('Landlord NIF/NIE', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_landlord_address'] = [
            'id' => 'hostpn_contract_landlord_address',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Landlord address', 'hostpn'),
            'placeholder' => __('Domicile address of the landlord', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_landlord_email'] = [
            'id' => 'hostpn_contract_landlord_email',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'email',
            'label' => __('Landlord email', 'hostpn'),
            'placeholder' => __('Email of the landlord', 'hostpn'),
        ];

        // ── Tenant data ────────────────────────────────────────────
        $hostpn_fields_meta['hostpn_contract_tenant_name'] = [
            'id' => 'hostpn_contract_tenant_name',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Tenant name', 'hostpn'),
            'placeholder' => __('Full name of the tenant', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_tenant_nif'] = [
            'id' => 'hostpn_contract_tenant_nif',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Tenant NIF/NIE', 'hostpn'),
            'placeholder' => __('Tenant NIF/NIE', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_tenant_address'] = [
            'id' => 'hostpn_contract_tenant_address',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Tenant address', 'hostpn'),
            'placeholder' => __('Domicile address of the tenant', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_tenant_email'] = [
            'id' => 'hostpn_contract_tenant_email',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'email',
            'label' => __('Tenant email', 'hostpn'),
            'placeholder' => __('Tenant email', 'hostpn'),
        ];

        // ── Duration ───────────────────────────────────────────────
        $hostpn_fields_meta['hostpn_contract_duration'] = [
            'id' => 'hostpn_contract_duration',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Duration', 'hostpn'),
            'placeholder' => __('E.g.: 12 months, 1 year...', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_start_date'] = [
            'id' => 'hostpn_contract_start_date',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'date',
            'label' => __('Start date', 'hostpn'),
            'placeholder' => __('Start date', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_end_date'] = [
            'id' => 'hostpn_contract_end_date',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'date',
            'label' => __('End date', 'hostpn'),
            'placeholder' => __('End date', 'hostpn'),
        ];

        // ── Rent ───────────────────────────────────────────────────
        $hostpn_fields_meta['hostpn_contract_rent_amount'] = [
            'id' => 'hostpn_contract_rent_amount',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Monthly rent (EUR)', 'hostpn'),
            'placeholder' => __('E.g.: 500', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_rent_words'] = [
            'id' => 'hostpn_contract_rent_words',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Rent in words', 'hostpn'),
            'placeholder' => __('E.g.: five hundred', 'hostpn'),
        ];

        // ── Payment ────────────────────────────────────────────────
        $hostpn_fields_meta['hostpn_contract_payment_day'] = [
            'id' => 'hostpn_contract_payment_day',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Payment day', 'hostpn'),
            'placeholder' => __('E.g.: 5', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_bank_name'] = [
            'id' => 'hostpn_contract_bank_name',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Bank name', 'hostpn'),
            'placeholder' => __('Bank name', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_iban'] = [
            'id' => 'hostpn_contract_iban',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('IBAN', 'hostpn'),
            'placeholder' => __('IBAN', 'hostpn'),
        ];

        // ── Deposit ────────────────────────────────────────────────
        $hostpn_fields_meta['hostpn_contract_deposit_amount'] = [
            'id' => 'hostpn_contract_deposit_amount',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Deposit amount (EUR)', 'hostpn'),
            'placeholder' => __('E.g.: 500', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_deposit_words'] = [
            'id' => 'hostpn_contract_deposit_words',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Deposit in words', 'hostpn'),
            'placeholder' => __('E.g.: five hundred', 'hostpn'),
        ];

        // ── Tourist-specific ───────────────────────────────────────
        $hostpn_fields_meta['hostpn_contract_guest_count'] = [
            'id' => 'hostpn_contract_guest_count',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'number',
            'min' => 1,
            'label' => __('Number of guests', 'hostpn'),
            'placeholder' => __('Number of guests', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_checkin_time'] = [
            'id' => 'hostpn_contract_checkin_time',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Check-in time', 'hostpn'),
            'placeholder' => __('E.g.: 15:00', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_checkout_time'] = [
            'id' => 'hostpn_contract_checkout_time',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Check-out time', 'hostpn'),
            'placeholder' => __('E.g.: 11:00', 'hostpn'),
        ];
        $hostpn_fields_meta['hostpn_contract_total_price'] = [
            'id' => 'hostpn_contract_total_price',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Total price (EUR)', 'hostpn'),
            'placeholder' => __('Total price for the stay', 'hostpn'),
        ];

        // ── Room-specific ──────────────────────────────────────────
        $hostpn_fields_meta['hostpn_contract_notice_days'] = [
            'id' => 'hostpn_contract_notice_days',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text',
            'label' => __('Notice days', 'hostpn'),
            'placeholder' => __('E.g.: 30', 'hostpn'),
        ];

        // ── Inventory ──────────────────────────────────────────────
        $hostpn_fields_meta['hostpn_contract_inventory_enabled'] = [
            'id' => 'hostpn_contract_inventory_enabled',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'checkbox',
            'parent' => 'this',
            'label' => __('Enable inventory annex', 'hostpn'),
        ];
        $inventory_categories = [
            'mobiliario'              => __('Furniture', 'hostpn'),
            'equipamiento_individual' => __('Individual equipment', 'hostpn'),
            'menaje_individual'       => __('Individual kitchenware', 'hostpn'),
            'equipamiento_comunitario' => __('Community equipment', 'hostpn'),
            'otros_enseres'           => __('Other items', 'hostpn'),
        ];

        foreach ($inventory_categories as $cat_key => $cat_label) {
            $hostpn_fields_meta['hostpn_contract_inv_' . $cat_key] = [
                'id' => 'hostpn_contract_inv_' . $cat_key,
                'class' => 'hostpn-input hostpn-width-100-percent hostpn-contract-inventory-items',
                'input' => 'html_multi',
                'parent' => 'hostpn_contract_inventory_enabled',
                'parent_option' => 'on',
                'label' => $cat_label,
                'html_multi_fields' => [
                    [
                        'id' => 'hostpn_contract_inv_' . $cat_key . '_name',
                        'class' => 'hostpn-input hostpn-width-100-percent',
                        'input' => 'input',
                        'type' => 'text',
                        'label' => __('Item name', 'hostpn'),
                        'placeholder' => __('Item name', 'hostpn'),
                    ],
                    [
                        'id' => 'hostpn_contract_inv_' . $cat_key . '_url',
                        'class' => 'hostpn-input hostpn-width-100-percent',
                        'input' => 'input',
                        'type' => 'text',
                        'label' => __('URL (optional)', 'hostpn'),
                        'placeholder' => __('https://...', 'hostpn'),
                    ],
                ],
            ];
        }

        // ── Private files (hidden, managed programmatically) ──────
        $hostpn_fields_meta['hostpn_contract_pdf_attachment_id'] = [
            'id' => 'hostpn_contract_pdf_attachment_id',
            'input' => 'input',
            'type' => 'hidden',
            'value' => '',
        ];
        $hostpn_fields_meta['hostpn_contract_signed_pdf_attachment_id'] = [
            'id' => 'hostpn_contract_signed_pdf_attachment_id',
            'input' => 'input',
            'type' => 'hidden',
            'value' => '',
        ];

        // ── Dates (hidden, set programmatically) ──────────────────
        $hostpn_fields_meta['hostpn_contract_sent_date'] = [
            'id' => 'hostpn_contract_sent_date',
            'input' => 'input',
            'type' => 'hidden',
            'value' => '',
        ];
        $hostpn_fields_meta['hostpn_contract_signed_date'] = [
            'id' => 'hostpn_contract_signed_date',
            'input' => 'input',
            'type' => 'hidden',
            'value' => '',
        ];

        // ── Form ───────────────────────────────────────────────────
        $hostpn_fields_meta['hostpn_contract_form'] = [
            'id' => 'hostpn_contract_form',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'hidden',
            'value' => 'hostpn_contract_form',
        ];
        $hostpn_fields_meta['hostpn_ajax_nonce'] = [
            'id' => 'hostpn_ajax_nonce',
            'input' => 'input',
            'type' => 'nonce',
        ];

        return $hostpn_fields_meta;
    }

    /**
     * Get contract status options.
     *
     * @return array
     */
    public static function hostpn_get_contract_statuses()
    {
        return [
            'draft' => __('Draft', 'hostpn'),
            'sent' => __('Sent', 'hostpn'),
            'signed' => __('Signed', 'hostpn'),
            'expired' => __('Expired', 'hostpn'),
            'cancelled' => __('Cancelled', 'hostpn'),
        ];
    }

    /**
     * Register Contract post type.
     *
     * @since    1.0.83
     */
    public function hostpn_contract_register_post_type()
    {
        $labels = [
            'name' => _x('Contract', 'Post Type general name', 'hostpn'),
            'singular_name' => _x('Contract', 'Post Type singular name', 'hostpn'),
            'menu_name' => __('Contracts', 'hostpn'),
            'parent_item_colon' => __('Parent Contract', 'hostpn'),
            'all_items' => __('All Contracts', 'hostpn'),
            'view_item' => __('View Contract', 'hostpn'),
            'add_new_item' => __('Add new Contract', 'hostpn'),
            'add_new' => __('Add new Contract', 'hostpn'),
            'edit_item' => __('Edit Contract', 'hostpn'),
            'update_item' => __('Update Contract', 'hostpn'),
            'search_items' => __('Search Contracts', 'hostpn'),
            'not_found' => __('No Contracts found', 'hostpn'),
            'not_found_in_trash' => __('No Contracts found in Trash', 'hostpn'),
        ];

        $args = [
            'labels' => $labels,
            'label' => __('Contract', 'hostpn'),
            'description' => __('Rental contracts linked to accommodations and guests', 'hostpn'),
            'supports' => ['title', 'author'],
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => false,
            'menu_position' => 5,
            'can_export' => false,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability_type' => 'page',
            'show_in_rest' => false,
        ];

        register_post_type('hostpn_contract', $args);
    }

    /**
     * Add Contract dashboard metabox.
     *
     * @since    1.0.83
     */
    public function hostpn_contract_add_meta_box()
    {
        add_meta_box(
            'hostpn_contract_meta_box',
            __('Contract details', 'hostpn'),
            [$this, 'hostpn_contract_meta_box_function'],
            'hostpn_contract',
            'normal',
            'high',
            ['__block_editor_compatible_meta_box' => true]
        );
    }

    /**
     * Defines Contract dashboard contents.
     *
     * @since    1.0.83
     */
    public function hostpn_contract_meta_box_function($post)
    {
        echo '<div class="hostpn-contract-metabox-wrapper">';

        // Left column: fields
        echo '<div class="hostpn-contract-fields-column">';
        foreach (self::hostpn_contract_get_fields() as $hostpn_field) {
            HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $post->ID);
        }
        foreach (self::hostpn_contract_get_fields_meta() as $hostpn_field_meta) {
            HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field_meta, 'post', $post->ID);
        }
        echo '</div>';

        // Right column: preview
        echo '<div class="hostpn-contract-preview-column">';
        echo '<h3>' . esc_html__('Contract Preview', 'hostpn') . '</h3>';
        echo '<div id="hostpn-contract-preview-container" class="hostpn-contract-preview">';

        $contract_type = get_post_meta($post->ID, 'hostpn_contract_type', true);
        $accommodation_id = get_post_meta($post->ID, 'hostpn_contract_accommodation_id', true);
        if ($contract_type && $accommodation_id) {
            $template = HOSTPN_Contract_Templates::hostpn_get_saved_template($contract_type);
            echo wp_kses_post(HOSTPN_Contract_Templates::hostpn_resolve_shortcodes_from_contract(
                HOSTPN_Contract_Templates::hostpn_render_contract($contract_type, $template, $accommodation_id),
                $post->ID
            ));
        } else {
            echo '<p class="hostpn-text-muted">' . esc_html__('Select accommodation and contract type to see preview.', 'hostpn') . '</p>';
        }

        echo '</div>';
        echo '</div>';

        echo '</div>';
    }

    public function hostpn_contract_save_post($post_id, $cpt, $update)
    {
        if ($cpt->post_type == 'hostpn_contract' && array_key_exists('hostpn_contract_form', $_POST)) {
            if (array_key_exists('hostpn_form_subtype', $_POST)) {
                return;
            }

            if (!array_key_exists('hostpn_ajax_nonce', $_POST)) {
                return;
            }

            if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hostpn_ajax_nonce'])), 'hostpn-nonce')) {
                return;
            }

            foreach (array_merge(self::hostpn_contract_get_fields(), self::hostpn_contract_get_fields_meta()) as $hostpn_field) {
                $hostpn_input = array_key_exists('input', $hostpn_field) ? $hostpn_field['input'] : '';

                if (array_key_exists($hostpn_field['id'], $_POST) || $hostpn_input == 'html_multi') {
                    $hostpn_value = array_key_exists($hostpn_field['id'], $_POST) ?
                        HOSTPN_Forms::hostpn_sanitizer(
                            wp_unslash($_POST[$hostpn_field['id']]),
                            $hostpn_field['input'],
                            !empty($hostpn_field['type']) ? $hostpn_field['type'] : '',
                            $hostpn_field
                        ) : '';

                    if (!empty($hostpn_input)) {
                        switch ($hostpn_input) {
                            case 'input':
                                if (array_key_exists('type', $hostpn_field) && $hostpn_field['type'] == 'checkbox') {
                                    update_post_meta($post_id, $hostpn_field['id'], isset($_POST[$hostpn_field['id']]) ? $hostpn_value : '');
                                } else {
                                    update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                                }
                                break;
                            case 'html_multi':
                                foreach ($hostpn_field['html_multi_fields'] as $hostpn_multi_field) {
                                    if (array_key_exists($hostpn_multi_field['id'], $_POST)) {
                                        $multi_array = [];
                                        $empty = true;
                                        $sanitized_post_data = array_map(function ($value) {
                                            return sanitize_text_field(wp_unslash($value));
                                        }, (array) $_POST[$hostpn_multi_field['id']]);
                                        foreach ($sanitized_post_data as $multi_value) {
                                            if (!empty($multi_value)) {
                                                $empty = false;
                                            }
                                            $multi_array[] = HOSTPN_Forms::hostpn_sanitizer(
                                                $multi_value,
                                                $hostpn_multi_field['input'],
                                                !empty($hostpn_multi_field['type']) ? $hostpn_multi_field['type'] : '',
                                                $hostpn_multi_field
                                            );
                                        }
                                        update_post_meta($post_id, $hostpn_multi_field['id'], !$empty ? $multi_array : '');
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

            clean_post_cache($post_id);
        }
    }

    public function hostpn_contract_form_save($element_id, $key_value, $hostpn_form_type, $hostpn_form_subtype, $post_type)
    {
        if ($post_type == 'hostpn_contract') {
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

                            $contract_user_id = get_current_user_id();

                            // Generate token if not provided
                            if (empty($hostpn_contract_token)) {
                                $hostpn_contract_token = bin2hex(openssl_random_pseudo_bytes(16));
                                $key_value['hostpn_contract_token'] = $hostpn_contract_token;
                            }

                            if (!empty($element_id) && get_post($element_id)) {
                                $contract_id = $element_id;
                            } else {
                                $post_functions = new HOSTPN_Functions_Post();
                                $hostpn_contract_title = !empty($hostpn_contract_title) ? $hostpn_contract_title : __('Contract', 'hostpn') . ' - ' . gmdate('Y-m-d H:i:s', current_time('timestamp'));
                                $hostpn_contract_description = !empty($hostpn_contract_description) ? $hostpn_contract_description : '';
                                $contract_id = $post_functions->hostpn_insert_post(
                                    esc_html($hostpn_contract_title),
                                    $hostpn_contract_description,
                                    '',
                                    sanitize_title(esc_html($hostpn_contract_title)),
                                    $post_type,
                                    'publish',
                                    $contract_user_id
                                );
                            }

                            if (!empty($key_value)) {
                                foreach ($key_value as $key => $value) {
                                    update_post_meta($contract_id, $key, $value);
                                }
                            }

                            // Build title from type + guest + accommodation
                            $types = HOSTPN_Contract_Templates::hostpn_get_contract_types();
                            $type_label = !empty($key_value['hostpn_contract_type']) && isset($types[$key_value['hostpn_contract_type']]) ? $types[$key_value['hostpn_contract_type']] : '';
                            $guest_name = !empty($key_value['hostpn_contract_guest_id']) ? get_post_meta($key_value['hostpn_contract_guest_id'], 'hostpn_name', true) . ' ' . get_post_meta($key_value['hostpn_contract_guest_id'], 'hostpn_surname', true) : '';
                            $acc_title = !empty($key_value['hostpn_contract_accommodation_id']) ? get_the_title($key_value['hostpn_contract_accommodation_id']) : '';
                            $title = trim($type_label . ' - ' . $guest_name . ' - ' . $acc_title);
                            wp_update_post(['ID' => $contract_id, 'post_title' => $title, 'post_author' => $contract_user_id]);

                            clean_post_cache($contract_id);
                            break;

                        case 'post_edit':
                            if (!empty($key_value)) {
                                foreach ($key_value as $key => $value) {
                                    if (strpos($key, 'hostpn_') !== false) {
                                        ${$key} = $value;
                                    }
                                }
                            }

                            $contract_id = $element_id;

                            if (!empty($key_value)) {
                                foreach ($key_value as $key => $value) {
                                    update_post_meta($contract_id, $key, $value);
                                }
                            }

                            // Update title
                            $types = HOSTPN_Contract_Templates::hostpn_get_contract_types();
                            $type_label = !empty($key_value['hostpn_contract_type']) && isset($types[$key_value['hostpn_contract_type']]) ? $types[$key_value['hostpn_contract_type']] : '';
                            $guest_name = !empty($key_value['hostpn_contract_guest_id']) ? get_post_meta($key_value['hostpn_contract_guest_id'], 'hostpn_name', true) . ' ' . get_post_meta($key_value['hostpn_contract_guest_id'], 'hostpn_surname', true) : '';
                            $acc_title = !empty($key_value['hostpn_contract_accommodation_id']) ? get_the_title($key_value['hostpn_contract_accommodation_id']) : '';
                            $title = trim($type_label . ' - ' . $guest_name . ' - ' . $acc_title);
                            if (!empty($title) && $title !== ' -  - ') {
                                wp_update_post(['ID' => $contract_id, 'post_title' => $title]);
                            }
                            break;
                    }
            }
        }
    }

    /**
     * Get contracts by guest and accommodation.
     *
     * @param int $guest_id
     * @param int $accommodation_id
     * @return array Post IDs.
     */
    public static function hostpn_get_contracts($guest_id = 0, $accommodation_id = 0)
    {
        $args = [
            'post_type' => 'hostpn_contract',
            'post_status' => 'any',
            'numberposts' => -1,
            'fields' => 'ids',
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        $meta_query = [];

        if (!empty($guest_id)) {
            $meta_query[] = [
                'key' => 'hostpn_contract_guest_id',
                'value' => $guest_id,
            ];
        }

        if (!empty($accommodation_id)) {
            $meta_query[] = [
                'key' => 'hostpn_contract_accommodation_id',
                'value' => $accommodation_id,
            ];
        }

        if (!empty($meta_query)) {
            $args['meta_query'] = $meta_query;
        }

        return get_posts($args);
    }

    /**
     * Get the guest post ID linked to the current WordPress user.
     *
     * @param int $user_id WordPress user ID.
     * @return int|false Guest post ID or false.
     */
    public static function hostpn_get_guest_id_for_user($user_id)
    {
        if (empty($user_id)) {
            return false;
        }

        // First check by hostpn_guest_wp_user_id or hostpn_user_id meta
        $guests = get_posts([
            'post_type' => 'hostpn_guest',
            'post_status' => 'any',
            'numberposts' => 1,
            'fields' => 'ids',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => 'hostpn_guest_wp_user_id',
                    'value' => $user_id,
                ],
                [
                    'key' => 'hostpn_user_id',
                    'value' => $user_id,
                ],
            ],
        ]);

        if (!empty($guests)) {
            return $guests[0];
        }

        // Fallback: check by post_author
        $guests = get_posts([
            'post_type' => 'hostpn_guest',
            'post_status' => 'any',
            'numberposts' => 1,
            'fields' => 'ids',
            'author' => $user_id,
        ]);

        if (!empty($guests)) {
            return $guests[0];
        }

        return false;
    }

    // Admin columns

    public function hostpn_contract_custom_columns($columns)
    {
        $new_columns = [];
        $new_columns['cb'] = $columns['cb'];
        $new_columns['contract_info'] = __('Contract', 'hostpn');
        $new_columns['contract_guest'] = __('Guest', 'hostpn');
        $new_columns['contract_accommodation'] = __('Accommodation', 'hostpn');
        $new_columns['contract_type'] = __('Type', 'hostpn');
        $new_columns['contract_status'] = __('Status', 'hostpn');
        $new_columns['creation_date'] = __('Date', 'hostpn');
        return $new_columns;
    }

    public function hostpn_contract_sortable_columns($columns)
    {
        $columns['contract_info'] = 'title';
        $columns['creation_date'] = 'date';
        return $columns;
    }

    public function hostpn_contract_custom_column_content($column, $post_id)
    {
        switch ($column) {
            case 'contract_info':
                $edit_link = get_edit_post_link($post_id);
                $version = get_post_meta($post_id, 'hostpn_contract_version', true);
                echo '<p><a href="' . esc_url($edit_link) . '"><strong>#' . esc_html($post_id) . '</strong>';
                if ($version) {
                    echo ' <small>v' . esc_html($version) . '</small>';
                }
                echo '</a></p>';
                break;

            case 'contract_guest':
                $guest_id = get_post_meta($post_id, 'hostpn_contract_guest_id', true);
                if ($guest_id) {
                    $guest_name = get_post_meta($guest_id, 'hostpn_name', true) . ' ' . get_post_meta($guest_id, 'hostpn_surname', true);
                    $edit_link = get_edit_post_link($guest_id);
                    echo '<p><a href="' . esc_url($edit_link) . '">' . esc_html(trim($guest_name)) . '</a></p>';
                }
                break;

            case 'contract_accommodation':
                $acc_id = get_post_meta($post_id, 'hostpn_contract_accommodation_id', true);
                if ($acc_id) {
                    $edit_link = get_edit_post_link($acc_id);
                    echo '<p><a href="' . esc_url($edit_link) . '">' . esc_html(get_the_title($acc_id)) . '</a></p>';
                }
                break;

            case 'contract_type':
                $type = get_post_meta($post_id, 'hostpn_contract_type', true);
                $types = HOSTPN_Contract_Templates::hostpn_get_contract_types();
                echo '<p>' . esc_html(isset($types[$type]) ? $types[$type] : $type) . '</p>';
                break;

            case 'contract_status':
                $status = get_post_meta($post_id, 'hostpn_contract_status', true);
                $statuses = self::hostpn_get_contract_statuses();
                $status_classes = [
                    'draft' => 'hostpn-badge-grey',
                    'sent' => 'hostpn-badge-blue',
                    'signed' => 'hostpn-badge-green',
                    'expired' => 'hostpn-badge-orange',
                    'cancelled' => 'hostpn-badge-red',
                ];
                $class = isset($status_classes[$status]) ? $status_classes[$status] : '';
                $label = isset($statuses[$status]) ? $statuses[$status] : $status;
                echo '<span class="hostpn-badge ' . esc_attr($class) . '">' . esc_html($label) . '</span>';
                break;

            case 'creation_date':
                $post = get_post($post_id);
                echo '<p>' . esc_html(date_i18n(get_option('date_format'), strtotime($post->post_date))) . '</p>';
                break;
        }
    }

    // AJAX popup methods

    public function hostpn_contract_list()
    {
        $contract_atts = [
            'fields' => 'ids',
            'numberposts' => -1,
            'post_type' => 'hostpn_contract',
            'post_status' => 'any',
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        if (!HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
            $contract_atts['author'] = get_current_user_id();
        }

        $contracts = get_posts($contract_atts);
        $statuses = self::hostpn_get_contract_statuses();
        $types = HOSTPN_Contract_Templates::hostpn_get_contract_types();

        ob_start();
        ?>
        <ul class="hostpn-contracts hostpn-list-style-none hostpn-margin-auto">
            <?php if (!empty($contracts)): ?>
                <?php foreach ($contracts as $contract_id): ?>
                    <?php
                    $type = get_post_meta($contract_id, 'hostpn_contract_type', true);
                    $status = get_post_meta($contract_id, 'hostpn_contract_status', true);
                    $guest_id = get_post_meta($contract_id, 'hostpn_contract_guest_id', true);
                    $guest_name = $guest_id ? trim(get_post_meta($guest_id, 'hostpn_name', true) . ' ' . get_post_meta($guest_id, 'hostpn_surname', true)) : '';
                    $type_label = isset($types[$type]) ? $types[$type] : $type;
                    $status_label = isset($statuses[$status]) ? $statuses[$status] : $status;
                    ?>
                    <li class="hostpn-contract hostpn-mb-10" data-hostpn_contract-id="<?php echo esc_attr($contract_id); ?>"
                        data-hostpn-sort-name="<?php echo esc_attr(strtolower($guest_name)); ?>"
                        data-hostpn-sort-date="<?php echo esc_attr(get_post_field('post_date', $contract_id)); ?>">
                        <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-60-percent">
                                <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none"
                                    data-hostpn-popup-id="hostpn-popup-contract-view" data-hostpn-ajax-type="hostpn_contract_view">
                                    <span><?php echo esc_html($type_label); ?></span>
                                    <?php if ($guest_name): ?>
                                        <small> - <?php echo esc_html($guest_name); ?></small>
                                    <?php endif; ?>
                                    <small class="hostpn-badge hostpn-badge-<?php echo esc_attr($status); ?>"><?php echo esc_html($status_label); ?></small>
                                </a>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right hostpn-position-relative">
                                <i class="material-icons-outlined hostpn-menu-more-btn hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30">more_vert</i>
                                <div class="hostpn-menu-more hostpn-z-index-99 hostpn-display-none-soft">
                                    <ul class="hostpn-list-style-none">
                                        <li>
                                            <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none"
                                                data-hostpn-popup-id="hostpn-popup-contract-view" data-hostpn-ajax-type="hostpn_contract_view">
                                                <div class="hostpn-display-table hostpn-width-100-percent">
                                                    <div class="hostpn-display-inline-table hostpn-width-70-percent"><p><?php esc_html_e('View Contract', 'hostpn'); ?></p></div>
                                                    <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right"><i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">visibility</i></div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none"
                                                data-hostpn-popup-id="hostpn-popup-contract-edit" data-hostpn-ajax-type="hostpn_contract_edit">
                                                <div class="hostpn-display-table hostpn-width-100-percent">
                                                    <div class="hostpn-display-inline-table hostpn-width-70-percent"><p><?php esc_html_e('Edit Contract', 'hostpn'); ?></p></div>
                                                    <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right"><i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">edit</i></div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="hostpn-popup-open" data-hostpn-popup-id="hostpn-popup-contract-remove">
                                                <div class="hostpn-display-table hostpn-width-100-percent">
                                                    <div class="hostpn-display-inline-table hostpn-width-70-percent"><p><?php esc_html_e('Remove Contract', 'hostpn'); ?></p></div>
                                                    <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right"><i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">delete</i></div>
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
        </ul>
        <?php
        $hostpn_return_string = ob_get_contents();
        ob_end_clean();
        return $hostpn_return_string;
    }

    public function hostpn_contract_view($contract_id)
    {
        ob_start();
        $contract_type = get_post_meta($contract_id, 'hostpn_contract_type', true);
        $accommodation_id = get_post_meta($contract_id, 'hostpn_contract_accommodation_id', true);
        ?>
        <div class="contract-view hostpn-p-30" data-hostpn_contract-id="<?php echo esc_attr($contract_id); ?>">
            <h4 class="hostpn-text-align-center hostpn-mb-30"><?php echo esc_html(get_the_title($contract_id)); ?></h4>

            <div class="hostpn-contract-preview">
                <?php
                if ($contract_type && $accommodation_id) {
                    $template = HOSTPN_Contract_Templates::hostpn_get_saved_template($contract_type);
                    echo wp_kses_post(HOSTPN_Contract_Templates::hostpn_resolve_shortcodes_from_contract(
                        HOSTPN_Contract_Templates::hostpn_render_contract($contract_type, $template, $accommodation_id),
                        $contract_id
                    ));
                }
                ?>
            </div>

            <div class="hostpn-text-align-right hostpn-mt-30 hostpn-contract" data-hostpn_contract-id="<?php echo esc_attr($contract_id); ?>">
                <a href="#" class="hostpn-btn hostpn-btn-mini hostpn-popup-open-ajax"
                    data-hostpn-popup-id="hostpn-popup-contract-edit"
                    data-hostpn-ajax-type="hostpn_contract_edit"><?php esc_html_e('Edit Contract', 'hostpn'); ?></a>
            </div>
        </div>
        <?php
        $hostpn_return_string = ob_get_contents();
        ob_end_clean();
        return $hostpn_return_string;
    }

    public function hostpn_contract_new()
    {
        ob_start();
        ?>
        <div class="contract-new hostpn-p-30">
            <h4 class="hostpn-mb-30"><?php esc_html_e('Add new Contract', 'hostpn'); ?></h4>

            <form action="" method="post" id="hostpn-form" class="hostpn-form">
                <?php foreach (self::hostpn_contract_get_fields() as $hostpn_field): ?>
                    <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post'), HOSTPN_KSES); ?>
                <?php endforeach ?>

                <?php foreach (self::hostpn_contract_get_fields_meta() as $hostpn_field_meta): ?>
                    <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field_meta, 'post'), HOSTPN_KSES); ?>
                <?php endforeach ?>

                <div class="hostpn-text-align-right">
                    <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-subtype="post_new"
                        data-hostpn-post-type="hostpn_contract" type="submit"
                        value="<?php esc_attr_e('Create Contract', 'hostpn'); ?>" />
                </div>
            </form>
        </div>
        <?php
        $hostpn_return_string = ob_get_contents();
        ob_end_clean();
        return $hostpn_return_string;
    }

    public function hostpn_contract_edit($contract_id)
    {
        ob_start();
        ?>
        <div class="contract-edit hostpn-p-30">
            <p class="hostpn-text-align-center hostpn-mb-0"><?php esc_html_e('Editing', 'hostpn'); ?></p>
            <h4 class="hostpn-text-align-center hostpn-mb-30"><?php echo esc_html(get_the_title($contract_id)); ?></h4>

            <form action="" method="post" id="hostpn-form" class="hostpn-form">
                <?php foreach (self::hostpn_contract_get_fields($contract_id) as $hostpn_field): ?>
                    <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $contract_id), HOSTPN_KSES); ?>
                <?php endforeach ?>

                <?php foreach (self::hostpn_contract_get_fields_meta() as $hostpn_field_meta): ?>
                    <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field_meta, 'post', $contract_id), HOSTPN_KSES); ?>
                <?php endforeach ?>

                <div class="hostpn-text-align-right">
                    <input class="hostpn-btn" type="submit" data-hostpn-type="post" data-hostpn-subtype="post_edit"
                        data-hostpn-post-type="hostpn_contract" data-hostpn-post-id="<?php echo esc_attr($contract_id); ?>"
                        value="<?php esc_attr_e('Save Contract', 'hostpn'); ?>" />
                </div>
            </form>
        </div>
        <?php
        $hostpn_return_string = ob_get_contents();
        ob_end_clean();
        return $hostpn_return_string;
    }
}

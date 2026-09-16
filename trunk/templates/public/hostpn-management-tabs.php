<?php
/**
 * Management tabs panel for single accommodation frontend.
 *
 * Shows a tabbed management panel to logged-in admins and guests.
 * Tabs: Contracts, Financial management, Cleaning, Inventory.
 *
 * Uses the plugin's standard tab system (.hostpn-tabs / .hostpn-tab-content).
 *
 * Expected variables:
 *   $accommodation_id - The current accommodation post ID
 *
 * @link       padresenlanube.com/
 * @since      1.0.120
 * @package    hostpn
 * @subpackage hostpn/templates/public
 */

if (!defined('ABSPATH')) {
    exit;
}

// Only for logged-in users
if (!is_user_logged_in()) {
    return;
}

$current_user_id = get_current_user_id();
$is_admin = current_user_can('manage_options');
$guest_id = HOSTPN_Post_Type_Contract::hostpn_get_guest_id_for_user($current_user_id);
$accommodation_type = get_post_meta($accommodation_id, 'hostpn_accommodation_type', true);
$rooms = HOSTPN_Post_Type_Room::hostpn_get_rooms_by_accommodation($accommodation_id);

// Determine which tabs are visible
$tabs = [];

// Contracts tab: admin sees all, guest sees only theirs
if ($is_admin) {
    $all_contracts = HOSTPN_Post_Type_Contract::hostpn_get_contracts(0, $accommodation_id);
    if (!empty($all_contracts)) {
        $tabs['contracts'] = __('Contracts', 'hostpn');
    }
} elseif ($guest_id) {
    $guest_contracts = HOSTPN_Post_Type_Contract::hostpn_get_contracts($guest_id, $accommodation_id);
    if (!empty($guest_contracts)) {
        $tabs['contracts'] = __('Contracts', 'hostpn');
    }
}

// Financial tab: admin only
if ($is_admin) {
    $tabs['financial'] = __('Financial mgmt.', 'hostpn');
}

// Cleaning tab: admin only
if ($is_admin) {
    $tabs['cleaning'] = __('Cleaning', 'hostpn');
}

// Inventory tab: admin always, guest if they have a room with inventory
if ($is_admin) {
    $tabs['inventory'] = __('Inventory', 'hostpn');
} elseif ($guest_id) {
    // Check if the guest has a room in this accommodation
    foreach ($rooms as $room_id) {
        $room_guest_id = get_post_meta($room_id, 'hostpn_room_guest_id', true);
        if (intval($room_guest_id) === intval($guest_id)) {
            $tabs['inventory'] = __('Inventory', 'hostpn');
            break;
        }
    }
}

// Don't render panel if no tabs visible
if (empty($tabs)) {
    return;
}

// Build config for inline output (bypasses wp_enqueue_script entirely)
$mgmt_css_url = HOSTPN_URL . 'assets/css/public/hostpn-management-tabs.css?ver=' . HOSTPN_VERSION;
$mgmt_js_url  = HOSTPN_URL . 'assets/js/public/hostpn-management-tabs.js?ver=' . HOSTPN_VERSION;
$mgmt_config  = [
    'ajaxUrl'         => admin_url('admin-ajax.php'),
    'nonce'           => wp_create_nonce('hostpn-nonce'),
    'accommodationId' => $accommodation_id,
    'i18n'            => [
        'loading'          => __('Loading...', 'hostpn'),
        'save'             => __('Save', 'hostpn'),
        'saved'            => __('Saved successfully.', 'hostpn'),
        'errorLoading'     => __('Error loading data.', 'hostpn'),
        'errorSaving'      => __('Error saving.', 'hostpn'),
        'errorSending'     => __('Error sending email.', 'hostpn'),
        'notes'            => __('Notes', 'hostpn'),
        'markAllDone'      => __('Mark all completed', 'hostpn'),
        'noFinancialData'  => __('No financial data available.', 'hostpn'),
        'areaBedroom'      => __('Bedroom', 'hostpn'),
        'areaBathroom'     => __('Bathroom', 'hostpn'),
        'areaKitchen'      => __('Kitchen', 'hostpn'),
        'areaCommon'       => __('Common areas', 'hostpn'),
        'areaOther'        => __('Other', 'hostpn'),
        'generating'       => __('Generating checklist...', 'hostpn'),
        'noItems'          => __('No inventory items found.', 'hostpn'),
        'issue'            => __('Issue', 'hostpn'),
        'comment'          => __('Comment', 'hostpn'),
        'overallNotes'     => __('General notes', 'hostpn'),
        'saveInspection'   => __('Save inspection', 'hostpn'),
        'sendEmail'        => __('Send by email', 'hostpn'),
        'inspectionSaved'  => __('Inspection saved.', 'hostpn'),
        'emailSent'        => __('Email sent successfully.', 'hostpn'),
    ],
];

$tab_icons = [
    'contracts' => 'description',
    'financial' => 'account_balance',
    'cleaning'  => 'cleaning_services',
    'inventory' => 'inventory_2',
];

$first_tab = array_key_first($tabs);
$tab_count = count($tabs);
$tab_width_style = 'width:' . (100 / $tab_count) . '%';
?>

<div class="hostpn-mgmt-panel" data-accommodation-id="<?php echo esc_attr($accommodation_id); ?>">
    <div class="hostpn-tabs">
        <?php foreach ($tabs as $tab_key => $tab_label): ?>
            <button type="button"
                style="<?php echo esc_attr($tab_width_style); ?>"
                class="hostpn-mgmt-tab-btn<?php echo $tab_key === $first_tab ? ' active' : ''; ?>"
                data-tab="<?php echo esc_attr($tab_key); ?>">
                <i class="material-icons-outlined hostpn-icon-small"><?php echo esc_attr($tab_icons[$tab_key]); ?></i>
                <?php echo esc_html($tab_label); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Contracts Tab -->
    <?php if (isset($tabs['contracts'])): ?>
        <div class="hostpn-tab-content hostpn-mgmt-tab-pane" data-tab="contracts"<?php echo $first_tab !== 'contracts' ? ' style="display:none"' : ''; ?>>
            <?php
            if ($is_admin) {
                $contracts = $all_contracts;
            } else {
                $contracts = $guest_contracts;
            }
            include HOSTPN_DIR . 'templates/public/hostpn-contracts-block.php';
            ?>
        </div>
    <?php endif; ?>

    <!-- Financial Tab -->
    <?php if (isset($tabs['financial'])): ?>
        <div class="hostpn-tab-content hostpn-mgmt-tab-pane" data-tab="financial"<?php echo $first_tab !== 'financial' ? ' style="display:none"' : ''; ?>>
            <div class="hostpn-mgmt-financial-wrapper">
                <div class="hostpn-mgmt-loading">
                    <i class="material-icons-outlined hostpn-spin">sync</i>
                    <?php esc_html_e('Loading financial data...', 'hostpn'); ?>
                </div>
                <div class="hostpn-mgmt-financial-content"></div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Cleaning Tab -->
    <?php if (isset($tabs['cleaning'])): ?>
        <div class="hostpn-tab-content hostpn-mgmt-tab-pane" data-tab="cleaning"<?php echo $first_tab !== 'cleaning' ? ' style="display:none"' : ''; ?>>
            <div class="hostpn-mgmt-cleaning-wrapper">
                <div class="hostpn-mgmt-room-selector">
                    <label for="hostpn-mgmt-cleaning-room"><?php esc_html_e('Select room', 'hostpn'); ?></label>
                    <select id="hostpn-mgmt-cleaning-room" class="hostpn-mgmt-select">
                        <option value=""><?php esc_html_e('-- Select a room --', 'hostpn'); ?></option>
                        <?php foreach ($rooms as $room_id):
                            $room_number = get_post_meta($room_id, 'hostpn_room_number', true);
                            $room_label = !empty($room_number) ? sprintf(__('Room %s', 'hostpn'), $room_number) : get_the_title($room_id);
                        ?>
                            <option value="<?php echo esc_attr($room_id); ?>"><?php echo esc_html($room_label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="hostpn-mgmt-cleaning-content"></div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Inventory Tab -->
    <?php if (isset($tabs['inventory'])): ?>
        <div class="hostpn-tab-content hostpn-mgmt-tab-pane" data-tab="inventory"<?php echo $first_tab !== 'inventory' ? ' style="display:none"' : ''; ?>>
            <?php if ($is_admin): ?>
                <!-- Admin: inspection checklist -->
                <div class="hostpn-mgmt-inventory-admin">
                    <div class="hostpn-mgmt-room-selector">
                        <label for="hostpn-mgmt-inventory-room"><?php esc_html_e('Select room', 'hostpn'); ?></label>
                        <select id="hostpn-mgmt-inventory-room" class="hostpn-mgmt-select">
                            <option value=""><?php esc_html_e('-- Select a room --', 'hostpn'); ?></option>
                            <?php foreach ($rooms as $room_id):
                                $room_number = get_post_meta($room_id, 'hostpn_room_number', true);
                                $room_label = !empty($room_number) ? sprintf(__('Room %s', 'hostpn'), $room_number) : get_the_title($room_id);
                            ?>
                                <option value="<?php echo esc_attr($room_id); ?>"><?php echo esc_html($room_label); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="hostpn-btn hostpn-btn-mini hostpn-mgmt-generate-checklist" disabled>
                            <i class="material-icons-outlined hostpn-icon-small">checklist</i>
                            <?php esc_html_e('Generate checkout checklist', 'hostpn'); ?>
                        </button>
                    </div>
                    <div class="hostpn-mgmt-inventory-checklist"></div>
                </div>
            <?php else: ?>
                <!-- Guest: read-only inventory view -->
                <div class="hostpn-mgmt-inventory-guest">
                    <?php
                    $guest_room_id = 0;
                    foreach ($rooms as $room_id) {
                        $room_guest_id = get_post_meta($room_id, 'hostpn_room_guest_id', true);
                        if (intval($room_guest_id) === intval($guest_id)) {
                            $guest_room_id = $room_id;
                            break;
                        }
                    }

                    if ($guest_room_id) {
                        $categories = [
                            'mobiliario'               => __('Furniture', 'hostpn'),
                            'equipamiento_individual'  => __('Individual equipment', 'hostpn'),
                            'menaje_individual'        => __('Individual kitchenware', 'hostpn'),
                            'equipamiento_comunitario' => __('Community equipment', 'hostpn'),
                            'otros_enseres'            => __('Other items', 'hostpn'),
                        ];

                        $has_items = false;
                        $inventory_html = '';

                        foreach ($categories as $cat_key => $cat_label) {
                            $accom_items = HOSTPN_Contract_Templates::hostpn_collect_inventory_items(
                                get_post_meta($accommodation_id, 'hostpn_contract_inv_' . $cat_key . '_name', true),
                                get_post_meta($accommodation_id, 'hostpn_contract_inv_' . $cat_key . '_url', true)
                            );
                            $room_items = HOSTPN_Contract_Templates::hostpn_collect_inventory_items(
                                get_post_meta($guest_room_id, 'hostpn_room_inv_' . $cat_key . '_name', true),
                                get_post_meta($guest_room_id, 'hostpn_room_inv_' . $cat_key . '_url', true)
                            );
                            $merged = array_merge($accom_items, $room_items);

                            if (!empty($merged)) {
                                $has_items = true;
                                $inventory_html .= '<div class="hostpn-mgmt-inv-category">';
                                $inventory_html .= '<h4>' . esc_html($cat_label) . '</h4>';
                                $inventory_html .= '<table class="hostpn-mgmt-inv-table"><thead><tr>';
                                $inventory_html .= '<th>' . esc_html__('Item', 'hostpn') . '</th>';
                                $inventory_html .= '</tr></thead><tbody>';
                                foreach ($merged as $item) {
                                    $inventory_html .= '<tr><td>' . esc_html($item['name']) . '</td></tr>';
                                }
                                $inventory_html .= '</tbody></table></div>';
                            }
                        }

                        if ($has_items) {
                            $room_number = get_post_meta($guest_room_id, 'hostpn_room_number', true);
                            echo '<h3>' . esc_html(sprintf(__('Inventory - Room %s', 'hostpn'), $room_number)) . '</h3>';
                            echo wp_kses_post($inventory_html);
                        } else {
                            echo '<p class="hostpn-mgmt-empty">' . esc_html__('No inventory items registered for your room.', 'hostpn') . '</p>';
                        }
                    } else {
                        echo '<p class="hostpn-mgmt-empty">' . esc_html__('No room assigned.', 'hostpn') . '</p>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<link rel="stylesheet" href="<?php echo esc_url($mgmt_css_url); ?>" media="all">
<script>window.hostpnMgmtTabs = <?php echo wp_json_encode($mgmt_config); ?>;</script>
<script src="<?php echo esc_url($mgmt_js_url); ?>" defer></script>

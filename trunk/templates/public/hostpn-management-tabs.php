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

// Cleaning tab: admin always, guest if shared cleaning mode is active and they belong to this accommodation
$cleaning_system = get_post_meta($accommodation_id, 'hostpn_cleaning_system', true);
if (empty($cleaning_system)) {
    $cleaning_system = 'punctual';
}

if ($is_admin) {
    $tabs['cleaning'] = __('Cleaning', 'hostpn');
} elseif ($guest_id && $cleaning_system === 'shared') {
    foreach ($rooms as $room_id) {
        $room_guest_id = get_post_meta($room_id, 'hostpn_room_guest_id', true);
        if (intval($room_guest_id) === intval($guest_id)) {
            $tabs['cleaning'] = __('Cleaning', 'hostpn');
            break;
        }
    }
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

// Promotions tab: visible if promotions enabled
$promotions_enabled = get_option('hostpn_promotions_enabled', 'on');
if ($promotions_enabled === 'on') {
    $tabs['promotions'] = __('Promotions', 'hostpn');
}

// Don't render panel if no tabs visible
if (empty($tabs)) {
    return;
}

// Compute summary data for top hero header panel and promotions tab
$promo_summary = HOSTPN_Post_Type_Accommodation::hostpn_get_guest_promotions_summary($accommodation_id, $guest_id);
$stay_info = $promo_summary['stay_info'];
$primary_promo = $promo_summary['primary'];

$shared_info = HOSTPN_Post_Type_Accommodation::hostpn_get_shared_cleaning_info($accommodation_id);
$next_cleaning_str = !empty($shared_info['next_date']) ? $shared_info['next_date'] : '--';

// Build config for inline output (bypasses wp_enqueue_script entirely)
$mgmt_css_url = HOSTPN_URL . 'assets/css/public/hostpn-management-tabs.css?ver=' . HOSTPN_VERSION;
$mgmt_js_url  = HOSTPN_URL . 'assets/js/public/hostpn-management-tabs.js?ver=' . HOSTPN_VERSION;
$mgmt_config  = [
    'ajaxUrl'         => admin_url('admin-ajax.php'),
    'nonce'           => wp_create_nonce('hostpn-nonce'),
    'accommodationId' => $accommodation_id,
    'isAdmin'         => $is_admin ? 1 : 0,
    'cleaningSystem'  => $cleaning_system,
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
        'punctualCleaning' => __('Punctual per room', 'hostpn'),
        'sharedCleaning'   => __('Shared periodic rotation', 'hostpn'),
        'currentTurn'      => __('Current turn', 'hostpn'),
        'nextCleaningDate' => __('Next cleaning date', 'hostpn'),
        'frequency'        => __('Frequency (days)', 'hostpn'),
        'noticeDays'       => __('Notice days prior', 'hostpn'),
        'staysToClean'          => __('Stays / areas to clean', 'hostpn'),
        'cleaningInstructions'  => __('Cleaning instructions & steps', 'hostpn'),
        'rotationQueue'         => __('Rotation queue', 'hostpn'),
        'markCompleted'    => __('Mark cleaning completed', 'hostpn'),
        'sendReminderEmail'=> __('Send email reminder', 'hostpn'),
        'groupComments'    => __('Group comments', 'hostpn'),
        'noGroupComments'  => __('No group comments yet.', 'hostpn'),
        'addComment'       => __('Add comment', 'hostpn'),
        'writeComment'     => __('Write a comment for the group...', 'hostpn'),
        'cleaningHistory'  => __('Cleaning history', 'hostpn'),
        'adminConfig'      => __('Cleaning system configuration', 'hostpn'),
        'saveConfig'       => __('Save configuration', 'hostpn'),
        'roomLabel'        => __('Room', 'hostpn'),
        'everyXDays'       => __('Every %d days', 'hostpn'),
        'daysUnit'         => __('days', 'hostpn'),
        'estNextCleaning'  => __('Est. next cleaning:', 'hostpn'),
        'monthlyRent'      => __('Monthly Rent', 'hostpn'),
        'totalRent'        => __('Total Monthly Revenue', 'hostpn'),
        'totalDeposits'    => __('Total Deposits', 'hostpn'),
        'occupancy'        => __('Occupancy Rate', 'hostpn'),
        'guestName'        => __('Current Guest', 'hostpn'),
        'deposit'          => __('Deposit', 'hostpn'),
        'paymentDay'       => __('Payment Day', 'hostpn'),
        'available'        => __('Available', 'hostpn'),
        'occupied'         => __('Occupied', 'hostpn'),
    ],
];

$tab_icons = [
    'contracts'  => 'description',
    'financial'  => 'account_balance',
    'cleaning'   => 'cleaning_services',
    'inventory'  => 'inventory_2',
    'promotions' => 'card_giftcard',
];

$first_tab = array_key_first($tabs);
$tab_count = count($tabs);
$tab_width_style = 'width:' . (100 / $tab_count) . '%';
?>

<div class="hostpn-mgmt-panel" data-accommodation-id="<?php echo esc_attr($accommodation_id); ?>">
    <!-- Top Hero Summary Header Panel (Minimalist) -->
    <div class="hostpn-mgmt-hero-header">
        <div class="hostpn-hero-card hostpn-hero-card-stay">
            <div class="hostpn-hero-info">
                <span class="hostpn-hero-label"><?php esc_html_e('Days stayed', 'hostpn'); ?></span>
                <span class="hostpn-hero-val"><?php echo esc_html($stay_info['days']); ?> <?php esc_html_e('days', 'hostpn'); ?></span>
                <span class="hostpn-hero-sub"><?php echo esc_html($stay_info['formatted_duration']); ?></span>
            </div>
        </div>

        <div class="hostpn-hero-card hostpn-hero-card-cleaning">
            <div class="hostpn-hero-info">
                <span class="hostpn-hero-label"><?php esc_html_e('Next cleaning', 'hostpn'); ?></span>
                <span class="hostpn-hero-val"><?php echo esc_html($next_cleaning_str); ?></span>
                <span class="hostpn-hero-sub">
                    <?php
                    if (!empty($shared_info['current_turn'])) {
                        echo esc_html(sprintf(__('Turn: %s', 'hostpn'), $shared_info['current_turn']['room_label']));
                    } else {
                        echo esc_html($cleaning_system === 'shared' ? __('Shared rotation', 'hostpn') : __('Punctual per room', 'hostpn'));
                    }
                    ?>
                </span>
            </div>
        </div>

        <div class="hostpn-hero-card hostpn-hero-card-promo">
            <div class="hostpn-hero-info">
                <span class="hostpn-hero-label"><?php esc_html_e('Active promotion', 'hostpn'); ?></span>
                <?php if ($primary_promo): ?>
                    <span class="hostpn-hero-val">
                        <?php if ($primary_promo['unlocked']): ?>
                            <span class="hostpn-unlocked-badge"><?php esc_html_e('Unlocked!', 'hostpn'); ?></span>
                        <?php else: ?>
                            <?php echo esc_html(sprintf(__('Remaining: %d days', 'hostpn'), $primary_promo['days_remaining'])); ?>
                        <?php endif; ?>
                    </span>
                    <div class="hostpn-hero-progress-wrapper">
                        <div class="hostpn-hero-progress-bar" style="width: <?php echo esc_attr($primary_promo['progress_percent']); ?>%;"></div>
                    </div>
                    <span class="hostpn-hero-sub"><?php echo esc_html($primary_promo['title']); ?> (<?php echo esc_html($primary_promo['progress_percent']); ?>%)</span>
                <?php else: ?>
                    <span class="hostpn-hero-val">--</span>
                    <span class="hostpn-hero-sub"><?php esc_html_e('No active promotions', 'hostpn'); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="hostpn-tabs">
        <?php foreach ($tabs as $tab_key => $tab_label): ?>
            <button type="button"
                style="<?php echo esc_attr($tab_width_style); ?>"
                class="hostpn-mgmt-tab-btn<?php echo $tab_key === $first_tab ? ' active' : ''; ?>"
                data-tab="<?php echo esc_attr($tab_key); ?>">
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
                <?php if (!empty($accommodation_id)): ?>
                    <div class="hostpn-mgmt-financial-content">
                        <?php echo HOSTPN_Post_Type_Accommodation::hostpn_render_admin_financial_dashboard_content($accommodation_id); ?>
                    </div>
                <?php else: ?>
                    <div class="hostpn-mgmt-loading">
                        <i class="material-icons-outlined hostpn-spin">sync</i>
                        <?php esc_html_e('Loading financial data...', 'hostpn'); ?>
                    </div>
                    <div class="hostpn-mgmt-financial-content"></div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Cleaning Tab -->
    <?php if (isset($tabs['cleaning'])): ?>
        <div class="hostpn-tab-content hostpn-mgmt-tab-pane" data-tab="cleaning"<?php echo $first_tab !== 'cleaning' ? ' style="display:none"' : ''; ?>>
            <div class="hostpn-mgmt-cleaning-wrapper" data-system="<?php echo esc_attr($cleaning_system); ?>">
                
                <?php if ($is_admin): ?>
                    <!-- System Mode Switcher for Admin -->
                    <div class="hostpn-mgmt-cleaning-system-switch">
                        <span class="hostpn-mgmt-system-label"><?php esc_html_e('Cleaning system:', 'hostpn'); ?></span>
                        <div class="hostpn-mgmt-sys-btn-group">
                            <button type="button" class="hostpn-mgmt-sys-btn<?php echo $cleaning_system === 'punctual' ? ' active' : ''; ?>" data-sys="punctual">
                                <i class="material-icons-outlined hostpn-icon-small">cleaning_services</i>
                                <?php esc_html_e('Punctual per room', 'hostpn'); ?>
                            </button>
                            <button type="button" class="hostpn-mgmt-sys-btn<?php echo $cleaning_system === 'shared' ? ' active' : ''; ?>" data-sys="shared">
                                <i class="material-icons-outlined hostpn-icon-small">published_with_changes</i>
                                <?php esc_html_e('Shared periodic rotation', 'hostpn'); ?>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Punctual Cleaning Pane -->
                <div class="hostpn-mgmt-cleaning-punctual-pane"<?php echo $cleaning_system !== 'punctual' ? ' style="display:none"' : ''; ?>>
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

                <!-- Shared Cleaning Pane -->
                <div class="hostpn-mgmt-cleaning-shared-pane"<?php echo $cleaning_system !== 'shared' ? ' style="display:none"' : ''; ?>>
                    <div class="hostpn-mgmt-loading">
                        <i class="material-icons-outlined hostpn-spin">sync</i>
                        <?php esc_html_e('Loading shared cleaning status...', 'hostpn'); ?>
                    </div>
                    <div class="hostpn-mgmt-shared-content"></div>
                </div>

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
                                    if (!empty($item['url'])) {
                                        $inventory_html .= '<tr><td><a href="' . esc_url($item['url']) . '" target="_blank" rel="noopener noreferrer" class="hostpn-inv-item-link"><i class="material-icons-outlined hostpn-icon-small">link</i> ' . esc_html($item['name']) . '</a></td></tr>';
                                    } else {
                                        $inventory_html .= '<tr><td>' . esc_html($item['name']) . '</td></tr>';
                                    }
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

    <!-- Promotions Tab -->
    <?php if (isset($tabs['promotions'])): ?>
        <div class="hostpn-tab-content hostpn-mgmt-tab-pane" data-tab="promotions"<?php echo $first_tab !== 'promotions' ? ' style="display:none"' : ''; ?>>
            <div class="hostpn-mgmt-promotions-wrapper">
                <div class="hostpn-mgmt-stay-banner">
                    <div class="hostpn-mgmt-stay-badge">
                        <div>
                            <strong><?php esc_html_e('Your stay in this accommodation:', 'hostpn'); ?></strong>
                            <span><?php echo esc_html($stay_info['days']); ?> <?php esc_html_e('days', 'hostpn'); ?> (<?php echo esc_html($stay_info['formatted_duration']); ?>)</span>
                        </div>
                    </div>
                    <?php if (!empty($stay_info['start_date'])): ?>
                        <div class="hostpn-mgmt-stay-start">
                            <span><?php esc_html_e('Check-in:', 'hostpn'); ?> <?php echo esc_html($stay_info['start_date']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($promo_summary['promotions'])): ?>
                    <div class="hostpn-mgmt-promos-grid">
                        <?php foreach ($promo_summary['promotions'] as $promo_item): ?>
                            <div class="hostpn-mgmt-promo-card <?php echo $promo_item['unlocked'] ? 'unlocked' : ''; ?>">
                                <div class="hostpn-mgmt-promo-header">
                                    <div class="hostpn-mgmt-promo-title-wrap">
                                        <div>
                                            <h3 class="hostpn-mgmt-promo-title"><?php echo esc_html($promo_item['title']); ?></h3>
                                            <?php if (!empty($promo_item['target_accommodation_name'])): ?>
                                                <span class="hostpn-mgmt-promo-target"><?php echo esc_html($promo_item['target_accommodation_name']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if (!empty($promo_item['reward_desc'])): ?>
                                        <div class="hostpn-mgmt-promo-reward-badge">
                                            <?php echo esc_html($promo_item['reward_desc']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="hostpn-mgmt-promo-body">
                                    <div class="hostpn-mgmt-promo-progress-box">
                                        <div class="hostpn-mgmt-promo-progress-labels">
                                            <span><?php esc_html_e('Progress:', 'hostpn'); ?> <strong><?php echo esc_html($promo_item['days_stayed']); ?> / <?php echo esc_html($promo_item['required_days']); ?> <?php esc_html_e('days', 'hostpn'); ?></strong></span>
                                            <span class="hostpn-mgmt-promo-percent"><?php echo esc_html($promo_item['progress_percent']); ?>%</span>
                                        </div>
                                        <div class="hostpn-mgmt-promo-progress-track">
                                            <div class="hostpn-mgmt-promo-progress-fill" style="width: <?php echo esc_attr($promo_item['progress_percent']); ?>%;"></div>
                                        </div>
                                        <div class="hostpn-mgmt-promo-rem-text">
                                            <?php if ($promo_item['unlocked']): ?>
                                                <span class="hostpn-mgmt-unlocked-text"><?php esc_html_e('Promotion unlocked! Contact administration to redeem your reward.', 'hostpn'); ?></span>
                                            <?php else: ?>
                                                <span><?php esc_html_e('Time remaining:', 'hostpn'); ?> <strong><?php echo esc_html($promo_item['rem_formatted']); ?> (<?php echo esc_html($promo_item['days_remaining']); ?> <?php esc_html_e('days', 'hostpn'); ?>)</strong></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if (!empty($promo_item['conditions'])): ?>
                                        <div class="hostpn-mgmt-promo-conditions">
                                            <strong><?php esc_html_e('Terms & Conditions:', 'hostpn'); ?></strong>
                                            <p><?php echo esc_html($promo_item['conditions']); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="hostpn-mgmt-empty"><?php esc_html_e('No active promotions available at this moment.', 'hostpn'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<link rel="stylesheet" href="<?php echo esc_url($mgmt_css_url); ?>" media="all">
<script>window.hostpnMgmtTabs = <?php echo wp_json_encode($mgmt_config); ?>;</script>
<script src="<?php echo esc_url($mgmt_js_url); ?>" defer></script>

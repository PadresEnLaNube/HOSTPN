<?php
/**
 * Frontend contracts block for single accommodation.
 *
 * Shows logged-in guests their contracts for the current accommodation
 * with preview, download, and upload functionality.
 *
 * Expected variables:
 *   $accommodation_id - The current accommodation post ID
 *   $guest_id         - The guest post ID linked to the current user
 *   $contracts        - Array of contract post IDs
 *
 * @link       padresenlanube.com/
 * @since      1.0.83
 * @package    hostpn
 * @subpackage hostpn/templates/public
 */

if (!defined('ABSPATH')) {
    exit;
}

$statuses = HOSTPN_Post_Type_Contract::hostpn_get_contract_statuses();
$types = HOSTPN_Contract_Templates::hostpn_get_contract_types();
$status_classes = [
    'draft' => 'hostpn-cb-status-draft',
    'sent' => 'hostpn-cb-status-sent',
    'signed' => 'hostpn-cb-status-signed',
    'expired' => 'hostpn-cb-status-expired',
    'cancelled' => 'hostpn-cb-status-cancelled',
];
?>
<div class="hostpn-contracts-block" data-accommodation-id="<?php echo esc_attr($accommodation_id); ?>">
    <h2 class="hostpn-cb-title">
        <i class="material-icons-outlined hostpn-icon-medium">description</i>
        <?php esc_html_e('My Contracts', 'hostpn'); ?>
    </h2>

    <?php if (empty($contracts)): ?>
        <p class="hostpn-cb-empty"><?php esc_html_e('No contracts found for this accommodation.', 'hostpn'); ?></p>
    <?php else: ?>
        <div class="hostpn-cb-list">
            <?php foreach ($contracts as $contract_id):
                $contract_type = get_post_meta($contract_id, 'hostpn_contract_type', true);
                $contract_status = get_post_meta($contract_id, 'hostpn_contract_status', true);
                $contract_version = get_post_meta($contract_id, 'hostpn_contract_version', true) ?: 1;
                $start_date = get_post_meta($contract_id, 'hostpn_contract_start_date', true);
                $end_date = get_post_meta($contract_id, 'hostpn_contract_end_date', true);
                $pdf_file = get_post_meta($contract_id, 'hostpn_contract_pdf_attachment_id', true);
                $signed_file = get_post_meta($contract_id, 'hostpn_contract_signed_pdf_attachment_id', true);

                $type_label = isset($types[$contract_type]) ? $types[$contract_type] : $contract_type;
                $status_label = isset($statuses[$contract_status]) ? $statuses[$contract_status] : $contract_status;
                $status_class = isset($status_classes[$contract_status]) ? $status_classes[$contract_status] : '';

                $start_fmt = !empty($start_date) ? date_i18n(get_option('date_format'), strtotime($start_date)) : '';
                $end_fmt = !empty($end_date) ? date_i18n(get_option('date_format'), strtotime($end_date)) : '';
                ?>
                <div class="hostpn-cb-card" data-contract-id="<?php echo esc_attr($contract_id); ?>">
                    <div class="hostpn-cb-card-header">
                        <div class="hostpn-cb-card-info">
                            <h3 class="hostpn-cb-card-type">
                                <?php echo esc_html($type_label); ?>
                                <small class="hostpn-cb-card-version">v<?php echo esc_html($contract_version); ?></small>
                            </h3>
                            <span class="hostpn-cb-badge <?php echo esc_attr($status_class); ?>">
                                <?php echo esc_html($status_label); ?>
                            </span>
                        </div>
                        <?php if ($start_fmt || $end_fmt): ?>
                            <div class="hostpn-cb-card-dates">
                                <?php if ($start_fmt): ?>
                                    <span class="hostpn-cb-date">
                                        <i class="material-icons-outlined hostpn-icon-small">event</i>
                                        <?php echo esc_html($start_fmt); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($start_fmt && $end_fmt): ?>
                                    <span class="hostpn-cb-date-separator">-</span>
                                <?php endif; ?>
                                <?php if ($end_fmt): ?>
                                    <span class="hostpn-cb-date">
                                        <?php echo esc_html($end_fmt); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="hostpn-cb-card-actions no-print">
                        <button type="button" class="hostpn-cb-btn hostpn-cb-btn-preview"
                            data-contract-id="<?php echo esc_attr($contract_id); ?>">
                            <i class="material-icons-outlined">visibility</i>
                            <?php esc_html_e('Preview', 'hostpn'); ?>
                        </button>

                        <?php if (!empty($pdf_file)): ?>
                            <a href="<?php echo esc_url(admin_url('admin-ajax.php?action=hostpn_contract_download_pdf&contract_id=' . $contract_id . '&file_type=pdf&hostpn_ajax_nonce=' . wp_create_nonce('hostpn-nonce'))); ?>"
                                class="hostpn-cb-btn hostpn-cb-btn-download" target="_blank">
                                <i class="material-icons-outlined">file_download</i>
                                <?php esc_html_e('Download PDF', 'hostpn'); ?>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($signed_file)): ?>
                            <a href="<?php echo esc_url(admin_url('admin-ajax.php?action=hostpn_contract_download_pdf&contract_id=' . $contract_id . '&file_type=signed&hostpn_ajax_nonce=' . wp_create_nonce('hostpn-nonce'))); ?>"
                                class="hostpn-cb-btn hostpn-cb-btn-signed" target="_blank">
                                <i class="material-icons-outlined">verified</i>
                                <?php esc_html_e('Download Signed', 'hostpn'); ?>
                            </a>
                        <?php endif; ?>

                        <?php if ($contract_status !== 'signed' && $contract_status !== 'cancelled'): ?>
                            <button type="button" class="hostpn-cb-btn hostpn-cb-btn-upload"
                                data-contract-id="<?php echo esc_attr($contract_id); ?>">
                                <i class="material-icons-outlined">upload_file</i>
                                <?php esc_html_e('Upload Signed Copy', 'hostpn'); ?>
                            </button>
                            <input type="file" class="hostpn-cb-file-input hostpn-display-none-soft"
                                data-contract-id="<?php echo esc_attr($contract_id); ?>" accept=".pdf">
                        <?php endif; ?>
                    </div>

                    <div class="hostpn-cb-card-preview hostpn-display-none-soft" data-contract-id="<?php echo esc_attr($contract_id); ?>">
                        <div class="hostpn-cb-preview-loading">
                            <i class="material-icons-outlined hostpn-spin">sync</i>
                            <?php esc_html_e('Loading preview...', 'hostpn'); ?>
                        </div>
                        <div class="hostpn-cb-preview-content"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

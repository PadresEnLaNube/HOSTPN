<?php
/**
 * Template for public contract view with digital signature.
 *
 * Uses HOSTPN_Contract_Templates for template-based rendering.
 *
 * @package hostpn
 */

if (!defined('ABSPATH')) {
  exit;
}

get_header();

$token = isset($_GET['hostpn_contract']) ? sanitize_text_field(wp_unslash($_GET['hostpn_contract'])) : '';
$accommodation_id = 0;

if (!empty($token)) {
  $posts = get_posts([
    'post_type'   => 'hostpn_accommodation',
    'post_status' => 'any',
    'numberposts' => 1,
    'meta_key'    => 'hostpn_contract_token',
    'meta_value'  => $token,
  ]);

  if (!empty($posts)) {
    $accommodation_id = $posts[0]->ID;
  }
}

if (empty($accommodation_id)) {
  echo '<div class="hostpn-contract-public-wrapper" style="max-width:900px;margin:40px auto;padding:20px;">';
  echo '<h2>' . esc_html__('Contract not found', 'hostpn') . '</h2>';
  echo '<p>' . esc_html__('The contract link is invalid or has expired.', 'hostpn') . '</p>';
  echo '</div>';
  get_footer();
  return;
}

// Get contract type and rendered HTML via templates
$accommodation_type = get_post_meta($accommodation_id, 'hostpn_accommodation_type', true);
$contract_type = HOSTPN_Contract_Templates::hostpn_get_type_for_accommodation($accommodation_type);
$template = HOSTPN_Contract_Templates::hostpn_get_saved_template($contract_type);
$contract_html = HOSTPN_Contract_Templates::hostpn_render_contract($contract_type, $template, $accommodation_id);
$inventory_html = HOSTPN_Contract_Templates::hostpn_render_inventory($accommodation_id);

$landlordName = get_post_meta($accommodation_id, 'hostpn_contract_landlord_name', true);
$tenantName   = get_post_meta($accommodation_id, 'hostpn_contract_tenant_name', true);
?>

<div class="hostpn-contract-public-wrapper" id="hostpn-contract-public">
  <!-- Action buttons (not printed) -->
  <div class="hostpn-contract-actions no-print">
    <button type="button" class="hostpn-btn hostpn-contract-print-btn" id="hostpn-contract-print-btn">
      <i class="material-icons-outlined">print</i>
      <span><?php esc_html_e('Print contract', 'hostpn'); ?></span>
    </button>
    <button type="button" class="hostpn-btn hostpn-contract-pdf-btn" id="hostpn-contract-pdf-btn">
      <i class="material-icons-outlined">picture_as_pdf</i>
      <span><?php esc_html_e('Download PDF', 'hostpn'); ?></span>
    </button>
  </div>

  <!-- Contract content -->
  <div class="hostpn-contract-document" id="hostpn-contract-document">
    <?php echo wp_kses_post($contract_html); ?>

    <!-- Signature section -->
    <div class="hostpn-contract-signatures">
      <div class="hostpn-contract-signature-block">
        <p><strong><?php echo $contract_type === 'turistico' ? esc_html__('THE OWNER', 'hostpn') : esc_html__('THE LANDLORD', 'hostpn'); ?></strong></p>
        <div class="hostpn-signature-pad-wrapper no-print" id="hostpn-signature-landlord-wrapper">
          <canvas id="hostpn-signature-landlord" class="hostpn-signature-canvas" width="400" height="150"></canvas>
          <button type="button" class="hostpn-signature-clear-btn" data-target="hostpn-signature-landlord"><?php esc_html_e('Clear', 'hostpn'); ?></button>
        </div>
        <div class="hostpn-signature-image-placeholder" id="hostpn-signature-landlord-image"></div>
        <div class="hostpn-contract-signature-line"></div>
        <p>Fdo.: <?php echo esc_html($landlordName); ?></p>
      </div>
      <div class="hostpn-contract-signature-block">
        <p><strong><?php echo $contract_type === 'turistico' ? esc_html__('THE GUEST', 'hostpn') : esc_html__('THE TENANT', 'hostpn'); ?></strong></p>
        <div class="hostpn-signature-pad-wrapper no-print" id="hostpn-signature-tenant-wrapper">
          <canvas id="hostpn-signature-tenant" class="hostpn-signature-canvas" width="400" height="150"></canvas>
          <button type="button" class="hostpn-signature-clear-btn" data-target="hostpn-signature-tenant"><?php esc_html_e('Clear', 'hostpn'); ?></button>
        </div>
        <div class="hostpn-signature-image-placeholder" id="hostpn-signature-tenant-image"></div>
        <div class="hostpn-contract-signature-line"></div>
        <p>Fdo.: <?php echo esc_html($tenantName); ?></p>
      </div>
    </div>

    <!-- Inventory annex -->
    <?php echo wp_kses_post($inventory_html); ?>
  </div>
</div>

<?php
get_footer();

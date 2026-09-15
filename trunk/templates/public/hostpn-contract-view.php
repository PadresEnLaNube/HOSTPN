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

// Resolve optional room parameter
$room_id = isset($_GET['hostpn_contract_room']) ? absint($_GET['hostpn_contract_room']) : 0;
if ($room_id) {
  $room_acc = get_post_meta($room_id, 'hostpn_room_accommodation_id', true);
  if (intval($room_acc) !== $accommodation_id) {
    $room_id = 0;
  }
}

// Determine the site's default locale (WPLANG) for initial rendering.
// get_locale() may return a user-profile override (e.g. en_GB), so read WPLANG directly.
$site_locale = get_option('WPLANG', '');
if (empty($site_locale)) {
  $site_locale = 'en_US';
}

// Load translations directly from .l10n.php (bypasses WP 7.x x-domain validation)
if ($site_locale !== 'en_US') {
  $l10n_file = HOSTPN_DIR . 'languages/hostpn-' . $site_locale . '.l10n.php';
  if (file_exists($l10n_file)) {
    $l10n_data = @include $l10n_file;
    if (is_array($l10n_data) && !empty($l10n_data['messages'])) {
      $hostpn_messages = $l10n_data['messages'];
      add_filter('gettext', function ($translation, $text, $domain) use ($hostpn_messages) {
        if ($domain === 'hostpn' && isset($hostpn_messages[$text]) && $hostpn_messages[$text] !== '') {
          return $hostpn_messages[$text];
        }
        return $translation;
      }, 1, 3);
    }
  }
}

// Get contract type and rendered HTML via templates
$accommodation_type = get_post_meta($accommodation_id, 'hostpn_accommodation_type', true);
$contract_type = HOSTPN_Contract_Templates::hostpn_get_type_for_accommodation($accommodation_type);
$template = HOSTPN_Contract_Templates::hostpn_get_default_template($contract_type);
$contract_html = HOSTPN_Contract_Templates::hostpn_render_contract($contract_type, $template, $accommodation_id, $room_id);
$inventory_html = HOSTPN_Contract_Templates::hostpn_render_inventory($accommodation_id, $room_id);

$landlordName = get_post_meta($accommodation_id, 'hostpn_contract_landlord_name', true);
$tenantName   = get_post_meta($accommodation_id, 'hostpn_contract_tenant_name', true);

// Override tenant name from room's guest when available
if ($room_id) {
  $guest_id = get_post_meta($room_id, 'hostpn_room_guest_id', true);
  if ($guest_id && get_post($guest_id)) {
    $tenantName = trim(
      get_post_meta($guest_id, 'hostpn_name', true) . ' ' .
      get_post_meta($guest_id, 'hostpn_surname', true) . ' ' .
      get_post_meta($guest_id, 'hostpn_surname_alt', true)
    );
  }
}

// Language selector data
$available_languages = HOSTPN_Contract_Templates::hostpn_get_available_contract_languages();
$current_locale = $site_locale;
if (!isset($available_languages[$current_locale])) {
  $current_locale = 'en_US';
}

// Localize script data for AJAX
wp_localize_script('hostpn-contract-public', 'hostpnContractPublic', [
  'ajaxUrl' => admin_url('admin-ajax.php'),
  'nonce'   => wp_create_nonce('hostpn-contract-public'),
  'token'   => $token,
  'roomId'  => $room_id,
]);

// Determine signature labels
$landlord_label = $contract_type === 'turistico' ? esc_html__('THE OWNER', 'hostpn') : esc_html__('THE LANDLORD', 'hostpn');
$tenant_label   = $contract_type === 'turistico' ? esc_html__('THE GUEST', 'hostpn') : esc_html__('THE TENANT', 'hostpn');
?>

<div class="hostpn-contract-public-wrapper" id="hostpn-contract-public">
  <!-- Language selector -->
  <?php if (count($available_languages) > 1) : ?>
  <div class="hostpn-contract-actions">
    <select id="hostpn-contract-lang-select" class="hostpn-contract-lang-select">
      <?php foreach ($available_languages as $locale => $label) : ?>
        <option value="<?php echo esc_attr($locale); ?>" <?php selected($locale, $current_locale); ?>>
          <?php echo esc_html($label); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php endif; ?>

  <!-- Contract content -->
  <div class="hostpn-contract-document" id="hostpn-contract-document">
    <div id="hostpn-contract-text">
      <?php echo wp_kses_post($contract_html); ?>
    </div>

    <!-- Signature section -->
    <div class="hostpn-contract-signatures">
      <div class="hostpn-contract-signature-block">
        <p><strong data-sig-label="landlord"><?php echo $landlord_label; ?></strong></p>
        <div class="hostpn-signature-pad-wrapper no-print" id="hostpn-signature-landlord-wrapper">
          <canvas id="hostpn-signature-landlord" class="hostpn-signature-canvas" width="400" height="150"></canvas>
          <button type="button" class="hostpn-signature-clear-btn" data-target="hostpn-signature-landlord"><?php esc_html_e('Clear', 'hostpn'); ?></button>
        </div>
        <div class="hostpn-signature-image-placeholder" id="hostpn-signature-landlord-image"></div>
        <div class="hostpn-contract-signature-line"></div>
        <p>Fdo.: <?php echo esc_html($landlordName); ?></p>
      </div>
      <div class="hostpn-contract-signature-block">
        <p><strong data-sig-label="tenant"><?php echo $tenant_label; ?></strong></p>
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
    <div id="hostpn-contract-inventory">
      <?php echo wp_kses_post($inventory_html); ?>
    </div>
  </div>

  <!-- Download PDF button -->
  <div class="hostpn-contract-actions hostpn-contract-actions-bottom">
    <button type="button" class="hostpn-btn hostpn-contract-pdf-btn" id="hostpn-contract-pdf-btn">
      <i class="material-icons-outlined">picture_as_pdf</i>
      <span><?php esc_html_e('Download PDF', 'hostpn'); ?></span>
    </button>
  </div>
</div>

<?php
get_footer();

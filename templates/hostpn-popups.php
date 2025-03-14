<?php
/**
 * Provide a common footer area view for the plugin
 *
 * This file is used to markup the common footer facing aspects of the plugin.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 *
 * @package    HOSTPN
 * @subpackage HOSTPN/common/templates
 */

  if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>
<!-- PART OF TRAVELER -->
<div id="hostpn-popup-guest-add" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostpn-popup-guest-check" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostpn-popup-guest-view" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostpn-popup-guest-edit" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostpn-popup-guest-remove" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <h3 class="hostpn-text-align-center"><?php esc_html_e('Guest removal', 'hostpn'); ?></h3>
  <p class="hostpn-text-align-center"><?php esc_html_e('The guest will be completely deleted. This process cannot be reversed and the guest cannot be recovered.', 'hostpn'); ?></p>

  <div class="hostpn-display-table hostpn-width-100-percent">
    <div class="hostpn-display-inline-table hostpn-width-50-percent hostpn-text-align-center">
      <a href="#" class="hostpn-popup-close hostpn-text-decoration-none hostpn-font-size-small"><?php esc_html_e('Cancel', 'hostpn'); ?></a>
    </div>
    <div class="hostpn-display-inline-table hostpn-width-50-percent hostpn-text-align-center">
      <a href="#" class="hostpn-btn hostpn-btn-mini hostpn-guest-remove" data-hostpn-post-type="hostpn_guest"><?php esc_html_e('Remove', 'hostpn'); ?></a>
    </div>
  </div>
</div>

<!-- ACcommodation -->
<div id="hostpn-popup-accommodation-add" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostpn-popup-accommodation-check" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostpn-popup-accommodation-view" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostpn-popup-accommodation-edit" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostpn-popup-accommodation-share" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostpn-popup-accommodation-remove" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <h3 class="hostpn-text-align-center"><?php esc_html_e('Accommodation removal', 'hostpn'); ?></h3>
  <p class="hostpn-text-align-center"><?php esc_html_e('The accommodation will be completely deleted. This process cannot be reversed and the accommodation cannot be recovered.', 'hostpn'); ?></p>

  <div class="hostpn-display-table hostpn-width-100-percent">
    <div class="hostpn-display-inline-table hostpn-width-50-percent hostpn-text-align-center">
      <a href="#" class="hostpn-popup-close hostpn-text-decoration-none hostpn-font-size-small"><?php esc_html_e('Cancel', 'hostpn'); ?></a>
    </div>
    <div class="hostpn-display-inline-table hostpn-width-50-percent hostpn-text-align-center">
      <a href="#" class="hostpn-btn hostpn-btn-mini hostpn-accommodation-remove" data-hostpn-post-type="hostpn_accomm"><?php esc_html_e('Remove', 'hostpn'); ?></a>
    </div>
  </div>
</div>

<div id="hostpn-popup-accommodation-add" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<!-- HOST -->
<div id="hostpn-popup-part-add" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostpn-popup-part-check" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostpn-popup-part-view" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostpn-popup-part-edit" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostpn-popup-part-remove" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <h3 class="hostpn-text-align-center"><?php esc_html_e('part removal', 'hostpn'); ?></h3>
  <p class="hostpn-text-align-center"><?php esc_html_e('The part will be completely deleted. This process cannot be reversed and the part cannot be recovered.', 'hostpn'); ?></p>

  <div class="hostpn-display-table hostpn-width-100-percent">
    <div class="hostpn-display-inline-table hostpn-width-50-percent hostpn-text-align-center">
      <a href="#" class="hostpn-popup-close hostpn-text-decoration-none hostpn-font-size-small"><?php esc_html_e('Cancel', 'hostpn'); ?></a>
    </div>
    <div class="hostpn-display-inline-table hostpn-width-50-percent hostpn-text-align-center">
      <a href="#" class="hostpn-btn hostpn-btn-mini hostpn-part-remove" data-hostpn-post-type="hostpn_part"><?php esc_html_e('Remove', 'hostpn'); ?></a>
    </div>
  </div>
</div>

<div id="hostpn-popup-part-add" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
  <div class="hostpn-popup-content">
    <div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>
<?php
/**
 * Provide a common footer area view for the plugin
 *
 * This file is used to markup the common footer facing aspects of the plugin.
 *
 * @link       wordpress-heroes.com/
 * @since      1.0.0
 *
 * @package    HOSTWPH
 * @subpackage HOSTWPH/common/templates
 */

  if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>
<!-- PART OF TRAVELER -->
<div id="hostwph-popup-host-add" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <div class="hostwph-popup-content">
    <div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostwph-popup-host-check" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <div class="hostwph-popup-content">
    <div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostwph-popup-host-view" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <div class="hostwph-popup-content">
    <div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostwph-popup-host-edit" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <div class="hostwph-popup-content">
    <div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostwph-popup-host-remove" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <h3 class="hostwph-text-align-center"><?php esc_html_e('Host removal', 'hostwph'); ?></h3>
  <p class="hostwph-text-align-center"><?php esc_html_e('The host will be completely deleted. This process cannot be reversed and the host cannot be recovered.', 'hostwph'); ?></p>

  <div class="hostwph-display-table hostwph-width-100-percent">
    <div class="hostwph-display-inline-table hostwph-width-50-percent hostwph-text-align-center">
      <a href="#" class="hostwph-popup-close hostwph-text-decoration-none hostwph-font-size-small"><?php esc_html_e('Cancel', 'hostwph'); ?></a>
    </div>
    <div class="hostwph-display-inline-table hostwph-width-50-percent hostwph-text-align-center">
      <a href="#" class="hostwph-btn hostwph-btn-mini hostwph-guest-remove" data-hostwph-post-type="hostwph_guest"><?php esc_html_e('Remove', 'hostwph'); ?></a>
    </div>
  </div>
</div>

<!-- ACCOMODATION -->
<div id="hostwph-popup-accomodation-add" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <div class="hostwph-popup-content">
    <div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostwph-popup-accomodation-check" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <div class="hostwph-popup-content">
    <div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostwph-popup-accomodation-view" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <div class="hostwph-popup-content">
    <div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostwph-popup-accomodation-edit" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <div class="hostwph-popup-content">
    <div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostwph-popup-accomodation-remove" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <h3 class="hostwph-text-align-center"><?php esc_html_e('Accomodation removal', 'hostwph'); ?></h3>
  <p class="hostwph-text-align-center"><?php esc_html_e('The accomodation will be completely deleted. This process cannot be reversed and the accomodation cannot be recovered.', 'hostwph'); ?></p>

  <div class="hostwph-display-table hostwph-width-100-percent">
    <div class="hostwph-display-inline-table hostwph-width-50-percent hostwph-text-align-center">
      <a href="#" class="hostwph-popup-close hostwph-text-decoration-none hostwph-font-size-small"><?php esc_html_e('Cancel', 'hostwph'); ?></a>
    </div>
    <div class="hostwph-display-inline-table hostwph-width-50-percent hostwph-text-align-center">
      <a href="#" class="hostwph-btn hostwph-btn-mini hostwph-accomodation-remove" data-hostwph-post-type="hostwph_accomodation"><?php esc_html_e('Remove', 'hostwph'); ?></a>
    </div>
  </div>
</div>

<div id="hostwph-popup-accomodation-add" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <div class="hostwph-popup-content">
    <div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<!-- HOST -->
<div id="hostwph-popup-part-add" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <div class="hostwph-popup-content">
    <div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostwph-popup-part-check" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <div class="hostwph-popup-content">
    <div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostwph-popup-part-view" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <div class="hostwph-popup-content">
    <div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostwph-popup-part-edit" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <div class="hostwph-popup-content">
    <div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>

<div id="hostwph-popup-part-remove" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <h3 class="hostwph-text-align-center"><?php esc_html_e('part removal', 'hostwph'); ?></h3>
  <p class="hostwph-text-align-center"><?php esc_html_e('The part will be completely deleted. This process cannot be reversed and the part cannot be recovered.', 'hostwph'); ?></p>

  <div class="hostwph-display-table hostwph-width-100-percent">
    <div class="hostwph-display-inline-table hostwph-width-50-percent hostwph-text-align-center">
      <a href="#" class="hostwph-popup-close hostwph-text-decoration-none hostwph-font-size-small"><?php esc_html_e('Cancel', 'hostwph'); ?></a>
    </div>
    <div class="hostwph-display-inline-table hostwph-width-50-percent hostwph-text-align-center">
      <a href="#" class="hostwph-btn hostwph-btn-mini hostwph-part-remove" data-hostwph-post-type="hostwph_part"><?php esc_html_e('Remove', 'hostwph'); ?></a>
    </div>
  </div>
</div>

<div id="hostwph-popup-part-add" class="hostwph-popup hostwph-popup-size-medium hostwph-display-none-soft">
  <div class="hostwph-popup-content">
    <div class="hostwph-loader-circle-wrapper"><div class="hostwph-text-align-center"><div class="hostwph-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
  </div>
</div>
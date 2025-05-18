<?php
/**
 * Provide common popups for the plugin
 *
 * This file is used to markup the common popups of the plugin.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 *
 * @package    hostpn
 * @subpackage hostpn/common/templates
 */

  if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>
<div class="hostpn-popup-overlay hostpn-display-none-soft"></div>
<div class="hostpn-menu-more-overlay hostpn-display-none-soft"></div>

<?php foreach (HOSTPN_CPTS as $cpt => $cpt_name) : ?>
  <div id="hostpn-popup-<?php echo esc_attr($cpt); ?>-add" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
    <?php HOSTPN_Data::hostpn_popup_loader(); ?>
  </div>

  <div id="hostpn-popup-<?php echo esc_attr($cpt); ?>-check" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
    <?php HOSTPN_Data::hostpn_popup_loader(); ?>
  </div>

  <div id="hostpn-popup-<?php echo esc_attr($cpt); ?>-view" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
    <?php HOSTPN_Data::hostpn_popup_loader(); ?>
  </div>

  <div id="hostpn-popup-<?php echo esc_attr($cpt); ?>-edit" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
    <?php HOSTPN_Data::hostpn_popup_loader(); ?>
  </div>

  <div id="hostpn-popup-<?php echo esc_attr($cpt); ?>-remove" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
    <div class="hostpn-popup-content">
      <div class="hostpn-p-30">
        <h3 class="hostpn-text-align-center"><?php echo esc_html($cpt_name); ?> <?php esc_html_e('removal', 'hostpn'); ?></h3>
        <p class="hostpn-text-align-center"><?php echo esc_html($cpt_name); ?> <?php esc_html_e('will be completely deleted. This process cannot be reversed and cannot be recovered.', 'hostpn'); ?></p>

        <div class="hostpn-display-table hostpn-width-100-percent">
          <div class="hostpn-display-inline-table hostpn-width-50-percent hostpn-text-align-center">
            <a href="#" class="hostpn-popup-close hostpn-text-decoration-none hostpn-font-size-small"><?php esc_html_e('Cancel', 'hostpn'); ?></a>
          </div>
          <div class="hostpn-display-inline-table hostpn-width-50-percent hostpn-text-align-center">
            <a href="#" class="hostpn-btn hostpn-btn-mini hostpn-<?php echo esc_attr($cpt); ?>-remove" data-hostpn-post-type="hostpn_<?php echo esc_attr($cpt); ?>"><?php esc_html_e('Remove', 'hostpn'); ?> <?php echo esc_html($cpt_name); ?></a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php if ($cpt == 'accommodation') : ?>
    <div id="hostpn-popup-accommodation-share" class="hostpn-popup hostpn-popup-size-medium hostpn-display-none-soft">
      <?php HOSTPN_Data::hostpn_popup_loader(); ?>
    </div>
  <?php endif; ?>
<?php endforeach; ?>
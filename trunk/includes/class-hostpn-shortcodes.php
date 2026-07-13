<?php
/**
 * Platform shortcodes.
 *
 * This class defines all shortcodes of the platform.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Shortcodes {    
  /**
   * Manage the shortcodes in the platform.
   *
   * @since    1.0.0
   */
  public function hostpn_navigation() {
    global $post;
    $post_id = $post->ID ?? 0;
    $hostpn_pages = get_option('hostpn_pages') ?? [];
    $hostpn_page_accommodation = !empty(get_option('hostpn_pages_accommodation')) ? get_option('hostpn_pages_accommodation') : url_to_postid(home_url());
    $hostpn_page_part = !empty(get_option('hostpn_pages_part')) ? get_option('hostpn_pages_part') : url_to_postid(home_url());
    $hostpn_page_host = !empty(get_option('hostpn_pages_guest')) ? get_option('hostpn_pages_guest') : url_to_postid(home_url());
    
    ob_start();
    if (HOSTPN_Functions_User::is_user_admin(get_current_user_id()) && !is_admin() && !defined('DOING_AJAX')) {
      if (in_array($post_id, $hostpn_pages)) {
        ?>
          <div class="hostpn-navigation">
            <div class="hostpn-display-table hostpn-width-100-percent">
              <div class="hostpn-display-inline-table hostpn-width-33-percent hostpn-img-hover-zoom hostpn-tooltip" title="<?php echo esc_html(__('Accommodations', 'hostpn')); ?>">
                <a href="<?php echo esc_url(get_permalink(HOSTPN_i18n::hostpn_get_post($hostpn_page_accommodation))); ?>">
                  <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-50 <?php echo ($post_id == $hostpn_page_accommodation) ? 'hostpn-color-main-0' : ''; ?>">hotel</i>
                </a>
              </div>
              <div class="hostpn-display-inline-table hostpn-width-33-percent hostpn-img-hover-zoom hostpn-tooltip" title="<?php echo esc_html(__('Parts of travelers', 'hostpn')); ?>">
                <a href="<?php echo esc_url(get_permalink(HOSTPN_i18n::hostpn_get_post($hostpn_page_part))); ?>">
                  <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-50 <?php echo ($post_id == $hostpn_page_part) ? 'hostpn-color-main-0' : ''; ?>">luggage</i>
                </a>
              </div>
              <div class="hostpn-display-inline-table hostpn-width-33-percent hostpn-img-hover-zoom hostpn-tooltip" title="<?php echo esc_html(__('Guests', 'hostpn')); ?>">
                <a href="<?php echo esc_url(get_permalink(HOSTPN_i18n::hostpn_get_post($hostpn_page_host))); ?>">
                  <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-50 <?php echo ($post_id == $hostpn_page_host) ? 'hostpn-color-main-0' : ''; ?>">account_circle</i>
                </a>
              </div>
            </div>
          </div>
        <?php
      }
    }
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_call_to_action($atts) {
    // echo do_shortcode('[hostpn-call-to-action hostpn_call_to_action_icon="error_outline" hostpn_call_to_action_title="' . esc_html(__('Default title', 'hostpn')) . '" hostpn_call_to_action_content="' . esc_html(__('Default content', 'hostpn')) . '" hostpn_call_to_action_button_link="#" hostpn_call_to_action_button_text="' . esc_html(__('Button text', 'hostpn')) . '" hostpn_call_to_action_button_class="hostpn-class"]');
    $a = extract(shortcode_atts(array(
      'hostpn_call_to_action_class' => '',
      'hostpn_call_to_action_icon' => '',
      'hostpn_call_to_action_title' => '',
      'hostpn_call_to_action_content' => '',
      'hostpn_call_to_action_button_link' => '#',
      'hostpn_call_to_action_button_text' => '',
      'hostpn_call_to_action_button_class' => '',
      'hostpn_call_to_action_button_data_key' => '',
      'hostpn_call_to_action_button_data_value' => '',
      'hostpn_call_to_action_button_blank' => 0,
    ), $atts));

    ob_start();
    ?>
      <div class="hostpn-call-to-action hostpn-text-align-center hostpn-pt-30 hostpn-pb-50 <?php echo esc_attr($hostpn_call_to_action_class); ?>">
        <div class="hostpn-call-to-action-icon">
          <i class="material-icons-outlined hostpn-font-size-75 hostpn-color-main-0"><?php echo esc_html($hostpn_call_to_action_icon); ?></i>
        </div>

        <h4 class="hostpn-call-to-action-title hostpn-text-align-center hostpn-mt-10 hostpn-mb-20"><?php echo esc_html($hostpn_call_to_action_title); ?></h4>

        <?php if (!empty($hostpn_call_to_action_content)): ?>
          <p class="hostpn-text-align-center"><?php echo wp_kses_post($hostpn_call_to_action_content); ?></p>
        <?php endif ?>

        <?php if (!empty($hostpn_call_to_action_button_text)): ?>
          <div class="hostpn-text-align-center hostpn-mt-20">
            <a class="hostpn-btn hostpn-btn-transparent hostpn-margin-auto <?php echo esc_attr($hostpn_call_to_action_button_class); ?>" <?php echo ($hostpn_call_to_action_button_blank) ? 'target="_blank"' : ''; ?> href="<?php echo esc_url($hostpn_call_to_action_button_link); ?>" <?php echo (!empty($hostpn_call_to_action_button_data_key) && !empty($hostpn_call_to_action_button_data_value)) ? esc_attr($hostpn_call_to_action_button_data_key) . '="' . esc_attr($hostpn_call_to_action_button_data_value) . '"' : ''; ?>><?php echo esc_html($hostpn_call_to_action_button_text); ?></a>
          </div>
        <?php endif ?>
      </div>
    <?php
    $hostpn_return_string = ob_get_contents();
    ob_end_clean();
    return $hostpn_return_string;
  }

  /**
   * Carousel shortcode
   *
   * Usage: [hostpn-carousel post_id="123" show_nav="true" show_dots="true" show_counter="false" show_autoplay="false" autoplay="false" autoplay_speed="5000" speed="500"]
   *
   * @param array $atts Shortcode attributes
   * @return string HTML output
   */
  public function hostpn_carousel($atts) {
    $a = extract(shortcode_atts(array(
      'post_id' => get_the_ID(),
      'show_nav' => 'true',
      'show_dots' => 'true',
      'show_counter' => 'false',
      'show_autoplay' => 'false',
      'autoplay' => 'false',
      'autoplay_speed' => '5000',
      'speed' => '500',
      'loop' => 'true',
      'items_desktop' => '1',
      'items_mobile' => '1',
      'class' => ''
    ), $atts));

    $post_id = intval($post_id);
    if (!$post_id) {
      return '';
    }

    // Get gallery images
    $gallery_ids = get_post_meta($post_id, 'hostpn_accommodation_gallery', true);
    if (empty($gallery_ids)) {
      return '';
    }

    $gallery_ids = explode(',', $gallery_ids);

    // Convert string booleans to actual booleans
    $show_nav_bool = filter_var($show_nav, FILTER_VALIDATE_BOOLEAN);
    $show_dots_bool = filter_var($show_dots, FILTER_VALIDATE_BOOLEAN);
    $show_counter_bool = filter_var($show_counter, FILTER_VALIDATE_BOOLEAN);
    $show_autoplay_bool = filter_var($show_autoplay, FILTER_VALIDATE_BOOLEAN);
    $autoplay_bool = filter_var($autoplay, FILTER_VALIDATE_BOOLEAN);
    $loop_bool = filter_var($loop, FILTER_VALIDATE_BOOLEAN);

    ob_start();
    ?>
    <div class="hostpn-carousel <?php echo esc_attr($class); ?>"
         data-auto-init="true"
         data-autoplay="<?php echo $autoplay_bool ? 'true' : 'false'; ?>"
         data-autoplay-speed="<?php echo esc_attr($autoplay_speed); ?>"
         data-speed="<?php echo esc_attr($speed); ?>"
         data-loop="<?php echo $loop_bool ? 'true' : 'false'; ?>"
         data-dots="<?php echo $show_dots_bool ? 'true' : 'false'; ?>"
         data-nav="<?php echo $show_nav_bool ? 'true' : 'false'; ?>"
         data-counter="<?php echo $show_counter_bool ? 'true' : 'false'; ?>"
         data-items-desktop="<?php echo esc_attr($items_desktop); ?>"
         data-items-mobile="<?php echo esc_attr($items_mobile); ?>">
      <?php foreach ($gallery_ids as $image_id): ?>
        <?php
        $image_id = intval($image_id);
        if (!$image_id) continue;

        $image_url = wp_get_attachment_image_url($image_id, 'large');
        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        ?>
        <div>
          <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
        </div>
      <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
  }
}
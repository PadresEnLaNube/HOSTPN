<?php
/**
 * Platform shortcodes.
 *
 * This class defines all shortcodes of the platform.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@padresenlanube.com>
 */
class HOSTWPH_Shortcodes {
  /**
   * Manage the shortcodes in the platform.
   *
   * @since    1.0.0
   */
  public function hostwph_navigation() {
    global $post;
    $post_id = $post->ID ?? 0;
    $hostwph_pages = get_option('hostwph_pages') ?? [];
    $hostwph_page_accommodation = !empty(get_option('hostwph_pages_accommodation')) ? get_option('hostwph_pages_accommodation') : url_to_postid(home_url());
    $hostwph_page_part = !empty(get_option('hostwph_pages_part')) ? get_option('hostwph_pages_part') : url_to_postid(home_url());
    $hostwph_page_host = !empty(get_option('hostwph_pages_guest')) ? get_option('hostwph_pages_guest') : url_to_postid(home_url());
    
    ob_start();
    if (HOSTWPH_Functions_User::is_user_admin(get_current_user_id())) {
      if (in_array($post_id, $hostwph_pages)) {
        ?>
          <div class="hostwph-navigation">
            <div class="hostwph-display-table hostwph-width-100-percent">
              <div class="hostwph-display-inline-table hostwph-width-33-percent hostwph-img-hover-zoom">
                <a href="<?php echo esc_url(get_permalink($hostwph_page_accommodation)) ?>">
                  <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-50 <?php echo ($post_id == $hostwph_page_accommodation) ? 'hostwph-color-main-0' : ''; ?>">hotel</i>
                </a>
              </div>
              <div class="hostwph-display-inline-table hostwph-width-33-percent hostwph-img-hover-zoom">
                <a href="<?php echo esc_url(get_permalink($hostwph_page_part)) ?>">
                  <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-50 <?php echo ($post_id == $hostwph_page_part) ? 'hostwph-color-main-0' : ''; ?>">luggage</i>
                </a>
              </div>
              <div class="hostwph-display-inline-table hostwph-width-33-percent hostwph-img-hover-zoom">
                <a href="<?php echo esc_url(get_permalink($hostwph_page_host)) ?>">
                  <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-50 <?php echo ($post_id == $hostwph_page_host) ? 'hostwph-color-main-0' : ''; ?>">account_circle</i>
                </a>
              </div>
            </div>
          </div>
        <?php
      }
    }
    $wph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $wph_return_string;
  }

  public function hostwph_call_to_action($atts) {
    // echo do_shortcode('[hostwph-call-to-action hostwph_call_to_action_icon="error_outline" hostwph_call_to_action_title="' . esc_html(__('Default title', 'hostwph')) . '" hostwph_call_to_action_content="' . esc_html(__('Default content', 'hostwph')) . '" hostwph_call_to_action_button_link="#" hostwph_call_to_action_button_text="' . esc_html(__('Button text', 'hostwph')) . '" hostwph_call_to_action_button_class="hostwph-class"]');
    $a = extract(shortcode_atts(array(
      'hostwph_call_to_action_class' => '',
      'hostwph_call_to_action_icon' => '',
      'hostwph_call_to_action_title' => '',
      'hostwph_call_to_action_content' => '',
      'hostwph_call_to_action_button_link' => '#',
      'hostwph_call_to_action_button_text' => '',
      'hostwph_call_to_action_button_class' => '',
      'hostwph_call_to_action_button_data_key' => '',
      'hostwph_call_to_action_button_data_value' => '',
      'hostwph_call_to_action_button_blank' => 0,
    ), $atts));

    ob_start();
    ?>
      <div class="hostwph-call-to-action hostwph-text-align-center hostwph-pt-30 hostwph-pb-50 <?php echo $hostwph_call_to_action_class; ?>">
        <div class="hostwph-call-to-action-icon">
          <i class="material-icons-outlined hostwph-font-size-75 hostwph-color-main-0"><?php echo $hostwph_call_to_action_icon; ?></i>
        </div>

        <h4 class="hostwph-call-to-action-title hostwph-text-align-center hostwph-mt-10 hostwph-mb-20"><?php echo $hostwph_call_to_action_title; ?></h4>
        
        <?php if (!empty($hostwph_call_to_action_content)): ?>
          <p class="hostwph-text-align-center"><?php echo $hostwph_call_to_action_content; ?></p>
        <?php endif ?>

        <?php if (!empty($hostwph_call_to_action_button_text)): ?>
          <div class="hostwph-text-align-center hostwph-mt-20">
            <a class="hostwph-btn hostwph-btn-transparent wph-margin-auto <?php echo $hostwph_call_to_action_button_class; ?>" <?php echo ($hostwph_call_to_action_button_blank) ? 'target="_blank"' : ''; ?> href="<?php echo $hostwph_call_to_action_button_link; ?>" <?php echo (!empty($hostwph_call_to_action_button_data_key) && !empty($hostwph_call_to_action_button_data_value)) ? $hostwph_call_to_action_button_data_key . '="' . $hostwph_call_to_action_button_data_value . '"' : ''; ?>><?php echo $hostwph_call_to_action_button_text; ?></a>
          </div>
        <?php endif ?>
      </div>
    <?php 
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }
}
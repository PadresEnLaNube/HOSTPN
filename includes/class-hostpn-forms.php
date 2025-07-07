<?php
/**
 * Fired from activate() function.
 *
 * This class defines all post types necessary to run during the plugin's life cycle.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Forms {
  /**
   * Plaform forms.
   *
   * @since    1.0.0
   */

  /**
   * Get the current value of a field based on its type and storage
   * 
   * @param string $field_id The field ID
   * @param string $hostpn_type The type of field (user, post, option)
   * @param int $hostpn_id The ID of the user/post/option
   * @param int $hostpn_meta_array Whether the field is part of a meta array
   * @param int $hostpn_array_index The index in the meta array
   * @param array $hostpn_input The input array containing field configuration
   * @return mixed The current value of the field
   */
  private static function hostpn_get_field_value($field_id, $hostpn_type, $hostpn_id = 0, $hostpn_meta_array = 0, $hostpn_array_index = 0, $hostpn_input = []) {
    $current_value = '';

    if ($hostpn_meta_array) {
      switch ($hostpn_type) {
        case 'user':
          $meta = get_user_meta($hostpn_id, $field_id, true);
          if (is_array($meta) && isset($meta[$hostpn_array_index])) {
            $current_value = $meta[$hostpn_array_index];
          }
          break;
        case 'post':
          $meta = get_post_meta($hostpn_id, $field_id, true);
          if (is_array($meta) && isset($meta[$hostpn_array_index])) {
            $current_value = $meta[$hostpn_array_index];
          }
          break;
        case 'option':
          $option = get_option($field_id);
          if (is_array($option) && isset($option[$hostpn_array_index])) {
            $current_value = $option[$hostpn_array_index];
          }
          break;
      }
    } else {
      switch ($hostpn_type) {
        case 'user':
          $current_value = get_user_meta($hostpn_id, $field_id, true);
          break;
        case 'post':
          $current_value = get_post_meta($hostpn_id, $field_id, true);
          break;
        case 'option':
          $current_value = get_option($field_id);
          break;
      }
    }

    // If no value is found and there's a default value in the input config, use it
    if (empty($current_value) && !empty($hostpn_input['value'])) {
      $current_value = $hostpn_input['value'];
    }

    return $current_value;
  }

  public static function hostpn_input_builder($hostpn_input, $hostpn_type, $hostpn_id = 0, $disabled = 0, $hostpn_meta_array = 0, $hostpn_array_index = 0) {
    // Get the current value using the new function
    $hostpn_value = self::hostpn_get_field_value($hostpn_input['id'], $hostpn_type, $hostpn_id, $hostpn_meta_array, $hostpn_array_index, $hostpn_input);

    $hostpn_parent_block = (!empty($hostpn_input['parent']) ? 'data-hostpn-parent="' . $hostpn_input['parent'] . '"' : '') . ' ' . (!empty($hostpn_input['parent_option']) ? 'data-hostpn-parent-option="' . $hostpn_input['parent_option'] . '"' : '');

    switch ($hostpn_input['input']) {
      case 'input':        
        switch ($hostpn_input['type']) {
          case 'file':
            ?>
              <?php if (empty($hostpn_value)): ?>
                <p class="hostpn-m-10"><?php esc_html_e('No file found', 'hostpn'); ?></p>
              <?php else: ?>
                <p class="hostpn-m-10">
                  <a href="<?php echo esc_url(get_post_meta($hostpn_id, $hostpn_input['id'], true)['url']); ?>" target="_blank"><?php echo esc_html(basename(get_post_meta($hostpn_id, $hostpn_input['id'], true)['url'])); ?></a>
                </p>
              <?php endif ?>
            <?php
            break;
          case 'checkbox':
            ?>
              <label class="hostpn-switch">
                <input id="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? '[]' : ''); ?>" name="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? '[]' : ''); ?>" class="<?php echo array_key_exists('class', $hostpn_input) ? esc_attr($hostpn_input['class']) : ''; ?> hostpn-checkbox hostpn-checkbox-switch hostpn-field" type="<?php echo esc_attr($hostpn_input['type']); ?>" <?php echo $hostpn_value == 'on' ? 'checked="checked"' : ''; ?> <?php echo (((array_key_exists('disabled', $hostpn_input) && $hostpn_input['disabled'] == 'true') || $disabled) ? 'disabled' : ''); ?> <?php echo ((array_key_exists('required', $hostpn_input) && $hostpn_input['required'] == true) ? 'required' : ''); ?> <?php echo wp_kses_post($hostpn_parent_block); ?>>
                <span class="hostpn-slider hostpn-round"></span>
              </label>
            <?php
            break;
          case 'radio':
            ?>
              <div class="hostpn-input-radio-wrapper">
                <?php if (!empty($hostpn_input['radio_options'])): ?>
                  <?php foreach ($hostpn_input['radio_options'] as $radio_option): ?>
                    <div class="hostpn-input-radio-item">
                      <label for="<?php echo esc_attr($radio_option['id']); ?>">
                        <?php echo wp_kses_post(wp_specialchars_decode($radio_option['label'])); ?>
                        
                        <input type="<?php echo esc_attr($hostpn_input['type']); ?>"
                          id="<?php echo esc_attr($radio_option['id']); ?>"
                          name="<?php echo esc_attr($hostpn_input['id']); ?>"
                          value="<?php echo esc_attr($radio_option['value']); ?>"
                          <?php echo $hostpn_value == $radio_option['value'] ? 'checked="checked"' : ''; ?>
                          <?php echo ((array_key_exists('required', $hostpn_input) && $hostpn_input['required'] == 'true') ? 'required' : ''); ?>>

                        <div class="hostpn-radio-control"></div>
                      </label>
                    </div>
                  <?php endforeach ?>
                <?php endif ?>
              </div>
            <?php
            break;
          case 'range':
            ?>
              <div class="hostpn-input-range-wrapper">
                <div class="hostpn-width-100-percent">
                  <?php if (!empty($hostpn_input['hostpn_label_min'])): ?>
                    <p class="hostpn-input-range-label-min"><?php echo esc_html($hostpn_input['hostpn_label_min']); ?></p>
                  <?php endif ?>

                  <?php if (!empty($hostpn_input['hostpn_label_max'])): ?>
                    <p class="hostpn-input-range-label-max"><?php echo esc_html($hostpn_input['hostpn_label_max']); ?></p>
                  <?php endif ?>
                </div>

                <input type="<?php echo esc_attr($hostpn_input['type']); ?>" id="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? '[]' : ''); ?>" name="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? '[]' : ''); ?>" class="hostpn-input-range <?php echo array_key_exists('class', $hostpn_input) ? esc_attr($hostpn_input['class']) : ''; ?>" <?php echo ((array_key_exists('required', $hostpn_input) && $hostpn_input['required'] == true) ? 'required' : ''); ?> <?php echo (((array_key_exists('disabled', $hostpn_input) && $hostpn_input['disabled'] == 'true') || $disabled) ? 'disabled' : ''); ?> <?php echo (isset($hostpn_input['hostpn_max']) ? 'max=' . esc_attr($hostpn_input['hostpn_max']) : ''); ?> <?php echo (isset($hostpn_input['hostpn_min']) ? 'min=' . esc_attr($hostpn_input['hostpn_min']) : ''); ?> <?php echo (((array_key_exists('step', $hostpn_input) && $hostpn_input['step'] != '')) ? 'step="' . esc_attr($hostpn_input['step']) . '"' : ''); ?> <?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple'] ? 'multiple' : ''); ?> value="<?php echo (!empty($hostpn_input['button_text']) ? esc_html($hostpn_input['button_text']) : esc_html($hostpn_value)); ?>"/>
                <h3 class="hostpn-input-range-output"></h3>
              </div>
            <?php
            break;
          case 'stars':
            $hostpn_stars = !empty($hostpn_input['stars_number']) ? $hostpn_input['stars_number'] : 5;
            ?>
              <div class="hostpn-input-stars-wrapper">
                <div class="hostpn-width-100-percent">
                  <?php if (!empty($hostpn_input['hostpn_label_min'])): ?>
                    <p class="hostpn-input-stars-label-min"><?php echo esc_html($hostpn_input['hostpn_label_min']); ?></p>
                  <?php endif ?>

                  <?php if (!empty($hostpn_input['hostpn_label_max'])): ?>
                    <p class="hostpn-input-stars-label-max"><?php echo esc_html($hostpn_input['hostpn_label_max']); ?></p>
                  <?php endif ?>
                </div>

                <div class="hostpn-input-stars hostpn-text-align-center hostpn-pt-20">
                  <?php foreach (range(1, $hostpn_stars) as $index => $star): ?>
                    <i class="material-icons-outlined hostpn-input-star">star_outlined</i>
                  <?php endforeach ?>
                </div>

                <input type="number" <?php echo ((array_key_exists('required', $hostpn_input) && $hostpn_input['required'] == true) ? 'required' : ''); ?> <?php echo ((array_key_exists('disabled', $hostpn_input) && $hostpn_input['disabled'] == 'true') ? 'disabled' : ''); ?> id="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? '[]' : ''); ?>" name="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? '[]' : ''); ?>" class="hostpn-input-hidden-stars <?php echo array_key_exists('class', $hostpn_input) ? esc_attr($hostpn_input['class']) : ''; ?>" min="1" max="<?php echo esc_attr($hostpn_stars) ?>">
              </div>
            <?php
            break;
          case 'submit':
            ?>
              <div class="hostpn-text-align-right">
                <input type="submit" value="<?php echo esc_attr($hostpn_input['value']); ?>" name="<?php echo esc_attr($hostpn_input['id']); ?>" id="<?php echo esc_attr($hostpn_input['id']); ?>" name="<?php echo esc_attr($hostpn_input['id']); ?>" class="hostpn-btn" data-hostpn-type="<?php echo esc_attr($hostpn_type); ?>" data-hostpn-subtype="<?php echo ((array_key_exists('subtype', $hostpn_input)) ? esc_attr($hostpn_input['subtype']) : ''); ?>" data-hostpn-user-id="<?php echo esc_attr($hostpn_id); ?>" data-hostpn-post-id="<?php echo !empty(get_the_ID()) ? esc_attr(get_the_ID()) : ''; ?>"/><?php esc_html(HOSTPN_Data::hostpn_loader()); ?>
              </div>
            <?php
            break;
          case 'hidden':
            ?>
              <input type="hidden" id="<?php echo esc_attr($hostpn_input['id']); ?>" name="<?php echo esc_attr($hostpn_input['id']); ?>" value="<?php echo esc_attr($hostpn_value); ?>" <?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple'] == 'true' ? 'multiple' : ''); ?>>
            <?php
            break;
          case 'nonce':
            ?>
              <input type="hidden" id="<?php echo esc_attr($hostpn_input['id']); ?>" name="<?php echo esc_attr($hostpn_input['id']); ?>" value="<?php echo esc_attr(wp_create_nonce('hostpn-nonce')); ?>">
            <?php
            break;
          case 'password':
            ?>
              <div class="hostpn-password-checker">
                <div class="hostpn-password-input hostpn-position-relative">
                  <input id="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple'] == 'true') ? '[]' : ''); ?>" name="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple'] == 'true') ? '[]' : ''); ?>" <?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple'] == 'true' ? 'multiple' : ''); ?> class="hostpn-field hostpn-password-strength <?php echo array_key_exists('class', $hostpn_input) ? esc_attr($hostpn_input['class']) : ''; ?>" type="<?php echo esc_attr($hostpn_input['type']); ?>" <?php echo ((array_key_exists('required', $hostpn_input) && $hostpn_input['required'] == 'true') ? 'required' : ''); ?> <?php echo ((array_key_exists('disabled', $hostpn_input) && $hostpn_input['disabled'] == 'true') ? 'disabled' : ''); ?> value="<?php echo (!empty($hostpn_input['button_text']) ? esc_html($hostpn_input['button_text']) : esc_attr($hostpn_value)); ?>" placeholder="<?php echo (array_key_exists('placeholder', $hostpn_input) ? esc_attr($hostpn_input['placeholder']) : ''); ?>" <?php echo wp_kses_post($hostpn_parent_block); ?>/>

                  <a href="#" class="hostpn-show-pass hostpn-cursor-pointer hostpn-display-none-soft">
                    <i class="material-icons-outlined hostpn-font-size-20">visibility</i>
                  </a>
                </div>

                <div id="hostpn-popover-pass" class="hostpn-display-none-soft">
                  <div class="hostpn-progress-bar-wrapper">
                    <div class="hostpn-password-strength-bar"></div>
                  </div>

                  <h3 class="hostpn-mt-20"><?php esc_html_e('Password strength checker', 'hostpn'); ?> <i class="material-icons-outlined hostpn-cursor-pointer hostpn-close-icon hostpn-mt-30">close</i></h3>
                  <ul class="hostpn-list-style-none">
                    <li class="low-upper-case">
                      <i class="material-icons-outlined hostpn-font-size-20 hostpn-vertical-align-middle">radio_button_unchecked</i>
                      <span><?php esc_html_e('Lowercase & Uppercase', 'hostpn'); ?></span>
                    </li>
                    <li class="one-number">
                      <i class="material-icons-outlined hostpn-font-size-20 hostpn-vertical-align-middle">radio_button_unchecked</i>
                      <span><?php esc_html_e('Number (0-9)', 'hostpn'); ?></span>
                    </li>
                    <li class="one-special-char">
                      <i class="material-icons-outlined hostpn-font-size-20 hostpn-vertical-align-middle">radio_button_unchecked</i>
                      <span><?php esc_html_e('Special Character (!@#$%^&*)', 'hostpn'); ?></span>
                    </li>
                    <li class="eight-character">
                      <i class="material-icons-outlined hostpn-font-size-20 hostpn-vertical-align-middle">radio_button_unchecked</i>
                      <span><?php esc_html_e('Atleast 8 Character', 'hostpn'); ?></span>
                    </li>
                  </ul>
                </div>
              </div>
            <?php
            break;
          case 'color':
            ?>
              <input id="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? '[]' : ''); ?>" name="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? '[]' : ''); ?>" <?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple'] ? 'multiple' : ''); ?> class="hostpn-field <?php echo array_key_exists('class', $hostpn_input) ? esc_attr($hostpn_input['class']) : ''; ?>" type="<?php echo esc_attr($hostpn_input['type']); ?>" <?php echo ((array_key_exists('required', $hostpn_input) && $hostpn_input['required'] == true) ? 'required' : ''); ?> <?php echo (((array_key_exists('disabled', $hostpn_input) && $hostpn_input['disabled'] == 'true') || $disabled) ? 'disabled' : ''); ?> value="<?php echo (!empty($hostpn_value) ? esc_attr($hostpn_value) : '#000000'); ?>" placeholder="<?php echo (array_key_exists('placeholder', $hostpn_input) ? esc_attr($hostpn_input['placeholder']) : ''); ?>" <?php echo wp_kses_post($hostpn_parent_block); ?>/>
            <?php
            break;
          default:
            ?>
              <input 
                <?php /* ID and name attributes */ ?>
                id="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? '[]' : ''); ?>" 
                name="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? '[]' : ''); ?>"
                
                <?php /* Type and styling */ ?>
                class="hostpn-field <?php echo array_key_exists('class', $hostpn_input) ? esc_attr($hostpn_input['class']) : ''; ?>" 
                type="<?php echo esc_attr($hostpn_input['type']); ?>"
                
                <?php /* State attributes */ ?>
                <?php echo ((array_key_exists('required', $hostpn_input) && $hostpn_input['required'] == true) ? 'required' : ''); ?>
                <?php echo (((array_key_exists('disabled', $hostpn_input) && $hostpn_input['disabled'] == 'true') || $disabled) ? 'disabled' : ''); ?>
                <?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple'] ? 'multiple' : ''); ?>
                
                <?php /* Validation and limits */ ?>
                <?php echo (((array_key_exists('step', $hostpn_input) && $hostpn_input['step'] != '')) ? 'step="' . esc_attr($hostpn_input['step']) . '"' : ''); ?>
                <?php echo (isset($hostpn_input['max']) ? 'max="' . esc_attr($hostpn_input['max']) . '"' : ''); ?>
                <?php echo (isset($hostpn_input['min']) ? 'min="' . esc_attr($hostpn_input['min']) . '"' : ''); ?>
                <?php echo (isset($hostpn_input['maxlength']) ? 'maxlength="' . esc_attr($hostpn_input['maxlength']) . '"' : ''); ?>
                <?php echo (isset($hostpn_input['pattern']) ? 'pattern="' . esc_attr($hostpn_input['pattern']) . '"' : ''); ?>
                
                <?php /* Content attributes */ ?>
                value="<?php echo (!empty($hostpn_input['button_text']) ? esc_html($hostpn_input['button_text']) : esc_html($hostpn_value)); ?>"
                placeholder="<?php echo (array_key_exists('placeholder', $hostpn_input) ? esc_html($hostpn_input['placeholder']) : ''); ?>"
                
                <?php /* Custom data attributes */ ?>
                <?php echo wp_kses_post($hostpn_parent_block); ?>
              />
            <?php
            break;
        }
        break;
      case 'select':
        if (!empty($hostpn_input['options']) && is_array($hostpn_input['options'])) {
          ?>
          <select 
            id="<?php echo esc_attr($hostpn_input['id']); ?>" 
            name="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? '[]' : ''); ?>" 
            class="hostpn-field <?php echo array_key_exists('class', $hostpn_input) ? esc_attr($hostpn_input['class']) : ''; ?>"
            <?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? 'multiple' : ''; ?>
            <?php echo ((array_key_exists('required', $hostpn_input) && $hostpn_input['required'] == true) ? 'required' : ''); ?>
            <?php echo (((array_key_exists('disabled', $hostpn_input) && $hostpn_input['disabled'] == 'true') || $disabled) ? 'disabled' : ''); ?>
            <?php echo wp_kses_post($hostpn_parent_block); ?>
          >
            <?php if (array_key_exists('placeholder', $hostpn_input) && !empty($hostpn_input['placeholder'])): ?>
              <option value=""><?php echo esc_html($hostpn_input['placeholder']); ?></option>
            <?php endif; ?>
            
            <?php 
            $selected_values = array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple'] ? 
              (is_array($hostpn_value) ? $hostpn_value : array()) : 
              array($hostpn_value);
            
            foreach ($hostpn_input['options'] as $value => $label): 
              $is_selected = in_array($value, $selected_values);
            ?>
              <option 
                value="<?php echo esc_attr($value); ?>"
                <?php echo $is_selected ? 'selected="selected"' : ''; ?>
              >
                <?php echo esc_html($label); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php
        }
        break;
      case 'textarea':
        ?>
          <textarea id="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? '[]' : ''); ?>" name="<?php echo esc_attr($hostpn_input['id']) . ((array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? '[]' : ''); ?>" <?php echo wp_kses_post($hostpn_parent_block); ?> class="hostpn-field <?php echo array_key_exists('class', $hostpn_input) ? esc_attr($hostpn_input['class']) : ''; ?>" <?php echo ((array_key_exists('required', $hostpn_input) && $hostpn_input['required'] == true) ? 'required' : ''); ?> <?php echo (((array_key_exists('disabled', $hostpn_input) && $hostpn_input['disabled'] == 'true') || $disabled) ? 'disabled' : ''); ?> <?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple'] ? 'multiple' : ''); ?> placeholder="<?php echo (array_key_exists('placeholder', $hostpn_input) ? esc_attr($hostpn_input['placeholder']) : ''); ?>"><?php echo esc_html($hostpn_value); ?></textarea>
        <?php
        break;
      case 'image':
        ?>
          <div class="hostpn-field hostpn-images-block" <?php echo wp_kses_post($hostpn_parent_block); ?> data-hostpn-multiple="<?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? 'true' : 'false'; ?>">
            <?php if (!empty($hostpn_value)): ?>
              <div class="hostpn-images">
                <?php foreach (explode(',', $hostpn_value) as $hostpn_image): ?>
                  <?php echo wp_get_attachment_image($hostpn_image, 'medium'); ?>
                <?php endforeach ?>
              </div>

              <div class="hostpn-text-align-center hostpn-position-relative"><a href="#" class="hostpn-btn hostpn-btn-mini hostpn-image-btn"><?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? esc_html(__('Edit images', 'hostpn')) : esc_html(__('Edit image', 'hostpn')); ?></a></div>
            <?php else: ?>
              <div class="hostpn-images"></div>

              <div class="hostpn-text-align-center hostpn-position-relative"><a href="#" class="hostpn-btn hostpn-btn-mini hostpn-image-btn"><?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? esc_html(__('Add images', 'hostpn')) : esc_html(__('Add image', 'hostpn')); ?></a></div>
            <?php endif ?>

            <input id="<?php echo esc_attr($hostpn_input['id']); ?>" name="<?php echo esc_attr($hostpn_input['id']); ?>" class="hostpn-display-none hostpn-image-input" type="text" value="<?php echo esc_attr($hostpn_value); ?>"/>
          </div>
        <?php
        break;
      case 'video':
        ?>
        <div class="hostpn-field hostpn-videos-block" <?php echo wp_kses_post($hostpn_parent_block); ?>>
            <?php if (!empty($hostpn_value)): ?>
              <div class="hostpn-videos">
                <?php foreach (explode(',', $hostpn_value) as $hostpn_video): ?>
                  <div class="hostpn-video hostpn-tooltip" title="<?php echo esc_html(get_the_title($hostpn_video)); ?>"><i class="dashicons dashicons-media-video"></i></div>
                <?php endforeach ?>
              </div>

              <div class="hostpn-text-align-center hostpn-position-relative"><a href="#" class="hostpn-btn hostpn-video-btn"><?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? esc_html(__('Edit videos', 'hostpn')) : esc_html(__('Edit video', 'hostpn')); ?></a></div>
            <?php else: ?>
              <div class="hostpn-videos"></div>

              <div class="hostpn-text-align-center hostpn-position-relative"><a href="#" class="hostpn-btn hostpn-video-btn"><?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? esc_html(__('Add videos', 'hostpn')) : esc_html(__('Add video', 'hostpn')); ?></a></div>
            <?php endif ?>

            <input id="<?php echo esc_attr($hostpn_input['id']); ?>" name="<?php echo esc_attr($hostpn_input['id']); ?>" class="hostpn-display-none hostpn-video-input" type="text" value="<?php echo esc_attr($hostpn_value); ?>"/>
          </div>
        <?php
        break;
      case 'audio':
        ?>
          <div class="hostpn-field hostpn-audios-block" <?php echo wp_kses_post($hostpn_parent_block); ?>>
            <?php if (!empty($hostpn_value)): ?>
              <div class="hostpn-audios">
                <?php foreach (explode(',', $hostpn_value) as $hostpn_audio): ?>
                  <div class="hostpn-audio hostpn-tooltip" title="<?php echo esc_html(get_the_title($hostpn_audio)); ?>"><i class="dashicons dashicons-media-audio"></i></div>
                <?php endforeach ?>
              </div>

              <div class="hostpn-text-align-center hostpn-position-relative"><a href="#" class="hostpn-btn hostpn-btn-mini hostpn-audio-btn"><?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? esc_html(__('Edit audios', 'hostpn')) : esc_html(__('Edit audio', 'hostpn')); ?></a></div>
            <?php else: ?>
              <div class="hostpn-audios"></div>

              <div class="hostpn-text-align-center hostpn-position-relative"><a href="#" class="hostpn-btn hostpn-btn-mini hostpn-audio-btn"><?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? esc_html(__('Add audios', 'hostpn')) : esc_html(__('Add audio', 'hostpn')); ?></a></div>
            <?php endif ?>

            <input id="<?php echo esc_attr($hostpn_input['id']); ?>" name="<?php echo esc_attr($hostpn_input['id']); ?>" class="hostpn-display-none hostpn-audio-input" type="text" value="<?php echo esc_attr($hostpn_value); ?>"/>
          </div>
        <?php
        break;
      case 'file':
        ?>
          <div class="hostpn-field hostpn-files-block" <?php echo wp_kses_post($hostpn_parent_block); ?>>
            <?php if (!empty($hostpn_value)): ?>
              <div class="hostpn-files hostpn-text-align-center">
                <?php foreach (explode(',', $hostpn_value) as $hostpn_file): ?>
                  <embed src="<?php echo esc_url(wp_get_attachment_url($hostpn_file)); ?>" type="application/pdf" class="hostpn-embed-file"/>
                <?php endforeach ?>
              </div>

              <div class="hostpn-text-align-center hostpn-position-relative"><a href="#" class="hostpn-btn hostpn-btn-mini hostpn-file-btn"><?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? esc_html(__('Edit files', 'hostpn')) : esc_html(__('Edit file', 'hostpn')); ?></a></div>
            <?php else: ?>
              <div class="hostpn-files"></div>

              <div class="hostpn-text-align-center hostpn-position-relative"><a href="#" class="hostpn-btn hostpn-btn-mini hostpn-btn-mini hostpn-file-btn"><?php echo (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) ? esc_html(__('Add files', 'hostpn')) : esc_html(__('Add file', 'hostpn')); ?></a></div>
            <?php endif ?>

            <input id="<?php echo esc_attr($hostpn_input['id']); ?>" name="<?php echo esc_attr($hostpn_input['id']); ?>" class="hostpn-display-none hostpn-file-input hostpn-btn-mini" type="text" value="<?php echo esc_attr($hostpn_value); ?>"/>
          </div>
        <?php
        break;
      case 'editor':
        ?>
          <div class="hostpn-field" <?php echo wp_kses_post($hostpn_parent_block); ?>>
            <textarea id="<?php echo esc_attr($hostpn_input['id']); ?>" name="<?php echo esc_attr($hostpn_input['id']); ?>" class="hostpn-input hostpn-width-100-percent hostpn-wysiwyg"><?php echo ((empty($hostpn_value)) ? (array_key_exists('placeholder', $hostpn_input) ? esc_attr($hostpn_input['placeholder']) : '') : esc_html($hostpn_value)); ?></textarea>
          </div>
        <?php
        break;
      case 'html':
        ?>
          <div class="hostpn-field" <?php echo wp_kses_post($hostpn_parent_block); ?>>
            <?php echo !empty($hostpn_input['html_content']) ? wp_kses(do_shortcode($hostpn_input['html_content']), HOSTPN_KSES) : ''; ?>
          </div>
        <?php
        break;
      case 'html_multi':
        switch ($hostpn_type) {
          case 'user':
            $html_multi_fields_length = !empty(get_user_meta($hostpn_id, $hostpn_input['html_multi_fields'][0]['id'], true)) ? count(get_user_meta($hostpn_id, $hostpn_input['html_multi_fields'][0]['id'], true)) : 0;
            break;
          case 'post':
            $html_multi_fields_length = !empty(get_post_meta($hostpn_id, $hostpn_input['html_multi_fields'][0]['id'], true)) ? count(get_post_meta($hostpn_id, $hostpn_input['html_multi_fields'][0]['id'], true)) : 0;
            break;
          case 'option':
            $html_multi_fields_length = !empty(get_option($hostpn_input['html_multi_fields'][0]['id'])) ? count(get_option($hostpn_input['html_multi_fields'][0]['id'])) : 0;
        }

        ?>
          <div class="hostpn-field hostpn-html-multi-wrapper hostpn-mb-50" <?php echo wp_kses_post($hostpn_parent_block); ?>>
            <?php if ($html_multi_fields_length): ?>
              <?php foreach (range(0, ($html_multi_fields_length - 1)) as $length_index): ?>
                <div class="hostpn-html-multi-group hostpn-display-table hostpn-width-100-percent hostpn-mb-30">
                  <div class="hostpn-display-inline-table hostpn-width-90-percent">
                    <?php foreach ($hostpn_input['html_multi_fields'] as $index => $html_multi_field): ?>
                      <label><?php echo esc_html($html_multi_field['label']); ?></label>

                      <?php self::hostpn_input_builder($html_multi_field, $hostpn_type, $hostpn_id, false, true, $length_index); ?>
                    <?php endforeach ?>
                  </div>
                  <div class="hostpn-display-inline-table hostpn-width-10-percent hostpn-text-align-center">
                    <i class="material-icons-outlined hostpn-cursor-move hostpn-multi-sorting hostpn-vertical-align-super hostpn-tooltip" title="<?php esc_html_e('Order element', 'hostpn'); ?>">drag_handle</i>
                  </div>

                  <div class="hostpn-text-align-right">
                    <a href="#" class="hostpn-html-multi-remove-btn"><i class="material-icons-outlined hostpn-cursor-pointer hostpn-tooltip" title="<?php esc_html_e('Remove element', 'hostpn'); ?>">remove</i></a>
                  </div>
                </div>
              <?php endforeach ?>
            <?php else: ?>
              <div class="hostpn-html-multi-group hostpn-mb-50">
                <div class="hostpn-display-inline-table hostpn-width-90-percent">
                  <?php foreach ($hostpn_input['html_multi_fields'] as $html_multi_field): ?>
                    <?php self::hostpn_input_builder($html_multi_field, $hostpn_type); ?>
                  <?php endforeach ?>
                </div>
                <div class="hostpn-display-inline-table hostpn-width-10-percent hostpn-text-align-center">
                  <i class="material-icons-outlined hostpn-cursor-move hostpn-multi-sorting hostpn-vertical-align-super hostpn-tooltip" title="<?php esc_html_e('Order element', 'hostpn'); ?>">drag_handle</i>
                </div>

                <div class="hostpn-text-align-right">
                  <a href="#" class="hostpn-html-multi-remove-btn hostpn-tooltip" title="<?php esc_html_e('Remove element', 'hostpn'); ?>"><i class="material-icons-outlined hostpn-cursor-pointer">remove</i></a>
                </div>
              </div>
            <?php endif ?>

            <div class="hostpn-text-align-right">
              <a href="#" class="hostpn-html-multi-add-btn hostpn-tooltip" title="<?php esc_html_e('Add element', 'hostpn'); ?>"><i class="material-icons-outlined hostpn-cursor-pointer hostpn-font-size-40">add</i></a>
            </div>
          </div>
        <?php
        break;
    }
  }

  public static function hostpn_input_wrapper_builder($input_array, $type, $hostpn_id = 0, $disabled = 0, $hostpn_format = 'half'){
    ?>
      <?php if (array_key_exists('section', $input_array) && !empty($input_array['section'])): ?>      
        <?php if ($input_array['section'] == 'start'): ?>
          <div class="hostpn-toggle-wrapper hostpn-section-wrapper hostpn-position-relative hostpn-mb-30 <?php echo array_key_exists('class', $input_array) ? esc_attr($input_array['class']) : ''; ?>" id="<?php echo array_key_exists('id', $input_array) ? esc_attr($input_array['id']) : ''; ?>">
            <?php if (array_key_exists('description', $input_array) && !empty($input_array['description'])): ?>
              <i class="material-icons-outlined hostpn-section-helper hostpn-color-main-0 hostpn-tooltip" title="<?php echo wp_kses_post($input_array['description']); ?>">help</i>
            <?php endif ?>

            <a href="#" class="hostpn-toggle hostpn-width-100-percent hostpn-text-decoration-none">
              <div class="hostpn-display-table hostpn-width-100-percent hostpn-mb-20">
                <div class="hostpn-display-inline-table hostpn-width-90-percent">
                  <label class="hostpn-cursor-pointer hostpn-mb-20 hostpn-color-main-0"><?php echo wp_kses_post($input_array['label']); ?></label>
                </div>
                <div class="hostpn-display-inline-table hostpn-width-10-percent hostpn-text-align-right">
                  <i class="material-icons-outlined hostpn-cursor-pointer hostpn-color-main-0">add</i>
                </div>
              </div>
            </a>

            <div class="hostpn-content hostpn-pl-10 hostpn-toggle-content hostpn-mb-20 hostpn-display-none-soft">
        <?php elseif ($input_array['section'] == 'end'): ?>
            </div>
          </div>
        <?php endif ?>
      <?php else: ?>
        <div class="hostpn-input-wrapper <?php echo esc_attr($input_array['id']); ?> <?php echo !empty($input_array['tabs']) ? 'hostpn-input-tabbed' : ''; ?> hostpn-input-field-<?php echo esc_attr($input_array['input']); ?> <?php echo (!empty($input_array['required']) && $input_array['required'] == true) ? 'hostpn-input-field-required' : ''; ?> <?php echo ($disabled) ? 'hostpn-input-field-disabled' : ''; ?> hostpn-mb-30">
          <?php if (array_key_exists('label', $input_array) && !empty($input_array['label'])): ?>
            <div class="hostpn-display-inline-table <?php echo (($hostpn_format == 'half' && !(array_key_exists('type', $input_array) && $input_array['type'] == 'submit')) ? 'hostpn-width-40-percent' : 'hostpn-width-100-percent'); ?> hostpn-tablet-display-block hostpn-tablet-width-100-percent hostpn-vertical-align-top">
              <div class="hostpn-p-10 <?php echo (array_key_exists('parent', $input_array) && !empty($input_array['parent']) && $input_array['parent'] != 'this') ? 'hostpn-pl-30' : ''; ?>">
                <label class="hostpn-vertical-align-middle hostpn-display-block <?php echo (array_key_exists('description', $input_array) && !empty($input_array['description'])) ? 'hostpn-toggle' : ''; ?>" for="<?php echo esc_attr($input_array['id']); ?>"><?php echo esc_attr($input_array['label']); ?> <?php echo (array_key_exists('required', $input_array) && !empty($input_array['required']) && $input_array['required'] == true) ? '<span class="hostpn-tooltip" title="' . esc_html(__('Required field', 'hostpn')) . '">*</span>' : ''; ?><?php echo (array_key_exists('description', $input_array) && !empty($input_array['description'])) ? '<i class="material-icons-outlined hostpn-cursor-pointer hostpn-float-right">add</i>' : ''; ?></label>

                <?php if (array_key_exists('description', $input_array) && !empty($input_array['description'])): ?>
                  <div class="hostpn-toggle-content hostpn-display-none-soft">
                    <small><?php echo wp_kses_post(wp_specialchars_decode($input_array['description'])); ?></small>
                  </div>
                <?php endif ?>
              </div>
            </div>
          <?php endif ?>

          <div class="hostpn-display-inline-table <?php echo ((array_key_exists('label', $input_array) && empty($input_array['label'])) ? 'hostpn-width-100-percent' : (($hostpn_format == 'half' && !(array_key_exists('type', $input_array) && $input_array['type'] == 'submit')) ? 'hostpn-width-60-percent' : 'hostpn-width-100-percent')); ?> hostpn-tablet-display-block hostpn-tablet-width-100-percent hostpn-vertical-align-top">
            <div class="hostpn-p-10 <?php echo (array_key_exists('parent', $input_array) && !empty($input_array['parent']) && $input_array['parent'] != 'this') ? 'hostpn-pl-30' : ''; ?>">
              <div class="hostpn-input-field"><?php self::hostpn_input_builder($input_array, $type, $hostpn_id, $disabled); ?></div>
            </div>
          </div>
        </div>
      <?php endif ?>
    <?php
  }

  /**
   * Display wrapper for field values with format control
   * 
   * @param array $input_array The input array containing field configuration
   * @param string $type The type of field (user, post, option)
   * @param int $hostpn_id The ID of the user/post/option
   * @param int $hostpn_meta_array Whether the field is part of a meta array
   * @param int $hostpn_array_index The index in the meta array
   * @param string $hostpn_format The display format ('half' or 'full')
   * @return string Formatted HTML output
   */
  public static function hostpn_input_display_wrapper($input_array, $type, $hostpn_id = 0, $hostpn_meta_array = 0, $hostpn_array_index = 0, $hostpn_format = 'half') {
    ob_start();
    ?>
    <?php if (array_key_exists('section', $input_array) && !empty($input_array['section'])): ?>      
      <?php if ($input_array['section'] == 'start'): ?>
        <div class="hostpn-toggle-wrapper hostpn-section-wrapper hostpn-position-relative hostpn-mb-30 <?php echo array_key_exists('class', $input_array) ? esc_attr($input_array['class']) : ''; ?>" id="<?php echo array_key_exists('id', $input_array) ? esc_attr($input_array['id']) : ''; ?>">
          <?php if (array_key_exists('description', $input_array) && !empty($input_array['description'])): ?>
            <i class="material-icons-outlined hostpn-section-helper hostpn-color-main-0 hostpn-tooltip" title="<?php echo wp_kses_post($input_array['description']); ?>">help</i>
          <?php endif ?>

          <a href="#" class="hostpn-toggle hostpn-width-100-percent hostpn-text-decoration-none">
            <div class="hostpn-display-table hostpn-width-100-percent hostpn-mb-20">
              <div class="hostpn-display-inline-table hostpn-width-90-percent">
                <label class="hostpn-cursor-pointer hostpn-mb-20 hostpn-color-main-0"><?php echo wp_kses_post($input_array['label']); ?></label>
              </div>
              <div class="hostpn-display-inline-table hostpn-width-10-percent hostpn-text-align-right">
                <i class="material-icons-outlined hostpn-cursor-pointer hostpn-color-main-0">add</i>
              </div>
            </div>
          </a>

          <div class="hostpn-content hostpn-pl-10 hostpn-toggle-content hostpn-mb-20 hostpn-display-none-soft">
      <?php elseif ($input_array['section'] == 'end'): ?>
          </div>
        </div>
      <?php endif ?>
    <?php else: ?>
      <div class="hostpn-input-wrapper <?php echo esc_attr($input_array['id']); ?> hostpn-input-display-<?php echo esc_attr($input_array['input']); ?> <?php echo (!empty($input_array['required']) && $input_array['required'] == true) ? 'hostpn-input-field-required' : ''; ?> hostpn-mb-30">
        <?php if (array_key_exists('label', $input_array) && !empty($input_array['label'])): ?>
          <div class="hostpn-display-inline-table <?php echo ($hostpn_format == 'half' ? 'hostpn-width-40-percent' : 'hostpn-width-100-percent'); ?> hostpn-tablet-display-block hostpn-tablet-width-100-percent hostpn-vertical-align-top">
            <div class="hostpn-p-10 <?php echo (array_key_exists('parent', $input_array) && !empty($input_array['parent']) && $input_array['parent'] != 'this') ? 'hostpn-pl-30' : ''; ?>">
              <label class="hostpn-vertical-align-middle hostpn-display-block <?php echo (array_key_exists('description', $input_array) && !empty($input_array['description'])) ? 'hostpn-toggle' : ''; ?>" for="<?php echo esc_attr($input_array['id']); ?>">
                <?php echo esc_html($input_array['label']); ?>
                <?php echo (array_key_exists('required', $input_array) && !empty($input_array['required']) && $input_array['required'] == true) ? '<span class="hostpn-tooltip" title="' . esc_html(__('Required field', 'hostpn')) . '">*</span>' : ''; ?>
                <?php echo (array_key_exists('description', $input_array) && !empty($input_array['description'])) ? '<i class="material-icons-outlined hostpn-cursor-pointer hostpn-float-right">add</i>' : ''; ?>
              </label>

              <?php if (array_key_exists('description', $input_array) && !empty($input_array['description'])): ?>
                <div class="hostpn-toggle-content hostpn-display-none-soft">
                  <small><?php echo wp_kses_post(wp_specialchars_decode($input_array['description'])); ?></small>
                </div>
              <?php endif ?>
            </div>
          </div>
        <?php endif; ?>

        <div class="hostpn-display-inline-table <?php echo ((array_key_exists('label', $input_array) && empty($input_array['label'])) ? 'hostpn-width-100-percent' : ($hostpn_format == 'half' ? 'hostpn-width-60-percent' : 'hostpn-width-100-percent')); ?> hostpn-tablet-display-block hostpn-tablet-width-100-percent hostpn-vertical-align-top">
          <div class="hostpn-p-10 <?php echo (array_key_exists('parent', $input_array) && !empty($input_array['parent']) && $input_array['parent'] != 'this') ? 'hostpn-pl-30' : ''; ?>">
            <div class="hostpn-input-field">
              <?php self::hostpn_input_builder_display($input_array, $type, $hostpn_id, $hostpn_meta_array, $hostpn_array_index); ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <?php
    return ob_get_clean();
  }

  /**
   * Display formatted values of hostpn_input_builder fields in frontend
   * 
   * @param array $hostpn_input The input array containing field configuration
   * @param string $hostpn_type The type of field (user, post, option)
   * @param int $hostpn_id The ID of the user/post/option
   * @param int $hostpn_meta_array Whether the field is part of a meta array
   * @param int $hostpn_array_index The index in the meta array
   * @return string Formatted HTML output of field values
   */
  public static function hostpn_input_builder_display($hostpn_input, $hostpn_type, $hostpn_id = 0, $hostpn_meta_array = 0, $hostpn_array_index = 0) {
    // Get the current value using the new function
    $current_value = self::hostpn_get_field_value($hostpn_input['id'], $hostpn_type, $hostpn_id, $hostpn_meta_array, $hostpn_array_index, $hostpn_input);

    // Start the field value display
    ?>
      <div class="hostpn-field-value">
        <?php
        switch ($hostpn_input['input']) {
          case 'input':
            switch ($hostpn_input['type']) {
              case 'hidden':
                break;
              case 'nonce':
                break;
              case 'file':
                if (!empty($current_value)) {
                  $file_url = wp_get_attachment_url($current_value);
                  ?>
                    <div class="hostpn-file-display">
                      <a href="<?php echo esc_url($file_url); ?>" target="_blank" class="hostpn-file-link">
                        <?php echo esc_html(basename($file_url)); ?>
                      </a>
                    </div>
                  <?php
                } else {
                  echo '<span class="hostpn-no-file">' . esc_html__('No file uploaded', 'hostpn') . '</span>';
                }
                break;

              case 'checkbox':
                ?>
                  <div class="hostpn-checkbox-display">
                    <span class="hostpn-checkbox-status <?php echo $current_value === 'on' ? 'checked' : 'unchecked'; ?>">
                      <?php echo $current_value === 'on' ? esc_html__('Yes', 'hostpn') : esc_html__('No', 'hostpn'); ?>
                    </span>
                  </div>
                <?php
                break;

              case 'radio':
                if (!empty($hostpn_input['radio_options'])) {
                  foreach ($hostpn_input['radio_options'] as $option) {
                    if ($current_value === $option['value']) {
                      ?>
                        <span class="hostpn-radio-selected"><?php echo esc_html($option['label']); ?></span>
                      <?php
                    }
                  }
                }
                break;

              case 'color':
                ?>
                  <div class="hostpn-color-display">
                    <span class="hostpn-color-preview" style="background-color: <?php echo esc_attr($current_value); ?>"></span>
                    <span class="hostpn-color-value"><?php echo esc_html($current_value); ?></span>
                  </div>
                <?php
                break;

              default:
                ?>
                  <span class="hostpn-text-value"><?php echo esc_html($current_value); ?></span>
                <?php
                break;
            }
            break;

          case 'select':
            if (!empty($hostpn_input['options']) && is_array($hostpn_input['options'])) {
              if (array_key_exists('multiple', $hostpn_input) && $hostpn_input['multiple']) {
                // Handle multiple select
                $selected_values = is_array($current_value) ? $current_value : array();
                if (!empty($selected_values)) {
                  ?>
                  <div class="hostpn-select-values hostpn-select-values-column">
                    <?php foreach ($selected_values as $value): ?>
                      <?php if (isset($hostpn_input['options'][$value])): ?>
                        <div class="hostpn-select-value-item"><?php echo esc_html($hostpn_input['options'][$value]); ?></div>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                  <?php
                }
              } else {
                // Handle single select
                $current_value = is_scalar($current_value) ? (string)$current_value : '';
                if (isset($hostpn_input['options'][$current_value])) {
                  ?>
                  <span class="hostpn-select-value"><?php echo esc_html($hostpn_input['options'][$current_value]); ?></span>
                  <?php
                }
              }
            }
            break;

          case 'textarea':
            ?>
              <div class="hostpn-textarea-value"><?php echo wp_kses_post(nl2br($current_value)); ?></div>
            <?php
            break;
          case 'image':
            if (!empty($current_value)) {
              $image_ids = explode(',', $current_value);
              ?>
                <div class="hostpn-image-gallery">
                  <?php foreach ($image_ids as $image_id): ?>
                    <div class="hostpn-image-item">
                      <?php echo wp_get_attachment_image($image_id, 'medium'); ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php
            } else {
              ?>
                <span class="hostpn-no-image"><?php esc_html_e('No images uploaded', 'hostpn'); ?></span>
              <?php
            }
            break;

          case 'editor':
            ?>
              <div class="hostpn-editor-content"><?php echo wp_kses_post($current_value); ?></div>
            <?php
            break;

          case 'html':
            if (!empty($hostpn_input['html_content'])) {
              ?>
                <div class="hostpn-html-content"><?php echo wp_kses_post(do_shortcode($hostpn_input['html_content'])); ?></div>
              <?php
            }
            break;

          case 'html_multi':
            switch ($hostpn_type) {
              case 'user':
                $html_multi_fields_length = !empty(get_user_meta($hostpn_id, $hostpn_input['html_multi_fields'][0]['id'], true)) ? count(get_user_meta($hostpn_id, $hostpn_input['html_multi_fields'][0]['id'], true)) : 0;
                break;
              case 'post':
                $html_multi_fields_length = !empty(get_post_meta($hostpn_id, $hostpn_input['html_multi_fields'][0]['id'], true)) ? count(get_post_meta($hostpn_id, $hostpn_input['html_multi_fields'][0]['id'], true)) : 0;
                break;
              case 'option':
                $html_multi_fields_length = !empty(get_option($hostpn_input['html_multi_fields'][0]['id'])) ? count(get_option($hostpn_input['html_multi_fields'][0]['id'])) : 0;
            }

            ?>
              <div class="hostpn-html-multi-content">
                <?php if ($html_multi_fields_length): ?>
                  <?php foreach (range(0, ($html_multi_fields_length - 1)) as $length_index): ?>
                    <div class="hostpn-html-multi-group hostpn-display-table hostpn-width-100-percent hostpn-mb-30">
                      <?php foreach ($hostpn_input['html_multi_fields'] as $index => $html_multi_field): ?>
                          <div class="hostpn-display-inline-table hostpn-width-60-percent">
                            <label><?php echo esc_html($html_multi_field['label']); ?></label>
                          </div>

                          <div class="hostpn-display-inline-table hostpn-width-40-percent">
                            <?php self::hostpn_input_builder_display($html_multi_field, $hostpn_type, $hostpn_id, 1, $length_index); ?>
                          </div>
                      <?php endforeach ?>
                    </div>
                  <?php endforeach ?>
                <?php endif; ?>
              </div>
            <?php
            break;
        }
        ?>
      </div>
    <?php
  }

  public static function hostpn_sanitizer($value, $node = '', $type = '', $field_config = []) {
    // Use the new validation system
    $result = HOSTPN_Validation::hostpn_validate_and_sanitize($value, $node, $type, $field_config);
    
    // If validation failed, return empty value and log the error
    if (is_wp_error($result)) {
        return '';
    }
    
    return $result;
  }
}
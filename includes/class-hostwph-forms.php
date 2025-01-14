<?php
/**
 * Fired from activate() function.
 *
 * This class defines all post types necessary to run during the plugin's life cycle.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@padresenlanube.com>
 */
class HOSTWPH_Forms {
	/**
	 * Plaform forms.
	 *
	 * @since    1.0.0
	 */

  public static function input_builder($hostwph_input, $hostwph_type, $hostwph_id = 0, $disabled = 0, $hostwph_meta_array = 0, $hostwph_array_index = 0) {
    if ($hostwph_meta_array) {
      switch ($hostwph_type) {
        case 'user':
          $user_meta = get_user_meta($hostwph_id, $hostwph_input['id'], true);

          if (is_array($user_meta) && array_key_exists($hostwph_array_index, $user_meta) && !empty($user_meta[$hostwph_array_index])) {
            $hostwph_value = $user_meta[$hostwph_array_index];
          }else{
            if (array_key_exists('value', $hostwph_input)) {
              $hostwph_value = $hostwph_input['value'];
            }else{
              $hostwph_value = '';
            }
          }
          break;
        case 'post':
          $post_meta = get_post_meta($hostwph_id, $hostwph_input['id'], true);

          if (is_array($post_meta) && array_key_exists($hostwph_array_index, $post_meta) && !empty($post_meta[$hostwph_array_index])) {
            $hostwph_value = $post_meta[$hostwph_array_index];
          }else{
            if (array_key_exists('value', $hostwph_input)) {
              $hostwph_value = $hostwph_input['value'];
            }else{
              $hostwph_value = '';
            }
          }
          break;
        case 'option':
          $option = get_option($hostwph_input['id']);

          if (is_array($option) && array_key_exists($hostwph_array_index, $option) && !empty($option[$hostwph_array_index])) {
            $hostwph_value = $option[$hostwph_array_index];
          }else{
            if (array_key_exists('value', $hostwph_input)) {
              $hostwph_value = $hostwph_input['value'];
            }else{
              $hostwph_value = '';
            }
          }
          break;
      }
    }else{
      switch ($hostwph_type) {
        case 'user':
          $user_meta = get_user_meta($hostwph_id, $hostwph_input['id'], true);

          if ($user_meta != '') {
            $hostwph_value = $user_meta;
          }else{
            if (array_key_exists('value', $hostwph_input)) {
              $hostwph_value = $hostwph_input['value'];
            }else{
              $hostwph_value = '';
            }
          }
          break;
        case 'post':
          $post_meta = get_post_meta($hostwph_id, $hostwph_input['id'], true);

          if ($post_meta != '') {
            $hostwph_value = $post_meta;
          }else{
            if (array_key_exists('value', $hostwph_input)) {
              $hostwph_value = $hostwph_input['value'];
            }else{
              $hostwph_value = '';
            }
          }
          break;
        case 'option':
          $option = get_option($hostwph_input['id']);

          if ($option != '') {
            $hostwph_value = $option;
          }else{
            if (array_key_exists('value', $hostwph_input)) {
              $hostwph_value = $hostwph_input['value'];
            }else{
              $hostwph_value = '';
            }
          }
          break;
      }
    }

    $hostwph_parent_block = (!empty($hostwph_input['parent']) ? 'data-hostwph-parent="' . $hostwph_input['parent'] . '"' : '') . ' ' . (!empty($hostwph_input['parent_option']) ? 'data-hostwph-parent-option="' . $hostwph_input['parent_option'] . '"' : '');

    switch ($hostwph_input['input']) {
      case 'input':
        switch ($hostwph_input['type']) {
          case 'file':
            ?>
              <?php if (empty($hostwph_value)): ?>
                <p class="hostwph-m-10"><?php esc_html_e('No file found', 'hostwph'); ?></p>
              <?php else: ?>
                <p class="hostwph-m-10">
                  <a href="<?php echo esc_url(get_post_meta($hostwph_id, $hostwph_input['id'], true)['url']); ?>" target="_blank"><?php echo esc_html(basename(get_post_meta($hostwph_id, $hostwph_input['id'], true)['url'])); ?></a>
                </p>
              <?php endif ?>
            <?php
            break;
          case 'checkbox':
            ?>
              <label class="hostwph-switch">
                <input id="<?php echo esc_attr($hostwph_input['id']) . ((array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? '[]' : ''); ?>" name="<?php echo esc_attr($hostwph_input['id']) . ((array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? '[]' : ''); ?>" class="<?php echo array_key_exists('class', $hostwph_input) ? esc_attr($hostwph_input['class']) : ''; ?> hostwph-checkbox hostwph-checkbox-switch hostwph-field" type="<?php echo esc_attr($hostwph_input['type']); ?>" <?php echo $hostwph_value == 'on' ? 'checked="checked"' : ''; ?> <?php echo (((array_key_exists('disabled', $hostwph_input) && $hostwph_input['disabled'] == 'true') || $disabled) ? 'disabled' : ''); ?> <?php echo ((array_key_exists('required', $hostwph_input) && $hostwph_input['required'] == true) ? 'required' : ''); ?> <?php echo wp_kses_post($hostwph_parent_block); ?>>
                <span class="hostwph-slider hostwph-round"></span>
              </label>
            <?php
            break;
          case 'range':
            ?>
              <div class="hostwph-input-range-wrapper">
                <div class="hostwph-width-100-percent">
                  <?php if (!empty($hostwph_input['hostwph_label_min'])): ?>
                    <p class="hostwph-input-range-label-min"><?php echo esc_html($hostwph_input['hostwph_label_min']); ?></p>
                  <?php endif ?>

                  <?php if (!empty($hostwph_input['hostwph_label_max'])): ?>
                    <p class="hostwph-input-range-label-max"><?php echo esc_html($hostwph_input['hostwph_label_max']); ?></p>
                  <?php endif ?>
                </div>

                <input type="<?php echo esc_attr($hostwph_input['type']); ?>" id="<?php echo esc_attr($hostwph_input['id']) . ((array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? '[]' : ''); ?>" name="<?php echo esc_attr($hostwph_input['id']); ?>" class="hostwph-input-range <?php echo array_key_exists('class', $hostwph_input) ? esc_attr($hostwph_input['class']) : ''; ?>" <?php echo ((array_key_exists('required', $hostwph_input) && $hostwph_input['required'] == true) ? 'required' : ''); ?> <?php echo (((array_key_exists('disabled', $hostwph_input) && $hostwph_input['disabled'] == 'true') || $disabled) ? 'disabled' : ''); ?> <?php echo (isset($hostwph_input['hostwph_max']) ? 'max=' . esc_attr($hostwph_input['hostwph_max']) : ''); ?> <?php echo (isset($hostwph_input['hostwph_min']) ? 'min=' . esc_attr($hostwph_input['hostwph_min']) : ''); ?> <?php echo (((array_key_exists('step', $hostwph_input) && $hostwph_input['step'] != '')) ? 'step="' . esc_attr($hostwph_input['step']) . '"' : ''); ?> <?php echo (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple'] ? 'multiple' : ''); ?> value="<?php echo (!empty($hostwph_input['button_text']) ? esc_html($hostwph_input['button_text']) : esc_html($hostwph_value)); ?>"/>
                <h3 class="hostwph-input-range-output"></h3>
              </div>
            <?php
            break;
          case 'stars':
            $hostwph_stars = !empty($hostwph_input['stars_number']) ? $hostwph_input['stars_number'] : 5;
            ?>
              <div class="hostwph-input-stars-wrapper">
                <div class="hostwph-width-100-percent">
                  <?php if (!empty($hostwph_input['hostwph_label_min'])): ?>
                    <p class="hostwph-input-stars-label-min"><?php echo esc_html($hostwph_input['hostwph_label_min']); ?></p>
                  <?php endif ?>

                  <?php if (!empty($hostwph_input['hostwph_label_max'])): ?>
                    <p class="hostwph-input-stars-label-max"><?php echo esc_html($hostwph_input['hostwph_label_max']); ?></p>
                  <?php endif ?>
                </div>

                <div class="hostwph-input-stars hostwph-text-align-center hostwph-pt-20">
                  <?php foreach (range(1, $hostwph_stars) as $index => $star): ?>
                    <i class="material-icons-outlined hostwph-input-star">star_outlined</i>
                  <?php endforeach ?>
                </div>

                <input type="number" <?php echo ((array_key_exists('required', $hostwph_input) && $hostwph_input['required'] == true) ? 'required' : ''); ?> <?php echo ((array_key_exists('disabled', $hostwph_input) && $hostwph_input['disabled'] == 'true') ? 'disabled' : ''); ?> id="<?php echo esc_attr($hostwph_input['id']) . ((array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? '[]' : ''); ?>" name="<?php echo esc_attr($hostwph_input['id']); ?>" class="hostwph-input-hidden-stars <?php echo array_key_exists('class', $hostwph_input) ? esc_attr($hostwph_input['class']) : ''; ?>" min="1" max="<?php echo esc_attr($hostwph_stars) ?>">
              </div>
            <?php
            break;
          case 'submit':
            ?>
              <div class="hostwph-text-align-right">
                <input type="submit" value="<?php echo esc_attr($hostwph_input['value']); ?>" name="<?php echo esc_attr($hostwph_input['id']); ?>" id="<?php echo esc_attr($hostwph_input['id']); ?>" class="hostwph-btn" data-hostwph-type="<?php echo esc_attr($hostwph_type); ?>" data-hostwph-subtype="<?php echo ((array_key_exists('subtype', $hostwph_input)) ? esc_attr($hostwph_input['subtype']) : ''); ?>" data-hostwph-user-id="<?php echo esc_attr($hostwph_id); ?>" data-hostwph-post-id="<?php echo esc_attr(get_the_ID()); ?>" data-hostwph-post-type="<?php echo esc_attr(get_post_type(get_the_ID())); ?>"/><?php echo esc_html(HOSTWPH_Data::loader()); ?>
              </div>
            <?php
            break;
          case 'hidden':
            ?>
              <input type="hidden" id="<?php echo esc_attr($hostwph_input['id']); ?>" name="<?php echo esc_attr($hostwph_input['id']); ?>" value="<?php echo esc_attr($hostwph_value); ?>">
            <?php
            break;
          case 'nonce':
            ?>
              <input type="hidden" id="<?php echo esc_attr($hostwph_input['id']); ?>" name="<?php echo esc_attr($hostwph_input['id']); ?>" value="<?php echo esc_attr(wp_create_nonce('hostwph-nonce')); ?>">
            <?php
            break;
          case 'password':
            ?>
              <div class="hostwph-password-checker">
                <div class="hostwph-password-input hostwph-position-relative">
                  <input id="hostwph-password" name="<?php echo $hostwph_input['id'] . ((array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple'] == 'true') ? '[]' : ''); ?>" <?php echo (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple'] == 'true' ? 'multiple' : ''); ?> class="hostwph-field hostwph-password-strength <?php echo array_key_exists('class', $hostwph_input) ? $hostwph_input['class'] : ''; ?>" type="<?php echo $hostwph_input['type']; ?>" <?php echo ((array_key_exists('required', $hostwph_input) && $hostwph_input['required'] == 'true') ? 'required' : ''); ?> <?php echo ((array_key_exists('disabled', $hostwph_input) && $hostwph_input['disabled'] == 'true') ? 'disabled' : ''); ?> value="<?php echo (!empty($hostwph_input['button_text']) ? $hostwph_input['button_text'] : $hostwph_value); ?>" placeholder="<?php echo (array_key_exists('placeholder', $hostwph_input) ? $hostwph_input['placeholder'] : ''); ?>" <?php echo $hostwph_parent_block; ?>/>

                  <a href="#" class="hostwph-show-pass hostwph-cursor-pointer hostwph-display-none-soft">
                    <i class="material-icons-outlined hostwph-font-size-20 hostwph-vertical-align-middle">visibility</i>
                  </a>
                </div>

                <div id="hostwph-popover-pass" class="hostwph-display-none-soft">
                  <div class="hostwph-progress-bar-wrapper">
                    <div class="hostwph-password-strength-bar"></div>
                  </div>

                  <h3 class="hostwph-mt-20"><?php esc_html_e('Password strength checker', 'hostwph'); ?> <i class="material-icons-outlined hostwph-cursor-pointer hostwph-close-icon hostwph-mt-30">close</i></h3>
                  <ul class="hostwph-list-style-none">
                    <li class="low-upper-case">
                      <i class="material-icons-outlined hostwph-font-size-20 hostwph-vertical-align-middle">radio_button_unchecked</i>
                      <span><?php esc_html_e('Lowercase & Uppercase', 'hostwph'); ?></span>
                    </li>
                    <li class="one-number">
                      <i class="material-icons-outlined hostwph-font-size-20 hostwph-vertical-align-middle">radio_button_unchecked</i>
                      <span><?php esc_html_e('Number (0-9)', 'hostwph'); ?></span>
                    </li>
                    <li class="one-special-char">
                      <i class="material-icons-outlined hostwph-font-size-20 hostwph-vertical-align-middle">radio_button_unchecked</i>
                      <span><?php esc_html_e('Special Character (!@#$%^&*)', 'hostwph'); ?></span>
                    </li>
                    <li class="eight-character">
                      <i class="material-icons-outlined hostwph-font-size-20 hostwph-vertical-align-middle">radio_button_unchecked</i>
                      <span><?php esc_html_e('Atleast 8 Character', 'hostwph'); ?></span>
                    </li>
                  </ul>
                </div>
              </div>
            <?php
            break;
          default:
            ?>
              <input id="<?php echo esc_attr($hostwph_input['id']) . ((array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? '[]' : ''); ?>" name="<?php echo esc_attr($hostwph_input['id']) . ((array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? '[]' : ''); ?>" <?php echo (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple'] ? 'multiple' : ''); ?> class="hostwph-field <?php echo array_key_exists('class', $hostwph_input) ? esc_attr($hostwph_input['class']) : ''; ?>" type="<?php echo esc_attr($hostwph_input['type']); ?>" <?php echo ((array_key_exists('required', $hostwph_input) && $hostwph_input['required'] == true) ? 'required' : ''); ?> <?php echo (((array_key_exists('disabled', $hostwph_input) && $hostwph_input['disabled'] == 'true') || $disabled) ? 'disabled' : ''); ?> <?php echo (((array_key_exists('step', $hostwph_input) && $hostwph_input['step'] != '')) ? 'step="' . esc_attr($hostwph_input['step']) . '"' : ''); ?> <?php echo (isset($hostwph_input['max']) ? 'max=' . esc_attr($hostwph_input['max']) : ''); ?> <?php echo (isset($hostwph_input['min']) ? 'min=' . esc_attr($hostwph_input['min']) : ''); ?> <?php echo (isset($hostwph_input['pattern']) ? 'pattern=' . esc_attr($hostwph_input['pattern']) : ''); ?> value="<?php echo (!empty($hostwph_input['button_text']) ? esc_html($hostwph_input['button_text']) : esc_html($hostwph_value)); ?>" placeholder="<?php echo (array_key_exists('placeholder', $hostwph_input) ? esc_html($hostwph_input['placeholder']) : ''); ?>" <?php echo wp_kses_post($hostwph_parent_block); ?>/>
            <?php
            break;
        }
        break;
      case 'select':
        ?>
          <select <?php echo ((array_key_exists('required', $hostwph_input) && $hostwph_input['required'] == true) ? 'required' : ''); ?> <?php echo (((array_key_exists('disabled', $hostwph_input) && $hostwph_input['disabled'] == 'true') || $disabled) ? 'disabled' : ''); ?> <?php echo (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple'] ? 'multiple' : ''); ?> id="<?php echo esc_attr($hostwph_input['id']) . ((array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? '[]' : ''); ?>" name="<?php echo esc_attr($hostwph_input['id']) . ((array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? '[]' : ''); ?>" class="hostwph-field <?php echo array_key_exists('class', $hostwph_input) ? esc_attr($hostwph_input['class']) : ''; ?>" placeholder="<?php echo (array_key_exists('placeholder', $hostwph_input) ? esc_attr($hostwph_input['placeholder']) : ''); ?>" data-placeholder="<?php echo (array_key_exists('placeholder', $hostwph_input) ? esc_attr($hostwph_input['placeholder']) : ''); ?>" <?php echo wp_kses_post($hostwph_parent_block); ?>>

            <?php if (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']): ?>
              <?php 
                switch ($hostwph_type) {
                  case 'user':
                    $hostwph_selected_values = !empty(get_user_meta($hostwph_id, $hostwph_input['id'], true)) ? get_user_meta($hostwph_id, $hostwph_input['id'], true) : [];
                    break;
                  case 'post':
                    $hostwph_selected_values = !empty(get_post_meta($hostwph_id, $hostwph_input['id'], true)) ? get_post_meta($hostwph_id, $hostwph_input['id'], true) : [];
                    break;
                  case 'option':
                    $hostwph_selected_values = !empty(get_option($hostwph_input['id'])) ? get_option($hostwph_input['id']) : [];
                    break;
                }
              ?>
              
              <?php foreach ($hostwph_input['options'] as $hostwph_input_option_key => $hostwph_input_option_value): ?>
                <option value="<?php echo esc_attr($hostwph_input_option_key); ?>" <?php echo ((array_key_exists('all_selected', $hostwph_input) && $hostwph_input['all_selected'] == 'true') || (is_array($hostwph_selected_values) && in_array($hostwph_input_option_key, $hostwph_selected_values)) ? 'selected' : ''); ?>><?php echo esc_html($hostwph_input_option_value) ?></option>
              <?php endforeach ?>
            <?php else: ?>
              <option value="" <?php echo $hostwph_value == '' ? 'selected' : '';?>><?php esc_html_e('Select an option', 'hostwph'); ?></option>
              
              <?php foreach ($hostwph_input['options'] as $hostwph_input_option_key => $hostwph_input_option_value): ?>
                <option value="<?php echo esc_attr($hostwph_input_option_key); ?>" <?php echo ((array_key_exists('all_selected', $hostwph_input) && $hostwph_input['all_selected'] == 'true') || ($hostwph_value != '' && $hostwph_input_option_key == $hostwph_value) ? 'selected' : ''); ?>><?php echo esc_html($hostwph_input_option_value); ?></option>
              <?php endforeach ?>
            <?php endif ?>
          </select>
        <?php
        break;
      case 'textarea':
        ?>
          <textarea id="<?php echo esc_attr($hostwph_input['id']) . ((array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? '[]' : ''); ?>" name="<?php echo esc_attr($hostwph_input['id']) . ((array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? '[]' : ''); ?>" <?php echo wp_kses_post($hostwph_parent_block); ?> class="hostwph-field <?php echo array_key_exists('class', $hostwph_input) ? esc_attr($hostwph_input['class']) : ''; ?>" <?php echo ((array_key_exists('required', $hostwph_input) && $hostwph_input['required'] == true) ? 'required' : ''); ?> <?php echo (((array_key_exists('disabled', $hostwph_input) && $hostwph_input['disabled'] == 'true') || $disabled) ? 'disabled' : ''); ?> <?php echo (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple'] ? 'multiple' : ''); ?> placeholder="<?php echo (array_key_exists('placeholder', $hostwph_input) ? esc_attr($hostwph_input['placeholder']) : ''); ?>"><?php echo esc_html($hostwph_value); ?></textarea>
        <?php
        break;
      case 'image':
        ?>
          <div class="hostwph-field hostwph-images-block" <?php echo wp_kses_post($hostwph_parent_block); ?> data-hostwph-multiple="<?php echo (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? 'true' : 'false'; ?>">
            <?php if (!empty($hostwph_value)): ?>
              <div class="hostwph-images">
                <?php foreach (explode(',', $hostwph_value) as $hostwph_image): ?>
                  <?php echo wp_get_attachment_image($hostwph_image, 'medium'); ?>
                <?php endforeach ?>
              </div>

              <div class="hostwph-text-align-center hostwph-position-relative"><a href="#" class="hostwph-btn hostwph-btn-mini hostwph-image-btn"><?php echo (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? esc_html(__('Edit images', 'hostwph')) : esc_html(__('Edit image', 'hostwph')); ?></a></div>
            <?php else: ?>
              <div class="hostwph-images"></div>

              <div class="hostwph-text-align-center hostwph-position-relative"><a href="#" class="hostwph-btn hostwph-btn-mini hostwph-image-btn"><?php echo (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? esc_html(__('Add images', 'hostwph')) : esc_html(__('Add image', 'hostwph')); ?></a></div>
            <?php endif ?>

            <input name="<?php echo esc_attr($hostwph_input['id']); ?>" id="<?php echo esc_attr($hostwph_input['id']); ?>" class="hostwph-display-none hostwph-image-input" type="text" value="<?php echo esc_attr($hostwph_value); ?>"/>
          </div>
        <?php
        break;
      case 'video':
        ?>
        <div class="hostwph-field hostwph-videos-block" <?php echo wp_kses_post($hostwph_parent_block); ?>>
            <?php if (!empty($hostwph_value)): ?>
              <div class="hostwph-videos">
                <?php foreach (explode(',', $hostwph_value) as $hostwph_video): ?>
                  <div class="hostwph-video hostwph-tooltip" title="<?php echo esc_html(get_the_title($hostwph_video)); ?>"><i class="dashicons dashicons-media-video"></i></div>
                <?php endforeach ?>
              </div>

              <div class="hostwph-text-align-center hostwph-position-relative"><a href="#" class="hostwph-btn hostwph-video-btn"><?php echo (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? esc_html(__('Edit videos', 'hostwph')) : esc_html(__('Edit video', 'hostwph')); ?></a></div>
            <?php else: ?>
              <div class="hostwph-videos"></div>

              <div class="hostwph-text-align-center hostwph-position-relative"><a href="#" class="hostwph-btn hostwph-video-btn"><?php echo (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? esc_html(__('Add videos', 'hostwph')) : esc_html(__('Add video', 'hostwph')); ?></a></div>
            <?php endif ?>

            <input name="<?php echo esc_attr($hostwph_input['id']); ?>" id="<?php echo esc_attr($hostwph_input['id']); ?>" class="hostwph-display-none hostwph-video-input" type="text" value="<?php echo esc_attr($hostwph_value); ?>"/>
          </div>
        <?php
        break;
      case 'audio':
        ?>
          <div class="hostwph-field hostwph-audios-block" <?php echo wp_kses_post($hostwph_parent_block); ?>>
            <?php if (!empty($hostwph_value)): ?>
              <div class="hostwph-audios">
                <?php foreach (explode(',', $hostwph_value) as $hostwph_audio): ?>
                  <div class="hostwph-audio hostwph-tooltip" title="<?php echo esc_html(get_the_title($hostwph_audio)); ?>"><i class="dashicons dashicons-media-audio"></i></div>
                <?php endforeach ?>
              </div>

              <div class="hostwph-text-align-center hostwph-position-relative"><a href="#" class="hostwph-btn hostwph-btn-mini hostwph-audio-btn"><?php echo (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? esc_html(__('Edit audios', 'hostwph')) : esc_html(__('Edit audio', 'hostwph')); ?></a></div>
            <?php else: ?>
              <div class="hostwph-audios"></div>

              <div class="hostwph-text-align-center hostwph-position-relative"><a href="#" class="hostwph-btn hostwph-btn-mini hostwph-audio-btn"><?php echo (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? esc_html(__('Add audios', 'hostwph')) : esc_html(__('Add audio', 'hostwph')); ?></a></div>
            <?php endif ?>

            <input name="<?php echo esc_attr($hostwph_input['id']); ?>" id="<?php echo esc_attr($hostwph_input['id']); ?>" class="hostwph-display-none hostwph-audio-input" type="text" value="<?php echo esc_attr($hostwph_value); ?>"/>
          </div>
        <?php
        break;
      case 'file':
        ?>
          <div class="hostwph-field hostwph-files-block" <?php echo wp_kses_post($hostwph_parent_block); ?>>
            <?php if (!empty($hostwph_value)): ?>
              <div class="hostwph-files hostwph-text-align-center">
                <?php foreach (explode(',', $hostwph_value) as $hostwph_file): ?>
                  <embed src="<?php echo esc_url(wp_get_attachment_url($hostwph_file)); ?>" type="application/pdf" class="hostwph-embed-file"/>
                <?php endforeach ?>
              </div>

              <div class="hostwph-text-align-center hostwph-position-relative"><a href="#" class="hostwph-btn hostwph-btn-mini hostwph-file-btn"><?php echo (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? esc_html(__('Edit files', 'hostwph')) : esc_html(__('Edit file', 'hostwph')); ?></a></div>
            <?php else: ?>
              <div class="hostwph-files"></div>

              <div class="hostwph-text-align-center hostwph-position-relative"><a href="#" class="hostwph-btn hostwph-btn-mini hostwph-btn-mini hostwph-file-btn"><?php echo (array_key_exists('multiple', $hostwph_input) && $hostwph_input['multiple']) ? esc_html(__('Add files', 'hostwph')) : esc_html(__('Add file', 'hostwph')); ?></a></div>
            <?php endif ?>

            <input name="<?php echo esc_attr($hostwph_input['id']); ?>" id="<?php echo esc_attr($hostwph_input['id']); ?>" class="hostwph-display-none hostwph-file-input hostwph-btn-mini" type="text" value="<?php echo esc_attr($hostwph_value); ?>"/>
          </div>
        <?php
        break;
      case 'editor':
        ?>
          <div class="hostwph-field" <?php echo wp_kses_post($hostwph_parent_block); ?>>
            <textarea id="<?php echo esc_attr($hostwph_input['id']); ?>" name="<?php echo esc_attr($hostwph_input['id']); ?>" class="hostwph-input hostwph-width-100-percent hostwph-wysiwyg"><?php echo ((empty($hostwph_value)) ? (array_key_exists('placeholder', $hostwph_input) ? esc_attr($hostwph_input['placeholder']) : '') : esc_html($hostwph_value)); ?></textarea>
          </div>
        <?php
        break;
      case 'html':
        ?>
          <div class="hostwph-field" <?php echo wp_kses_post($hostwph_parent_block); ?>>
            <?php echo !empty($hostwph_input['html_content']) ? wp_kses_post(html_entity_decode(do_shortcode($hostwph_input['html_content']))) : ''; ?>
          </div>
        <?php
        break;
      case 'html_multi':
        switch ($hostwph_type) {
          case 'user':
            $html_multi_fields_length = !empty(get_user_meta($hostwph_id, $hostwph_input['html_multi_fields'][0]['id'], true)) ? count(get_user_meta($hostwph_id, $hostwph_input['html_multi_fields'][0]['id'], true)) : 0;
            break;
          case 'post':
            $html_multi_fields_length = !empty(get_post_meta($hostwph_id, $hostwph_input['html_multi_fields'][0]['id'], true)) ? count(get_post_meta($hostwph_id, $hostwph_input['html_multi_fields'][0]['id'], true)) : 0;
            break;
          case 'option':
            $html_multi_fields_length = !empty(get_option($hostwph_input['html_multi_fields'][0]['id'])) ? count(get_option($hostwph_input['html_multi_fields'][0]['id'])) : 0;
        }

        ?>
          <div class="hostwph-html-multi-wrapper hostwph-mb-50" <?php echo wp_kses_post($hostwph_parent_block); ?>>
            <?php if ($html_multi_fields_length): ?>
              <?php foreach (range(0, ($html_multi_fields_length - 1)) as $length_index): ?>
                <div class="hostwph-html-multi-group hostwph-display-table hostwph-width-100-percent hostwph-mb-30">
                  <div class="hostwph-display-inline-table hostwph-width-90-percent">
                    <?php foreach ($hostwph_input['html_multi_fields'] as $index => $html_multi_field): ?>
                      <?php self::input_builder($html_multi_field, $hostwph_type, $hostwph_id, false, true, $length_index); ?>
                    <?php endforeach ?>
                  </div>
                  <div class="hostwph-display-inline-table hostwph-width-10-percent hostwph-text-align-center">
                    <i class="material-icons-outlined hostwph-cursor-move hostwph-multi-sorting hostwph-vertical-align-super hostwph-tooltip" title="<?php esc_html_e('Order element', 'hostwph'); ?>">drag_handle</i>
                  </div>

                  <div class="hostwph-text-align-right">
                    <a href="#" class="hostwph-html-multi-remove-btn"><i class="material-icons-outlined hostwph-cursor-pointer hostwph-tooltip" title="<?php esc_html_e('Remove element', 'hostwph'); ?>">remove</i></a>
                  </div>
                </div>
              <?php endforeach ?>
            <?php else: ?>
              <div class="hostwph-html-multi-group hostwph-mb-50">
                <div class="hostwph-display-inline-table hostwph-width-90-percent">
                  <?php foreach ($hostwph_input['html_multi_fields'] as $html_multi_field): ?>
                    <?php self::input_builder($html_multi_field, $hostwph_type); ?>
                  <?php endforeach ?>
                </div>
                <div class="hostwph-display-inline-table hostwph-width-10-percent hostwph-text-align-center">
                  <i class="material-icons-outlined hostwph-cursor-move hostwph-multi-sorting hostwph-vertical-align-super hostwph-tooltip" title="<?php esc_html_e('Order element', 'hostwph'); ?>">drag_handle</i>
                </div>

                <div class="hostwph-text-align-right">
                  <a href="#" class="hostwph-html-multi-remove-btn hostwph-tooltip" title="<?php esc_html_e('Remove element', 'hostwph'); ?>"><i class="material-icons-outlined hostwph-cursor-pointer">remove</i></a>
                </div>
              </div>
            <?php endif ?>

            <div class="hostwph-text-align-right">
              <a href="#" class="hostwph-html-multi-add-btn hostwph-tooltip" title="<?php esc_html_e('Add element', 'hostwph'); ?>"><i class="material-icons-outlined hostwph-cursor-pointer hostwph-font-size-40">add</i></a>
            </div>
          </div>
        <?php
        break;
    }
  }

  public static function input_wrapper_builder($input_array, $type, $hostwph_id = 0, $disabled = 0, $hostwph_format = 'half'){
    ?>
      <?php if (array_key_exists('section', $input_array) && !empty($input_array['section'])): ?>
        <?php if ($input_array['section'] == 'start'): ?>
          <div class="hostwph-toggle-wrapper hostwph-section-wrapper hostwph-position-relative hostwph-mb-30 <?php echo array_key_exists('class', $input_array) ? esc_attr($input_array['class']) : ''; ?>" id="<?php echo array_key_exists('id', $input_array) ? esc_attr($input_array['id']) : ''; ?>">
            <?php if (array_key_exists('description', $input_array) && !empty($input_array['description'])): ?>
              <i class="material-icons-outlined hostwph-section-helper hostwph-color-main-0 hostwph-tooltip" title="<?php echo wp_kses_post($input_array['description']); ?>">help</i>
            <?php endif ?>

            <a href="#" class="hostwph-toggle hostwph-width-100-percent hostwph-text-decoration-none">
              <div class="hostwph-display-table hostwph-width-100-percent">
                <div class="hostwph-display-inline-table hostwph-width-90-percent">
                  <label class="hostwph-cursor-pointer hostwph-toggle hostwph-mb-20"><?php echo wp_kses_post($input_array['label']); ?></label>
                </div>
                <div class="hostwph-display-inline-table hostwph-width-10-percent hostwph-text-align-right">
                  <i class="material-icons-outlined hostwph-cursor-pointer hostwph-color-main-0">add</i>
                </div>
              </div>
            </a>

            <div class="hostwph-content hostwph-pl-10 hostwph-toggle-content hostwph-mb-20 hostwph-display-none-soft">
        <?php elseif ($input_array['section'] == 'end'): ?>
            </div>
          </div>
        <?php endif ?>
      <?php else: ?>
        <div class="hostwph-input-wrapper <?php echo esc_attr($input_array['id']); ?> <?php echo !empty($input_array['tabs']) ? 'hostwph-input-tabbed' : ''; ?> hostwph-input-field-<?php echo esc_attr($input_array['input']); ?> <?php echo (!empty($input_array['required']) && $input_array['required'] == true) ? 'hostwph-input-field-required' : ''; ?> <?php echo ($disabled) ? 'hostwph-input-field-disabled' : ''; ?>">
          <?php if (array_key_exists('label', $input_array) && !empty($input_array['label'])): ?>
            <div class="hostwph-display-inline-table <?php echo (($hostwph_format == 'half' && !(array_key_exists('type', $input_array) && $input_array['type'] == 'submit')) ? 'hostwph-width-40-percent' : 'hostwph-width-100-percent'); ?> hostwph-tablet-display-block hostwph-tablet-width-100-percent hostwph-vertical-align-top">
              <div class="hostwph-p-10 <?php echo (array_key_exists('parent', $input_array) && !empty($input_array['parent']) && $input_array['parent'] != 'this') ? 'hostwph-pl-30' : ''; ?>">
                <label class="hostwph-font-size-16 hostwph-vertical-align-middle hostwph-display-block <?php echo (array_key_exists('description', $input_array) && !empty($input_array['description'])) ? 'hostwph-toggle' : ''; ?>" for="<?php echo esc_attr($input_array['id']); ?>"><?php echo esc_attr($input_array['label']); ?> <?php echo (array_key_exists('required', $input_array) && !empty($input_array['required']) && $input_array['required'] == true) ? '<span class="hostwph-tooltip" title="' . esc_html(__('Required field', 'hostwph')) . '">*</span>' : ''; ?><?php echo (array_key_exists('description', $input_array) && !empty($input_array['description'])) ? '<i class="material-icons-outlined hostwph-cursor-pointer hostwph-float-right">add</i>' : ''; ?></label>

                <?php if (array_key_exists('description', $input_array) && !empty($input_array['description'])): ?>
                  <div class="hostwph-toggle-content hostwph-display-none-soft">
                    <small><?php echo wp_kses_post(wp_specialchars_decode($input_array['description'])); ?></small>
                  </div>
                <?php endif ?>
              </div>
            </div>
          <?php endif ?>

          <div class="hostwph-display-inline-table <?php echo ((array_key_exists('label', $input_array) && empty($input_array['label'])) ? 'hostwph-width-100-percent' : (($hostwph_format == 'half' && !(array_key_exists('type', $input_array) && $input_array['type'] == 'submit')) ? 'hostwph-width-60-percent' : 'hostwph-width-100-percent')); ?> hostwph-tablet-display-block hostwph-tablet-width-100-percent hostwph-vertical-align-top">
            <div class="hostwph-p-10 <?php echo (array_key_exists('parent', $input_array) && !empty($input_array['parent']) && $input_array['parent'] != 'this') ? 'hostwph-pl-30' : ''; ?>">
              <div class="hostwph-input-field"><?php self::input_builder($input_array, $type, $hostwph_id, $disabled); ?></div>
            </div>
          </div>
        </div>
      <?php endif ?>
    <?php
  }

  public static function sanitizer($value, $node = '', $type = '') {
    switch (strtolower($node)) {
      case 'input':
        switch (strtolower($type)) {
          case 'text':
            return sanitize_text_field($value);
          case 'email':
            return sanitize_email($value);
          case 'url':
            return sanitize_url($value);
          case 'color':
            return sanitize_hex_color($value);
          default:
            return sanitize_text_field($value);
        }
      case 'select':
        switch ($type) {
          case 'select-multiple':
            foreach ($value as $key => $values) {
              $value[$key] = sanitize_key($values);
            }

            return $value;
          default:
            return sanitize_key($value);
        }
      case 'textarea':
        return wp_kses_post($value);
      case 'editor':
        return wp_kses_post($value);
      default:
        return sanitize_text_field($value);
    }
  }
}
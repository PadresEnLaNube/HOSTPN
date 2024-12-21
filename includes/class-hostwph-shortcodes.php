<?php
/**
 * Platform shortcodes.
 *
 * This class defines all shortcodes of the platform.
 *
 * @link       wordpress-heroes.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@wordpress-heroes.com>
 */
class HOSTWPH_Shortcodes {
  /**
   * Manage the shortcodes in the platform.
   *
   * @since    1.0.0
   */

  public function hostwph_accomodation() {
    // PONER CALL TO ACTION QUE EN CASO DE QUE HAYA UNA VARIABLE PHP CARGUE ALGÚN TIPO DE AVISO DIFERENTE AL CALL TO ACTION NORMAL DE CREACIÓN DE CUENTA
    // PÁGINA DE HOSTING
      // RESERVA DE HOSPEDAJE
      // PARTE DE VIAJERO
    // PONER AGRUPADOS LOS MENÚS EN EL DASHBOARD PARA QUE NO OCUPE TANTO
    // QUE SE INDIQUE EL CHECK PARA COMPLETAR COMO PAGADO
    // QUE EL ADMINISTRADOR PUEDA CREAR NUEVOS PERFILES PARA HACER EL PARTE
    // QUE EL ADMINISTRADOR PUEDA INVITAR POR CORREO ELECTRONICO A LOS NUEVOS HUÉSPEDES PARA QUE RELLENEN LOS CAMPOS DEL FORMULARIO CREANDO UN USUAARIO
    // QUE LOS INVITADOS PUEDAN CREAR NUEVOS PERFILES TAMBIÉN
    // QUE HAYA FILTROS EN FUNCIÓN DE UN TEXTO O POSIBILIDAD DE ORDENAR POR FECHAS O ALFABÉTICO
    $hostwph_page_part = !empty(get_option('hostwph_pages_part')) ? get_option('hostwph_pages_part') : url_to_postid(home_url());

    ob_start();
    ?>
      <div class="hostwph-accomodation">
        <?php if (is_user_logged_in()): ?>
          <?php if (HOSTWPH_Functions_User::is_user_admin(get_current_user_id())): ?>
            <?php echo do_shortcode('[hostwph-accomodation-list]'); ?>
          <?php endif ?>

          <div class="hostwph-text-align-center">
            <a class="hostwph-btn hostwph-btn-mini" href="<?php echo esc_url(get_permalink($hostwph_page_part)); ?>"><?php esc_html_e('Add part of travelers', 'hostwph'); ?></a>
          </div>
        <?php else: ?>
          <?php echo do_shortcode('[hostwph-call-to-action hostwph_call_to_action_icon="account_circle" hostwph_call_to_action_title="' . __('Account needed', 'hostwph') . '" hostwph_call_to_action_content="' . __('You need a valid account to see this content. Please', 'hostwph') . ' ' . '<a href=\'#\' class=\'userswph-profile-popup-btn\'>' . __('login', 'hostwph') . '</a>' . ' ' . __('or', 'hostwph') . ' ' . '<a href=\'#\' class=\'userswph-profile-popup-btn\' data-userswph-action=\'register\'>' . __('register', 'hostwph') . '</a>' . ' ' . __('to go ahead', 'hostwph') . '" hostwph_call_to_action_button_link="#" hostwph_call_to_action_button_text="' . __('Login', 'hostwph') . '" hostwph_call_to_action_button_class="userswph-profile-popup-btn" hostwph_call_to_action_class="hostwph-mb-100"]'); ?>
        <?php endif ?>
      </div>
    <?php
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }

  public function hostwph_part() {
    ob_start();
    ?>
      <div class="hostwph-part">
        <?php if (is_user_logged_in()): ?>
          <?php if (HOSTWPH_Functions_User::is_user_admin(get_current_user_id())): ?>
            <?php echo do_shortcode('[hostwph-part-list]'); ?>
          <?php endif ?>
        <?php else: ?>
          <?php echo do_shortcode('[hostwph-call-to-action hostwph_call_to_action_icon="account_circle" hostwph_call_to_action_title="' . __('Account needed', 'hostwph') . '" hostwph_call_to_action_content="' . __('You need a valid account to see this content. Please', 'hostwph') . ' ' . '<a href=\'#\' class=\'userswph-profile-popup-btn\'>' . __('login', 'hostwph') . '</a>' . ' ' . __('or', 'hostwph') . ' ' . '<a href=\'#\' class=\'userswph-profile-popup-btn\' data-userswph-action=\'register\'>' . __('register', 'hostwph') . '</a>' . ' ' . __('to go ahead', 'hostwph') . '" hostwph_call_to_action_button_link="#" hostwph_call_to_action_button_text="' . __('Login', 'hostwph') . '" hostwph_call_to_action_button_class="userswph-profile-popup-btn" hostwph_call_to_action_class="hostwph-mb-100"]'); ?>
        <?php endif ?>
      </div>
    <?php
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
  }

	public function hostwph_guest() {
    // PONER CALL TO ACTION QUE EN CASO DE QUE HAYA UNA VARIABLE PHP CARGUE ALGÚN TIPO DE AVISO DIFERENTE AL CALL TO ACTION NORMAL DE CREACIÓN DE CUENTA
    // PÁGINA DE HOSTING
      // RESERVA DE HOSPEDAJE
      // PARTE DE VIAJERO
    // PONER AGRUPADOS LOS MENÚS EN EL DASHBOARD PARA QUE NO OCUPE TANTO
    // QUE SE INDIQUE EL CHECK PARA COMPLETAR COMO PAGADO
    // QUE EL ADMINISTRADOR PUEDA CREAR NUEVOS PERFILES PARA HACER EL PARTE
    // QUE EL ADMINISTRADOR PUEDA INVITAR POR CORREO ELECTRONICO A LOS NUEVOS HUÉSPEDES PARA QUE RELLENEN LOS CAMPOS DEL FORMULARIO CREANDO UN USUAARIO
    // QUE LOS INVITADOS PUEDAN CREAR NUEVOS PERFILES TAMBIÉN
    // QUE HAYA FILTROS EN FUNCIÓN DE UN TEXTO O POSIBILIDAD DE ORDENAR POR FECHAS O ALFABÉTICO

    ob_start();
    ?>
      <div class="hostwph-guest">
        <?php if (is_user_logged_in()): ?>
          <?php if (HOSTWPH_Functions_User::is_user_admin(get_current_user_id())): ?>
            <?php echo do_shortcode('[hostwph-guest-list]'); ?>
          <?php endif ?>

          <div class="hostwph-reservation"></div>
        <?php else: ?>
          <?php echo do_shortcode('[hostwph-call-to-action hostwph_call_to_action_icon="account_circle" hostwph_call_to_action_title="' . __('Account needed', 'hostwph') . '" hostwph_call_to_action_content="' . __('You need a valid account to see this content. Please', 'hostwph') . ' ' . '<a href=\'#\' class=\'userswph-profile-popup-btn\'>' . __('login', 'hostwph') . '</a>' . ' ' . __('or', 'hostwph') . ' ' . '<a href=\'#\' class=\'userswph-profile-popup-btn\' data-userswph-action=\'register\'>' . __('register', 'hostwph') . '</a>' . ' ' . __('to go ahead', 'hostwph') . '" hostwph_call_to_action_button_link="#" hostwph_call_to_action_button_text="' . __('Login', 'hostwph') . '" hostwph_call_to_action_button_class="userswph-profile-popup-btn" hostwph_call_to_action_class="hostwph-mb-100"]'); ?>
        <?php endif ?>
      </div>
    <?php
    $hostwph_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostwph_return_string;
	}

  public function hostwph_navigation() {
    global $post;
    $post_id = $post->ID ?? 0;
    $hostwph_pages = get_option('hostwph_pages') ?? [];
    $hostwph_page_accomodation = !empty(get_option('hostwph_pages_accomodation')) ? get_option('hostwph_pages_accomodation') : url_to_postid(home_url());
    $hostwph_page_part = !empty(get_option('hostwph_pages_part')) ? get_option('hostwph_pages_part') : url_to_postid(home_url());
    $hostwph_page_host = !empty(get_option('hostwph_pages_host')) ? get_option('hostwph_pages_host') : url_to_postid(home_url());

    ob_start();
    ?>
      <?php if (in_array($post_id, $hostwph_pages)): ?>
        <div class="hostwph-navigation">
          <div class="hostwph-display-table hostwph-width-100-percent">
            <div class="hostwph-display-inline-table hostwph-width-33-percent hostwph-img-hover-zoom">
              <a href="<?php echo esc_url(get_permalink($hostwph_page_accomodation)) ?>">
                <i class="material-icons-outlined hostwph-vertical-align-middle hostwph-font-size-50 <?php echo ($post_id == $hostwph_page_accomodation) ? 'hostwph-color-main-0' : ''; ?>">hotel</i>
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
      <?php endif ?>  
    <?php
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
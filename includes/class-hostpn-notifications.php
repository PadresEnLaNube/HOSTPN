<?php
/**
 * Guest registration email notifications.
 *
 * Sends notification emails to configured recipients when a new guest
 * is registered via the guest form. Uses mailpn plugin if available,
 * otherwise falls back to wp_mail.
 *
 * @link       padresenlanube.com/
 * @since      1.0.25
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Notifications {

  /**
   * Handle guest form save and send notification if applicable.
   *
   * Hooked to `hostpn_form_save` at high priority so all meta is already saved.
   *
   * @param int    $element_id        Post/user ID.
   * @param array  $key_value         Submitted key-value pairs.
   * @param string $hostpn_form_type  Form type (post, user, option).
   * @param string $hostpn_form_subtype Form subtype (post_new, post_edit, etc.).
   * @param string $post_type         Post type slug.
   */
  public function hostpn_guest_notification( $element_id, $key_value, $hostpn_form_type, $hostpn_form_subtype, $post_type = '' ) {
    // Only send for new guest posts
    if ( $post_type !== 'hostpn_guest' || $hostpn_form_subtype !== 'post_new' ) {
      return;
    }

    // Check if notifications are enabled
    if ( get_option( 'hostpn_notifications_enabled' ) !== 'on' ) {
      return;
    }

    // Gather recipient emails
    $recipients = self::get_notification_recipients();

    if ( empty( $recipients ) ) {
      return;
    }

    // Build email content from submitted data
    $guest_name            = ! empty( $key_value['hostpn_name'] ) ? $key_value['hostpn_name'] : '';
    $guest_surname         = ! empty( $key_value['hostpn_surname'] ) ? $key_value['hostpn_surname'] : '';
    $guest_surname_alt     = ! empty( $key_value['hostpn_surname_alt'] ) ? $key_value['hostpn_surname_alt'] : '';
    $guest_email           = ! empty( $key_value['hostpn_email'] ) ? $key_value['hostpn_email'] : '';
    $guest_phone           = ! empty( $key_value['hostpn_phone'] ) ? $key_value['hostpn_phone'] : '';
    $guest_nationality     = ! empty( $key_value['hostpn_nationality'] ) ? $key_value['hostpn_nationality'] : '';
    $guest_identity        = ! empty( $key_value['hostpn_identity'] ) ? $key_value['hostpn_identity'] : '';
    $guest_identity_number = ! empty( $key_value['hostpn_identity_number'] ) ? $key_value['hostpn_identity_number'] : '';

    $full_name = trim( $guest_name . ' ' . $guest_surname . ' ' . $guest_surname_alt );

    $subject = sprintf(
      /* translators: %1$s: site name, %2$s: guest full name */
      __( '[%1$s] New guest registered: %2$s', 'hostpn' ),
      get_bloginfo( 'name' ),
      $full_name
    );

    $content = self::build_notification_content(
      $full_name,
      $guest_email,
      $guest_phone,
      $guest_nationality,
      $guest_identity,
      $guest_identity_number,
      $element_id
    );

    // Send to each recipient
    foreach ( $recipients as $email ) {
      self::send_notification( $email, $subject, $content );
    }
  }

  /**
   * Collect all notification recipient email addresses.
   *
   * @return array Unique list of valid email addresses.
   */
  private static function get_notification_recipients() {
    $recipients = [];

    // Platform users selected in settings
    $user_ids = get_option( 'hostpn_notifications_users', [] );
    if ( ! empty( $user_ids ) && is_array( $user_ids ) ) {
      foreach ( $user_ids as $user_id ) {
        $user = get_userdata( (int) $user_id );
        if ( $user && ! empty( $user->user_email ) ) {
          $recipients[] = $user->user_email;
        }
      }
    }

    // External emails from html_multi field
    $external_emails = get_option( 'hostpn_notifications_external_email', [] );
    if ( ! empty( $external_emails ) && is_array( $external_emails ) ) {
      foreach ( $external_emails as $email ) {
        if ( is_email( $email ) ) {
          $recipients[] = $email;
        }
      }
    }

    return array_unique( $recipients );
  }

  /**
   * Build the HTML body for the notification email.
   *
   * @param string $full_name          Guest full name.
   * @param string $email              Guest email.
   * @param string $phone              Guest phone.
   * @param string $nationality        Guest nationality code.
   * @param string $identity_type      Document type (nif, nie, pas, etc.).
   * @param string $identity_number    Document number.
   * @param int    $guest_post_id      The guest post ID.
   * @return string HTML content.
   */
  private static function build_notification_content( $full_name, $email, $phone, $nationality, $identity_type, $identity_number, $guest_post_id ) {
    $identity_labels = [
      'nif'  => __( 'NIF', 'hostpn' ),
      'nie'  => __( 'NIE', 'hostpn' ),
      'pas'  => __( 'Passport', 'hostpn' ),
      'cif'  => __( 'CIF', 'hostpn' ),
      'otro' => __( 'Other', 'hostpn' ),
    ];

    $identity_label = isset( $identity_labels[ $identity_type ] ) ? $identity_labels[ $identity_type ] : $identity_type;

    $admin_link = admin_url( 'post.php?post=' . (int) $guest_post_id . '&action=edit' );

    ob_start();
    ?>
    <h2><?php echo esc_html( __( 'New Guest Registration', 'hostpn' ) ); ?></h2>
    <p><?php echo esc_html( __( 'A new guest has been registered on your platform.', 'hostpn' ) ); ?></p>
    <table style="border-collapse:collapse;width:100%;max-width:600px;" cellpadding="8" cellspacing="0">
      <tr style="background:#f8f9fa;">
        <td style="border:1px solid #dee2e6;font-weight:bold;"><?php echo esc_html( __( 'Name', 'hostpn' ) ); ?></td>
        <td style="border:1px solid #dee2e6;"><?php echo esc_html( $full_name ); ?></td>
      </tr>
      <?php if ( ! empty( $email ) ) : ?>
      <tr>
        <td style="border:1px solid #dee2e6;font-weight:bold;"><?php echo esc_html( __( 'Email', 'hostpn' ) ); ?></td>
        <td style="border:1px solid #dee2e6;"><?php echo esc_html( $email ); ?></td>
      </tr>
      <?php endif; ?>
      <?php if ( ! empty( $phone ) ) : ?>
      <tr style="background:#f8f9fa;">
        <td style="border:1px solid #dee2e6;font-weight:bold;"><?php echo esc_html( __( 'Phone', 'hostpn' ) ); ?></td>
        <td style="border:1px solid #dee2e6;"><?php echo esc_html( $phone ); ?></td>
      </tr>
      <?php endif; ?>
      <?php if ( ! empty( $nationality ) ) : ?>
      <tr>
        <td style="border:1px solid #dee2e6;font-weight:bold;"><?php echo esc_html( __( 'Nationality', 'hostpn' ) ); ?></td>
        <td style="border:1px solid #dee2e6;"><?php echo esc_html( $nationality ); ?></td>
      </tr>
      <?php endif; ?>
      <?php if ( ! empty( $identity_type ) && ! empty( $identity_number ) ) : ?>
      <tr style="background:#f8f9fa;">
        <td style="border:1px solid #dee2e6;font-weight:bold;"><?php echo esc_html( $identity_label ); ?></td>
        <td style="border:1px solid #dee2e6;"><?php echo esc_html( $identity_number ); ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td style="border:1px solid #dee2e6;font-weight:bold;"><?php echo esc_html( __( 'Date', 'hostpn' ) ); ?></td>
        <td style="border:1px solid #dee2e6;"><?php echo esc_html( wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) ); ?></td>
      </tr>
    </table>
    <p style="margin-top:20px;">
      <a href="<?php echo esc_url( $admin_link ); ?>" style="background:#d45500;color:#fff;padding:10px 20px;text-decoration:none;border-radius:4px;display:inline-block;">
        <?php echo esc_html( __( 'View guest in dashboard', 'hostpn' ) ); ?>
      </a>
    </p>
    <?php
    return ob_get_clean();
  }

  /**
   * Send a notification email using mailpn if available, otherwise wp_mail.
   *
   * @param string $to      Recipient email.
   * @param string $subject Email subject.
   * @param string $content HTML email body.
   */
  private static function send_notification( $to, $subject, $content ) {
    if ( ! is_email( $to ) ) {
      return;
    }

    // Try mailpn first
    if ( shortcode_exists( 'mailpn-sender' ) ) {
      do_shortcode(
        '[mailpn-sender mailpn_user_to="' . esc_attr( $to ) . '" mailpn_subject="' . esc_attr( $subject ) . '" mailpn_type="email_coded"]'
        . $content
        . '[/mailpn-sender]'
      );
      return;
    }

    // Fallback to wp_mail
    $headers = [ 'Content-Type: text/html; charset=UTF-8' ];
    wp_mail( $to, $subject, $content, $headers );
  }
}

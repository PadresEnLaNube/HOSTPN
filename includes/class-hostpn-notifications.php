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

    // Schedule the notification to be sent after a delay to ensure data is fully saved
    // Increased to 5 seconds to prevent the email from being sent with incomplete data
    wp_schedule_single_event( time() + 5, 'hostpn_send_delayed_guest_notification', [ $element_id ] );
  }

  /**
   * Handle delayed guest notification after data has been saved.
   *
   * This is called via wp_schedule_single_event to ensure all post meta
   * is fully saved before sending the notification email.
   *
   * @param int $guest_post_id The guest post ID.
   */
  public function hostpn_send_delayed_guest_notification( $guest_post_id ) {
    self::send_guest_notification_by_post_id( $guest_post_id );
  }

  /**
   * Send guest notification by post ID.
   *
   * Collects data from post meta and sends notification email.
   * Can be called directly when a guest post is created or to resend notifications.
   *
   * @param int $guest_post_id The guest post ID.
   * @return bool True if notification was sent, false otherwise.
   */
  public static function send_guest_notification_by_post_id( $guest_post_id ) {
    // Check if notifications are enabled
    $notifications_enabled = get_option( 'hostpn_notifications_enabled' );
    if ( $notifications_enabled !== 'on' ) {
      error_log( 'HOSTPN Notification: skipped — notifications not enabled (value: ' . var_export( $notifications_enabled, true ) . ')' );
      return false;
    }

    // Gather recipients
    $recipients = self::get_notification_recipients();
    if ( empty( $recipients ) ) {
      error_log( 'HOSTPN Notification: skipped — no recipients configured' );
      return false;
    }

    // Get post author (the user)
    $post = get_post( $guest_post_id );
    if ( ! $post || $post->post_type !== 'hostpn_guest' ) {
      error_log( 'HOSTPN Notification: skipped — invalid guest post ID: ' . $guest_post_id );
      return false;
    }

    $user_id = $post->post_author;

    // Collect data from post meta AND user meta
    $key_value = [];

    // Get all post meta - this is the primary source for guest data
    $post_meta = get_post_meta( $guest_post_id );
    foreach ( $post_meta as $key => $values ) {
      if ( strpos( $key, 'hostpn_' ) === 0 && ! empty( $values[0] ) ) {
        $key_value[ $key ] = $values[0];
      }
    }

    // If this guest has an associated WordPress user ID, check their user meta
    $guest_wp_user_id = get_post_meta( $guest_post_id, 'hostpn_guest_wp_user_id', true );
    if ( ! empty( $guest_wp_user_id ) ) {
      $guest_user_meta = get_user_meta( $guest_wp_user_id );
      foreach ( $guest_user_meta as $key => $values ) {
        if ( strpos( $key, 'hostpn_' ) === 0 && ! isset( $key_value[ $key ] ) && ! empty( $values[0] ) ) {
          $key_value[ $key ] = $values[0];
        }
      }
    }

    // Also check post author's user meta for fields that might not be in post meta yet
    // This is a fallback for cases where the post author is the guest themselves
    $user_meta = get_user_meta( $user_id );
    foreach ( $user_meta as $key => $values ) {
      if ( strpos( $key, 'hostpn_' ) === 0 && ! isset( $key_value[ $key ] ) && ! empty( $values[0] ) ) {
        $key_value[ $key ] = $values[0];
      }
    }

    // Get name from post meta, then try user meta as fallback
    if ( empty( $key_value['hostpn_name'] ) ) {
      // Try guest's user if they have an account
      if ( ! empty( $guest_wp_user_id ) ) {
        $first_name = get_user_meta( $guest_wp_user_id, 'first_name', true );
        if ( ! empty( $first_name ) ) {
          $key_value['hostpn_name'] = $first_name;
        }
      }
      // Fallback to post author's name
      if ( empty( $key_value['hostpn_name'] ) ) {
        $first_name = get_user_meta( $user_id, 'first_name', true );
        if ( ! empty( $first_name ) ) {
          $key_value['hostpn_name'] = $first_name;
        }
      }
    }

    if ( empty( $key_value['hostpn_surname'] ) ) {
      // Try guest's user if they have an account
      if ( ! empty( $guest_wp_user_id ) ) {
        $last_name = get_user_meta( $guest_wp_user_id, 'last_name', true );
        if ( ! empty( $last_name ) ) {
          $key_value['hostpn_surname'] = $last_name;
        }
      }
      // Fallback to post author's name
      if ( empty( $key_value['hostpn_surname'] ) ) {
        $last_name = get_user_meta( $user_id, 'last_name', true );
        if ( ! empty( $last_name ) ) {
          $key_value['hostpn_surname'] = $last_name;
        }
      }
    }

    // Get email from post meta or user
    if ( empty( $key_value['hostpn_email'] ) ) {
      // Try guest's user if they have an account
      if ( ! empty( $guest_wp_user_id ) ) {
        $guest_user = get_userdata( $guest_wp_user_id );
        if ( $guest_user && ! empty( $guest_user->user_email ) ) {
          $key_value['hostpn_email'] = $guest_user->user_email;
        }
      }
      // Fallback to post author's email
      if ( empty( $key_value['hostpn_email'] ) ) {
        $user = get_userdata( $user_id );
        if ( $user && ! empty( $user->user_email ) ) {
          $key_value['hostpn_email'] = $user->user_email;
        }
      }
    }

    // Build full name
    $guest_name        = ! empty( $key_value['hostpn_name'] ) ? $key_value['hostpn_name'] : '';
    $guest_surname     = ! empty( $key_value['hostpn_surname'] ) ? $key_value['hostpn_surname'] : '';
    $guest_surname_alt = ! empty( $key_value['hostpn_surname_alt'] ) ? $key_value['hostpn_surname_alt'] : '';
    $full_name = trim( $guest_name . ' ' . $guest_surname . ' ' . $guest_surname_alt );

    $subject = sprintf(
      /* translators: %1$s: guest full name, %2$s: site name */
      __( 'New guest registered: %1$s - %2$s', 'hostpn' ),
      $full_name,
      get_bloginfo( 'name' )
    );

    $content = self::build_notification_content( $key_value, $full_name, $guest_post_id );

    // Send to each recipient
    $labels = array_map( function( $r ) {
      return $r['type'] === 'user_id' ? 'user:' . $r['value'] : $r['value'];
    }, $recipients );
    error_log( 'HOSTPN Notification: sending to ' . count( $recipients ) . ' recipient(s): ' . implode( ', ', $labels ) );

    foreach ( $recipients as $recipient ) {
      if ( $recipient['type'] === 'user_id' ) {
        self::send_notification_to_user( (int) $recipient['value'], $subject, $content );
      } else {
        self::send_notification( $recipient['value'], $subject, $content );
      }
    }

    return true;
  }

  /**
   * Collect all notification recipients as structured data.
   *
   * @return array List of [ 'type' => 'user_id'|'email', 'value' => int|string ].
   */
  private static function get_notification_recipients() {
    $recipients = [];

    // Platform users selected in settings — keep as user IDs
    $user_ids = get_option( 'hostpn_notifications_users', [] );
    if ( ! empty( $user_ids ) && is_array( $user_ids ) ) {
      foreach ( $user_ids as $user_id ) {
        $user = get_userdata( (int) $user_id );
        if ( $user && ! empty( $user->user_email ) ) {
          $recipients[] = [ 'type' => 'user_id', 'value' => (int) $user_id ];
        }
      }
    }

    // External emails from html_multi field
    $external_emails = get_option( 'hostpn_notifications_external_email', [] );
    if ( ! empty( $external_emails ) && is_array( $external_emails ) ) {
      foreach ( $external_emails as $email ) {
        if ( is_email( $email ) ) {
          $recipients[] = [ 'type' => 'email', 'value' => $email ];
        }
      }
    }

    return $recipients;
  }

  /**
   * Get stay information for a guest (check-in/check-out dates from parts).
   *
   * @param int $guest_post_id The guest post ID.
   * @return array Array with check_in_display, check_out_display, and accommodation_name.
   */
  private static function get_guest_stay_info( $guest_post_id ) {
    $info = [
      'check_in_display' => '',
      'check_out_display' => '',
      'accommodation_name' => '',
    ];

    // Find parts where this guest is included
    $args = [
      'post_type' => 'hostpn_part',
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'meta_query' => [
        [
          'key' => 'hostpn_people',
          'value' => serialize( (string) $guest_post_id ),
          'compare' => 'LIKE',
        ],
      ],
      'orderby' => 'meta_value',
      'meta_key' => 'hostpn_check_in_date',
      'order' => 'ASC',
    ];

    $parts = get_posts( $args );

    if ( ! empty( $parts ) ) {
      $today = current_time( 'Y-m-d' );
      $selected_part = null;

      // First, try to find an active or upcoming stay
      foreach ( $parts as $part ) {
        $check_in_date = get_post_meta( $part->ID, 'hostpn_check_in_date', true );
        $check_out_date = get_post_meta( $part->ID, 'hostpn_check_out_date', true );

        // If stay is active (today is between check-in and check-out) or upcoming
        if ( ! empty( $check_in_date ) && $check_in_date >= $today ) {
          $selected_part = $part;
          break;
        } elseif ( ! empty( $check_out_date ) && $check_out_date >= $today ) {
          $selected_part = $part;
          break;
        }
      }

      // If no active/upcoming stay found, use the most recent one
      if ( ! $selected_part && ! empty( $parts ) ) {
        $selected_part = end( $parts );
      }

      if ( $selected_part ) {
        $check_in_date = get_post_meta( $selected_part->ID, 'hostpn_check_in_date', true );
        $check_in_time = get_post_meta( $selected_part->ID, 'hostpn_check_in_time', true );
        $check_out_date = get_post_meta( $selected_part->ID, 'hostpn_check_out_date', true );
        $check_out_time = get_post_meta( $selected_part->ID, 'hostpn_check_out_time', true );
        $accommodation_id = get_post_meta( $selected_part->ID, 'hostpn_accommodation_id', true );

        // Format check-in display
        if ( ! empty( $check_in_date ) ) {
          $check_in_display = wp_date( get_option( 'date_format' ), strtotime( $check_in_date ) );
          if ( ! empty( $check_in_time ) ) {
            $check_in_display .= ' ' . $check_in_time;
          }
          $info['check_in_display'] = $check_in_display;
        }

        // Format check-out display
        if ( ! empty( $check_out_date ) ) {
          $check_out_display = wp_date( get_option( 'date_format' ), strtotime( $check_out_date ) );
          if ( ! empty( $check_out_time ) ) {
            $check_out_display .= ' ' . $check_out_time;
          }
          $info['check_out_display'] = $check_out_display;
        }

        // Get accommodation name
        if ( ! empty( $accommodation_id ) ) {
          $info['accommodation_name'] = get_the_title( $accommodation_id );
        }
      }
    }

    return $info;
  }

  /**
   * Build the HTML body for the notification email.
   *
   * @param array  $key_value     Submitted form key-value pairs.
   * @param string $full_name     Guest full name (pre-built).
   * @param int    $guest_post_id The guest post ID.
   * @return string HTML content.
   */
  private static function build_notification_content( $key_value, $full_name, $guest_post_id ) {
    $identity_labels = [
      'nif'  => __( 'NIF', 'hostpn' ),
      'nie'  => __( 'NIE', 'hostpn' ),
      'pas'  => __( 'Passport', 'hostpn' ),
      'cif'  => __( 'CIF', 'hostpn' ),
      'otro' => __( 'Other', 'hostpn' ),
    ];

    $gender_labels = [
      'h' => __( 'Male', 'hostpn' ),
      'm' => __( 'Female', 'hostpn' ),
      'o' => __( 'Other', 'hostpn' ),
    ];

    $countries     = class_exists( 'HOSTPN_Data' ) ? HOSTPN_Data::hostpn_countries() : [];
    $relationships = class_exists( 'HOSTPN_Data' ) ? HOSTPN_Data::hostpn_relationships() : [];

    // Extract all fields with safe defaults
    $email                  = ! empty( $key_value['hostpn_email'] ) ? $key_value['hostpn_email'] : '';
    $phone                  = ! empty( $key_value['hostpn_phone'] ) ? $key_value['hostpn_phone'] : '';
    $phone_alt              = ! empty( $key_value['hostpn_phone_alt'] ) ? $key_value['hostpn_phone_alt'] : '';
    $identity_type          = ! empty( $key_value['hostpn_identity'] ) ? $key_value['hostpn_identity'] : '';
    $identity_number        = ! empty( $key_value['hostpn_identity_number'] ) ? $key_value['hostpn_identity_number'] : '';
    $identity_support       = ! empty( $key_value['hostpn_identity_support_number'] ) ? $key_value['hostpn_identity_support_number'] : '';
    $birthdate              = ! empty( $key_value['hostpn_birthdate'] ) ? $key_value['hostpn_birthdate'] : '';
    $gender                 = ! empty( $key_value['hostpn_gender'] ) ? $key_value['hostpn_gender'] : '';
    $nationality            = ! empty( $key_value['hostpn_nationality'] ) ? $key_value['hostpn_nationality'] : '';
    $address                = ! empty( $key_value['hostpn_address'] ) ? $key_value['hostpn_address'] : '';
    $address_alt            = ! empty( $key_value['hostpn_address_alt'] ) ? $key_value['hostpn_address_alt'] : '';
    $country                = ! empty( $key_value['hostpn_country'] ) ? $key_value['hostpn_country'] : '';
    $postal_code            = ! empty( $key_value['hostpn_postal_code'] ) ? $key_value['hostpn_postal_code'] : '';
    $city                   = ! empty( $key_value['hostpn_city'] ) ? $key_value['hostpn_city'] : '';
    $contract_holder_check  = ! empty( $key_value['hostpn_contract_holder_check'] ) ? $key_value['hostpn_contract_holder_check'] : '';
    $relationship           = ! empty( $key_value['hostpn_relationship'] ) ? $key_value['hostpn_relationship'] : '';

    // Resolve labels from codes
    $identity_label    = isset( $identity_labels[ $identity_type ] ) ? $identity_labels[ $identity_type ] : $identity_type;
    $gender_label      = isset( $gender_labels[ $gender ] ) ? $gender_labels[ $gender ] : $gender;
    $nationality_label = isset( $countries[ $nationality ] ) ? $countries[ $nationality ] : $nationality;
    $country_label     = isset( $countries[ $country ] ) ? $countries[ $country ] : $country;
    $relationship_label = isset( $relationships[ $relationship ] ) ? $relationships[ $relationship ] : $relationship;

    $admin_link = admin_url( 'post.php?post=' . (int) $guest_post_id . '&action=edit' );

    // Get stay information (check-in/check-out dates)
    $stay_info = self::get_guest_stay_info( $guest_post_id );

    // Build rows: [ label, value, condition ]
    $rows = [
      [ __( 'Name', 'hostpn' ),                          $full_name,        true ],
      [ __( 'Email', 'hostpn' ),                          $email,            ! empty( $email ) ],
      [ __( 'Phone', 'hostpn' ),                          $phone,            ! empty( $phone ) ],
      [ __( 'Alternative phone', 'hostpn' ),              $phone_alt,        ! empty( $phone_alt ) ],
      [ $identity_label,                                   $identity_number,  ! empty( $identity_type ) && ! empty( $identity_number ) ],
      [ __( 'Document support number', 'hostpn' ),        $identity_support, ! empty( $identity_support ) ],
      [ __( 'Birthdate', 'hostpn' ),                      $birthdate,        ! empty( $birthdate ) ],
      [ __( 'Gender', 'hostpn' ),                          $gender_label,     ! empty( $gender ) ],
      [ __( 'Nationality', 'hostpn' ),                    $nationality_label, ! empty( $nationality ) ],
      [ __( 'Address', 'hostpn' ),                        $address,          ! empty( $address ) ],
      [ __( 'Address complementary information', 'hostpn' ), $address_alt,   ! empty( $address_alt ) ],
      [ __( 'Country', 'hostpn' ),                        $country_label,    ! empty( $country ) ],
      [ __( 'Postal code', 'hostpn' ),                    $postal_code,      ! empty( $postal_code ) ],
      [ __( 'City', 'hostpn' ),                            $city,             ! empty( $city ) ],
      [ __( 'Check-in date', 'hostpn' ),                  $stay_info['check_in_display'], ! empty( $stay_info['check_in_display'] ) ],
      [ __( 'Check-out date', 'hostpn' ),                 $stay_info['check_out_display'], ! empty( $stay_info['check_out_display'] ) ],
      [ __( 'Accommodation', 'hostpn' ),                  $stay_info['accommodation_name'], ! empty( $stay_info['accommodation_name'] ) ],
      [ __( 'Relationship with contract holder', 'hostpn' ), $relationship_label, $contract_holder_check === 'on' && ! empty( $relationship ) ],
      [ __( 'Date', 'hostpn' ),                            wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ), true ],
    ];

    ob_start();
    ?>
    <h2><?php echo esc_html( __( 'New Guest Registration', 'hostpn' ) ); ?></h2>
    <p><?php echo esc_html( __( 'A new guest has been registered on your platform.', 'hostpn' ) ); ?></p>
    <table style="border-collapse:collapse;width:100%;max-width:600px;" cellpadding="8" cellspacing="0">
      <?php
      $i = 0;
      foreach ( $rows as $row ) :
        if ( ! $row[2] ) continue;
        $bg = ( $i % 2 === 0 ) ? ' style="background:#f8f9fa;"' : '';
        $i++;
      ?>
      <tr<?php echo $bg; ?>>
        <td style="border:1px solid #dee2e6;font-weight:bold;"><?php echo esc_html( $row[0] ); ?></td>
        <td style="border:1px solid #dee2e6;"><?php echo esc_html( $row[1] ); ?></td>
      </tr>
      <?php endforeach; ?>
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
   * Send a notification email to a WordPress user by ID.
   *
   * Tries mailpn with user ID first (proper mailpn path with SMTP),
   * then mailpn with email, then wp_mail as last fallback.
   *
   * @param int    $user_id User ID.
   * @param string $subject Email subject.
   * @param string $content HTML email body.
   * @return bool True on success, false on failure.
   */
  public static function send_notification_to_user( $user_id, $subject, $content ) {
    $user = get_userdata( $user_id );
    if ( ! $user || empty( $user->user_email ) ) {
      error_log( 'HOSTPN Notification: FAILED — user ID ' . $user_id . ' not found or has no email' );
      return false;
    }

    $email = $user->user_email;

    if ( ! shortcode_exists( 'mailpn-sender' ) ) {
      error_log( 'HOSTPN Notification: mailpn not available, sending via wp_mail to user ' . $user_id . ' (' . $email . ')' );
      return self::send_via_wp_mail( $email, $subject, $content );
    }

    // Pre-check: diagnose potential mailpn blockers
    $pre_diagnosis = self::diagnose_mailpn_filters( $email, $user_id );
    if ( ! empty( $pre_diagnosis ) ) {
      error_log( 'HOSTPN Notification: mailpn pre-check for user ' . $user_id . ' (' . $email . ') — ' . $pre_diagnosis );
    }

    // Escape brackets in subject to prevent breaking shortcode parser
    $safe_subject = self::escape_shortcode_attr( $subject );

    // Attempt 1: mailpn with user ID (proper path, uses SMTP + templates)
    $result = do_shortcode(
      '[mailpn-sender mailpn_user_to="' . (int) $user_id . '" mailpn_subject="' . $safe_subject . '" mailpn_type="email_coded"]'
      . $content
      . '[/mailpn-sender]'
    );

    if ( $result !== '' && $result !== false ) {
      error_log( 'HOSTPN Notification: OK — sent via mailpn (user ID) to user ' . $user_id . ' (' . $email . ')' );
      return true;
    }

    error_log( 'HOSTPN Notification: mailpn rejected user ID ' . $user_id . ', trying with email address...' );

    // Attempt 2: mailpn with direct email
    $result = do_shortcode(
      '[mailpn-sender mailpn_user_to="' . esc_attr( $email ) . '" mailpn_subject="' . $safe_subject . '" mailpn_type="email_coded"]'
      . $content
      . '[/mailpn-sender]'
    );

    if ( $result !== '' && $result !== false ) {
      error_log( 'HOSTPN Notification: OK — sent via mailpn (email) to user ' . $user_id . ' (' . $email . ')' );
      return true;
    }

    error_log( 'HOSTPN Notification: mailpn rejected email too for user ' . $user_id . ', falling back to wp_mail' );

    // Attempt 3: wp_mail fallback
    return self::send_via_wp_mail( $email, $subject, $content );
  }

  /**
   * Send a notification email to a direct email address.
   *
   * Tries mailpn first, then wp_mail as fallback.
   *
   * @param string $to      Recipient email.
   * @param string $subject Email subject.
   * @param string $content HTML email body.
   * @return bool True on success, false on failure.
   */
  public static function send_notification( $to, $subject, $content ) {
    if ( ! is_email( $to ) ) {
      error_log( 'HOSTPN Notification: FAILED — invalid email address: ' . var_export( $to, true ) );
      return false;
    }

    if ( ! shortcode_exists( 'mailpn-sender' ) ) {
      return self::send_via_wp_mail( $to, $subject, $content );
    }

    // Pre-check: diagnose potential mailpn blockers
    $pre_diagnosis = self::diagnose_mailpn_filters( $to );
    if ( ! empty( $pre_diagnosis ) ) {
      error_log( 'HOSTPN Notification: mailpn pre-check for ' . $to . ' — ' . $pre_diagnosis );
    }

    $safe_subject = self::escape_shortcode_attr( $subject );

    $result = do_shortcode(
      '[mailpn-sender mailpn_user_to="' . esc_attr( $to ) . '" mailpn_subject="' . $safe_subject . '" mailpn_type="email_coded"]'
      . $content
      . '[/mailpn-sender]'
    );

    if ( $result !== '' && $result !== false ) {
      error_log( 'HOSTPN Notification: OK — sent via mailpn to ' . $to );
      return true;
    }

    error_log( 'HOSTPN Notification: FAILED via mailpn to ' . $to . ', falling back to wp_mail' );

    return self::send_via_wp_mail( $to, $subject, $content );
  }

  /**
   * Send email via wp_mail (last resort fallback).
   *
   * @param string $to      Recipient email.
   * @param string $subject Email subject.
   * @param string $content HTML email body.
   * @return bool
   */
  private static function send_via_wp_mail( $to, $subject, $content ) {
    $headers = [ 'Content-Type: text/html; charset=UTF-8' ];
    $result  = wp_mail( $to, $subject, $content, $headers );
    error_log( 'HOSTPN Notification: wp_mail to ' . $to . ' — result: ' . var_export( $result, true ) );
    return $result;
  }

  /**
   * Escape a string for safe use inside a shortcode attribute value.
   *
   * WordPress shortcode parser uses ] to close the tag, so square brackets
   * inside attribute values break parsing (e.g. site names like [Sea Suite]).
   * Moves bracketed text to the end with a dash separator:
   * "[Site Name] Subject text" → "Subject text - Site Name"
   *
   * @param string $value The attribute value to escape.
   * @return string Escaped value safe for shortcode attributes.
   */
  private static function escape_shortcode_attr( $value ) {
    // Move bracketed content to the end: "[Site] Subject" → "Subject - Site"
    if ( preg_match_all( '/\[([^\]]*)\]/', $value, $matches, PREG_SET_ORDER ) ) {
      $suffixes = [];
      foreach ( $matches as $match ) {
        $value      = str_replace( $match[0], '', $value );
        $suffixes[] = trim( $match[1] );
      }
      $value = trim( $value );
      if ( ! empty( $suffixes ) ) {
        $value .= ' - ' . implode( ' - ', $suffixes );
      }
    }
    // Remove any remaining stray [ or ] that could break the shortcode parser
    $value = str_replace( [ '[', ']' ], '', $value );
    return esc_attr( $value );
  }

  /**
   * Diagnose mailpn exception filters that could block delivery.
   *
   * Checks the same conditions that mailpn_sender() checks internally,
   * returning a human-readable warning if the email would be blocked.
   *
   * @param string   $email   Recipient email address.
   * @param int|null $user_id Optional user ID (for userspn_notifications check).
   * @return string Warning message, or empty string if no issues detected.
   */
  private static function diagnose_mailpn_filters( $email, $user_id = null ) {
    $warnings = [];

    // Check exception emails filter
    if ( get_option( 'mailpn_exception_emails' ) === 'on' ) {
      // Domain blocklist
      if ( get_option( 'mailpn_exception_emails_domains' ) === 'on' ) {
        $blocked_domains = get_option( 'mailpn_exception_emails_domain' );
        if ( ! empty( $blocked_domains ) && is_array( $blocked_domains ) ) {
          foreach ( $blocked_domains as $domain ) {
            if ( strpos( $email, $domain ) !== false ) {
              // Check whitelist
              $whitelist_enabled = get_option( 'mailpn_exception_emails_domains_whitelist' );
              $whitelist = ( $whitelist_enabled === 'on' ) ? get_option( 'mailpn_exception_emails_domains_whitelist_address' ) : [];
              if ( empty( $whitelist ) || ! in_array( $email, $whitelist, true ) ) {
                $warnings[] = 'BLOCKED by domain filter: "' . $domain . '" matches ' . $email;
              }
            }
          }
        }
      }

      // Address blocklist
      if ( get_option( 'mailpn_exception_emails_addresses' ) === 'on' ) {
        $blocked_addresses = get_option( 'mailpn_exception_emails_address' );
        if ( ! empty( $blocked_addresses ) && is_array( $blocked_addresses ) && in_array( $email, $blocked_addresses, true ) ) {
          $warnings[] = 'BLOCKED by address filter: ' . $email . ' is in the blocklist';
        }
      }
    }

    // Check userspn_notifications for user ID path
    if ( $user_id !== null && class_exists( 'USERSPN' ) ) {
      $notifications_pref = get_user_meta( $user_id, 'userspn_notifications', true );
      if ( $notifications_pref !== 'on' ) {
        $warnings[] = 'userspn_notifications for user ' . $user_id . ' is "' . $notifications_pref . '" (must be "on" for mailpn user-ID path)';
      }
    }

    return implode( ' | ', $warnings );
  }
}

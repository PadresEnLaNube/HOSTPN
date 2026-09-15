<?php
/**
 * Contract templates manager.
 *
 * Handles contract template types, sections, default texts, shortcode registry,
 * shortcode resolution, and contract rendering for PDF and public views.
 *
 * @link       padresenlanube.com/
 * @since      1.0.82
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Contract_Templates {

  /**
   * Get available contract languages based on existing .mo files.
   *
   * @return array Locale code => display name.
   */
  public static function hostpn_get_available_contract_languages() {
    $languages = ['en_US' => 'English'];
    $lang_dir = HOSTPN_DIR . 'languages/';

    $locale_names = [
      'es_ES' => 'Español',
      'gl_ES' => 'Galego',
      'ca'    => 'Català',
      'eu'    => 'Euskara',
      'it_IT' => 'Italiano',
      'pt_PT' => 'Português',
    ];

    foreach ($locale_names as $locale => $name) {
      if (file_exists($lang_dir . 'hostpn-' . $locale . '.mo')) {
        $languages[$locale] = $name;
      }
    }

    return $languages;
  }

  /**
   * Get available contract types.
   *
   * @return array
   */
  public static function hostpn_get_contract_types() {
    return [
      'habitacion' => __('Room rental', 'hostpn'),
      'turistico'  => __('Tourist rental', 'hostpn'),
      'lau'        => __('Long-stay rental', 'hostpn'),
    ];
  }

  /**
   * Map accommodation type to contract type.
   *
   * @param string $accommodation_type The accommodation type slug.
   * @return string Contract type key.
   */
  public static function hostpn_get_type_for_accommodation($accommodation_type) {
    $accommodation_type = strtolower(trim($accommodation_type));
    $map = [
      'habitacion' => 'habitacion',
      'vut'        => 'turistico',
      'vft'        => 'turistico',
    ];
    return isset($map[$accommodation_type]) ? $map[$accommodation_type] : 'lau';
  }

  /**
   * Get contract sections for a given type.
   *
   * @param string $contract_type Contract type key.
   * @return array Associative array of section_key => section_label.
   */
  public static function hostpn_get_contract_sections($contract_type) {
    $sections = [];

    switch ($contract_type) {
      case 'habitacion':
        $sections = [
          'reunidos'        => __('PARTIES', 'hostpn'),
          'exponen'         => __('RECITALS', 'hostpn'),
          'primera'         => __('CLAUSE 1 - Object', 'hostpn'),
          'segunda'         => __('CLAUSE 2 - Duration', 'hostpn'),
          'tercera'         => __('CLAUSE 3 - Rent and payment', 'hostpn'),
          'cuarta'          => __('CLAUSE 4 - Supplies', 'hostpn'),
          'quinta'          => __('CLAUSE 5 - Deposit', 'hostpn'),
          'sexta'           => __('CLAUSE 6 - House rules', 'hostpn'),
          'septima'         => __('CLAUSE 7 - Works', 'hostpn'),
          'octava'          => __('CLAUSE 8 - Assignment', 'hostpn'),
          'novena'          => __('CLAUSE 9 - Breach', 'hostpn'),
          'decima'          => __('CLAUSE 10 - Legislation', 'hostpn'),
          'firmas'          => __('SIGNATURES + INVENTORY', 'hostpn'),
        ];
        break;

      case 'turistico':
        $sections = [
          'reunidos'        => __('PARTIES', 'hostpn'),
          'exponen'         => __('RECITALS', 'hostpn'),
          'primera'         => __('CLAUSE 1 - Object', 'hostpn'),
          'segunda'         => __('CLAUSE 2 - Stay duration', 'hostpn'),
          'tercera'         => __('CLAUSE 3 - Price', 'hostpn'),
          'cuarta'          => __('CLAUSE 4 - Deposit', 'hostpn'),
          'quinta'          => __('CLAUSE 5 - Rules', 'hostpn'),
          'sexta'           => __('CLAUSE 6 - Inventory', 'hostpn'),
          'septima'         => __('CLAUSE 7 - Liability', 'hostpn'),
          'octava'          => __('CLAUSE 8 - Legislation', 'hostpn'),
          'firmas'          => __('SIGNATURES', 'hostpn'),
        ];
        break;

      case 'lau':
        $sections = [
          'reunidos'        => __('PARTIES', 'hostpn'),
          'exponen'         => __('RECITALS', 'hostpn'),
          'primera'         => __('CLAUSE 1 - Object', 'hostpn'),
          'segunda'         => __('CLAUSE 2 - Duration', 'hostpn'),
          'tercera'         => __('CLAUSE 3 - Rent', 'hostpn'),
          'cuarta'          => __('CLAUSE 4 - Deposit', 'hostpn'),
          'quinta'          => __('CLAUSE 5 - Supplies', 'hostpn'),
          'sexta'           => __('CLAUSE 6 - Works', 'hostpn'),
          'septima'         => __('CLAUSE 7 - Assignment', 'hostpn'),
          'octava'          => __('CLAUSE 8 - Termination', 'hostpn'),
          'novena'          => __('CLAUSE 9 - Legislation', 'hostpn'),
          'firmas'          => __('SIGNATURES', 'hostpn'),
        ];
        break;
    }

    return $sections;
  }

  /**
   * Get default template texts for a contract type.
   *
   * @param string $contract_type Contract type key.
   * @return array Associative array of section_key => HTML content with shortcodes.
   */
  public static function hostpn_get_default_template($contract_type) {
    $templates = [];

    switch ($contract_type) {
      case 'habitacion':
        $templates = self::hostpn_get_default_habitacion();
        break;
      case 'turistico':
        $templates = self::hostpn_get_default_turistico();
        break;
      case 'lau':
        $templates = self::hostpn_get_default_lau();
        break;
    }

    return $templates;
  }

  /**
   * Default template for room rental.
   */
  private static function hostpn_get_default_habitacion() {
    return [
      'reunidos' =>
        '<h2>' . __('PARTIES', 'hostpn') . '</h2>'
        . '<p>' . sprintf(__('On one hand, MR/MS <strong>[hostpn-host-name]</strong>, of legal age, with ID number <strong>[hostpn-host-id]</strong>, with address at <strong>[hostpn-host-address]</strong> and email <strong>[hostpn-host-email]</strong>. Hereinafter referred to as the %s.', 'hostpn'), '&laquo;' . __('LANDLORD', 'hostpn') . '&raquo;') . '</p>'
        . '<p>' . sprintf(__('On the other hand, MR/MS <strong>[hostpn-guest-name]</strong>, of legal age, with ID number <strong>[hostpn-guest-id-card]</strong>, with address at <strong>[hostpn-guest-address]</strong> and email <strong>[hostpn-guest-email]</strong>. Hereinafter referred to as the %s.', 'hostpn'), '&laquo;' . __('TENANT', 'hostpn') . '&raquo;') . '</p>'
        . '<p>' . __('Both parties mutually acknowledge sufficient legal capacity to enter into this room rental agreement and, to that effect,', 'hostpn') . '</p>',

      'exponen' =>
        '<h2>' . __('RECITALS', 'hostpn') . '</h2>'
        . '<p><strong>I.</strong> ' . __('The LANDLORD is the rightful owner/manager of the property located at <strong>[hostpn-accommodation-address]</strong>, a flat consisting of several rooms and common areas.', 'hostpn') . '</p>'
        . '<p><strong>II.</strong> ' . __('The TENANT is interested in renting the exclusive use of <strong>Room [hostpn-room-name]</strong> of said property, as well as the right to shared use of the common areas of the flat (kitchen, bathroom and hallways if applicable).', 'hostpn') . '</p>'
        . '<p><strong>III.</strong> ' . __('Both parties having reached an agreement, they formalize this contract subject to the following:', 'hostpn') . '</p>',

      'primera' =>
        '<h3>' . __('CLAUSES', 'hostpn') . '</h3>'
        . '<h3>' . __('CLAUSE 1. Object', 'hostpn') . '</h3>'
        . '<p>' . __('The LANDLORD leases to the TENANT the exclusive use of <strong>Room [hostpn-room-name]</strong>, furnished as per the attached inventory, located in the shared property at <strong>[hostpn-accommodation-address]</strong>.', 'hostpn') . '</p>'
        . '<p>' . __('The TENANT shall have the right to shared use with the other occupants of the property of the services and common areas: kitchen, bathroom, laundry area and hallways. The exclusive use of any other room in the flat is expressly excluded.', 'hostpn') . '</p>',

      'segunda' =>
        '<h3>' . __('CLAUSE 2. Contract duration', 'hostpn') . '</h3>'
        . '<p>' . __('This contract is agreed for a term of <strong>[hostpn-contract-duration]</strong> from <strong>[hostpn-contract-start-date]</strong> until <strong>[hostpn-contract-end-date]</strong>.', 'hostpn') . '</p>'
        . '<p>' . __('Upon the expiry date, the contract shall automatically terminate without prior notice. If the TENANT wishes to terminate the contract before its expiry, they must notify the LANDLORD with a minimum of <strong>[hostpn-contract-notice-days] days</strong> notice. Failure to comply with this notice period shall result in compensation to the LANDLORD equal to the proportional rent for the unfulfilled notice days.', 'hostpn') . '</p>',

      'tercera' =>
        '<h3>' . __('CLAUSE 3. Rent and method of payment', 'hostpn') . '</h3>'
        . '<p>' . __('The agreed monthly rent is <strong>[hostpn-contract-rent-amount] EUR</strong> (<strong>[hostpn-contract-rent-words]</strong>).', 'hostpn') . '</p>'
        . '<p>' . __('Rent shall be paid in advance within the first <strong>[hostpn-contract-payment-day]</strong> days of each month, by bank transfer to the LANDLORD\'s account:', 'hostpn') . '</p>'
        . '<p>' . __('Bank:', 'hostpn') . ' <strong>[hostpn-contract-bank-name]</strong><br>'
        . __('IBAN:', 'hostpn') . ' <strong>[hostpn-contract-iban]</strong></p>',

      'cuarta' =>
        '<h3>' . __('CLAUSE 4. Supplies and expenses', 'hostpn') . '</h3>'
        . '<p><strong>' . __('Included expenses:', 'hostpn') . '</strong> ' . __('The monthly rent includes water, community fees, property tax, waste collection and broadband Internet.', 'hostpn') . '</p>'
        . '<p><strong>' . __('Excluded expenses:', 'hostpn') . '</strong> ' . __('Electricity costs are not included in the rent and shall be paid monthly, proportionally divided among the rooms for common consumption (water heater, appliances and hallway/kitchen lighting) and individually for the TENANT\'s own consumption in their room.', 'hostpn') . '</p>',

      'quinta' =>
        '<h3>' . __('CLAUSE 5. Payments and deposit', 'hostpn') . '</h3>'
        . '<p>' . __('Upon signing this contract, the TENANT delivers to the LANDLORD the amount of <strong>[hostpn-contract-deposit-amount] EUR</strong> (<strong>[hostpn-contract-deposit-words]</strong>) as a security deposit.', 'hostpn') . '</p>'
        . '<p>' . __('The deposit shall cover the fulfilment of contractual obligations, the return of the room and its contents in the same condition as received, and the payment of any outstanding rent or utilities. It shall be returned within 30 days of key handover, after inspection of the property. Under no circumstances shall the deposit serve as payment for the last month\'s rent.', 'hostpn') . '</p>'
        . '<p>' . __('If the TENANT wishes to reserve the room in advance, half of the deposit may be provided as a booking fee, reserving the room for a maximum of 7 days from receipt of the funds.', 'hostpn') . '</p>',

      'sexta' =>
        '<h3>' . __('CLAUSE 6. House rules', 'hostpn') . '</h3>'
        . '<p>' . __('The TENANT agrees to respect the basic rules of coexistence with the other flatmates:', 'hostpn') . '</p>'
        . '<p><strong>' . __('Cleaning:', 'hostpn') . '</strong> ' . __('Keep the rented room in a perfect state of cleanliness and order, and actively participate in the cleaning rota for the common areas.', 'hostpn') . '</p>'
        . '<p><strong>' . __('Visitors and overnight stays:', 'hostpn') . '</strong> ' . __('Visits are limited to daytime hours. Overnight stays by persons not party to this contract are expressly prohibited without prior written consent from the LANDLORD and the other cohabitants.', 'hostpn') . '</p>'
        . '<p><strong>' . __('Pets:', 'hostpn') . '</strong> ' . __('Keeping animals in the property is prohibited unless expressly authorized in writing by the LANDLORD.', 'hostpn') . '</p>'
        . '<p><strong>' . __('Smoking and substances:', 'hostpn') . '</strong> ' . __('Smoking or consuming drugs in the room and common areas of the property is strictly prohibited.', 'hostpn') . '</p>'
        . '<p><strong>' . __('Noise:', 'hostpn') . '</strong> ' . __('Community rest shall be respected, avoiding noise or parties after 23:00.', 'hostpn') . '</p>',

      'septima' =>
        '<h3>' . __('CLAUSE 7. Works and maintenance', 'hostpn') . '</h3>'
        . '<p>' . __('The TENANT may not carry out works, modifications or drilling in the walls of the room or common areas without written authorization from the LANDLORD.', 'hostpn') . '</p>'
        . '<p>' . __('Minor repairs required by ordinary wear and tear of the room and its contents shall be borne by the TENANT.', 'hostpn') . '</p>',

      'octava' =>
        '<h3>' . __('CLAUSE 8. Assignment and subletting', 'hostpn') . '</h3>'
        . '<p>' . __('The assignment of this contract, as well as the partial or total subletting of the room to third parties, is expressly prohibited.', 'hostpn') . '</p>',

      'novena' =>
        '<h3>' . __('CLAUSE 9. Breach of contract', 'hostpn') . '</h3>'
        . '<p>' . __('Breach by either party of the obligations arising from this contract shall entitle the compliant party to demand termination of the contract or its fulfilment, with the corresponding compensation for damages.', 'hostpn') . '</p>'
        . '<p>' . __('Special grounds for termination include failure to pay rent or the deposit, conducting nuisance, unhealthy, harmful or illegal activities, or violation of the house rules set out in Clause 6.', 'hostpn') . '</p>',

      'decima' =>
        '<h3>' . __('CLAUSE 10. Applicable legislation and jurisdiction', 'hostpn') . '</h3>'
        . '<p>' . __('This contract is governed by what has been freely agreed by the parties and, failing that, by the applicable civil legislation.', 'hostpn') . '</p>'
        . '<p>' . __('For the resolution of any judicial dispute arising from the interpretation or fulfilment of this contract, both parties submit to the jurisdiction of the Courts of the place where the property is located.', 'hostpn') . '</p>',

      'firmas' =>
        '<p style="margin-top:20pt;">' . __('In witness whereof, both parties sign this contract in duplicate and to a single effect, at the place and date indicated above.', 'hostpn') . '</p>'
        . '<div class="contract-signatures">'
        . '<div class="contract-signature-block"><p><strong>' . __('THE LANDLORD', 'hostpn') . '</strong></p><div class="contract-signature-line"></div><p>' . __('Signed:', 'hostpn') . ' [hostpn-host-name]</p></div>'
        . '<div class="contract-signature-block"><p><strong>' . __('THE TENANT', 'hostpn') . '</strong></p><div class="contract-signature-line"></div><p>' . __('Signed:', 'hostpn') . ' [hostpn-guest-name]</p></div>'
        . '</div>',
    ];
  }

  /**
   * Default template for tourist rental.
   */
  private static function hostpn_get_default_turistico() {
    return [
      'reunidos' =>
        '<h2>' . __('PARTIES', 'hostpn') . '</h2>'
        . '<p>' . sprintf(__('On one hand, MR/MS <strong>[hostpn-host-name]</strong>, of legal age, with ID number <strong>[hostpn-host-id]</strong>, with address at <strong>[hostpn-host-address]</strong> and email <strong>[hostpn-host-email]</strong>. Hereinafter referred to as the %s.', 'hostpn'), '&laquo;' . __('OWNER', 'hostpn') . '&raquo;') . '</p>'
        . '<p>' . sprintf(__('On the other hand, MR/MS <strong>[hostpn-guest-name]</strong>, of legal age, with ID number <strong>[hostpn-guest-id-card]</strong>, with address at <strong>[hostpn-guest-address]</strong> and email <strong>[hostpn-guest-email]</strong>. Hereinafter referred to as the %s.', 'hostpn'), '&laquo;' . __('GUEST', 'hostpn') . '&raquo;') . '</p>'
        . '<p>' . __('Both parties acknowledge sufficient legal capacity and agree as follows:', 'hostpn') . '</p>',

      'exponen' =>
        '<h2>' . __('RECITALS', 'hostpn') . '</h2>'
        . '<p><strong>I.</strong> ' . __('The OWNER is the rightful owner of the tourist accommodation property located at <strong>[hostpn-accommodation-address]</strong>, <strong>[hostpn-accommodation-city]</strong>.', 'hostpn') . '</p>'
        . '<p><strong>II.</strong> ' . __('The GUEST wishes to stay temporarily in said property for tourist or holiday purposes.', 'hostpn') . '</p>'
        . '<p><strong>III.</strong> ' . __('Both parties formalize this tourist accommodation contract.', 'hostpn') . '</p>',

      'primera' =>
        '<h3>' . __('CLAUSE 1. Object', 'hostpn') . '</h3>'
        . '<p>' . __('The OWNER grants the temporary use of the tourist property located at <strong>[hostpn-accommodation-address]</strong>, furnished and equipped as per inventory, for the exclusive purpose of tourist accommodation.', 'hostpn') . '</p>'
        . '<p>' . __('Maximum number of guests: <strong>[hostpn-contract-guest-count]</strong>.', 'hostpn') . '</p>',

      'segunda' =>
        '<h3>' . __('CLAUSE 2. Stay duration', 'hostpn') . '</h3>'
        . '<p>' . __('The stay is agreed from <strong>[hostpn-contract-start-date]</strong> until <strong>[hostpn-contract-end-date]</strong>.', 'hostpn') . '</p>'
        . '<p>' . __('Check-in time: <strong>[hostpn-contract-checkin-time]</strong>. Check-out time: <strong>[hostpn-contract-checkout-time]</strong>.', 'hostpn') . '</p>'
        . '<p>' . __('The property must be vacated by the indicated check-out time.', 'hostpn') . '</p>',

      'tercera' =>
        '<h3>' . __('CLAUSE 3. Price', 'hostpn') . '</h3>'
        . '<p>' . __('The total price for the stay is <strong>[hostpn-contract-total-price] EUR</strong>.', 'hostpn') . '</p>'
        . '<p>' . __('Payment shall be made according to the conditions agreed between both parties.', 'hostpn') . '</p>',

      'cuarta' =>
        '<h3>' . __('CLAUSE 4. Deposit', 'hostpn') . '</h3>'
        . '<p>' . __('The GUEST delivers to the OWNER <strong>[hostpn-contract-deposit-amount] EUR</strong> ([hostpn-contract-deposit-words]) as a security deposit.', 'hostpn') . '</p>'
        . '<p>' . __('It shall be returned after inspection of the property at the end of the stay.', 'hostpn') . '</p>',

      'quinta' =>
        '<h3>' . __('CLAUSE 5. House rules', 'hostpn') . '</h3>'
        . '<p>' . __('The GUEST agrees to:', 'hostpn') . '</p>'
        . '<p>- ' . __('Use the property solely as tourist accommodation.', 'hostpn') . '</p>'
        . '<p>- ' . __('Not carry out nuisance, unhealthy or illegal activities.', 'hostpn') . '</p>'
        . '<p>- ' . __('Respect the neighbours\' rest.', 'hostpn') . '</p>'
        . '<p>- ' . __('Not accommodate more persons than indicated.', 'hostpn') . '</p>'
        . '<p>- ' . __('Smoking inside the property is prohibited.', 'hostpn') . '</p>'
        . '<p>- ' . __('Pets are prohibited unless expressly authorized.', 'hostpn') . '</p>',

      'sexta' =>
        '<h3>' . __('CLAUSE 6. Inventory', 'hostpn') . '</h3>'
        . '<p>' . __('An inventory of the property\'s contents and equipment is attached. The GUEST must return the property in the same condition as received.', 'hostpn') . '</p>',

      'septima' =>
        '<h3>' . __('CLAUSE 7. Liability', 'hostpn') . '</h3>'
        . '<p>' . __('The GUEST shall be liable for any damage caused to the property, contents or common areas during their stay, except for normal wear and tear.', 'hostpn') . '</p>'
        . '<p>' . __('The OWNER shall not be liable for the GUEST\'s personal belongings.', 'hostpn') . '</p>',

      'octava' =>
        '<h3>' . __('CLAUSE 8. Applicable legislation', 'hostpn') . '</h3>'
        . '<p>' . __('This contract is governed by the applicable regional regulations on tourist accommodation properties, and additionally by the applicable civil legislation.', 'hostpn') . '</p>',

      'firmas' =>
        '<p style="margin-top:20pt;">' . __('In witness whereof, both parties sign this contract.', 'hostpn') . '</p>'
        . '<div class="contract-signatures">'
        . '<div class="contract-signature-block"><p><strong>' . __('THE OWNER', 'hostpn') . '</strong></p><div class="contract-signature-line"></div><p>' . __('Signed:', 'hostpn') . ' [hostpn-host-name]</p></div>'
        . '<div class="contract-signature-block"><p><strong>' . __('THE GUEST', 'hostpn') . '</strong></p><div class="contract-signature-line"></div><p>' . __('Signed:', 'hostpn') . ' [hostpn-guest-name]</p></div>'
        . '</div>',
    ];
  }

  /**
   * Default template for long-stay rental.
   */
  private static function hostpn_get_default_lau() {
    return [
      'reunidos' =>
        '<h2>' . __('PARTIES', 'hostpn') . '</h2>'
        . '<p>' . sprintf(__('On one hand, MR/MS <strong>[hostpn-host-name]</strong>, of legal age, with ID number <strong>[hostpn-host-id]</strong>, with address at <strong>[hostpn-host-address]</strong> and email <strong>[hostpn-host-email]</strong>. Hereinafter referred to as the %s.', 'hostpn'), '&laquo;' . __('LANDLORD', 'hostpn') . '&raquo;') . '</p>'
        . '<p>' . sprintf(__('On the other hand, MR/MS <strong>[hostpn-guest-name]</strong>, of legal age, with ID number <strong>[hostpn-guest-id-card]</strong>, with address at <strong>[hostpn-guest-address]</strong> and email <strong>[hostpn-guest-email]</strong>. Hereinafter referred to as the %s.', 'hostpn'), '&laquo;' . __('TENANT', 'hostpn') . '&raquo;') . '</p>'
        . '<p>' . __('Both parties acknowledge sufficient legal capacity and agree as follows:', 'hostpn') . '</p>',

      'exponen' =>
        '<h2>' . __('RECITALS', 'hostpn') . '</h2>'
        . '<p><strong>I.</strong> ' . __('The LANDLORD is the rightful owner of the property located at <strong>[hostpn-accommodation-address]</strong>, <strong>[hostpn-accommodation-city]</strong>.', 'hostpn') . '</p>'
        . '<p><strong>II.</strong> ' . __('The TENANT wishes to rent said property as their habitual and permanent residence.', 'hostpn') . '</p>'
        . '<p><strong>III.</strong> ' . __('Both parties formalize this long-stay residential lease agreement.', 'hostpn') . '</p>',

      'primera' =>
        '<h3>' . __('CLAUSE 1. Object', 'hostpn') . '</h3>'
        . '<p>' . __('The LANDLORD leases to the TENANT the property located at <strong>[hostpn-accommodation-address]</strong>, <strong>[hostpn-accommodation-city]</strong>, for use as the TENANT\'s habitual and permanent residence.', 'hostpn') . '</p>',

      'segunda' =>
        '<h3>' . __('CLAUSE 2. Duration', 'hostpn') . '</h3>'
        . '<p>' . __('This contract is agreed for a term of <strong>[hostpn-contract-duration]</strong>, from <strong>[hostpn-contract-start-date]</strong> until <strong>[hostpn-contract-end-date]</strong>.', 'hostpn') . '</p>'
        . '<p>' . __('If the agreed term is shorter than the minimum period established by the applicable legislation, the contract shall be automatically extended in annual increments until said minimum period is reached, unless the TENANT expresses their wish not to renew with 30 days\' notice.', 'hostpn') . '</p>',

      'tercera' =>
        '<h3>' . __('CLAUSE 3. Rent', 'hostpn') . '</h3>'
        . '<p>' . __('The agreed monthly rent is <strong>[hostpn-contract-rent-amount] EUR</strong> ([hostpn-contract-rent-words]).', 'hostpn') . '</p>'
        . '<p>' . __('Payment shall be made within the first <strong>[hostpn-contract-payment-day]</strong> days of each month by bank transfer to:', 'hostpn') . '</p>'
        . '<p>' . __('Bank:', 'hostpn') . ' <strong>[hostpn-contract-bank-name]</strong><br>' . __('IBAN:', 'hostpn') . ' <strong>[hostpn-contract-iban]</strong></p>'
        . '<p>' . __('The rent shall be updated annually according to the applicable reference index established by the legislation in force at the time of update.', 'hostpn') . '</p>',

      'cuarta' =>
        '<h3>' . __('CLAUSE 4. Deposit', 'hostpn') . '</h3>'
        . '<p>' . __('The TENANT delivers <strong>[hostpn-contract-deposit-amount] EUR</strong> ([hostpn-contract-deposit-words]) as a legal security deposit (equivalent to one month\'s rent).', 'hostpn') . '</p>'
        . '<p>' . __('The deposit shall be returned upon termination of the contract, after inspection of the property, within one month of key handover.', 'hostpn') . '</p>',

      'quinta' =>
        '<h3>' . __('CLAUSE 5. Supplies and expenses', 'hostpn') . '</h3>'
        . '<p>' . __('Supply costs (water, electricity, gas, telecommunications) shall be borne by the TENANT for the duration of the contract.', 'hostpn') . '</p>'
        . '<p>' . __('Community fees and property tax shall be borne by the LANDLORD, unless expressly agreed otherwise.', 'hostpn') . '</p>',

      'sexta' =>
        '<h3>' . __('CLAUSE 6. Works and maintenance', 'hostpn') . '</h3>'
        . '<p>' . __('The LANDLORD shall carry out the necessary repairs to keep the property in habitable condition, except for damage attributable to the TENANT.', 'hostpn') . '</p>'
        . '<p>' . __('The TENANT may not carry out works that alter the layout of the property without written consent from the LANDLORD.', 'hostpn') . '</p>',

      'septima' =>
        '<h3>' . __('CLAUSE 7. Assignment and subletting', 'hostpn') . '</h3>'
        . '<p>' . __('The TENANT may not assign or sublet the property, in whole or in part, without written consent from the LANDLORD.', 'hostpn') . '</p>',

      'octava' =>
        '<h3>' . __('CLAUSE 8. Termination', 'hostpn') . '</h3>'
        . '<p>' . __('Grounds for termination include: failure to pay rent, unauthorized subletting, wilful damage, unauthorized works, and nuisance, unhealthy, harmful, dangerous or illegal activities.', 'hostpn') . '</p>'
        . '<p>' . __('The TENANT may withdraw from the contract after six months, by giving 30 days\' notice.', 'hostpn') . '</p>',

      'novena' =>
        '<h3>' . __('CLAUSE 9. Applicable legislation', 'hostpn') . '</h3>'
        . '<p>' . __('This contract is governed by the applicable tenancy legislation and additionally by the applicable civil legislation.', 'hostpn') . '</p>'
        . '<p>' . __('For the resolution of any dispute, both parties submit to the jurisdiction of the Courts of the place where the property is located.', 'hostpn') . '</p>',

      'firmas' =>
        '<p style="margin-top:20pt;">' . __('In witness whereof, both parties sign this contract in duplicate.', 'hostpn') . '</p>'
        . '<div class="contract-signatures">'
        . '<div class="contract-signature-block"><p><strong>' . __('THE LANDLORD', 'hostpn') . '</strong></p><div class="contract-signature-line"></div><p>' . __('Signed:', 'hostpn') . ' [hostpn-host-name]</p></div>'
        . '<div class="contract-signature-block"><p><strong>' . __('THE TENANT', 'hostpn') . '</strong></p><div class="contract-signature-line"></div><p>' . __('Signed:', 'hostpn') . ' [hostpn-guest-name]</p></div>'
        . '</div>',
    ];
  }

  /**
   * Get saved template from database, or defaults if not saved.
   *
   * @param string $contract_type Contract type key.
   * @return array
   */
  public static function hostpn_get_saved_template($contract_type) {
    $saved = get_option('hostpn_contract_template_' . $contract_type, false);
    if (!empty($saved) && is_array($saved)) {
      return $saved;
    }
    return self::hostpn_get_default_template($contract_type);
  }

  /**
   * Save template to database.
   *
   * @param string $contract_type Contract type key.
   * @param array  $sections Associative array of section_key => HTML content.
   * @return bool
   */
  public static function hostpn_save_template($contract_type, $sections) {
    $valid_types = array_keys(self::hostpn_get_contract_types());
    if (!in_array($contract_type, $valid_types, true)) {
      return false;
    }
    $valid_sections = array_keys(self::hostpn_get_contract_sections($contract_type));
    $clean = [];
    foreach ($sections as $key => $content) {
      if (in_array($key, $valid_sections, true)) {
        $clean[$key] = wp_kses_post($content);
      }
    }
    return update_option('hostpn_contract_template_' . $contract_type, $clean);
  }

  /**
   * Get the shortcodes registry with descriptions.
   *
   * @return array
   */
  public static function hostpn_get_shortcodes_registry() {
    return [
      // Common
      'hostpn-host-name'              => __('Landlord full name', 'hostpn'),
      'hostpn-host-id'                => __('Landlord NIF/NIE', 'hostpn'),
      'hostpn-host-address'           => __('Landlord address', 'hostpn'),
      'hostpn-host-email'             => __('Landlord email', 'hostpn'),
      'hostpn-guest-name'             => __('Tenant full name', 'hostpn'),
      'hostpn-guest-id-card'          => __('Tenant NIF/NIE', 'hostpn'),
      'hostpn-guest-address'          => __('Tenant address', 'hostpn'),
      'hostpn-guest-email'            => __('Tenant email', 'hostpn'),
      'hostpn-accommodation-address'  => __('Full accommodation address', 'hostpn'),
      'hostpn-accommodation-city'     => __('Accommodation city', 'hostpn'),
      'hostpn-contract-duration'      => __('Contract duration', 'hostpn'),
      'hostpn-contract-start-date'    => __('Start date', 'hostpn'),
      'hostpn-contract-end-date'      => __('End date', 'hostpn'),
      'hostpn-contract-rent-amount'   => __('Monthly rent (EUR)', 'hostpn'),
      'hostpn-contract-rent-words'    => __('Rent in words', 'hostpn'),
      'hostpn-contract-deposit-amount' => __('Deposit amount (EUR)', 'hostpn'),
      'hostpn-contract-deposit-words' => __('Deposit in words', 'hostpn'),
      // Room only
      'hostpn-room-name'              => __('Room identification', 'hostpn'),
      'hostpn-contract-notice-days'   => __('Notice days', 'hostpn'),
      'hostpn-contract-payment-day'   => __('Payment day', 'hostpn'),
      'hostpn-contract-bank-name'     => __('Bank name', 'hostpn'),
      'hostpn-contract-iban'          => __('IBAN', 'hostpn'),
      // Tourist only
      'hostpn-contract-guest-count'   => __('Number of guests', 'hostpn'),
      'hostpn-contract-checkin-time'  => __('Check-in time', 'hostpn'),
      'hostpn-contract-checkout-time' => __('Check-out time', 'hostpn'),
      'hostpn-contract-total-price'   => __('Total price', 'hostpn'),
    ];
  }

  /**
   * Get shortcode to field ID map (for JS client-side replacement).
   *
   * @return array shortcode => field_id
   */
  public static function hostpn_get_shortcode_field_map() {
    return [
      'hostpn-host-name'              => 'hostpn_contract_landlord_name',
      'hostpn-host-id'                => 'hostpn_contract_landlord_nif',
      'hostpn-host-address'           => 'hostpn_contract_landlord_address',
      'hostpn-host-email'             => 'hostpn_contract_landlord_email',
      'hostpn-guest-name'             => 'hostpn_contract_tenant_name',
      'hostpn-guest-id-card'          => 'hostpn_contract_tenant_nif',
      'hostpn-guest-address'          => 'hostpn_contract_tenant_address',
      'hostpn-guest-email'            => 'hostpn_contract_tenant_email',
      'hostpn-accommodation-address'  => '_computed_address',
      'hostpn-accommodation-city'     => 'hostpn_accommodation_city',
      'hostpn-contract-duration'      => 'hostpn_contract_duration',
      'hostpn-contract-start-date'    => 'hostpn_contract_start_date',
      'hostpn-contract-end-date'      => 'hostpn_contract_end_date',
      'hostpn-contract-rent-amount'   => 'hostpn_contract_rent_amount',
      'hostpn-contract-rent-words'    => 'hostpn_contract_rent_words',
      'hostpn-contract-deposit-amount' => 'hostpn_contract_deposit_amount',
      'hostpn-contract-deposit-words' => 'hostpn_contract_deposit_words',
      'hostpn-room-name'              => '_computed_room_name',
      'hostpn-contract-notice-days'   => 'hostpn_contract_notice_days',
      'hostpn-contract-payment-day'   => 'hostpn_contract_payment_day',
      'hostpn-contract-bank-name'     => 'hostpn_contract_bank_name',
      'hostpn-contract-iban'          => 'hostpn_contract_iban',
      'hostpn-contract-guest-count'   => 'hostpn_contract_guest_count',
      'hostpn-contract-checkin-time'  => 'hostpn_contract_checkin_time',
      'hostpn-contract-checkout-time' => 'hostpn_contract_checkout_time',
      'hostpn-contract-total-price'   => 'hostpn_contract_total_price',
    ];
  }

  /**
   * Resolve shortcodes in text using actual accommodation meta values.
   *
   * When $room_id is provided and valid, tenant/room shortcodes are overridden
   * with data from the room's assigned guest instead of accommodation meta.
   *
   * @param string $text Text with shortcodes.
   * @param int    $accommodation_id Post ID.
   * @param int    $room_id          Room post ID (0 = use accommodation meta).
   * @return string
   */
  public static function hostpn_resolve_shortcodes($text, $accommodation_id, $room_id = 0) {
    $meta = function($key) use ($accommodation_id) {
      return get_post_meta($accommodation_id, $key, true);
    };

    // Compute full address
    $address    = $meta('hostpn_accommodation_address');
    $address_alt = $meta('hostpn_accommodation_address_alt');
    $postal     = $meta('hostpn_accommodation_postal_code');
    $city       = $meta('hostpn_accommodation_city');
    $full_address = $address . (!empty($address_alt) ? ', ' . $address_alt : '');
    if (!empty($postal) || !empty($city)) {
      $full_address .= ', ' . trim($postal . ' ' . $city);
    }

    // Format dates
    $start_date = $meta('hostpn_contract_start_date');
    $end_date   = $meta('hostpn_contract_end_date');
    $start_fmt  = !empty($start_date) ? gmdate('d/m/Y', strtotime($start_date)) : '';
    $end_fmt    = !empty($end_date) ? gmdate('d/m/Y', strtotime($end_date)) : '';

    $replacements = [
      '[hostpn-host-name]'              => $meta('hostpn_contract_landlord_name'),
      '[hostpn-host-id]'                => $meta('hostpn_contract_landlord_nif'),
      '[hostpn-host-address]'           => $meta('hostpn_contract_landlord_address'),
      '[hostpn-host-email]'             => $meta('hostpn_contract_landlord_email'),
      '[hostpn-guest-name]'             => $meta('hostpn_contract_tenant_name'),
      '[hostpn-guest-id-card]'          => $meta('hostpn_contract_tenant_nif'),
      '[hostpn-guest-address]'          => $meta('hostpn_contract_tenant_address'),
      '[hostpn-guest-email]'            => $meta('hostpn_contract_tenant_email'),
      '[hostpn-accommodation-address]'  => $full_address,
      '[hostpn-accommodation-city]'     => $city,
      '[hostpn-contract-duration]'      => $meta('hostpn_contract_duration'),
      '[hostpn-contract-start-date]'    => $start_fmt,
      '[hostpn-contract-end-date]'      => $end_fmt,
      '[hostpn-contract-rent-amount]'   => $meta('hostpn_contract_rent_amount'),
      '[hostpn-contract-rent-words]'    => $meta('hostpn_contract_rent_words'),
      '[hostpn-contract-deposit-amount]' => $meta('hostpn_contract_deposit_amount'),
      '[hostpn-contract-deposit-words]' => $meta('hostpn_contract_deposit_words'),
      '[hostpn-room-name]'              => (function() use ($meta) {
        $rid = $meta('hostpn_contract_room_id');
        if (!empty($rid)) {
          $rn = get_post_meta($rid, 'hostpn_room_number', true);
          return !empty($rn) ? $rn : get_the_title($rid);
        }
        return '';
      })(),
      '[hostpn-contract-notice-days]'   => $meta('hostpn_contract_notice_days'),
      '[hostpn-contract-payment-day]'   => $meta('hostpn_contract_payment_day'),
      '[hostpn-contract-bank-name]'     => $meta('hostpn_contract_bank_name'),
      '[hostpn-contract-iban]'          => $meta('hostpn_contract_iban'),
      '[hostpn-contract-guest-count]'   => $meta('hostpn_contract_guest_count'),
      '[hostpn-contract-checkin-time]'  => $meta('hostpn_contract_checkin_time'),
      '[hostpn-contract-checkout-time]' => $meta('hostpn_contract_checkout_time'),
      '[hostpn-contract-total-price]'   => $meta('hostpn_contract_total_price'),
    ];

    // Override room/guest/contract shortcodes when a specific room is provided
    if (!empty($room_id) && get_post($room_id)) {
      $room_number = get_post_meta($room_id, 'hostpn_room_number', true);
      $replacements['[hostpn-room-name]'] = !empty($room_number) ? $room_number : get_the_title($room_id);

      // Override guest data from room's assigned guest
      $guest_id = get_post_meta($room_id, 'hostpn_room_guest_id', true);
      if (!empty($guest_id) && get_post($guest_id)) {
        $replacements['[hostpn-guest-name]'] = trim(
          get_post_meta($guest_id, 'hostpn_name', true) . ' ' .
          get_post_meta($guest_id, 'hostpn_surname', true) . ' ' .
          get_post_meta($guest_id, 'hostpn_surname_alt', true)
        );
        $replacements['[hostpn-guest-id-card]'] = get_post_meta($guest_id, 'hostpn_identity_number', true);
        $guest_address     = get_post_meta($guest_id, 'hostpn_address', true);
        $guest_address_alt = get_post_meta($guest_id, 'hostpn_address_alt', true);
        $replacements['[hostpn-guest-address]'] = trim($guest_address . (!empty($guest_address_alt) ? ', ' . $guest_address_alt : ''));
        $replacements['[hostpn-guest-email]'] = get_post_meta($guest_id, 'hostpn_email', true);
      }

      // Override contract fields from room meta (only when the room has a value)
      $room_contract_map = [
        '[hostpn-contract-duration]'      => 'hostpn_room_contract_duration',
        '[hostpn-contract-notice-days]'   => 'hostpn_room_contract_notice_days',
        '[hostpn-contract-rent-amount]'   => 'hostpn_room_contract_rent_amount',
        '[hostpn-contract-rent-words]'    => 'hostpn_room_contract_rent_words',
        '[hostpn-contract-payment-day]'   => 'hostpn_room_contract_payment_day',
        '[hostpn-contract-deposit-amount]' => 'hostpn_room_contract_deposit_amount',
        '[hostpn-contract-deposit-words]' => 'hostpn_room_contract_deposit_words',
      ];
      foreach ($room_contract_map as $shortcode => $meta_key) {
        $room_val = get_post_meta($room_id, $meta_key, true);
        if ($room_val !== '' && $room_val !== null) {
          $replacements[$shortcode] = $room_val;
        }
      }

      // Override dates with formatting
      $room_start = get_post_meta($room_id, 'hostpn_room_contract_start_date', true);
      if (!empty($room_start)) {
        $replacements['[hostpn-contract-start-date]'] = gmdate('d/m/Y', strtotime($room_start));
      }
      $room_end = get_post_meta($room_id, 'hostpn_room_contract_end_date', true);
      if (!empty($room_end)) {
        $replacements['[hostpn-contract-end-date]'] = gmdate('d/m/Y', strtotime($room_end));
      }
    }

    return str_replace(array_keys($replacements), array_values($replacements), $text);
  }

  /**
   * Render a full contract HTML from template.
   *
   * @param string $contract_type Contract type key.
   * @param array  $template Template sections array.
   * @param int    $accommodation_id Post ID.
   * @param int    $room_id          Room post ID (0 = use accommodation meta).
   * @return string Full HTML of the contract.
   */
  public static function hostpn_render_contract($contract_type, $template, $accommodation_id, $room_id = 0) {
    $types = self::hostpn_get_contract_types();
    $type_label = isset($types[$contract_type]) ? $types[$contract_type] : '';

    $title_map = [
      'habitacion' => __('ROOM RENTAL AGREEMENT IN SHARED PROPERTY', 'hostpn'),
      'turistico'  => __('TOURIST ACCOMMODATION AGREEMENT', 'hostpn'),
      'lau'        => __('LONG-STAY RESIDENTIAL LEASE AGREEMENT', 'hostpn'),
    ];
    $title = isset($title_map[$contract_type]) ? $title_map[$contract_type] : '';

    $html = '<h1>' . $title . '</h1>';

    foreach ($template as $section_key => $section_content) {
      $resolved = self::hostpn_resolve_shortcodes($section_content, $accommodation_id, $room_id);
      $html .= $resolved;
    }

    return $html;
  }

  /**
   * Resolve shortcodes using Contract CPT meta instead of accommodation meta.
   *
   * When a contract is stored as its own CPT, the contract-specific fields
   * (landlord, tenant, dates, amounts, etc.) live on the contract post,
   * while accommodation address/city still come from the accommodation post.
   *
   * @param string $text              Already-rendered HTML (may still contain unresolved shortcodes).
   * @param int    $contract_id       Contract CPT post ID.
   * @param int    $accommodation_id  Accommodation post ID.
   * @return string
   */
  public static function hostpn_resolve_shortcodes_from_contract($text, $contract_id, $accommodation_id) {
    if (empty($contract_id)) {
      return $text;
    }

    $cmeta = function($key) use ($contract_id) {
      return get_post_meta($contract_id, $key, true);
    };
    $ameta = function($key) use ($accommodation_id) {
      return get_post_meta($accommodation_id, $key, true);
    };

    // Compute full accommodation address
    $address     = $ameta('hostpn_accommodation_address');
    $address_alt = $ameta('hostpn_accommodation_address_alt');
    $postal      = $ameta('hostpn_accommodation_postal_code');
    $city        = $ameta('hostpn_accommodation_city');
    $full_address = $address . (!empty($address_alt) ? ', ' . $address_alt : '');
    if (!empty($postal) || !empty($city)) {
      $full_address .= ', ' . trim($postal . ' ' . $city);
    }

    // Format dates
    $start_date = $cmeta('hostpn_contract_start_date');
    $end_date   = $cmeta('hostpn_contract_end_date');
    $start_fmt  = !empty($start_date) ? gmdate('d/m/Y', strtotime($start_date)) : '';
    $end_fmt    = !empty($end_date) ? gmdate('d/m/Y', strtotime($end_date)) : '';

    // Room name from room CPT
    $room_id   = $cmeta('hostpn_contract_room_id');
    $room_name = '';
    if (!empty($room_id)) {
      $room_number = get_post_meta($room_id, 'hostpn_room_number', true);
      $room_name   = !empty($room_number) ? $room_number : get_the_title($room_id);
    }

    $replacements = [
      '[hostpn-host-name]'               => $cmeta('hostpn_contract_landlord_name'),
      '[hostpn-host-id]'                 => $cmeta('hostpn_contract_landlord_nif'),
      '[hostpn-host-address]'            => $cmeta('hostpn_contract_landlord_address'),
      '[hostpn-host-email]'              => $cmeta('hostpn_contract_landlord_email'),
      '[hostpn-guest-name]'              => $cmeta('hostpn_contract_tenant_name'),
      '[hostpn-guest-id-card]'           => $cmeta('hostpn_contract_tenant_nif'),
      '[hostpn-guest-address]'           => $cmeta('hostpn_contract_tenant_address'),
      '[hostpn-guest-email]'             => $cmeta('hostpn_contract_tenant_email'),
      '[hostpn-accommodation-address]'   => $full_address,
      '[hostpn-accommodation-city]'      => $city,
      '[hostpn-contract-duration]'       => $cmeta('hostpn_contract_duration'),
      '[hostpn-contract-start-date]'     => $start_fmt,
      '[hostpn-contract-end-date]'       => $end_fmt,
      '[hostpn-contract-rent-amount]'    => $cmeta('hostpn_contract_rent_amount'),
      '[hostpn-contract-rent-words]'     => $cmeta('hostpn_contract_rent_words'),
      '[hostpn-contract-deposit-amount]' => $cmeta('hostpn_contract_deposit_amount'),
      '[hostpn-contract-deposit-words]'  => $cmeta('hostpn_contract_deposit_words'),
      '[hostpn-room-name]'               => $room_name,
      '[hostpn-contract-notice-days]'    => $cmeta('hostpn_contract_notice_days'),
      '[hostpn-contract-payment-day]'    => $cmeta('hostpn_contract_payment_day'),
      '[hostpn-contract-bank-name]'      => $cmeta('hostpn_contract_bank_name'),
      '[hostpn-contract-iban]'           => $cmeta('hostpn_contract_iban'),
      '[hostpn-contract-guest-count]'    => $cmeta('hostpn_contract_guest_count'),
      '[hostpn-contract-checkin-time]'   => $cmeta('hostpn_contract_checkin_time'),
      '[hostpn-contract-checkout-time]'  => $cmeta('hostpn_contract_checkout_time'),
      '[hostpn-contract-total-price]'    => $cmeta('hostpn_contract_total_price'),
    ];

    return str_replace(array_keys($replacements), array_values($replacements), $text);
  }

  /**
   * Render a contract for public view (with signature pads).
   *
   * @param int $accommodation_id Post ID.
   * @return string Full HTML.
   */
  public static function hostpn_render_contract_public($accommodation_id) {
    $accommodation_type = get_post_meta($accommodation_id, 'hostpn_accommodation_type', true);
    $contract_type = self::hostpn_get_type_for_accommodation($accommodation_type);
    $template = self::hostpn_get_saved_template($contract_type);

    $html = self::hostpn_render_contract($contract_type, $template, $accommodation_id);

    // Add inventory
    $html .= self::hostpn_render_inventory($accommodation_id);

    return $html;
  }

  /**
   * Render inventory annex from contract html_multi items.
   * Combines global accommodation items with room-specific items when a room_id is provided.
   *
   * @param int $accommodation_id Post ID.
   * @param int $room_id          Room post ID (0 = no room items).
   * @return string HTML for inventory section, or empty if disabled.
   */
  public static function hostpn_render_inventory($accommodation_id, $room_id = 0) {
    $enabled = get_post_meta($accommodation_id, 'hostpn_contract_inventory_enabled', true);
    if (empty($enabled) || $enabled !== 'on') {
      return '';
    }

    $categories = [
      'mobiliario'              => __('Furniture', 'hostpn'),
      'equipamiento_individual' => __('Individual equipment', 'hostpn'),
      'menaje_individual'       => __('Individual kitchenware', 'hostpn'),
      'equipamiento_comunitario' => __('Community equipment', 'hostpn'),
      'otros_enseres'           => __('Other items', 'hostpn'),
    ];

    $html = '';
    $has_any = false;

    foreach ($categories as $cat_key => $cat_label) {
      // Accommodation-level items
      $accom_items = self::hostpn_collect_inventory_items(
        get_post_meta($accommodation_id, 'hostpn_contract_inv_' . $cat_key . '_name', true),
        get_post_meta($accommodation_id, 'hostpn_contract_inv_' . $cat_key . '_url', true)
      );
      // Room-level items
      $room_items = [];
      if (!empty($room_id) && get_post($room_id)) {
        $room_items = self::hostpn_collect_inventory_items(
          get_post_meta($room_id, 'hostpn_room_inv_' . $cat_key . '_name', true),
          get_post_meta($room_id, 'hostpn_room_inv_' . $cat_key . '_url', true)
        );
      }
      $merged = array_merge($accom_items, $room_items);
      if (!empty($merged)) {
        $has_any = true;
        $html .= '<div class="contract-inventory-section">';
        $html .= '<h3>' . esc_html($cat_label) . '</h3>';
        $html .= self::hostpn_render_inventory_table($merged);
        $html .= '</div>';
      }
    }

    if (!$has_any) {
      return '';
    }

    $result = '<h2 class="contract-inventory-title">' . esc_html__('ANNEX: LEASED ITEMS INVENTORY', 'hostpn') . '</h2>';
    $result .= $html;
    return $result;
  }

  /**
   * Collect inventory items from parallel name/url meta arrays.
   *
   * @param mixed $names Name meta values (array or empty).
   * @param mixed $urls  URL meta values (array or empty).
   * @return array Items with 'name' and 'url' keys.
   */
  private static function hostpn_collect_inventory_items($names, $urls) {
    if (!is_array($names)) {
      $names = [];
    }
    if (!is_array($urls)) {
      $urls = [];
    }

    $items = [];
    foreach ($names as $i => $name) {
      $name = trim($name);
      if (!empty($name)) {
        $url = isset($urls[$i]) ? trim($urls[$i]) : '';
        $items[] = ['name' => $name, 'url' => $url];
      }
    }
    return $items;
  }

  /**
   * Render a single inventory table from an items array.
   *
   * @param array $items Items with 'name' and 'url' keys.
   * @return string HTML table.
   */
  private static function hostpn_render_inventory_table($items) {
    $html = '<table class="contract-inventory-table"><thead><tr>';
    $html .= '<th>' . esc_html__('Item', 'hostpn') . '</th>';
    $html .= '<th>' . esc_html__('URL', 'hostpn') . '</th>';
    $html .= '</tr></thead><tbody>';

    foreach ($items as $item) {
      $html .= '<tr>';
      $html .= '<td>' . esc_html($item['name']) . '</td>';
      if (!empty($item['url'])) {
        $html .= '<td><a href="' . esc_url($item['url']) . '" target="_blank">' . esc_html($item['url']) . '</a></td>';
      } else {
        $html .= '<td></td>';
      }
      $html .= '</tr>';
    }

    $html .= '</tbody></table>';
    return $html;
  }
}

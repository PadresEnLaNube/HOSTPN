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
   * Get available contract types.
   *
   * @return array
   */
  public static function hostpn_get_contract_types() {
    return [
      'habitacion' => __('Room rental', 'hostpn'),
      'turistico'  => __('Tourist rental', 'hostpn'),
      'lau'        => __('LAU rental', 'hostpn'),
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
          'reunidos'        => __('REUNIDOS', 'hostpn'),
          'exponen'         => __('EXPONEN', 'hostpn'),
          'primera'         => __('PRIMERA - Object', 'hostpn'),
          'segunda'         => __('SEGUNDA - Duration', 'hostpn'),
          'tercera'         => __('TERCERA - Rent and payment', 'hostpn'),
          'cuarta'          => __('CUARTA - Supplies', 'hostpn'),
          'quinta'          => __('QUINTA - Deposit', 'hostpn'),
          'sexta'           => __('SEXTA - House rules', 'hostpn'),
          'septima'         => __('SEPTIMA - Works', 'hostpn'),
          'octava'          => __('OCTAVA - Assignment', 'hostpn'),
          'novena'          => __('NOVENA - Breach', 'hostpn'),
          'decima'          => __('DECIMA - Legislation', 'hostpn'),
          'firmas'          => __('SIGNATURES + INVENTORY', 'hostpn'),
        ];
        break;

      case 'turistico':
        $sections = [
          'reunidos'        => __('REUNIDOS', 'hostpn'),
          'exponen'         => __('EXPONEN', 'hostpn'),
          'primera'         => __('PRIMERA - Object', 'hostpn'),
          'segunda'         => __('SEGUNDA - Stay duration', 'hostpn'),
          'tercera'         => __('TERCERA - Price', 'hostpn'),
          'cuarta'          => __('CUARTA - Deposit', 'hostpn'),
          'quinta'          => __('QUINTA - Rules', 'hostpn'),
          'sexta'           => __('SEXTA - Inventory', 'hostpn'),
          'septima'         => __('SEPTIMA - Liability', 'hostpn'),
          'octava'          => __('OCTAVA - Legislation', 'hostpn'),
          'firmas'          => __('SIGNATURES', 'hostpn'),
        ];
        break;

      case 'lau':
        $sections = [
          'reunidos'        => __('REUNIDOS', 'hostpn'),
          'exponen'         => __('EXPONEN', 'hostpn'),
          'primera'         => __('PRIMERA - Object', 'hostpn'),
          'segunda'         => __('SEGUNDA - LAU duration', 'hostpn'),
          'tercera'         => __('TERCERA - Rent with CPI', 'hostpn'),
          'cuarta'          => __('CUARTA - LAU deposit', 'hostpn'),
          'quinta'          => __('QUINTA - Supplies', 'hostpn'),
          'sexta'           => __('SEXTA - Works', 'hostpn'),
          'septima'         => __('SEPTIMA - Assignment', 'hostpn'),
          'octava'          => __('OCTAVA - Termination', 'hostpn'),
          'novena'          => __('NOVENA - LAU legislation', 'hostpn'),
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
        '<h2>REUNIDOS</h2>'
        . '<p>De una parte, DON/DO&Ntilde;A <strong>[hostpn-host-name]</strong>, mayor de edad, con NIF/NIE <strong>[hostpn-host-id]</strong>, y domicilio a efectos de notificaciones en <strong>[hostpn-host-address]</strong>. En adelante, el &laquo;ARRENDADOR&raquo;.</p>'
        . '<p>Y de otra parte, DON/DO&Ntilde;A <strong>[hostpn-guest-name]</strong>, mayor de edad, con NIF/NIE <strong>[hostpn-guest-id-card]</strong>, y correo electr&oacute;nico <strong>[hostpn-guest-email]</strong>. En adelante, el &laquo;ARRENDATARIO&raquo;.</p>'
        . '<p>Ambas partes se reconocen mutuamente la capacidad legal suficiente para el otorgamiento del presente contrato de arrendamiento de habitaci&oacute;n y, al efecto,</p>',

      'exponen' =>
        '<h2>EXPONEN</h2>'
        . '<p><strong>I.</strong> Que el ARRENDADOR es titular/gestor leg&iacute;timo de la vivienda ubicada en <strong>[hostpn-accommodation-address]</strong>, <strong>[hostpn-accommodation-city]</strong>.</p>'
        . '<p><strong>II.</strong> Que el ARRENDATARIO est&aacute; interesado en alquilar el uso exclusivo de la Habitaci&oacute;n n&ordm; <strong>[hostpn-room-name]</strong> de dicha vivienda, as&iacute; como el derecho al uso compartido de las zonas comunes del piso.</p>'
        . '<p><strong>III.</strong> Que habiendo llegado ambas partes a un acuerdo, formalizan el presente contrato sujet&aacute;ndose a las siguientes:</p>',

      'primera' =>
        '<h3>PRIMERA. Objeto</h3>'
        . '<p>El ARRENDADOR cede en arrendamiento al ARRENDATARIO el uso exclusivo de la Habitaci&oacute;n n&ordm; <strong>[hostpn-room-name]</strong>, amueblada seg&uacute;n inventario anexo, ubicada en la vivienda sita en <strong>[hostpn-accommodation-address]</strong>.</p>'
        . '<p>El ARRENDATARIO tendr&aacute; derecho al uso compartido con los dem&aacute;s ocupantes de la vivienda de los servicios y zonas comunes: cocina, cuarto/s de ba&ntilde;o, tendedero y pasillos de distribuci&oacute;n.</p>',

      'segunda' =>
        '<h3>SEGUNDA. Duraci&oacute;n del contrato</h3>'
        . '<p>El presente contrato se estipula por un plazo de <strong>[hostpn-contract-duration]</strong> a contar desde el d&iacute;a <strong>[hostpn-contract-start-date]</strong> hasta el d&iacute;a <strong>[hostpn-contract-end-date]</strong>.</p>'
        . '<p>Llegada la fecha de vencimiento, el contrato se extinguir&aacute; de forma autom&aacute;tica. Si el ARRENDATARIO deseara desistir antes, deber&aacute; notificarlo con una antelaci&oacute;n m&iacute;nima de <strong>[hostpn-contract-notice-days] d&iacute;as</strong>.</p>',

      'tercera' =>
        '<h3>TERCERA. Renta y forma de pago</h3>'
        . '<p>La renta mensual pactada es de <strong>[hostpn-contract-rent-amount] EUROS</strong> ([hostpn-contract-rent-words] &euro;).</p>'
        . '<p>El pago se realizar&aacute; dentro de los primeros <strong>[hostpn-contract-payment-day]</strong> d&iacute;as de cada mes, mediante transferencia bancaria:</p>'
        . '<p>Entidad: <strong>[hostpn-contract-bank-name]</strong><br>IBAN: <strong>[hostpn-contract-iban]</strong></p>',

      'cuarta' =>
        '<h3>CUARTA. Suministros y gastos</h3>'
        . '<p>Los gastos de agua, electricidad, gas e internet se regular&aacute;n seg&uacute;n lo pactado entre las partes.</p>',

      'quinta' =>
        '<h3>QUINTA. Fianza</h3>'
        . '<p>El ARRENDATARIO entrega al ARRENDADOR <strong>[hostpn-contract-deposit-amount] EUROS</strong> ([hostpn-contract-deposit-words] &euro;) en concepto de fianza.</p>'
        . '<p>La fianza se devolver&aacute; dentro de los 30 d&iacute;as siguientes a la entrega de llaves, previa comprobaci&oacute;n del estado del inmueble.</p>',

      'sexta' =>
        '<h3>SEXTA. Normas de convivencia y uso</h3>'
        . '<p>El ARRENDATARIO se compromete a respetar las normas b&aacute;sicas de convivencia:</p>'
        . '<p><strong>Limpieza:</strong> Mantener la habitaci&oacute;n y colaborar en la limpieza de zonas comunes.</p>'
        . '<p><strong>Visitas:</strong> Limitadas a horario diurno. Prohibida la pernocta de terceros sin autorizaci&oacute;n.</p>'
        . '<p><strong>Mascotas:</strong> Prohibidas salvo autorizaci&oacute;n expresa.</p>'
        . '<p><strong>Tabaco:</strong> Prohibido fumar en la vivienda.</p>'
        . '<p><strong>Ruidos:</strong> Respetar el descanso comunitario a partir de las 23:00 horas.</p>',

      'septima' =>
        '<h3>S&Eacute;PTIMA. Obras y conservaci&oacute;n</h3>'
        . '<p>El ARRENDATARIO no podr&aacute; realizar obras ni modificaciones sin autorizaci&oacute;n escrita del ARRENDADOR.</p>'
        . '<p>Las peque&ntilde;as reparaciones por uso ordinario ser&aacute;n a cargo del ARRENDATARIO.</p>',

      'octava' =>
        '<h3>OCTAVA. Cesi&oacute;n y subarriendo</h3>'
        . '<p>Queda prohibida la cesi&oacute;n del contrato y el subarriendo parcial o total de la habitaci&oacute;n.</p>',

      'novena' =>
        '<h3>NOVENA. Incumplimiento</h3>'
        . '<p>El incumplimiento de las obligaciones dar&aacute; derecho a la parte cumplidora a exigir la resoluci&oacute;n del contrato con indemnizaci&oacute;n de da&ntilde;os y perjuicios.</p>',

      'decima' =>
        '<h3>D&Eacute;CIMA. Legislaci&oacute;n aplicable</h3>'
        . '<p>El presente contrato se rige por el C&oacute;digo Civil espa&ntilde;ol (Arts. 1542 y ss.), quedando excluido de la LAU al tratarse de un arrendamiento por habitaciones.</p>',

      'firmas' =>
        '<p style="margin-top:20pt;">Y para que as&iacute; conste, firman el presente contrato por duplicado ejemplar.</p>'
        . '<div class="contract-signatures">'
        . '<div class="contract-signature-block"><p><strong>EL ARRENDADOR</strong></p><div class="contract-signature-line"></div><p>Fdo.: [hostpn-host-name]</p></div>'
        . '<div class="contract-signature-block"><p><strong>EL ARRENDATARIO</strong></p><div class="contract-signature-line"></div><p>Fdo.: [hostpn-guest-name]</p></div>'
        . '</div>',
    ];
  }

  /**
   * Default template for tourist rental.
   */
  private static function hostpn_get_default_turistico() {
    return [
      'reunidos' =>
        '<h2>REUNIDOS</h2>'
        . '<p>De una parte, DON/DO&Ntilde;A <strong>[hostpn-host-name]</strong>, mayor de edad, con NIF/NIE <strong>[hostpn-host-id]</strong>, y domicilio en <strong>[hostpn-host-address]</strong>. En adelante, el &laquo;PROPIETARIO&raquo;.</p>'
        . '<p>Y de otra parte, DON/DO&Ntilde;A <strong>[hostpn-guest-name]</strong>, mayor de edad, con NIF/NIE <strong>[hostpn-guest-id-card]</strong>, y correo electr&oacute;nico <strong>[hostpn-guest-email]</strong>. En adelante, el &laquo;HU&Eacute;SPED&raquo;.</p>'
        . '<p>Ambas partes se reconocen capacidad legal suficiente y acuerdan lo siguiente:</p>',

      'exponen' =>
        '<h2>EXPONEN</h2>'
        . '<p><strong>I.</strong> Que el PROPIETARIO es titular leg&iacute;timo de la vivienda de uso tur&iacute;stico ubicada en <strong>[hostpn-accommodation-address]</strong>, <strong>[hostpn-accommodation-city]</strong>.</p>'
        . '<p><strong>II.</strong> Que el HU&Eacute;SPED desea alojarse temporalmente en dicha vivienda con fines tur&iacute;sticos o vacacionales.</p>'
        . '<p><strong>III.</strong> Que ambas partes formalizan el presente contrato de alojamiento tur&iacute;stico.</p>',

      'primera' =>
        '<h3>PRIMERA. Objeto</h3>'
        . '<p>El PROPIETARIO cede el uso temporal de la vivienda tur&iacute;stica ubicada en <strong>[hostpn-accommodation-address]</strong>, amueblada y equipada seg&uacute;n inventario, para uso exclusivo de alojamiento tur&iacute;stico.</p>'
        . '<p>N&uacute;mero m&aacute;ximo de hu&eacute;spedes: <strong>[hostpn-contract-guest-count]</strong>.</p>',

      'segunda' =>
        '<h3>SEGUNDA. Duraci&oacute;n de la estancia</h3>'
        . '<p>La estancia se pacta desde el d&iacute;a <strong>[hostpn-contract-start-date]</strong> hasta el d&iacute;a <strong>[hostpn-contract-end-date]</strong>.</p>'
        . '<p>Hora de entrada (check-in): <strong>[hostpn-contract-checkin-time]</strong>. Hora de salida (check-out): <strong>[hostpn-contract-checkout-time]</strong>.</p>'
        . '<p>La vivienda deber&aacute; ser desalojada a la hora de check-out indicada.</p>',

      'tercera' =>
        '<h3>TERCERA. Precio</h3>'
        . '<p>El precio total de la estancia es de <strong>[hostpn-contract-total-price] EUROS</strong>.</p>'
        . '<p>El pago se realizar&aacute; seg&uacute;n las condiciones acordadas entre las partes.</p>',

      'cuarta' =>
        '<h3>CUARTA. Fianza</h3>'
        . '<p>El HU&Eacute;SPED entrega al PROPIETARIO <strong>[hostpn-contract-deposit-amount] EUROS</strong> ([hostpn-contract-deposit-words] &euro;) en concepto de fianza.</p>'
        . '<p>Se devolver&aacute; tras la comprobaci&oacute;n del estado de la vivienda al finalizar la estancia.</p>',

      'quinta' =>
        '<h3>QUINTA. Normas de uso</h3>'
        . '<p>El HU&Eacute;SPED se compromete a:</p>'
        . '<p>- Usar la vivienda &uacute;nicamente como alojamiento tur&iacute;stico.</p>'
        . '<p>- No realizar actividades molestas, insalubres o il&iacute;citas.</p>'
        . '<p>- Respetar el descanso de los vecinos.</p>'
        . '<p>- No alojar a m&aacute;s personas de las indicadas.</p>'
        . '<p>- Prohibido fumar en el interior de la vivienda.</p>'
        . '<p>- Prohibida la tenencia de mascotas salvo autorizaci&oacute;n expresa.</p>',

      'sexta' =>
        '<h3>SEXTA. Inventario</h3>'
        . '<p>Se adjunta inventario de los enseres y equipamiento de la vivienda. El HU&Eacute;SPED deber&aacute; devolver la vivienda en el mismo estado en que la recibi&oacute;.</p>',

      'septima' =>
        '<h3>S&Eacute;PTIMA. Responsabilidad</h3>'
        . '<p>El HU&Eacute;SPED ser&aacute; responsable de los da&ntilde;os ocasionados en la vivienda, enseres o zonas comunes durante su estancia, salvo desgaste por uso normal.</p>'
        . '<p>El PROPIETARIO no ser&aacute; responsable de los objetos personales del HU&Eacute;SPED.</p>',

      'octava' =>
        '<h3>OCTAVA. Legislaci&oacute;n aplicable</h3>'
        . '<p>El presente contrato se rige por la normativa auton&oacute;mica de viviendas de uso tur&iacute;stico aplicable, y supletoriamente por el C&oacute;digo Civil.</p>'
        . '<p>Queda excluido de la Ley de Arrendamientos Urbanos conforme al art&iacute;culo 5.e) de la LAU.</p>',

      'firmas' =>
        '<p style="margin-top:20pt;">Y para que as&iacute; conste, firman el presente contrato.</p>'
        . '<div class="contract-signatures">'
        . '<div class="contract-signature-block"><p><strong>EL PROPIETARIO</strong></p><div class="contract-signature-line"></div><p>Fdo.: [hostpn-host-name]</p></div>'
        . '<div class="contract-signature-block"><p><strong>EL HU&Eacute;SPED</strong></p><div class="contract-signature-line"></div><p>Fdo.: [hostpn-guest-name]</p></div>'
        . '</div>',
    ];
  }

  /**
   * Default template for LAU rental.
   */
  private static function hostpn_get_default_lau() {
    return [
      'reunidos' =>
        '<h2>REUNIDOS</h2>'
        . '<p>De una parte, DON/DO&Ntilde;A <strong>[hostpn-host-name]</strong>, mayor de edad, con NIF/NIE <strong>[hostpn-host-id]</strong>, y domicilio en <strong>[hostpn-host-address]</strong>. En adelante, el &laquo;ARRENDADOR&raquo;.</p>'
        . '<p>Y de otra parte, DON/DO&Ntilde;A <strong>[hostpn-guest-name]</strong>, mayor de edad, con NIF/NIE <strong>[hostpn-guest-id-card]</strong>, y correo electr&oacute;nico <strong>[hostpn-guest-email]</strong>. En adelante, el &laquo;ARRENDATARIO&raquo;.</p>'
        . '<p>Ambas partes se reconocen capacidad legal suficiente y acuerdan lo siguiente:</p>',

      'exponen' =>
        '<h2>EXPONEN</h2>'
        . '<p><strong>I.</strong> Que el ARRENDADOR es propietario/titular leg&iacute;timo de la vivienda ubicada en <strong>[hostpn-accommodation-address]</strong>, <strong>[hostpn-accommodation-city]</strong>.</p>'
        . '<p><strong>II.</strong> Que el ARRENDATARIO desea arrendar dicha vivienda como residencia habitual y permanente.</p>'
        . '<p><strong>III.</strong> Que ambas partes formalizan el presente contrato de arrendamiento de vivienda al amparo de la Ley 29/1994, de 24 de noviembre, de Arrendamientos Urbanos (LAU).</p>',

      'primera' =>
        '<h3>PRIMERA. Objeto</h3>'
        . '<p>El ARRENDADOR arrienda al ARRENDATARIO la vivienda ubicada en <strong>[hostpn-accommodation-address]</strong>, <strong>[hostpn-accommodation-city]</strong>, para destinarla a vivienda habitual y permanente del ARRENDATARIO.</p>',

      'segunda' =>
        '<h3>SEGUNDA. Duraci&oacute;n</h3>'
        . '<p>El contrato se pacta por un plazo de <strong>[hostpn-contract-duration]</strong>, desde el <strong>[hostpn-contract-start-date]</strong> hasta el <strong>[hostpn-contract-end-date]</strong>.</p>'
        . '<p>Conforme al art. 9 de la LAU, si la duraci&oacute;n pactada fuera inferior a cinco a&ntilde;os (siete si el arrendador es persona jur&iacute;dica), el contrato se prorrogar&aacute; obligatoriamente por plazos anuales hasta alcanzar dicho plazo m&iacute;nimo, salvo que el ARRENDATARIO manifieste su voluntad de no renovar con 30 d&iacute;as de antelaci&oacute;n.</p>',

      'tercera' =>
        '<h3>TERCERA. Renta</h3>'
        . '<p>La renta mensual pactada es de <strong>[hostpn-contract-rent-amount] EUROS</strong> ([hostpn-contract-rent-words] &euro;).</p>'
        . '<p>El pago se realizar&aacute; dentro de los primeros <strong>[hostpn-contract-payment-day]</strong> d&iacute;as de cada mes mediante transferencia a:</p>'
        . '<p>Entidad: <strong>[hostpn-contract-bank-name]</strong><br>IBAN: <strong>[hostpn-contract-iban]</strong></p>'
        . '<p>La renta se actualizar&aacute; anualmente conforme al &iacute;ndice de referencia vigente seg&uacute;n la legislaci&oacute;n aplicable en el momento de la actualizaci&oacute;n.</p>',

      'cuarta' =>
        '<h3>CUARTA. Fianza</h3>'
        . '<p>El ARRENDATARIO entrega <strong>[hostpn-contract-deposit-amount] EUROS</strong> ([hostpn-contract-deposit-words] &euro;) como fianza legal (equivalente a un mes de renta, conforme al art. 36 LAU).</p>'
        . '<p>La fianza se devolver&aacute; al finalizar el contrato, previa comprobaci&oacute;n del estado de la vivienda, en el plazo de un mes desde la entrega de llaves.</p>',

      'quinta' =>
        '<h3>QUINTA. Gastos y suministros</h3>'
        . '<p>Los gastos de suministros (agua, electricidad, gas, telecomunicaciones) ser&aacute;n por cuenta del ARRENDATARIO durante la vigencia del contrato.</p>'
        . '<p>Los gastos de comunidad y el IBI ser&aacute;n por cuenta del ARRENDADOR, salvo pacto expreso en contrario.</p>',

      'sexta' =>
        '<h3>SEXTA. Obras y conservaci&oacute;n</h3>'
        . '<p>El ARRENDADOR realizar&aacute; las reparaciones necesarias para conservar la vivienda en condiciones de habitabilidad (art. 21 LAU), salvo deterioro imputable al ARRENDATARIO.</p>'
        . '<p>El ARRENDATARIO no podr&aacute; realizar obras que modifiquen la configuraci&oacute;n de la vivienda sin consentimiento escrito del ARRENDADOR (art. 23 LAU).</p>',

      'septima' =>
        '<h3>S&Eacute;PTIMA. Cesi&oacute;n y subarriendo</h3>'
        . '<p>El ARRENDATARIO no podr&aacute; ceder ni subarrendar la vivienda, total ni parcialmente, sin consentimiento escrito del ARRENDADOR (art. 8 LAU).</p>',

      'octava' =>
        '<h3>OCTAVA. Resoluci&oacute;n del contrato</h3>'
        . '<p>Ser&aacute;n causas de resoluci&oacute;n las previstas en el art. 27 de la LAU, especialmente: falta de pago de la renta, subarriendo no consentido, da&ntilde;os dolosos, obras no consentidas, y actividades molestas, insalubres, nocivas, peligrosas o il&iacute;citas.</p>'
        . '<p>El ARRENDATARIO podr&aacute; desistir del contrato una vez transcurridos seis meses, comunic&aacute;ndolo con 30 d&iacute;as de antelaci&oacute;n (art. 11 LAU).</p>',

      'novena' =>
        '<h3>NOVENA. Legislaci&oacute;n aplicable</h3>'
        . '<p>El presente contrato se rige por la Ley 29/1994, de 24 de noviembre, de Arrendamientos Urbanos (LAU), y supletoriamente por el C&oacute;digo Civil.</p>'
        . '<p>Para la resoluci&oacute;n de controversias, ambas partes se someten a los Juzgados y Tribunales del lugar donde se ubica la vivienda.</p>',

      'firmas' =>
        '<p style="margin-top:20pt;">Y para que as&iacute; conste, firman el presente contrato por duplicado.</p>'
        . '<div class="contract-signatures">'
        . '<div class="contract-signature-block"><p><strong>EL ARRENDADOR</strong></p><div class="contract-signature-line"></div><p>Fdo.: [hostpn-host-name]</p></div>'
        . '<div class="contract-signature-block"><p><strong>EL ARRENDATARIO</strong></p><div class="contract-signature-line"></div><p>Fdo.: [hostpn-guest-name]</p></div>'
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
      'hostpn-guest-name'             => __('Tenant full name', 'hostpn'),
      'hostpn-guest-id-card'          => __('Tenant NIF/NIE', 'hostpn'),
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
      'hostpn-guest-name'             => 'hostpn_contract_tenant_name',
      'hostpn-guest-id-card'          => 'hostpn_contract_tenant_nif',
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
      'hostpn-room-name'              => 'hostpn_contract_room_id',
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
   * @param string $text Text with shortcodes.
   * @param int    $accommodation_id Post ID.
   * @return string
   */
  public static function hostpn_resolve_shortcodes($text, $accommodation_id) {
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
      '[hostpn-guest-name]'             => $meta('hostpn_contract_tenant_name'),
      '[hostpn-guest-id-card]'          => $meta('hostpn_contract_tenant_nif'),
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
      '[hostpn-room-name]'              => $meta('hostpn_contract_room_id'),
      '[hostpn-contract-notice-days]'   => $meta('hostpn_contract_notice_days'),
      '[hostpn-contract-payment-day]'   => $meta('hostpn_contract_payment_day'),
      '[hostpn-contract-bank-name]'     => $meta('hostpn_contract_bank_name'),
      '[hostpn-contract-iban]'          => $meta('hostpn_contract_iban'),
      '[hostpn-contract-guest-count]'   => $meta('hostpn_contract_guest_count'),
      '[hostpn-contract-checkin-time]'  => $meta('hostpn_contract_checkin_time'),
      '[hostpn-contract-checkout-time]' => $meta('hostpn_contract_checkout_time'),
      '[hostpn-contract-total-price]'   => $meta('hostpn_contract_total_price'),
    ];

    return str_replace(array_keys($replacements), array_values($replacements), $text);
  }

  /**
   * Render a full contract HTML from template.
   *
   * @param string $contract_type Contract type key.
   * @param array  $template Template sections array.
   * @param int    $accommodation_id Post ID.
   * @return string Full HTML of the contract.
   */
  public static function hostpn_render_contract($contract_type, $template, $accommodation_id) {
    $types = self::hostpn_get_contract_types();
    $type_label = isset($types[$contract_type]) ? $types[$contract_type] : '';

    $title_map = [
      'habitacion' => 'CONTRATO DE ARRENDAMIENTO DE HABITACI&Oacute;N EN VIVIENDA COMPARTIDA',
      'turistico'  => 'CONTRATO DE ALOJAMIENTO TUR&Iacute;STICO',
      'lau'        => 'CONTRATO DE ARRENDAMIENTO DE VIVIENDA',
    ];
    $title = isset($title_map[$contract_type]) ? $title_map[$contract_type] : '';

    $html = '<h1>' . $title . '</h1>';

    foreach ($template as $section_key => $section_content) {
      $resolved = self::hostpn_resolve_shortcodes($section_content, $accommodation_id);
      $html .= $resolved;
    }

    return $html;
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
   * Render inventory table from accommodation features.
   *
   * @param int $accommodation_id Post ID.
   * @return string HTML for inventory section.
   */
  public static function hostpn_render_inventory($accommodation_id) {
    $accommodation_features = HOSTPN_i18n::hostpn_get_accommodation_features();
    $feature_categories = [];

    foreach ($accommodation_features as $cat_key => $cat_data) {
      $items = [];
      foreach ($cat_data['features'] as $meta_key => $label) {
        $val = get_post_meta($accommodation_id, $meta_key, true);
        if (!empty($val) && $val === 'on') {
          $items[] = $label;
        }
      }
      $custom_key = 'hostpn_' . $cat_key . '_custom_name';
      $custom_vals = get_post_meta($accommodation_id, $custom_key, true);
      if (is_array($custom_vals)) {
        foreach ($custom_vals as $cv) {
          if (!empty($cv)) $items[] = $cv;
        }
      }
      if (!empty($items)) {
        $feature_categories[$cat_data['title']] = $items;
      }
    }

    $html = '<div class="contract-page-break"></div>';
    $html .= '<h2>' . esc_html__('ANNEX: ACCOMMODATION INVENTORY', 'hostpn') . '</h2>';

    if (!empty($feature_categories)) {
      $html .= '<table class="contract-inventory-table"><thead><tr>';
      $html .= '<th>' . esc_html__('Category', 'hostpn') . '</th>';
      $html .= '<th>' . esc_html__('Items', 'hostpn') . '</th>';
      $html .= '</tr></thead><tbody>';
      foreach ($feature_categories as $cat_title => $items) {
        $html .= '<tr><td><strong>' . esc_html($cat_title) . '</strong></td>';
        $html .= '<td>' . esc_html(implode(', ', $items)) . '</td></tr>';
      }
      $html .= '</tbody></table>';
    } else {
      $html .= '<p><em>' . esc_html__('No items have been selected for the inventory.', 'hostpn') . '</em></p>';
    }

    return $html;
  }
}

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
        . '<p>De una parte, DON/DO&Ntilde;A <strong>[hostpn-host-name]</strong>, mayor de edad, con NIF <strong>[hostpn-host-id]</strong>, y domicilio a efectos de notificaciones en <strong>[hostpn-host-address]</strong>. En adelante, el &laquo;ARRENDADOR&raquo;.</p>'
        . '<p>Y de otra parte, DON/DO&Ntilde;A <strong>[hostpn-guest-name]</strong>, mayor de edad, con NIF <strong>[hostpn-guest-id-card]</strong>, y correo electr&oacute;nico <strong>[hostpn-guest-email]</strong>. En adelante, el &laquo;ARRENDATARIO&raquo;.</p>'
        . '<p>Ambas partes se reconocen mutuamente la capacidad legal suficiente para el otorgamiento del presente contrato de arrendamiento de habitaci&oacute;n y, al efecto,</p>',

      'exponen' =>
        '<h2>EXPONEN</h2>'
        . '<p><strong>I.</strong> Que el ARRENDADOR es titular/gestor leg&iacute;timo de la vivienda ubicada en <strong>[hostpn-accommodation-address]</strong>, piso que consta de varias habitaciones y zonas comunes.</p>'
        . '<p><strong>II.</strong> Que el ARRENDATARIO est&aacute; interesado en alquilar el uso exclusivo de la <strong>Habitaci&oacute;n [hostpn-room-name]</strong> de dicha vivienda, as&iacute; como el derecho al uso compartido de las zonas comunes del piso (cocina, ba&ntilde;o y pasillos si lo hubiere).</p>'
        . '<p><strong>III.</strong> Que habiendo llegado ambas partes a un acuerdo, formalizan el presente contrato sujet&aacute;ndose a las siguientes:</p>',

      'primera' =>
        '<h3>CL&Aacute;USULAS</h3>'
        . '<h3>PRIMERA. Objeto</h3>'
        . '<p>El ARRENDADOR cede en arrendamiento al ARRENDATARIO el uso exclusivo de la <strong>Habitaci&oacute;n [hostpn-room-name]</strong> amueblada seg&uacute;n inventario anexo, ubicada en la vivienda compartida sita en <strong>[hostpn-accommodation-address]</strong>.</p>'
        . '<p>El ARRENDATARIO tendr&aacute; derecho al uso compartido con los dem&aacute;s ocupantes de la vivienda de los servicios y zonas comunes: cocina, cuarto de ba&ntilde;o, tendedero y pasillos de distribuci&oacute;n. Queda expresamente excluido el uso exclusivo de cualquier otra habitaci&oacute;n del piso.</p>',

      'segunda' =>
        '<h3>SEGUNDA. Duraci&oacute;n del contrato</h3>'
        . '<p>El presente contrato se estipula por un plazo de <strong>[hostpn-contract-duration]</strong> a contar desde el d&iacute;a <strong>[hostpn-contract-start-date]</strong> hasta el d&iacute;a <strong>[hostpn-contract-end-date]</strong>.</p>'
        . '<p>Llegada la fecha de vencimiento, el contrato se extinguir&aacute; de forma autom&aacute;tica sin necesidad de requerimiento previo. Si el ARRENDATARIO deseara desistir del contrato antes de su finalizaci&oacute;n, deber&aacute; notificarlo al ARRENDADOR con una antelaci&oacute;n m&iacute;nima de <strong>[hostpn-contract-notice-days] d&iacute;as</strong> naturales. En caso de incumplimiento de dicho plazo, indemnizar&aacute; al ARRENDADOR con la parte proporcional de la renta equivalente a los d&iacute;as de preaviso no cumplidos.</p>',

      'tercera' =>
        '<h3>TERCERA. Renta y forma de pago</h3>'
        . '<p>La renta mensual pactada es de <strong>[hostpn-contract-rent-amount] EUROS</strong> (<strong>[hostpn-contract-rent-words]</strong> &euro;).</p>'
        . '<p>El pago de la renta se realizar&aacute; de forma anticipada dentro de los primeros <strong>[hostpn-contract-payment-day]</strong> d&iacute;as de cada mes, mediante transferencia bancaria o ingreso en la cuenta corriente titularidad del ARRENDADOR:</p>'
        . '<p>Entidad Bancaria: <strong>[hostpn-contract-bank-name]</strong><br>'
        . 'IBAN: <strong>[hostpn-contract-iban]</strong></p>',

      'cuarta' =>
        '<h3>CUARTA. Suministros y gastos</h3>'
        . '<p><strong>Gastos incluidos:</strong> En el precio de la renta mensual est&aacute;n incluidos los gastos de agua, comunidad, IBI, basuras e Internet de banda ancha.</p>'
        . '<p><strong>Gastos no incluidos:</strong> Los gastos de electricidad no est&aacute;n incluidos en la renta y se abonar&aacute;n mensualmente de forma proporcional entre las habitaciones del piso en lo relativo a consumos comunes (termo de agua caliente, electrodom&eacute;sticos y luces de pasillos y cocina) y de forma individual en lo relativo a los consumos individuales del ARRENDATARIO en su habitaci&oacute;n.</p>',

      'quinta' =>
        '<h3>QUINTA. Pagos y Fianza</h3>'
        . '<p>A la firma del presente contrato, el ARRENDATARIO hace entrega al ARRENDADOR de la cantidad de <strong>[hostpn-contract-deposit-amount] EUROS</strong> (<strong>[hostpn-contract-deposit-words]</strong> &euro;) en concepto de fianza.</p>'
        . '<p>La fianza responder&aacute; del cumplimiento de las obligaciones contractuales, de la devoluci&oacute;n de la habitaci&oacute;n y sus enseres en el mismo estado en que se recibieron, y del pago de rentas o suministros pendientes. Se devolver&aacute; dentro de los 30 d&iacute;as siguientes a la entrega de llaves, previa comprobaci&oacute;n del estado del inmueble. En ning&uacute;n caso la fianza servir&aacute; como pago de la &uacute;ltima mensualidad de renta.</p>'
        . '<p>Si se quiere reservar la habitaci&oacute;n con antelaci&oacute;n se puede aportar la mitad de la fianza como se&ntilde;al de forma que esta quedar&aacute; reservada hasta un m&aacute;ximo de 7 d&iacute;as desde la recepci&oacute;n del dinero.</p>',

      'sexta' =>
        '<h3>SEXTA. Normas de convivencia y uso</h3>'
        . '<p>El ARRENDATARIO se compromete a respetar las normas b&aacute;sicas de convivencia con los dem&aacute;s compa&ntilde;eros/as de piso:</p>'
        . '<p><strong>Limpieza:</strong> Mantener en perfecto estado de limpieza y orden la habitaci&oacute;n arrendada y colaborar activamente en el turno de limpieza de las zonas comunes.</p>'
        . '<p><strong>Visitas y pernocta:</strong> Las visitas quedan limitadas a horario diurno. Queda expresamente prohibida la pernocta de terceras personas ajenas al contrato en la habitaci&oacute;n sin el consentimiento previo y por escrito del ARRENDADOR y de los dem&aacute;s convivientes.</p>'
        . '<p><strong>Mascotas:</strong> Queda prohibida la tenencia de animales en la vivienda, salvo autorizaci&oacute;n expresa por escrito del ARRENDADOR.</p>'
        . '<p><strong>Tabaco y sustancias:</strong> Queda estrictamente prohibido fumar o consumir drogas en la habitaci&oacute;n y en las zonas comunes de la vivienda.</p>'
        . '<p><strong>Ruidos:</strong> Se respetar&aacute; el descanso comunitario, evitando ruidos o fiestas a partir de las 23:00 horas.</p>',

      'septima' =>
        '<h3>S&Eacute;PTIMA. Obras y conservaci&oacute;n</h3>'
        . '<p>El ARRENDATARIO no podr&aacute; realizar obras, modificaciones ni taladros en las paredes de la habitaci&oacute;n ni de las zonas comunes sin autorizaci&oacute;n escrita del ARRENDADOR.</p>'
        . '<p>Las peque&ntilde;as reparaciones que exija el desgaste por el uso ordinario de la habitaci&oacute;n y sus enseres ser&aacute;n a cargo del ARRENDATARIO.</p>',

      'octava' =>
        '<h3>OCTAVA. Cesi&oacute;n y subarriendo</h3>'
        . '<p>Queda expresamente prohibida la cesi&oacute;n del contrato, as&iacute; como el subarriendo parcial o total de la habitaci&oacute;n a terceras personas.</p>',

      'novena' =>
        '<h3>NOVENA. Incumplimiento de contrato</h3>'
        . '<p>El incumplimiento por cualquiera de las partes de las obligaciones derivadas de este contrato dar&aacute; derecho a la parte que hubiere cumplido a exigir la resoluci&oacute;n del contrato o su cumplimiento, con la correspondiente indemnizaci&oacute;n de da&ntilde;os y perjuicios.</p>'
        . '<p>Ser&aacute; causa especial de resoluci&oacute;n la falta de pago de la renta o de la fianza, la realizaci&oacute;n de actividades molestas, insalubres, nocivas o il&iacute;citas, o la infracci&oacute;n de las normas de convivencia fijadas en la cl&aacute;usula sexta.</p>',

      'decima' =>
        '<h3>D&Eacute;CIMA. Legislaci&oacute;n aplicable y jurisdicci&oacute;n</h3>'
        . '<p>El presente contrato se rige por lo libremente pactado por las partes y, en su defecto, por las disposiciones del C&oacute;digo Civil espa&ntilde;ol (Arts. 1542 y ss.), quedando expresamente excluido de la Ley de Arrendamientos Urbanos (LAU) al tratarse de un arrendamiento por habitaciones.</p>'
        . '<p>Para la resoluci&oacute;n de cualquier controversia judicial que pudiera derivarse de la interpretaci&oacute;n o cumplimiento de este contrato, ambas partes se someten a la jurisdicci&oacute;n de los Juzgados y Tribunales del lugar donde se encuentra ubicada la vivienda.</p>',

      'firmas' =>
        '<p style="margin-top:20pt;">Y para que as&iacute; conste, firman el presente contrato por duplicado ejemplar y a un solo efecto, en el lugar y fecha arriba indicados.</p>'
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
      '[hostpn-guest-name]'              => $cmeta('hostpn_contract_tenant_name'),
      '[hostpn-guest-id-card]'           => $cmeta('hostpn_contract_tenant_nif'),
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
   *
   * @param int $accommodation_id Post ID.
   * @return string HTML for inventory section, or empty if disabled.
   */
  public static function hostpn_render_inventory($accommodation_id) {
    $enabled = get_post_meta($accommodation_id, 'hostpn_contract_inventory_enabled', true);
    if (empty($enabled) || $enabled !== 'on') {
      return '';
    }

    $names = get_post_meta($accommodation_id, 'hostpn_contract_inventory_name', true);
    $urls  = get_post_meta($accommodation_id, 'hostpn_contract_inventory_url', true);

    if (!is_array($names)) {
      $names = [];
    }
    if (!is_array($urls)) {
      $urls = [];
    }

    // Filter out empty rows
    $items = [];
    foreach ($names as $i => $name) {
      $name = trim($name);
      if (!empty($name)) {
        $url = isset($urls[$i]) ? trim($urls[$i]) : '';
        $items[] = ['name' => $name, 'url' => $url];
      }
    }

    if (empty($items)) {
      return '';
    }

    $html = '<div class="contract-page-break"></div>';
    $html .= '<h2>' . esc_html__('ANEXO: LISTADO DE ENSERES ARRENDADOS', 'hostpn') . '</h2>';
    $html .= '<table class="contract-inventory-table"><thead><tr>';
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

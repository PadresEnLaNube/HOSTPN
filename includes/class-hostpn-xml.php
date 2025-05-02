<?php
/**
 * Load XML files.
 *
 * Loads the plugin XML files getting them from database, processing and managing downloads.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_XML {
	/**
	 * Load and download XML file.
	 *
	 * @since    1.0.0
	 */
	public function part_download($part_id) {
    $accommodation_id = get_post_meta($part_id, 'hostpn_accommodation_id', true);
    $accommodation_code = get_post_meta($accommodation_id, 'hostpn_accommodation_code', true);

    $part_reference = get_post_meta($part_id, 'hostpn_reference', true);

    $part_date = self::timestamp_to_iso8601(get_post_meta($part_id, 'hostpn_date', true), true);
    $check_in_date = self::timestamp_to_iso8601(get_post_meta($part_id, 'hostpn_check_in_date', true) . ' ' . get_post_meta($part_id, 'hostpn_check_in_time', true));
    $check_out_date = self::timestamp_to_iso8601(get_post_meta($part_id, 'hostpn_check_out_date', true) . ' ' . get_post_meta($part_id, 'hostpn_check_out_time', true));
    $part_people_number = get_post_meta($part_id, 'hostpn_people_number', true);
    $part_rooms = get_post_meta($part_id, 'hostpn_rooms', true);
    $part_internet = (get_post_meta($part_id, 'hostpn_internet', true) == 'on') ? 1 : 0;
    $payment_type = strtoupper(get_post_meta($part_id, 'hostpn_payment_type', true));
    $payment_date = self::timestamp_to_iso8601(get_post_meta($part_id, 'hostpn_payment_date', true), true);
    $payment_method = strtoupper(get_post_meta($part_id, 'hostpn_payment_method', true));
    $payment_holder = get_post_meta($part_id, 'hostpn_payment_holder', true);
    $payment_expiration = get_post_meta($part_id, 'hostpn_payment_expiration', true);
    $people = get_post_meta($part_id, 'hostpn_people', true);

    $dom = new DOMDocument('1.0', 'UTF-8');
    
    $root = $dom->createElement('ns2:peticion');
    $root->setAttribute('xmlns:ns2', 'http://www.neg.hospedajes.mir.es/altaParteHospedaje');
		  $root = $dom->appendChild($root);

		$solicitud = $dom->createElement('solicitud');
    $solicitud = $root->appendChild($solicitud);
      $solicitud->appendChild($dom->createElement('codigoEstablecimiento', $accommodation_code));
    $comunicacion = $solicitud->appendChild($dom->createElement('comunicacion'));
    $contrato = $comunicacion->appendChild($dom->createElement('contrato'));
      $contrato->appendChild($dom->createElement('referencia', $part_reference));
      $contrato->appendChild($dom->createElement('fechaContrato', $part_date));
      $contrato->appendChild($dom->createElement('fechaEntrada', $check_in_date));
      $contrato->appendChild($dom->createElement('fechaSalida', $check_out_date));
      $contrato->appendChild($dom->createElement('numPersonas', $part_people_number));
      $contrato->appendChild($dom->createElement('numHabitaciones', $part_rooms));
      $contrato->appendChild($dom->createElement('internet', $part_internet));
      $pago = $contrato->appendChild($dom->createElement('pago'));
        $pago->appendChild($dom->createElement('tipoPago', $payment_type));
        $pago->appendChild($dom->createElement('fechaPago', $payment_date));
        $pago->appendChild($dom->createElement('medioPago', $payment_method));
        $pago->appendChild($dom->createElement('titular', $payment_holder));
        $pago->appendChild($dom->createElement('caducidadTarjeta', $payment_expiration));

      if (!empty($people)) {
        foreach ($people as $guest_id) {
          $role = 'VI';
          $name = get_post_meta($guest_id, 'hostpn_name', true);
          $surname = get_post_meta($guest_id, 'hostpn_surname', true);
          $surname_alt = get_post_meta($guest_id, 'hostpn_surname_alt', true);
          $identity = strtoupper(get_post_meta($guest_id, 'hostpn_identity', true));
          $identity_number = get_post_meta($guest_id, 'hostpn_identity_number', true);
          $identity_support = get_post_meta($guest_id, 'hostpn_identity_support_number', true);
          $birthdate = self::timestamp_to_iso8601(get_post_meta($guest_id, 'hostpn_birthdate', true), true);
          $nationality = strtoupper(get_post_meta($guest_id, 'hostpn_nationality', true));
          $gender = strtoupper(get_post_meta($guest_id, 'hostpn_gender', true));
          $address = get_post_meta($guest_id, 'hostpn_address', true);
          $address_alt = get_post_meta($guest_id, 'hostpn_address_alt', true);
          $country = strtoupper(get_post_meta($guest_id, 'hostpn_country', true));
          $city = get_post_meta($guest_id, 'hostpn_city', true);
          $phone = get_post_meta($guest_id, 'hostpn_phone', true);
          $phone_alt = get_post_meta($guest_id, 'hostpn_phone_alt', true);
          $postal_code = get_post_meta($guest_id, 'hostpn_postal_code', true);
          $city_code = get_post_meta($guest_id, 'hostpn_city_code', true);
          $email = get_post_meta($guest_id, 'hostpn_email', true);
          $relationship = strtoupper(get_post_meta($guest_id, 'hostpn_relationship', true));

          $persona = $comunicacion->appendChild($dom->createElement('persona'));
            $persona->appendChild($dom->createElement('rol', $role));
            $persona->appendChild($dom->createElement('nombre', $name));
            $persona->appendChild($dom->createElement('apellido1', $surname));
            $persona->appendChild($dom->createElement('apellido2', $surname_alt));
            $persona->appendChild($dom->createElement('tipoDocumento', $identity));
            $persona->appendChild($dom->createElement('numeroDocumento', $identity_number));

            if (in_array($identity, ['NIF', 'NIE'])) {
              $persona->appendChild($dom->createElement('soporteDocumento', $identity_support));
            }

            $persona->appendChild($dom->createElement('fechaNacimiento', $birthdate));
            $persona->appendChild($dom->createElement('nacionalidad', $nationality));
            $persona->appendChild($dom->createElement('sexo', $gender));
            $direccion = $persona->appendChild($dom->createElement('direccion'));
              $direccion->appendChild($dom->createElement('direccion', $address));
              $direccion->appendChild($dom->createElement('direccionComplementaria', $address_alt));

              if ($country == 'ESP') {
                $direccion->appendChild($dom->createElement('codigoMunicipio', $city_code));
              }

              $direccion->appendChild($dom->createElement('nombreMunicipio', value: $city));
              $direccion->appendChild($dom->createElement('codigoPostal', $postal_code));
              $direccion->appendChild($dom->createElement('pais', $country));
            $persona->appendChild($dom->createElement('telefono', $phone));
            $persona->appendChild($dom->createElement('telefono2', $phone_alt));
            $persona->appendChild($dom->createElement('correo', $email));
            $persona->appendChild($dom->createElement('parentesco', $relationship));
        }
      }
    $dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;

    // header_remove();
    echo $dom->saveXML();
    exit();
	}

  public function timestamp_to_iso8601($timestamp, $date_only = false) {
    date_default_timezone_set('Europe/Madrid');
    $date_time = new DateTime($timestamp);

    if ($date_only) {
      return $date_time->format('Y-m-d');
    }else{
      return $date_time->format(DateTime::ATOM);
    }
  }
}
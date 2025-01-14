<?php
/**
 * Load XML files.
 *
 * Loads the plugin XML files getting them from database, processing and managing downloads.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@padresenlanube.com>
 */
class HOSTWPH_XML {
	/**
	 * Load and download XML file.
	 *
	 * @since    1.0.0
	 */
	public function part_download($part_id) {
    $dom = new DOMDocument('1.0', 'UTF-8');
    $root = $dom->createElement('ns2:peticion');
    $root->setAttribute('xmlns:ns2', 'http://www.neg.hospedajes.mir.es/altaParteHospedaje');
		$root = $dom->appendChild($root);


		$solicitud = $dom->createElement('solicitud');
    $solicitud = $root->appendChild($solicitud);
    $solicitud->appendChild($dom->createElement('codigoEstablecimiento', 'codigoEstablecimiento'));
    
    $comunicacion = $solicitud->appendChild($dom->createElement('comunicacion'));
    
    $contrato = $comunicacion->appendChild($dom->createElement('contrato'));
    $contrato->appendChild($dom->createElement('referencia', 'string'));
    $contrato->appendChild($dom->createElement('fechaContrato', 'string'));
    $contrato->appendChild($dom->createElement('fechaEntrada', 'string'));
    $contrato->appendChild($dom->createElement('fechaSalida', 'string'));
    $contrato->appendChild($dom->createElement('numPersonas', 'string'));
    $contrato->appendChild($dom->createElement('numHabitaciones', 'string'));
    $contrato->appendChild($dom->createElement('internet', 'string'));
    
    $pago = $contrato->appendChild($dom->createElement('pago'));
    $pago->appendChild($dom->createElement('tipoPago', 'string'));
    $pago->appendChild($dom->createElement('fechaPago', 'string'));
    $pago->appendChild($dom->createElement('medioPago', 'string'));
    $pago->appendChild($dom->createElement('titular', 'string'));
    $pago->appendChild($dom->createElement('caducidadTarjeta', 'string'));

    $persona = $comunicacion->appendChild($dom->createElement('persona'));
    $persona->appendChild($dom->createElement('rol', 'string'));
    $persona->appendChild($dom->createElement('nombre', 'string'));
    $persona->appendChild($dom->createElement('apellido1', 'string'));
    $persona->appendChild($dom->createElement('apellido2', 'string'));
    $persona->appendChild($dom->createElement('tipoDocumento', 'string'));
    $persona->appendChild($dom->createElement('numeroDocumento', 'string'));
    $persona->appendChild($dom->createElement('soporteDocumento', 'string'));
    $persona->appendChild($dom->createElement('fechaNacimiento', 'string'));
    $persona->appendChild($dom->createElement('nacionalidad', 'string'));
    $persona->appendChild($dom->createElement('sexo', 'string'));
    
    $direccion = $persona->appendChild($dom->createElement('direccion'));
    $direccion->appendChild($dom->createElement('direccion', 'string'));
    $direccion->appendChild($dom->createElement('direccionComplementaria', 'string'));
    $direccion->appendChild($dom->createElement('codigoMunicipio', 'string'));
    $direccion->appendChild($dom->createElement('nombreMunicipio', 'string'));
    $direccion->appendChild($dom->createElement('codigoPostal', 'string'));
    $direccion->appendChild($dom->createElement('pais', 'string'));

    $persona->appendChild($dom->createElement('telefono', 'string'));
    $persona->appendChild($dom->createElement('telefono2', 'string'));
    $persona->appendChild($dom->createElement('correo', 'string'));
    $persona->appendChild($dom->createElement('parentesco', 'string'));
    
    $dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;

    ob_end_clean();
		header_remove();

    header('Content-type: text/xml');
		header('Content-Disposition: attachment; filename="text.xml"');

    echo $dom->saveXML();
    exit();
	}
}
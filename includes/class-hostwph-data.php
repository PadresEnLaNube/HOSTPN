<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin so that it is ready for translation.
 *
 * @link       wordpress-heroes.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@wordpress-heroes.com>
 */
class HOSTWPH_Data {
	/**
	 * The main data array.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      HOSTWPH_Data    $data    Empty array.
	 */
	protected $data = [];

	/**
	 * Load the plugin most usefull data.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_data() {
		$this->data['user_id'] = get_current_user_id();

		if (is_admin()) {
			$this->data['post_id'] = !empty($GLOBALS['_REQUEST']['post']) ? $GLOBALS['_REQUEST']['post'] : 0;
		}else{
			$this->data['post_id'] = get_the_ID();
		}

		$GLOBALS['hostwph_data'] = $this->data;
	}

	/**
	 * Flus wp rewrite rules.
	 *
	 * @since    1.0.0
	 */
	public function flush_rewrite_rules() {
    if (get_option('hostwph_options_changed')) {
      flush_rewrite_rules();
      update_option('hostwph_options_changed', false);
    }
  }

  /**
	 * Get buttons mini loader.
	 *
	 * @since    1.0.0
	 */
	public static function loader() {
		?>
			<div class="hostwph-waiting hostwph-display-inline hostwph-display-none-soft">
				<div class="hostwph-loader-circle-waiting"><div></div><div></div><div></div><div></div></div>
			</div>
		<?php
  }

  /**
	 * Load countries.
	 *
	 * @since    1.0.0
	 */
	public static function countries() {
		// HOSTWPH_Data::countries();
    return ['af' => esc_html(__('Afghanistan', 'hostwph')), 'ax' => esc_html(__('Aland Islands', 'hostwph')), 'al' => esc_html(__('Albania', 'hostwph')), 'dz' => esc_html(__('Algeria', 'hostwph')), 'as' => esc_html(__('American Samoa', 'hostwph')), 'ad' => esc_html(__('Andorra', 'hostwph')), 'ao' => esc_html(__('Angola', 'hostwph')), 'ai' => esc_html(__('Anguilla', 'hostwph')), 'aq' => esc_html(__('Antarctica', 'hostwph')), 'ag' => esc_html(__('Antigua and Barbuda', 'hostwph')), 'ar' => esc_html(__('Argentina', 'hostwph')), 'am' => esc_html(__('Armenia', 'hostwph')), 'aw' => esc_html(__('Aruba', 'hostwph')), 'au' => esc_html(__('Australia', 'hostwph')), 'at' => esc_html(__('Austria', 'hostwph')), 'az' => esc_html(__('Azerbaijan', 'hostwph')), 'bs' => esc_html(__('Bahamas the', 'hostwph')), 'bh' => esc_html(__('Bahrain', 'hostwph')), 'bd' => esc_html(__('Bangladesh', 'hostwph')), 'bb' => esc_html(__('Barbados', 'hostwph')), 'by' => esc_html(__('Belarus', 'hostwph')), 'be' => esc_html(__('Belgium', 'hostwph')), 'bz' => esc_html(__('Belize', 'hostwph')), 'bj' => esc_html(__('Benin', 'hostwph')), 'bm' => esc_html(__('Bermuda', 'hostwph')), 'bt' => esc_html(__('Bhutan', 'hostwph')), 'bo' => esc_html(__('Bolivia', 'hostwph')), 'ba' => esc_html(__('Bosnia and Herzegovina', 'hostwph')), 'bw' => esc_html(__('Botswana', 'hostwph')), 'bv' => esc_html(__('Bouvet Island (Bouvetoya)', 'hostwph')), 'br' => esc_html(__('Brazil', 'hostwph')), 'io' => esc_html(__('British Indian Ocean Territory (Chagos Archipelago)', 'hostwph')), 'vg' => esc_html(__('British Virgin Islands', 'hostwph')), 'bn' => esc_html(__('Brunei Darussalam', 'hostwph')), 'bg' => esc_html(__('Bulgaria', 'hostwph')), 'bf' => esc_html(__('Burkina Faso', 'hostwph')), 'bi' => esc_html(__('Burundi', 'hostwph')), 'kh' => esc_html(__('Cambodia', 'hostwph')), 'cm' => esc_html(__('Cameroon', 'hostwph')), 'ca' => esc_html(__('Canada', 'hostwph')), 'cv' => esc_html(__('Cape Verde', 'hostwph')), 'ky' => esc_html(__('Cayman Islands', 'hostwph')), 'cf' => esc_html(__('Central African Republic', 'hostwph')), 'td' => esc_html(__('Chad', 'hostwph')), 'cl' => esc_html(__('Chile', 'hostwph')), 'cn' => esc_html(__('China', 'hostwph')), 'cx' => esc_html(__('Christmas Island', 'hostwph')), 'cc' => esc_html(__('Cocos (Keeling) Islands', 'hostwph')), 'co' => esc_html(__('Colombia', 'hostwph')), 'km' => esc_html(__('Comoros the', 'hostwph')), 'cd' => esc_html(__('Congo', 'hostwph')), 'cg' => esc_html(__('Congo the', 'hostwph')), 'ck' => esc_html(__('Cook Islands', 'hostwph')), 'cr' => esc_html(__('Costa Rica', 'hostwph')), 'ci' => esc_html(__('Cote d\'Ivoire', 'hostwph')), 'hr' => esc_html(__('Croatia', 'hostwph')), 'cu' => esc_html(__('Cuba', 'hostwph')), 'cy' => esc_html(__('Cyprus', 'hostwph')), 'cz' => esc_html(__('Czech Republic', 'hostwph')), 'dk' => esc_html(__('Denmark', 'hostwph')), 'dj' => esc_html(__('Djibouti', 'hostwph')), 'dm' => esc_html(__('Dominica', 'hostwph')), 'do' => esc_html(__('Dominican Republic', 'hostwph')), 'ec' => esc_html(__('Ecuador', 'hostwph')), 'eg' => esc_html(__('Egypt', 'hostwph')), 'sv' => esc_html(__('El Salvador', 'hostwph')), 'gq' => esc_html(__('Equatorial Guinea', 'hostwph')), 'er' => esc_html(__('Eritrea', 'hostwph')), 'ee' => esc_html(__('Estonia', 'hostwph')), 'et' => esc_html(__('Ethiopia', 'hostwph')), 'fo' => esc_html(__('Faroe Islands', 'hostwph')), 'fk' => esc_html(__('Falkland Islands (Malvinas)', 'hostwph')), 'fj' => esc_html(__('Fiji the Fiji Islands', 'hostwph')), 'fi' => esc_html(__('Finland', 'hostwph')), 'fr' => esc_html(__('France, French Republic', 'hostwph')), 'gf' => esc_html(__('French Guiana', 'hostwph')), 'pf' => esc_html(__('French Polynesia', 'hostwph')), 'tf' => esc_html(__('French Southern Territories', 'hostwph')), 'ga' => esc_html(__('Gabon', 'hostwph')), 'gm' => esc_html(__('Gambia the', 'hostwph')), 'ge' => esc_html(__('Georgia', 'hostwph')), 'de' => esc_html(__('Germany', 'hostwph')), 'gh' => esc_html(__('Ghana', 'hostwph')), 'gi' => esc_html(__('Gibraltar', 'hostwph')), 'gr' => esc_html(__('Greece', 'hostwph')), 'gl' => esc_html(__('Greenland', 'hostwph')), 'gd' => esc_html(__('Grenada', 'hostwph')), 'gp' => esc_html(__('Guadeloupe', 'hostwph')), 'gu' => esc_html(__('Guam', 'hostwph')), 'gt' => esc_html(__('Guatemala', 'hostwph')), 'gg' => esc_html(__('Guernsey', 'hostwph')), 'gn' => esc_html(__('Guinea', 'hostwph')), 'gw' => esc_html(__('Guinea-Bissau', 'hostwph')), 'gy' => esc_html(__('Guyana', 'hostwph')), 'ht' => esc_html(__('Haiti', 'hostwph')), 'hm' => esc_html(__('Heard Island and McDonald Islands', 'hostwph')), 'va' => esc_html(__('Holy See (Vatican City State)', 'hostwph')), 'hn' => esc_html(__('Honduras', 'hostwph')), 'hk' => esc_html(__('Hong Kong', 'hostwph')), 'hu' => esc_html(__('Hungary', 'hostwph')), 'is' => esc_html(__('Iceland', 'hostwph')), 'in' => esc_html(__('India', 'hostwph')), 'id' => esc_html(__('Indonesia', 'hostwph')), 'ir' => esc_html(__('Iran', 'hostwph')), 'iq' => esc_html(__('Iraq', 'hostwph')), 'ie' => esc_html(__('Ireland', 'hostwph')), 'im' => esc_html(__('Isle of Man', 'hostwph')), 'il' => esc_html(__('Israel', 'hostwph')), 'it' => esc_html(__('Italy', 'hostwph')), 'jm' => esc_html(__('Jamaica', 'hostwph')), 'jp' => esc_html(__('Japan', 'hostwph')), 'je' => esc_html(__('Jersey', 'hostwph')), 'jo' => esc_html(__('Jordan', 'hostwph')), 'kz' => esc_html(__('Kazakhstan', 'hostwph')), 'ke' => esc_html(__('Kenya', 'hostwph')), 'ki' => esc_html(__('Kiribati', 'hostwph')), 'kp' => esc_html(__('Korea', 'hostwph')), 'kr' => esc_html(__('Korea', 'hostwph')), 'kw' => esc_html(__('Kuwait', 'hostwph')), 'kg' => esc_html(__('Kyrgyz Republic', 'hostwph')), 'la' => esc_html(__('Lao', 'hostwph')), 'lv' => esc_html(__('Latvia', 'hostwph')), 'lb' => esc_html(__('Lebanon', 'hostwph')), 'ls' => esc_html(__('Lesotho', 'hostwph')), 'lr' => esc_html(__('Liberia', 'hostwph')), 'ly' => esc_html(__('Libyan Arab Jamahiriya', 'hostwph')), 'li' => esc_html(__('Liechtenstein', 'hostwph')), 'lt' => esc_html(__('Lithuania', 'hostwph')), 'lu' => esc_html(__('Luxembourg', 'hostwph')), 'mo' => esc_html(__('Macao', 'hostwph')), 'mk' => esc_html(__('Macedonia', 'hostwph')), 'mg' => esc_html(__('Madagascar', 'hostwph')), 'mw' => esc_html(__('Malawi', 'hostwph')), 'my' => esc_html(__('Malaysia', 'hostwph')), 'mv' => esc_html(__('Maldives', 'hostwph')), 'ml' => esc_html(__('Mali', 'hostwph')), 'mt' => esc_html(__('Malta', 'hostwph')), 'mh' => esc_html(__('Marshall Islands', 'hostwph')), 'mq' => esc_html(__('Martinique', 'hostwph')), 'mr' => esc_html(__('Mauritania', 'hostwph')), 'mu' => esc_html(__('Mauritius', 'hostwph')), 'yt' => esc_html(__('Mayotte', 'hostwph')), 'mx' => esc_html(__('Mexico', 'hostwph')), 'fm' => esc_html(__('Micronesia', 'hostwph')), 'md' => esc_html(__('Moldova', 'hostwph')), 'mc' => esc_html(__('Monaco', 'hostwph')), 'mn' => esc_html(__('Mongolia', 'hostwph')), 'me' => esc_html(__('Montenegro', 'hostwph')), 'ms' => esc_html(__('Montserrat', 'hostwph')), 'ma' => esc_html(__('Morocco', 'hostwph')), 'mz' => esc_html(__('Mozambique', 'hostwph')), 'mm' => esc_html(__('Myanmar', 'hostwph')), 'na' => esc_html(__('Namibia', 'hostwph')), 'nr' => esc_html(__('Nauru', 'hostwph')), 'np' => esc_html(__('Nepal', 'hostwph')), 'an' => esc_html(__('Netherlands Antilles', 'hostwph')), 'nl' => esc_html(__('Netherlands the', 'hostwph')), 'nc' => esc_html(__('New Caledonia', 'hostwph')), 'nz' => esc_html(__('New Zealand', 'hostwph')), 'ni' => esc_html(__('Nicaragua', 'hostwph')), 'ne' => esc_html(__('Niger', 'hostwph')), 'ng' => esc_html(__('Nigeria', 'hostwph')), 'nu' => esc_html(__('Niue', 'hostwph')), 'nf' => esc_html(__('Norfolk Island', 'hostwph')), 'mp' => esc_html(__('Northern Mariana Islands', 'hostwph')), 'no' => esc_html(__('Norway', 'hostwph')), 'om' => esc_html(__('Oman', 'hostwph')), 'pk' => esc_html(__('Pakistan', 'hostwph')), 'pw' => esc_html(__('Palau', 'hostwph')), 'ps' => esc_html(__('Palestinian Territory', 'hostwph')), 'pa' => esc_html(__('Panama', 'hostwph')), 'pg' => esc_html(__('Papua New Guinea', 'hostwph')), 'py' => esc_html(__('Paraguay', 'hostwph')), 'pe' => esc_html(__('Peru', 'hostwph')), 'ph' => esc_html(__('Philippines', 'hostwph')), 'pn' => esc_html(__('Pitcairn Islands', 'hostwph')), 'pl' => esc_html(__('Poland', 'hostwph')), 'pt' => esc_html(__('Portugal, Portuguese Republic', 'hostwph')), 'pr' => esc_html(__('Puerto Rico', 'hostwph')), 'qa' => esc_html(__('Qatar', 'hostwph')), 're' => esc_html(__('Reunion', 'hostwph')), 'ro' => esc_html(__('Romania', 'hostwph')), 'ru' => esc_html(__('Russian Federation', 'hostwph')), 'rw' => esc_html(__('Rwanda', 'hostwph')), 'bl' => esc_html(__('Saint Barthelemy', 'hostwph')), 'sh' => esc_html(__('Saint Helena', 'hostwph')), 'kn' => esc_html(__('Saint Kitts and Nevis', 'hostwph')), 'lc' => esc_html(__('Saint Lucia', 'hostwph')), 'mf' => esc_html(__('Saint Martin', 'hostwph')), 'pm' => esc_html(__('Saint Pierre and Miquelon', 'hostwph')), 'vc' => esc_html(__('Saint Vincent and the Grenadines', 'hostwph')), 'ws' => esc_html(__('Samoa', 'hostwph')), 'sm' => esc_html(__('San Marino', 'hostwph')), 'st' => esc_html(__('Sao Tome and Principe', 'hostwph')), 'sa' => esc_html(__('Saudi Arabia', 'hostwph')), 'sn' => esc_html(__('Senegal', 'hostwph')), 'rs' => esc_html(__('Serbia', 'hostwph')), 'sc' => esc_html(__('Seychelles', 'hostwph')), 'sl' => esc_html(__('Sierra Leone', 'hostwph')), 'sg' => esc_html(__('Singapore', 'hostwph')), 'sk' => esc_html(__('Slovakia (Slovak Republic)', 'hostwph')), 'si' => esc_html(__('Slovenia', 'hostwph')), 'sb' => esc_html(__('Solomon Islands', 'hostwph')), 'so' => esc_html(__('Somalia, Somali Republic', 'hostwph')), 'za' => esc_html(__('South Africa', 'hostwph')), 'gs' => esc_html(__('South Georgia and the South Sandwich Islands', 'hostwph')), 'es' => esc_html(__('Spain', 'hostwph')), 'lk' => esc_html(__('Sri Lanka', 'hostwph')), 'sd' => esc_html(__('Sudan', 'hostwph')), 'sr' => esc_html(__('Suriname', 'hostwph')), 'sj' => esc_html(__('Svalbard & Jan Mayen Islands', 'hostwph')), 'sz' => esc_html(__('Swaziland', 'hostwph')), 'se' => esc_html(__('Sweden', 'hostwph')), 'ch' => esc_html(__('Switzerland, Swiss Confederation', 'hostwph')), 'sy' => esc_html(__('Syrian Arab Republic', 'hostwph')), 'tw' => esc_html(__('Taiwan', 'hostwph')), 'tj' => esc_html(__('Tajikistan', 'hostwph')), 'tz' => esc_html(__('Tanzania', 'hostwph')), 'th' => esc_html(__('Thailand', 'hostwph')), 'tl' => esc_html(__('Timor-Leste', 'hostwph')), 'tg' => esc_html(__('Togo', 'hostwph')), 'tk' => esc_html(__('Tokelau', 'hostwph')), 'to' => esc_html(__('Tonga', 'hostwph')), 'tt' => esc_html(__('Trinidad and Tobago', 'hostwph')), 'tn' => esc_html(__('Tunisia', 'hostwph')), 'tr' => esc_html(__('Turkey', 'hostwph')), 'tm' => esc_html(__('Turkmenistan', 'hostwph')), 'tc' => esc_html(__('Turks and Caicos Islands', 'hostwph')), 'tv' => esc_html(__('Tuvalu', 'hostwph')), 'ug' => esc_html(__('Uganda', 'hostwph')), 'ua' => esc_html(__('Ukraine', 'hostwph')), 'ae' => esc_html(__('United Arab Emirates', 'hostwph')), 'gb' => esc_html(__('United Kingdom', 'hostwph')), 'us' => esc_html(__('United States of America', 'hostwph')), 'um' => esc_html(__('United States Minor Outlying Islands', 'hostwph')), 'vi' => esc_html(__('United States Virgin Islands', 'hostwph')), 'uy' => esc_html(__('Uruguay, Eastern Republic of', 'hostwph')), 'uz' => esc_html(__('Uzbekistan', 'hostwph')), 'vu' => esc_html(__('Vanuatu', 'hostwph')), 've' => esc_html(__('Venezuela', 'hostwph')), 'vn' => esc_html(__('Vietnam', 'hostwph')), 'wf' => esc_html(__('Wallis and Futuna', 'hostwph')), 'eh' => esc_html(__('Western Sahara', 'hostwph')), 'ye' => esc_html(__('Yemen', 'hostwph')), 'zm' => esc_html(__('Zambia', 'hostwph')), 'zw' => esc_html(__('Zimbabwe', 'hostwph')), ];
	}

  /**
	 * Load accomodation types.
	 *
	 * @since    1.0.0
	 */

	public static function accomodation_types() {
		// HOSTWPH_Data::accomodation_types();
		return ['agroturism' => esc_html(__('Agrotourism', 'hostwph')), 'albergue' => esc_html(__('Hostel', 'hostwph')), 'apart' => esc_html(__('Apartment', 'hostwph')), 'aparthotel' => esc_html(__('Aparthotel', 'hostwph')), 'ap_rural' => esc_html(__('Rural apartment', 'hostwph')), 'balneario' => esc_html(__('Spa', 'hostwph')), 'bungalow' => esc_html(__('Bungalow', 'hostwph')), 'camping' => esc_html(__('Camping', 'hostwph')), 'casa' => esc_html(__('House or private home', 'hostwph')), 'casa_huesp' => esc_html(__('Guest house', 'hostwph')), 'casa_rural' => esc_html(__('Rural house', 'hostwph')), 'chalet' => esc_html(__('Detached or semi-detached chalet', 'hostwph')), 'glamping' => esc_html(__('Glamping', 'hostwph')), 'habitacion' => esc_html(__('Room/s in a house', 'hostwph')), 'hostal' => esc_html(__('Hostel', 'hostwph')), 'hotel' => esc_html(__( 'Hotel', 'hostwph')), 'h_rural' => esc_html(__('Rural hotel', 'hostwph')), 'motel' => esc_html(__('Motel', 'hostwph')), 'ofic_vehic' => esc_html(__('Vehicle rental office', 'hostwph')), 'parador' => esc_html(__('Parador de tourism', 'hostwph')), 'pension' => esc_html(__('Pension', 'hostwph')), 'refugio' => esc_html(__('Refuge', 'hostwph')), 'residencia' => esc_html(__('Residence', 'hostwph')), 'vft' => esc_html(__('Housing for tourism purposes', 'hostwph')), 'villa' => esc_html(__('Villa', 'hostwph')), 'vut' => esc_html(__('Tourist accommodation', 'hostwph')), 'otros' => esc_html(__('Other', 'hostwph')),];
	}

	public static function relationships() {
		// HOSTWPH_Data::relationships();
	 return ['ab' => esc_html(__('Grandfather/Grandmother', 'hostwph')), 'ba' => esc_html(__('Great-Grandfather/Grandmother', 'hostwph')), 'bn' => esc_html(__('Great-Grandson/Granddaughter', 'hostwph')), 'cd' => esc_html(__('Brother/Sister-in-Law', 'hostwph')), 'cy' => esc_html(__('Spouse', 'hostwph')), 'hj' => esc_html(__('Son/Sister-in-Law', 'hostwph')), 'hr' => esc_html(__('Brother/Sister-in-Law', 'hostwph')), 'ni' => esc_html(__('Grandson/Granddaughter', 'hostwph')), 'pm' => esc_html(__('Father or Mother', 'hostwph')), 'sb' => esc_html(__('Nephew/Nephew', 'hostwph')), 'sg' => esc_html(__('Father-in-Law', 'hostwph')), 'ti' => esc_html(__('Uncle/Uncle', 'hostwph')), 'yn' => esc_html(__('Son-in-Law or Daughter-in-Law', 'hostwph')), 'tu' => esc_html(__('Guardian', 'hostwph')), 'ot' => esc_html(__('Other', 'hostwph')),];
	}
}
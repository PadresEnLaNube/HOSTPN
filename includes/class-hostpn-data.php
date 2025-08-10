<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin so that it is ready for translation.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Data {
	/**
	 * The main data array.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      HOSTPN_Data    $data    Empty array.
	 */
	protected $data = [];

	/**
	 * Load the plugin most usefull data.
	 *
	 * @since    1.0.0
	 */
	public function hostpn_load_plugin_data() {
		$this->data['user_id'] = get_current_user_id();

		if (is_admin()) {
			$this->data['post_id'] = !empty($GLOBALS['_REQUEST']['post']) ? $GLOBALS['_REQUEST']['post'] : 0;
		}else{
			$this->data['post_id'] = get_the_ID();
		}

		$GLOBALS['hostpn_data'] = $this->data;
	}

	/**
	 * Flus wp rewrite rules.
	 *
	 * @since    1.0.0
	 */
	public function hostpn_flush_rewrite_rules() {
		if (get_option('hostpn_options_changed')) {
			flush_rewrite_rules();
			update_option('hostpn_options_changed', false);
		}
	}

  	/**
	 * Get buttons mini loader.
	 *
	 * @since    1.0.0
	 */
	public static function hostpn_loader() {
		?>
			<div class="hostpn-waiting hostpn-display-inline hostpn-display-none-soft">
				<div class="hostpn-loader-circle-waiting"><div></div><div></div><div></div><div></div></div>
			</div>
		<?php
  	}

	/**
	 * Load popup loader.
	 *
	 * @since    1.0.0
	 */
	public static function hostpn_popup_loader() {
		?>
			<div class="hostpn-popup-content">
				<div class="hostpn-loader-circle-wrapper"><div class="hostpn-text-align-center"><div class="hostpn-loader-circle"><div></div><div></div><div></div><div></div></div></div></div>
			</div>
		<?php
	}

  	/**
	 * Load countries.
	 *
	 * @since    1.0.0
	 */
	public static function hostpn_countries() {
		// HOSTPN_Data::hostpn_countries();
		return [
			'afg' => esc_html(__('Afghanistan', 'hostpn')),
			'alb' => esc_html(__('Albania', 'hostpn')),
			'dza' => esc_html(__('Algeria', 'hostpn')),
			'and' => esc_html(__('Andorra', 'hostpn')),
			'ago' => esc_html(__('Angola', 'hostpn')),
			'atg' => esc_html(__('Antigua and Barbuda', 'hostpn')),
			'arg' => esc_html(__('Argentina', 'hostpn')),
			'arm' => esc_html(__('Armenia', 'hostpn')),
			'aus' => esc_html(__('Australia', 'hostpn')),
			'aut' => esc_html(__('Austria', 'hostpn')),
			'aze' => esc_html(__('Azerbaijan', 'hostpn')),
			'bhs' => esc_html(__('Bahamas', 'hostpn')),
			'bhr' => esc_html(__('Bahrain', 'hostpn')),
			'bgd' => esc_html(__('Bangladesh', 'hostpn')),
			'brb' => esc_html(__('Barbados', 'hostpn')),
			'blr' => esc_html(__('Belarus', 'hostpn')),
			'bel' => esc_html(__('Belgium', 'hostpn')),
			'blz' => esc_html(__('Belize', 'hostpn')),
			'ben' => esc_html(__('Benin', 'hostpn')),
			'btn' => esc_html(__('Bhutan', 'hostpn')),
			'bol' => esc_html(__('Bolivia (Plurinational State of)', 'hostpn')),
			'bih' => esc_html(__('Bosnia and Herzegovina', 'hostpn')),
			'bwa' => esc_html(__('Botswana', 'hostpn')),
			'bra' => esc_html(__('Brazil', 'hostpn')),
			'brn' => esc_html(__('Brunei Darussalam', 'hostpn')),
			'bgr' => esc_html(__('Bulgaria', 'hostpn')),
			'bfa' => esc_html(__('Burkina Faso', 'hostpn')),
			'bdi' => esc_html(__('Burundi', 'hostpn')),
			'cpv' => esc_html(__('Cabo Verde', 'hostpn')),
			'khm' => esc_html(__('Cambodia', 'hostpn')),
			'cmr' => esc_html(__('Cameroon', 'hostpn')),
			'can' => esc_html(__('Canada', 'hostpn')),
			'caf' => esc_html(__('Central African Republic', 'hostpn')),
			'tcd' => esc_html(__('Chad', 'hostpn')),
			'chl' => esc_html(__('Chile', 'hostpn')),
			'chn' => esc_html(__('China', 'hostpn')),
			'col' => esc_html(__('Colombia', 'hostpn')),
			'com' => esc_html(__('Comoros', 'hostpn')),
			'cog' => esc_html(__('Congo', 'hostpn')),
			'cod' => esc_html(__('Congo, Democratic Republic of the', 'hostpn')),
			'cri' => esc_html(__('Costa Rica', 'hostpn')),
			'civ' => esc_html(__('Côte d\'Ivoire', 'hostpn')),
			'hrv' => esc_html(__('Croatia', 'hostpn')),
			'cub' => esc_html(__('Cuba', 'hostpn')),
			'cyp' => esc_html(__('Cyprus', 'hostpn')),
			'cze' => esc_html(__('Czechia', 'hostpn')),
			'dnk' => esc_html(__('Denmark', 'hostpn')),
			'dji' => esc_html(__('Djibouti', 'hostpn')),
			'dma' => esc_html(__('Dominica', 'hostpn')),
			'dom' => esc_html(__('Dominican Republic', 'hostpn')),
			'ecu' => esc_html(__('Ecuador', 'hostpn')),
			'egy' => esc_html(__('Egypt', 'hostpn')),
			'slv' => esc_html(__('El Salvador', 'hostpn')),
			'gnq' => esc_html(__('Equatorial Guinea', 'hostpn')),
			'eri' => esc_html(__('Eritrea', 'hostpn')),
			'est' => esc_html(__('Estonia', 'hostpn')),
			'swz' => esc_html(__('Eswatini', 'hostpn')),
			'eth' => esc_html(__('Ethiopia', 'hostpn')),
			'fji' => esc_html(__('Fiji', 'hostpn')),
			'fin' => esc_html(__('Finland', 'hostpn')),
			'fra' => esc_html(__('France', 'hostpn')),
			'gab' => esc_html(__('Gabon', 'hostpn')),
			'gmb' => esc_html(__('Gambia', 'hostpn')),
			'geo' => esc_html(__('Georgia', 'hostpn')),
			'deu' => esc_html(__('Germany', 'hostpn')),
			'gha' => esc_html(__('Ghana', 'hostpn')),
			'grc' => esc_html(__('Greece', 'hostpn')),
			'grd' => esc_html(__('Grenada', 'hostpn')),
			'gtm' => esc_html(__('Guatemala', 'hostpn')),
			'gin' => esc_html(__('Guinea', 'hostpn')),
			'gnb' => esc_html(__('Guinea-Bissau', 'hostpn')),
			'guy' => esc_html(__('Guyana', 'hostpn')),
			'hti' => esc_html(__('Haiti', 'hostpn')),
			'hnd' => esc_html(__('Honduras', 'hostpn')),
			'hun' => esc_html(__('Hungary', 'hostpn')),
			'isl' => esc_html(__('Iceland', 'hostpn')),
			'ind' => esc_html(__('India', 'hostpn')),
			'idn' => esc_html(__('Indonesia', 'hostpn')),
			'irn' => esc_html(__('Iran (Islamic Republic of)', 'hostpn')),
			'irq' => esc_html(__('Iraq', 'hostpn')),
			'irl' => esc_html(__('Ireland', 'hostpn')),
			'isr' => esc_html(__('Israel', 'hostpn')),
			'ita' => esc_html(__('Italy', 'hostpn')),
			'jam' => esc_html(__('Jamaica', 'hostpn')),
			'jpn' => esc_html(__('Japan', 'hostpn')),
			'jor' => esc_html(__('Jordan', 'hostpn')),
			'kaz' => esc_html(__('Kazakhstan', 'hostpn')),
			'ken' => esc_html(__('Kenya', 'hostpn')),
			'kir' => esc_html(__('Kiribati', 'hostpn')),
			'kwt' => esc_html(__('Kuwait', 'hostpn')),
			'kgz' => esc_html(__('Kyrgyzstan', 'hostpn')),
			'lao' => esc_html(__('Lao People\'s Democratic Republic', 'hostpn')),
			'lva' => esc_html(__('Latvia', 'hostpn')),
			'lbn' => esc_html(__('Lebanon', 'hostpn')),
			'lso' => esc_html(__('Lesotho', 'hostpn')),
			'lbr' => esc_html(__('Liberia', 'hostpn')),
			'lby' => esc_html(__('Libya', 'hostpn')),
			'lie' => esc_html(__('Liechtenstein', 'hostpn')),
			'ltu' => esc_html(__('Lithuania', 'hostpn')),
			'lux' => esc_html(__('Luxembourg', 'hostpn')),
			'mdg' => esc_html(__('Madagascar', 'hostpn')),
			'mwi' => esc_html(__('Malawi', 'hostpn')),
			'mys' => esc_html(__('Malaysia', 'hostpn')),
			'mdv' => esc_html(__('Maldives', 'hostpn')),
			'mli' => esc_html(__('Mali', 'hostpn')),
			'mlt' => esc_html(__('Malta', 'hostpn')),
			'mhl' => esc_html(__('Marshall Islands', 'hostpn')),
			'mrt' => esc_html(__('Mauritania', 'hostpn')),
			'mus' => esc_html(__('Mauritius', 'hostpn')),
			'mex' => esc_html(__('Mexico', 'hostpn')),
			'fsm' => esc_html(__('Micronesia (Federated States of)', 'hostpn')),
			'mda' => esc_html(__('Moldova (Republic of)', 'hostpn')),
			'mco' => esc_html(__('Monaco', 'hostpn')),
			'mng' => esc_html(__('Mongolia', 'hostpn')),
			'mne' => esc_html(__('Montenegro', 'hostpn')),
			'mar' => esc_html(__('Morocco', 'hostpn')),
			'moz' => esc_html(__('Mozambique', 'hostpn')),
			'mmr' => esc_html(__('Myanmar', 'hostpn')),
			'nam' => esc_html(__('Namibia', 'hostpn')),
			'nru' => esc_html(__('Nauru', 'hostpn')),
			'npl' => esc_html(__('Nepal', 'hostpn')),
			'nld' => esc_html(__('Netherlands', 'hostpn')),
			'nzl' => esc_html(__('New Zealand', 'hostpn')),
			'nic' => esc_html(__('Nicaragua', 'hostpn')),
			'ner' => esc_html(__('Niger', 'hostpn')),
			'nga' => esc_html(__('Nigeria', 'hostpn')),
			'prk' => esc_html(__('North Korea', 'hostpn')),
			'mkd' => esc_html(__('North Macedonia', 'hostpn')),
			'nor' => esc_html(__('Norway', 'hostpn')),
			'omn' => esc_html(__('Oman', 'hostpn')),
			'pak' => esc_html(__('Pakistan', 'hostpn')),
			'plw' => esc_html(__('Palau', 'hostpn')),
			'pan' => esc_html(__('Panama', 'hostpn')),
			'png' => esc_html(__('Papua New Guinea', 'hostpn')),
			'pry' => esc_html(__('Paraguay', 'hostpn')),
			'per' => esc_html(__('Peru', 'hostpn')),
			'phl' => esc_html(__('Philippines', 'hostpn')),
			'pol' => esc_html(__('Poland', 'hostpn')),
			'prt' => esc_html(__('Portugal', 'hostpn')),
			'qat' => esc_html(__('Qatar', 'hostpn')),
			'rou' => esc_html(__('Romania', 'hostpn')),
			'rus' => esc_html(__('Russian Federation', 'hostpn')),
			'rwa' => esc_html(__('Rwanda', 'hostpn')),
			'kna' => esc_html(__('Saint Kitts and Nevis', 'hostpn')),
			'lca' => esc_html(__('Saint Lucia', 'hostpn')),
			'vct' => esc_html(__('Saint Vincent and the Grenadines', 'hostpn')),
			'wsm' => esc_html(__('Samoa', 'hostpn')),
			'smr' => esc_html(__('San Marino', 'hostpn')),
			'stp' => esc_html(__('Sao Tome and Principe', 'hostpn')),
			'sau' => esc_html(__('Saudi Arabia', 'hostpn')),
			'sen' => esc_html(__('Senegal', 'hostpn')),
			'srb' => esc_html(__('Serbia', 'hostpn')),
			'syc' => esc_html(__('Seychelles', 'hostpn')),
			'sle' => esc_html(__('Sierra Leone', 'hostpn')),
			'sgp' => esc_html(__('Singapore', 'hostpn')),
			'svk' => esc_html(__('Slovakia', 'hostpn')),
			'svn' => esc_html(__('Slovenia', 'hostpn')),
			'slb' => esc_html(__('Solomon Islands', 'hostpn')),
			'som' => esc_html(__('Somalia', 'hostpn')),
			'zaf' => esc_html(__('South Africa', 'hostpn')),
			'kor' => esc_html(__('South Korea', 'hostpn')),
			'ssd' => esc_html(__('South Sudan', 'hostpn')),
			'esp' => esc_html(__('Spain', 'hostpn')),
			'lka' => esc_html(__('Sri Lanka', 'hostpn')),
			'sdn' => esc_html(__('Sudan', 'hostpn')),
			'sur' => esc_html(__('Suriname', 'hostpn')),
			'swe' => esc_html(__('Sweden', 'hostpn')),
			'che' => esc_html(__('Switzerland', 'hostpn')),
			'syr' => esc_html(__('Syrian Arab Republic', 'hostpn')),
			'tjk' => esc_html(__('Tajikistan', 'hostpn')),
			'tza' => esc_html(__('Tanzania, United Republic of', 'hostpn')),
			'tha' => esc_html(__('Thailand', 'hostpn')),
			'tls' => esc_html(__('Timor-Leste', 'hostpn')),
			'tgo' => esc_html(__('Togo', 'hostpn')),
			'ton' => esc_html(__('Tonga', 'hostpn')),
			'tto' => esc_html(__('Trinidad and Tobago', 'hostpn')),
			'tun' => esc_html(__('Tunisia', 'hostpn')),
			'tur' => esc_html(__('Turkey', 'hostpn')),
			'tkm' => esc_html(__('Turkmenistan', 'hostpn')),
			'tuv' => esc_html(__('Tuvalu', 'hostpn')),
			'uga' => esc_html(__('Uganda', 'hostpn')),
			'ukr' => esc_html(__('Ukraine', 'hostpn')),
			'are' => esc_html(__('United Arab Emirates', 'hostpn')),
			'gbr' => esc_html(__('United Kingdom', 'hostpn')),
			'usa' => esc_html(__('United States of America', 'hostpn')),
			'ury' => esc_html(__('Uruguay', 'hostpn')),
			'uzb' => esc_html(__('Uzbekistan', 'hostpn')),
			'vut' => esc_html(__('Vanuatu', 'hostpn')),
			'ven' => esc_html(__('Venezuela (Bolivarian Republic of)', 'hostpn')),
			'vnm' => esc_html(__('Viet Nam', 'hostpn')),
			'yem' => esc_html(__('Yemen', 'hostpn')),
			'zmb' => esc_html(__('Zambia', 'hostpn')),
			'zwe' => esc_html(__('Zimbabwe', 'hostpn'))
		];
	}

  /**
	 * Load nationalities.
	 *
	 * @since    1.0.0
	 */
	public static function hostpn_nationalities() {
		// HOSTPN_Data::hostpn_nationalities();
		return [
			'afg' => esc_html(__('Afghan', 'hostpn')),
			'alb' => esc_html(__('Albanian', 'hostpn')),
			'dza' => esc_html(__('Algerian', 'hostpn')),
			'and' => esc_html(__('Andorran', 'hostpn')),
			'ago' => esc_html(__('Angolan', 'hostpn')),
			'atg' => esc_html(__('Antiguan', 'hostpn')),
			'arg' => esc_html(__('Argentine', 'hostpn')),
			'arm' => esc_html(__('Armenian', 'hostpn')),
			'aus' => esc_html(__('Australian', 'hostpn')),
			'aut' => esc_html(__('Austrian', 'hostpn')),
			'aze' => esc_html(__('Azerbaijani', 'hostpn')),
			'bhs' => esc_html(__('Bahamian', 'hostpn')),
			'bhr' => esc_html(__('Bahraini', 'hostpn')),
			'bgd' => esc_html(__('Bangladeshi', 'hostpn')),
			'brb' => esc_html(__('Barbadian', 'hostpn')),
			'blr' => esc_html(__('Belarusian', 'hostpn')),
			'bel' => esc_html(__('Belgian', 'hostpn')),
			'bz' => esc_html(__('Belizean', 'hostpn')),
			'ben' => esc_html(__('Beninese', 'hostpn')),
			'btn' => esc_html(__('Bhutanese', 'hostpn')),
			'bol' => esc_html(__('Bolivian', 'hostpn')),
			'bih' => esc_html(__('Bosnian', 'hostpn')),
			'bwa' => esc_html(__('Botswanan', 'hostpn')),
			'bra' => esc_html(__('Brazilian', 'hostpn')),
			'brn' => esc_html(__('Bruneian', 'hostpn')),
			'bgr' => esc_html(__('Bulgarian', 'hostpn')),
			'bfa' => esc_html(__('Burkinabe', 'hostpn')),
			'bdi' => esc_html(__('Burundian', 'hostpn')),
			'khm' => esc_html(__('Cambodian', 'hostpn')),
			'cmr' => esc_html(__('Cameroonian', 'hostpn')),
			'can' => esc_html(__('Canadian', 'hostpn')),
			'cpv' => esc_html(__('Cape Verdean', 'hostpn')),
			'caf' => esc_html(__('Central African', 'hostpn')),
			'tcd' => esc_html(__('Chadian', 'hostpn')),
			'chl' => esc_html(__('Chilean', 'hostpn')),
			'chn' => esc_html(__('Chinese', 'hostpn')),
			'col' => esc_html(__('Colombian', 'hostpn')),
			'com' => esc_html(__('Comorian', 'hostpn')),
			'cog' => esc_html(__('Congolese', 'hostpn')),
			'cod' => esc_html(__('DR Congolese', 'hostpn')),
			'cri' => esc_html(__('Costa Rican', 'hostpn')),
			'hrv' => esc_html(__('Croatian', 'hostpn')),
			'cub' => esc_html(__('Cuban', 'hostpn')),
			'cyp' => esc_html(__('Cypriot', 'hostpn')),
			'cze' => esc_html(__('Czech', 'hostpn')),
			'dnk' => esc_html(__('Danish', 'hostpn')),
			'dji' => esc_html(__('Djiboutian', 'hostpn')),
			'dom' => esc_html(__('Dominican', 'hostpn')),
			'ecu' => esc_html(__('Ecuadorian', 'hostpn')),
			'egy' => esc_html(__('Egyptian', 'hostpn')),
			'slv' => esc_html(__('Salvadoran', 'hostpn')),
			'gnq' => esc_html(__('Equatorial Guinean', 'hostpn')),
			'eri' => esc_html(__('Eritrean', 'hostpn')),
			'est' => esc_html(__('Estonian', 'hostpn')),
			'eth' => esc_html(__('Ethiopian', 'hostpn')),
			'fji' => esc_html(__('Fijian', 'hostpn')),
			'fin' => esc_html(__('Finnish', 'hostpn')),
			'fra' => esc_html(__('French', 'hostpn')),
			'gab' => esc_html(__('Gabonese', 'hostpn')),
			'gmb' => esc_html(__('Gambian', 'hostpn')),
			'geo' => esc_html(__('Georgian', 'hostpn')),
			'deu' => esc_html(__('German', 'hostpn')),
			'gha' => esc_html(__('Ghanaian', 'hostpn')),
			'grc' => esc_html(__('Greek', 'hostpn')),
			'grd' => esc_html(__('Grenadian', 'hostpn')),
			'gtm' => esc_html(__('Guatemalan', 'hostpn')),
			'gin' => esc_html(__('Guinean', 'hostpn')),
			'gnb' => esc_html(__('Guinea-Bissauan', 'hostpn')),
			'guy' => esc_html(__('Guyanese', 'hostpn')),
			'hti' => esc_html(__('Haitian', 'hostpn')),
			'hnd' => esc_html(__('Honduran', 'hostpn')),
			'hun' => esc_html(__('Hungarian', 'hostpn')),
			'isl' => esc_html(__('Icelandic', 'hostpn')),
			'ind' => esc_html(__('Indian', 'hostpn')),
			'idn' => esc_html(__('Indonesian', 'hostpn')),
			'irn' => esc_html(__('Iranian', 'hostpn')),
			'irq' => esc_html(__('Iraqi', 'hostpn')),
			'irl' => esc_html(__('Irish', 'hostpn')),
			'isr' => esc_html(__('Israeli', 'hostpn')),
			'ita' => esc_html(__('Italian', 'hostpn')),
			'jam' => esc_html(__('Jamaican', 'hostpn')),
			'jpn' => esc_html(__('Japanese', 'hostpn')),
			'jor' => esc_html(__('Jordanian', 'hostpn')),
			'kaz' => esc_html(__('Kazakhstani', 'hostpn')),
			'ken' => esc_html(__('Kenyan', 'hostpn')),
			'kwt' => esc_html(__('Kuwaiti', 'hostpn')),
			'kgz' => esc_html(__('Kyrgyzstani', 'hostpn')),
			'lao' => esc_html(__('Laotian', 'hostpn')),
			'lva' => esc_html(__('Latvian', 'hostpn')),
			'lbn' => esc_html(__('Lebanese', 'hostpn')),
			'lso' => esc_html(__('Basotho', 'hostpn')),
			'lbr' => esc_html(__('Liberian', 'hostpn')),
			'lby' => esc_html(__('Libyan', 'hostpn')),
			'ltu' => esc_html(__('Lithuanian', 'hostpn')),
			'lux' => esc_html(__('Luxembourgish', 'hostpn')),
			'mkd' => esc_html(__('Macedonian', 'hostpn')),
			'mdg' => esc_html(__('Malagasy', 'hostpn')),
			'mwi' => esc_html(__('Malawian', 'hostpn')),
			'mys' => esc_html(__('Malaysian', 'hostpn')),
			'mdv' => esc_html(__('Maldivian', 'hostpn')),
			'mli' => esc_html(__('Malian', 'hostpn')),
			'mlt' => esc_html(__('Maltese', 'hostpn')),
			'mhl' => esc_html(__('Marshallese', 'hostpn')),
			'mrt' => esc_html(__('Mauritanian', 'hostpn')),
			'mus' => esc_html(__('Mauritian', 'hostpn')),
			'mex' => esc_html(__('Mexican', 'hostpn')),
			'fsm' => esc_html(__('Micronesian', 'hostpn')),
			'mda' => esc_html(__('Moldovan', 'hostpn')),
			'mco' => esc_html(__('Monacan', 'hostpn')),
			'mng' => esc_html(__('Mongolian', 'hostpn')),
			'mne' => esc_html(__('Montenegrin', 'hostpn')),
			'mar' => esc_html(__('Moroccan', 'hostpn')),
			'moz' => esc_html(__('Mozambican', 'hostpn')),
			'mmr' => esc_html(__('Myanmar', 'hostpn')),
			'nam' => esc_html(__('Namibian', 'hostpn')),
			'nru' => esc_html(__('Nauruan', 'hostpn')),
			'npl' => esc_html(__('Nepalese', 'hostpn')),
			'nld' => esc_html(__('Dutch', 'hostpn')),
			'nzl' => esc_html(__('New Zealander', 'hostpn')),
			'nic' => esc_html(__('Nicaraguan', 'hostpn')),
			'ner' => esc_html(__('Nigerien', 'hostpn')),
			'nga' => esc_html(__('Nigerian', 'hostpn')),
			'nor' => esc_html(__('Norwegian', 'hostpn')),
			'omn' => esc_html(__('Omani', 'hostpn')),
			'pak' => esc_html(__('Pakistani', 'hostpn')),
			'plw' => esc_html(__('Palauan', 'hostpn')),
			'pan' => esc_html(__('Panamanian', 'hostpn')),
			'png' => esc_html(__('Papua New Guinean', 'hostpn')),
			'pry' => esc_html(__('Paraguayan', 'hostpn')),
			'per' => esc_html(__('Peruvian', 'hostpn')),
			'phl' => esc_html(__('Filipino', 'hostpn')),
			'pol' => esc_html(__('Polish', 'hostpn')),
			'prt' => esc_html(__('Portuguese', 'hostpn')),
			'qat' => esc_html(__('Qatari', 'hostpn')),
			'rou' => esc_html(__('Romanian', 'hostpn')),
			'rus' => esc_html(__('Russian', 'hostpn')),
			'rwa' => esc_html(__('Rwandan', 'hostpn')),
			'kna' => esc_html(__('Saint Kitts and Nevis', 'hostpn')),
			'lca' => esc_html(__('Saint Lucian', 'hostpn')),
			'vct' => esc_html(__('Saint Vincentian', 'hostpn')),
			'wsm' => esc_html(__('Samoan', 'hostpn')),
			'smr' => esc_html(__('San Marinese', 'hostpn')),
			'stp' => esc_html(__('Sao Tomean', 'hostpn')),
			'sau' => esc_html(__('Saudi', 'hostpn')),
			'sen' => esc_html(__('Senegalese', 'hostpn')),
			'srb' => esc_html(__('Serbian', 'hostpn')),
			'syc' => esc_html(__('Seychellois', 'hostpn')),
			'sle' => esc_html(__('Sierra Leonean', 'hostpn')),
			'sgp' => esc_html(__('Singaporean', 'hostpn')),
			'svk' => esc_html(__('Slovak', 'hostpn')),
			'svn' => esc_html(__('Slovenian', 'hostpn')),
			'slb' => esc_html(__('Solomon Islander', 'hostpn')),
			'som' => esc_html(__('Somali', 'hostpn')),
			'zaf' => esc_html(__('South African', 'hostpn')),
			'kor' => esc_html(__('South Korean', 'hostpn')),
			'ssd' => esc_html(__('South Sudanese', 'hostpn')),
			'esp' => esc_html(__('Spanish', 'hostpn')),
			'lka' => esc_html(__('Sri Lankan', 'hostpn')),
			'sdn' => esc_html(__('Sudanese', 'hostpn')),
			'sur' => esc_html(__('Surinamese', 'hostpn')),
			'swe' => esc_html(__('Swedish', 'hostpn')),
			'che' => esc_html(__('Swiss', 'hostpn')),
			'syr' => esc_html(__('Syrian', 'hostpn')),
			'tjk' => esc_html(__('Tajikistani', 'hostpn')),
			'tza' => esc_html(__('Tanzanian', 'hostpn')),
			'tha' => esc_html(__('Thai', 'hostpn')),
			'tls' => esc_html(__('Timorese', 'hostpn')),
			'tgo' => esc_html(__('Togolese', 'hostpn')),
			'ton' => esc_html(__('Tongan', 'hostpn')),
			'tto' => esc_html(__('Trinidadian', 'hostpn')),
			'tun' => esc_html(__('Tunisian', 'hostpn')),
			'tur' => esc_html(__('Turkish', 'hostpn')),
			'tkm' => esc_html(__('Turkmen', 'hostpn')),
			'tuv' => esc_html(__('Tuvaluan', 'hostpn')),
			'uga' => esc_html(__('Ugandan', 'hostpn')),
			'ukr' => esc_html(__('Ukrainian', 'hostpn')),
			'are' => esc_html(__('Emirati', 'hostpn')),
			'gbr' => esc_html(__('British', 'hostpn')),
			'usa' => esc_html(__('American', 'hostpn')),
			'ury' => esc_html(__('Uruguayan', 'hostpn')),
			'uzb' => esc_html(__('Uzbekistani', 'hostpn')),
			'vut' => esc_html(__('Vanuatuan', 'hostpn')),
			'ven' => esc_html(__('Venezuelan', 'hostpn')),
			'vnm' => esc_html(__('Vietnamese', 'hostpn')),
			'yem' => esc_html(__('Yemeni', 'hostpn')),
			'zmb' => esc_html(__('Zambian', 'hostpn')),
			'zwe' => esc_html(__('Zimbabwean', 'hostpn'))
		];
	}

  /**
	 * Load spanish cities.
	 *
	 * @since    1.0.0
	 */
	public static function hostpn_spanish_cities() {
		// HOSTPN_Data::hostpn_spanish_cities();
		global $wp_filesystem;

		// Initialize the filesystem if not already done
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();

		if ( ! $wp_filesystem ) {
			return array(); // Return empty array if filesystem initialization failed
		}

		$file_path = HOSTPN_URL . 'assets/csv/hostpn_spanish_cities.csv';
		$spanish_cities = array();

		// Get the file contents
		$contents = $wp_filesystem->get_contents( $file_path );
		if ( false === $contents ) {
			return array(); // Return empty array if file read failed
		}

		// Parse CSV content
		$lines = explode( "\n", $contents );
		foreach ( $lines as $line ) {
			if ( empty( trim( $line ) ) ) {
				continue;
			}
			$data = str_getcsv( $line );
			if ( count( $data ) >= 2 ) {
				$city_code = trim( $data[0] );
				$city_name = trim( $data[1] );
				$spanish_cities[ $city_code ] = $city_name;
			}
		}

		return $spanish_cities;
	}
  
  /**
	 * Load accommodation types.
	 *
	 * @since    1.0.0
	 */

	public static function hostpn_accommodation_types() {
		// HOSTPN_Data::hostpn_accommodation_types();
		return ['agroturism' => esc_html(__('Agrotourism', 'hostpn')), 'albergue' => esc_html(__('Hostel', 'hostpn')), 'apart' => esc_html(__('Apartment', 'hostpn')), 'aparthotel' => esc_html(__('Aparthotel', 'hostpn')), 'AP_rural' => esc_html(__('Rural apartment', 'hostpn')), 'balneario' => esc_html(__('Spa', 'hostpn')), 'bungalow' => esc_html(__('Bungalow', 'hostpn')), 'camping' => esc_html(__('Camping', 'hostpn')), 'casa' => esc_html(__('House or private home', 'hostpn')), 'CASA_huesp' => esc_html(__('Guest house', 'hostpn')), 'CASA_rural' => esc_html(__('Rural house', 'hostpn')), 'chalet' => esc_html(__('Detached or semi-detached chalet', 'hostpn')), 'glamping' => esc_html(__('Glamping', 'hostpn')), 'habitacion' => esc_html(__('Room/s in a house', 'hostpn')), 'hostal' => esc_html(__('Hostel', 'hostpn')), 'hotel' => esc_html(__( 'Hotel', 'hostpn')), 'H_rural' => esc_html(__('Rural hotel', 'hostpn')), 'motel' => esc_html(__('Motel', 'hostpn')), 'OFIC_vehic' => esc_html(__('Vehicle rental office', 'hostpn')), 'parador' => esc_html(__('Parador de tourism', 'hostpn')), 'pension' => esc_html(__('Pension', 'hostpn')), 'refugio' => esc_html(__('Refuge', 'hostpn')), 'residencia' => esc_html(__('Residence', 'hostpn')), 'vft' => esc_html(__('Housing for tourism purposes', 'hostpn')), 'villa' => esc_html(__('Villa', 'hostpn')), 'vut' => esc_html(__('Tourist accommodation', 'hostpn')), 'otros' => esc_html(__('Other', 'hostpn')),];
	}
	public static function hostpn_relationships() {
		// HOSTPN_Data::hostpn_relationships();
		return ['ab' => esc_html(__('Grandfather / Grandmother', 'hostpn')), 'ba' => esc_html(__('Great-Grandfather / Great-Grandmother', 'hostpn')), 'bn' => esc_html(__('Great-Grandson / Great-Granddaughter', 'hostpn')), 'cd' => esc_html(__('Brother', 'hostpn')), 'cy' => esc_html(__('Spouse', 'hostpn')), 'hj' => esc_html(__('Son', 'hostpn')), 'hr' => esc_html(__('Brother', 'hostpn')), 'ni' => esc_html(__('Grandson / Granddaughter', 'hostpn')), 'pm' => esc_html(__('Father / Mother', 'hostpn')), 'sb' => esc_html(__('Nephew', 'hostpn')), 'sg' => esc_html(__('Father-in-Law', 'hostpn')), 'ti' => esc_html(__('Uncle', 'hostpn')), 'yn' => esc_html(__('Son-in-Law / Daughter-in-Law', 'hostpn')), 'tu' => esc_html(__('Guardian', 'hostpn')), 'ot' => esc_html(__('Other', 'hostpn')),];
	}
}
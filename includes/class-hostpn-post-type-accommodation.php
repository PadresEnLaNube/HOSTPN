<?php
/**
 * accommodation creator.
 *
 * This class defines accommodation options, menus and templates.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    hostpn
 * @subpackage hostpn/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Post_Type_Accommodation {
  public function hostpn_accommodation_get_fields($accommodation_id = 0) {
    $hostpn_fields = [];
      $hostpn_fields['hostpn_accommodation_title'] = [
        'id' => 'hostpn_accommodation_title',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'value' => !empty($accommodation_id) ? get_the_title($accommodation_id) : '',
        'label' => __('Accommodation title', 'hostpn'),
        'placeholder' => __('Accommodation title', 'hostpn'),
      ];
      $hostpn_fields['hostpn_description'] = [
        'id' => 'hostpn_description',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'value' => !empty($accommodation_id) ? (str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($accommodation_id)->post_content))) : '',
        'input' => 'textarea',
        'label' => __('Accommodation description', 'hostpn'),
      ];
      
    return $hostpn_fields;
  }

  public function hostpn_accommodation_get_fields_meta() {
    $hostpn_fields_meta = [];
      $hostpn_fields_meta['hostpn_accommodation_code'] = [
        'id' => 'hostpn_accommodation_code',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'required' => true,
        'xml' => 'codigoEstablecimiento',
        'label' => __('Accommodation code', 'hostpn'),
        'placeholder' => __('Accommodation code', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_accommodation_type'] = [
        'id' => 'hostpn_accommodation_type',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => HOSTPN_Data::hostpn_accommodation_types(),
        'xml' => 'codigoEstablecimiento',
        'label' => __('Accommodation type', 'hostpn'),
        'placeholder' => __('Accommodation type', 'hostpn'),
      ];
      $hostpn_fields_meta['hostpn_accommodation_address'] = [
        'id' => 'hostpn_accommodation_address',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'direccion',
        'label' => esc_html(__('Address', 'hostpn')),
        'placeholder' => esc_html(__('Address', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_accommodation_address_alt'] = [
        'id' => 'hostpn_accommodation_address_alt',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'direccionComplementaria',
        'label' => esc_html(__('Address complementary information', 'hostpn')),
        'placeholder' => esc_html(__('Address complementary information', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_accommodation_country'] = [
        'id' => 'hostpn_accommodation_country',
        'class' => 'hostpn-select hostpn-width-100-percent',
        'input' => 'select',
        'options' => HOSTPN_Data::hostpn_countries(),
        'parent' => 'this',
        'xml' => 'pais',
        'label' => esc_html(__('Accommodation country', 'hostpn')),
        'placeholder' => esc_html(__('Accommodation country', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_accommodation_postal_code'] = [
        'id' => 'hostpn_accommodation_postal_code',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'number',
        'parent' => 'hostpn_accommodation_country',
        'parent_option' => 'es',
        'xml' => 'codigoMunicipio',
        'label' => esc_html(__('Accommodation postal code', 'hostpn')),
        'placeholder' => esc_html(__('Accommodation postal code', 'hostpn')),
      ];
      $hostpn_fields_meta['hostpn_accommodation_city'] = [
        'id' => 'hostpn_accommodation_city',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'text',
        'xml' => 'nombreMunicipio',
        'label' => esc_html(__('Accommodation city', 'hostpn')),
        'placeholder' => esc_html(__('Accommodation city', 'hostpn')),
      ];

      $hostpn_fields_meta['hostpn_accommodation_form'] = [
        'id' => 'hostpn_accommodation_form',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'input' => 'input',
        'type' => 'hidden',
        'value' => 'hostpn_accommodation_form',
      ];
      $hostpn_fields_meta['hostpn_ajax_nonce'] = [
        'id' => 'hostpn_ajax_nonce',
        'input' => 'input',
        'type' => 'nonce',
      ];

      // Nuevos campos editor para normas y condiciones
      $hostpn_fields_meta['hostpn_accommodation_rules'] = [
        'id' => 'hostpn_accommodation_rules',
        'input' => 'editor',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'label' => esc_html(__('Accommodation Rules', 'hostpn')),
        'description' => esc_html(__('Enter the accommodation rules and policies', 'hostpn')),
      ];

      $hostpn_fields_meta['hostpn_checkin_conditions'] = [
        'id' => 'hostpn_checkin_conditions',
        'input' => 'editor',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'label' => esc_html(__('Check-in Conditions', 'hostpn')),
        'description' => esc_html(__('Enter the check-in conditions and requirements', 'hostpn')),
      ];

      $hostpn_fields_meta['hostpn_checkout_conditions'] = [
        'id' => 'hostpn_checkout_conditions',
        'input' => 'editor',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'label' => esc_html(__('Check-out Conditions', 'hostpn')),
        'description' => esc_html(__('Enter the check-out conditions and requirements', 'hostpn')),
      ];

      // Campos de características del alojamiento - Aparcamiento
      $hostpn_fields_meta['hostpn_parking_description'] = [
        'id' => 'hostpn_parking_description',
        'input' => 'textarea',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'label' => esc_html(__('Parking Description', 'hostpn')),
        'description' => esc_html(__('Describe parking availability and conditions', 'hostpn')),
      ];

      // Campos de características del alojamiento - Internet
      $hostpn_fields_meta['hostpn_internet_description'] = [
        'id' => 'hostpn_internet_description',
        'input' => 'textarea',
        'class' => 'hostpn-input hostpn-width-100-percent',
        'label' => esc_html(__('Internet Description', 'hostpn')),
        'description' => esc_html(__('Describe internet speed and features', 'hostpn')),
      ];

      // Accommodation features - Kitchen Section Start
      $hostpn_fields_meta['hostpn_kitchen_section_start'] = [
        'id' => 'hostpn_kitchen_section_start',
        'section' => 'start',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-kitchen-section',
        'label' => esc_html(__('Kitchen Features', 'hostpn')),
      ];

      // Kitchen features array
      $kitchen_features = [
        'dining_table' => __('Dining table', 'hostpn'),
        'coffee_maker' => __('Coffee maker', 'hostpn'),
        'cleaning_products' => __('Cleaning products', 'hostpn'),
        'toaster' => __('Toaster', 'hostpn'),
        'stovetops' => __('Stovetops', 'hostpn'),
        'oven' => __('Oven', 'hostpn'),
        'kitchen_utensils' => __('Kitchen utensils', 'hostpn'),
        'electric_kettle' => __('Electric kettle', 'hostpn'),
        'kitchen' => __('Kitchen', 'hostpn'),
        'washing_machine' => __('Washing machine', 'hostpn'),
        'dishwasher' => __('Dishwasher', 'hostpn'),
        'microwave' => __('Microwave', 'hostpn'),
        'refrigerator' => __('Refrigerator', 'hostpn'),
        'kitchen_area' => __('Kitchen area', 'hostpn'),
        'blender' => __('Blender', 'hostpn'),
        'food_processor' => __('Food processor', 'hostpn'),
        'juicer' => __('Juicer', 'hostpn'),
        'grill' => __('Grill', 'hostpn'),
        'steamer' => __('Steamer', 'hostpn'),
        'slow_cooker' => __('Slow cooker', 'hostpn'),
        'air_fryer' => __('Air fryer', 'hostpn'),
        'wine_cooler' => __('Wine cooler', 'hostpn'),
        'freezer' => __('Freezer', 'hostpn'),
        'kitchen_island' => __('Kitchen island', 'hostpn'),
        'pantry' => __('Pantry', 'hostpn'),
        'garbage_disposal' => __('Garbage disposal', 'hostpn'),
        'water_filter' => __('Water filter', 'hostpn'),
        'ice_maker' => __('Ice maker', 'hostpn'),
        'breakfast_bar' => __('Breakfast bar', 'hostpn'),
        'wine_rack' => __('Wine rack', 'hostpn'),
        'spice_rack' => __('Spice rack', 'hostpn'),
        'knife_block' => __('Knife block', 'hostpn'),
      ];

      // Generate kitchen features using foreach
      foreach ($kitchen_features as $feature_id => $feature_label) {
        $hostpn_fields_meta['hostpn_' . $feature_id] = [
          'id' => 'hostpn_' . $feature_id,
          'input' => 'input',
          'type' => 'checkbox',
          'class' => 'hostpn-input hostpn-width-100-percent  hostpn-kitchen-feature',
          'label' => esc_html($feature_label),
        ];
      }

      // Accommodation features - Kitchen additional items (html_multi)
      $hostpn_fields_meta['hostpn_kitchen_additional_features'] = [
        'id' => 'hostpn_kitchen_additional_features',
        'input' => 'html_multi',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-kitchen-additional-features',
        'html_multi_fields' => [
          [
            'id' => 'hostpn_kitchen_custom_name', 
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text', 
            'multiple' => true,
            'label' => esc_html(__('Custom kitchen feature name', 'hostpn')),
            'placeholder' => esc_html(__('Enter the name of the custom kitchen feature', 'hostpn')),
          ],
        ],
        'label' => esc_html(__('Additional Kitchen Features', 'hostpn')),
        'description' => esc_html(__('Add custom kitchen features not listed above', 'hostpn')),
      ];

      // Accommodation features - Kitchen Section End
      $hostpn_fields_meta['hostpn_kitchen_section_end'] = [
        'id' => 'hostpn_kitchen_section_end',
        'section' => 'end',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-kitchen-section',
        'label' => esc_html(__('Kitchen Features', 'hostpn')),
      ];

      // Accommodation features - Room Section Start
      $hostpn_fields_meta['hostpn_room_section_start'] = [
        'id' => 'hostpn_room_section_start',
        'section' => 'start',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-room-section',
        'label' => esc_html(__('Room Features', 'hostpn')),
      ];

      // Room features array
      $room_features = [
        'bed_linen' => __('Bed linen', 'hostpn'),
        'wardrobe' => __('Wardrobe', 'hostpn'),
        'nightstand' => __('Nightstand', 'hostpn'),
        'dresser' => __('Dresser', 'hostpn'),
        'mirror' => __('Mirror', 'hostpn'),
        'armchair' => __('Armchair', 'hostpn'),
        'reading_lamp' => __('Reading lamp', 'hostpn'),
        'bedside_lamp' => __('Bedside lamp', 'hostpn'),
        'ceiling_lamp' => __('Ceiling lamp', 'hostpn'),
        'floor_lamp' => __('Floor lamp', 'hostpn'),
        'table_lamp' => __('Table lamp', 'hostpn'),
        'wall_lamp' => __('Wall lamp', 'hostpn'),
        'curtains' => __('Curtains', 'hostpn'),
        'blinds' => __('Blinds', 'hostpn'),
        'shutters' => __('Shutters', 'hostpn'),
        'blackout_curtains' => __('Blackout curtains', 'hostpn'),
        'sheer_curtains' => __('Sheer curtains', 'hostpn'),
        'thermal_curtains' => __('Thermal curtains', 'hostpn'),
        'soundproofing' => __('Soundproofing', 'hostpn'),
        'air_conditioning' => __('Air conditioning', 'hostpn'),
        'heating' => __('Heating', 'hostpn'),
        'ceiling_fan' => __('Ceiling fan', 'hostpn'),
        'floor_fan' => __('Floor fan', 'hostpn'),
        'table_fan' => __('Table fan', 'hostpn'),
        'wall_fan' => __('Wall fan', 'hostpn'),
        'humidifier' => __('Humidifier', 'hostpn'),
        'dehumidifier' => __('Dehumidifier', 'hostpn'),
        'air_purifier' => __('Air purifier', 'hostpn'),
        'ionizer' => __('Ionizer', 'hostpn'),
        'ozone_generator' => __('Ozone generator', 'hostpn'),
        'uv_sterilizer' => __('UV sterilizer', 'hostpn'),
        'hepa_filter' => __('HEPA filter', 'hostpn'),
        'carbon_filter' => __('Carbon filter', 'hostpn'),
        'electrostatic_filter' => __('Electrostatic filter', 'hostpn'),
        'photocatalytic_filter' => __('Photocatalytic filter', 'hostpn'),
        'plasma_filter' => __('Plasma filter', 'hostpn'),
        'uv_filter' => __('UV filter', 'hostpn'),
        'ion_filter' => __('Ion filter', 'hostpn'),
        'ozone_filter' => __('Ozone filter', 'hostpn'),
      ];

      // Generate room features using foreach
      foreach ($room_features as $feature_id => $feature_label) {
        $hostpn_fields_meta['hostpn_' . $feature_id] = [
          'id' => 'hostpn_' . $feature_id,
          'input' => 'input',
          'type' => 'checkbox',
          'class' => 'hostpn-input hostpn-width-100-percent  hostpn-room-feature',
          'label' => esc_html($feature_label),
        ];
      }

      // Accommodation features - Room additional items (html_multi)
      $hostpn_fields_meta['hostpn_room_additional_features'] = [
        'id' => 'hostpn_room_additional_features',
        'input' => 'html_multi',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-room-additional-features',
        'html_multi_fields' => [
          [
            'id' => 'hostpn_room_custom_name', 
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text', 
            'multiple' => true,
            'label' => esc_html(__('Custom room feature name', 'hostpn')),
            'placeholder' => esc_html(__('Enter the name of the custom room feature', 'hostpn')),
          ],
        ],
        'label' => esc_html(__('Additional Room Features', 'hostpn')),
        'description' => esc_html(__('Add custom room features not listed above', 'hostpn')),
      ];

      // Accommodation features - Room Section End
      $hostpn_fields_meta['hostpn_room_section_end'] = [
        'id' => 'hostpn_room_section_end',
        'section' => 'end',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-room-section',
        'label' => esc_html(__('Room Features', 'hostpn')),
      ];

      // Accommodation features - Bathroom Section Start
      $hostpn_fields_meta['hostpn_bathroom_section_start'] = [
        'id' => 'hostpn_bathroom_section_start',
        'section' => 'start',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-bathroom-section',
        'label' => esc_html(__('Bathroom Features', 'hostpn')),
      ];

      // Bathroom features array
      $bathroom_features = [
        'private_bathroom' => __('Private bathroom', 'hostpn'),
        'shared_bathroom' => __('Shared bathroom', 'hostpn'),
        'hair_dryer' => __('Hair dryer', 'hostpn'),
        'shampoo' => __('Shampoo', 'hostpn'),
        'conditioner' => __('Conditioner', 'hostpn'),
        'body_wash' => __('Body wash', 'hostpn'),
        'soap' => __('Soap', 'hostpn'),
        'toilet_paper' => __('Toilet paper', 'hostpn'),
        'towels' => __('Towels', 'hostpn'),
        'bath_mat' => __('Bath mat', 'hostpn'),
        'shower_curtain' => __('Shower curtain', 'hostpn'),
        'bath_tub' => __('Bath tub', 'hostpn'),
        'shower' => __('Shower', 'hostpn'),
        'bidet' => __('Bidet', 'hostpn'),
        'toilet' => __('Toilet', 'hostpn'),
        'sink' => __('Sink', 'hostpn'),
        'mirror' => __('Mirror', 'hostpn'),
        'medicine_cabinet' => __('Medicine cabinet', 'hostpn'),
        'vanity' => __('Vanity', 'hostpn'),
        'heating' => __('Heating', 'hostpn'),
        'ventilation' => __('Ventilation', 'hostpn'),
      ];

      // Generate bathroom features using foreach
      foreach ($bathroom_features as $feature_id => $feature_label) {
        $hostpn_fields_meta['hostpn_' . $feature_id] = [
          'id' => 'hostpn_' . $feature_id,
          'input' => 'input',
          'type' => 'checkbox',
          'class' => 'hostpn-input hostpn-width-100-percent  hostpn-bathroom-feature',
          'label' => esc_html($feature_label),
        ];
      }

      // Accommodation features - Bathroom additional items (html_multi)
      $hostpn_fields_meta['hostpn_bathroom_additional_features'] = [
        'id' => 'hostpn_bathroom_additional_features',
        'input' => 'html_multi',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-bathroom-additional-features',
        'html_multi_fields' => [
          [
            'id' => 'hostpn_bathroom_custom_name',
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text', 
            'multiple' => true,
            'label' => esc_html(__('Custom bathroom feature name', 'hostpn')),
            'placeholder' => esc_html(__('Enter the name of the custom bathroom feature', 'hostpn')),
          ],
        ],
        'label' => esc_html(__('Additional Bathroom Features', 'hostpn')),
        'description' => esc_html(__('Add custom bathroom features not listed above', 'hostpn')),
      ];

      // Accommodation features - Bathroom Section End
      $hostpn_fields_meta['hostpn_bathroom_section_end'] = [
        'id' => 'hostpn_bathroom_section_end',
        'section' => 'end',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-bathroom-section',
        'label' => esc_html(__('Bathroom Features', 'hostpn')),
      ];

      // Accommodation features - Living Area Section Start
      $hostpn_fields_meta['hostpn_living_area_section_start'] = [
        'id' => 'hostpn_living_area_section_start',
        'section' => 'start',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-living-area-section',
        'label' => esc_html(__('Living Area Features', 'hostpn')),
      ];

      // Living Area features array
      $living_area_features = [
        'sofa' => __('Sofa', 'hostpn'),
        'armchair' => __('Armchair', 'hostpn'),
        'coffee_table' => __('Coffee table', 'hostpn'),
        'side_table' => __('Side table', 'hostpn'),
        'tv_stand' => __('TV stand', 'hostpn'),
        'bookcase' => __('Bookcase', 'hostpn'),
        'magazine_rack' => __('Magazine rack', 'hostpn'),
        'throw_pillows' => __('Throw pillows', 'hostpn'),
        'blanket' => __('Blanket', 'hostpn'),
        'rug' => __('Rug', 'hostpn'),
        'floor_lamp' => __('Floor lamp', 'hostpn'),
        'table_lamp' => __('Table lamp', 'hostpn'),
        'wall_lamp' => __('Wall lamp', 'hostpn'),
        'ceiling_lamp' => __('Ceiling lamp', 'hostpn'),
        'chandelier' => __('Chandelier', 'hostpn'),
        'pendant_lamp' => __('Pendant lamp', 'hostpn'),
        'track_lighting' => __('Track lighting', 'hostpn'),
        'recessed_lighting' => __('Recessed lighting', 'hostpn'),
        'sconces' => __('Sconces', 'hostpn'),
        'fireplace' => __('Fireplace', 'hostpn'),
        'wood_stove' => __('Wood stove', 'hostpn'),
        'pellet_stove' => __('Pellet stove', 'hostpn'),
        'gas_stove' => __('Gas stove', 'hostpn'),
        'electric_stove' => __('Electric stove', 'hostpn'),
        'propane_stove' => __('Propane stove', 'hostpn'),
        'kerosene_stove' => __('Kerosene stove', 'hostpn'),
        'ethanol_stove' => __('Ethanol stove', 'hostpn'),
        'bioethanol_stove' => __('Bioethanol stove', 'hostpn'),
        'gel_stove' => __('Gel stove', 'hostpn'),
        'paraffin_stove' => __('Paraffin stove', 'hostpn'),
        'denatured_alcohol_stove' => __('Denatured alcohol stove', 'hostpn'),
        'methylated_spirits_stove' => __('Methylated spirits stove', 'hostpn'),
        'isopropyl_alcohol_stove' => __('Isopropyl alcohol stove', 'hostpn'),
        'rubbing_alcohol_stove' => __('Rubbing alcohol stove', 'hostpn'),
      ];

      // Generate living area features using foreach
      foreach ($living_area_features as $feature_id => $feature_label) {
        $hostpn_fields_meta['hostpn_' . $feature_id] = [
          'id' => 'hostpn_' . $feature_id,
          'input' => 'input',
          'type' => 'checkbox',
          'class' => 'hostpn-input hostpn-width-100-percent  hostpn-living-area-feature',
          'label' => esc_html($feature_label),
        ];
      }

      // Accommodation features - Living Area additional items (html_multi)
      $hostpn_fields_meta['hostpn_living_area_additional_features'] = [
        'id' => 'hostpn_living_area_additional_features',
        'input' => 'html_multi',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-living-area-additional-features',
        'html_multi_fields' => [
          [
            'id' => 'hostpn_living_area_custom_name', 
            'class' => 'hostpn-input hostpn-width-100-percent',
            'input' => 'input',
            'type' => 'text', 
            'multiple' => true,
            'label' => esc_html(__('Custom living area feature name', 'hostpn')),
            'placeholder' => esc_html(__('Enter the name of the custom living area feature', 'hostpn')),
          ],
        ],
        'label' => esc_html(__('Additional Living Area Features', 'hostpn')),
        'description' => esc_html(__('Add custom living area features not listed above', 'hostpn')),
      ];

      // Accommodation features - Living Area Section End
      $hostpn_fields_meta['hostpn_living_area_section_end'] = [
        'id' => 'hostpn_living_area_section_end',
        'section' => 'end',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-living-area-section',
        'label' => esc_html(__('Living Area Features', 'hostpn')),
      ];

      // Accommodation features - Audiovisual Equipment Section Start
      $hostpn_fields_meta['hostpn_audiovisual_section_start'] = [
        'id' => 'hostpn_audiovisual_section_start',
        'section' => 'start',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-audiovisual-section',
        'label' => esc_html(__('Audiovisual Equipment', 'hostpn')),
      ];

      // Audiovisual features array
      $audiovisual_features = [
        'tv' => __('TV', 'hostpn'),
        'smart_tv' => __('Smart TV', 'hostpn'),
        '4k_tv' => __('4K TV', 'hostpn'),
        '8k_tv' => __('8K TV', 'hostpn'),
        'oled_tv' => __('OLED TV', 'hostpn'),
        'qled_tv' => __('QLED TV', 'hostpn'),
        'led_tv' => __('LED TV', 'hostpn'),
        'lcd_tv' => __('LCD TV', 'hostpn'),
        'plasma_tv' => __('Plasma TV', 'hostpn'),
        'crt_tv' => __('CRT TV', 'hostpn'),
        'projector' => __('Projector', 'hostpn'),
        'screen' => __('Screen', 'hostpn'),
        'sound_system' => __('Sound system', 'hostpn'),
      ];

      // Generate audiovisual features using foreach
      foreach ($audiovisual_features as $feature_id => $feature_label) {
        $hostpn_fields_meta['hostpn_' . $feature_id] = [
          'id' => 'hostpn_' . $feature_id,
          'input' => 'input',
          'type' => 'checkbox',
          'class' => 'hostpn-input hostpn-width-100-percent  hostpn-audiovisual-feature',
          'label' => esc_html($feature_label),
        ];
      }

      // Accommodation features - Audiovisual additional items (html_multi)
      $hostpn_fields_meta['hostpn_audiovisual_additional_features'] = [
        'id' => 'hostpn_audiovisual_additional_features',
        'input' => 'html_multi',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-audiovisual-additional-features',
        'html_multi_fields' => [
          [
            'id' => 'hostpn_audiovisual_custom_name', 
            'input' => 'input',
            'type' => 'text', 
            'multiple' => true,
            'label' => esc_html(__('Custom audiovisual feature name', 'hostpn')),
            'placeholder' => esc_html(__('Enter the name of the custom audiovisual feature', 'hostpn')),
          ],
        ],
        'label' => esc_html(__('Additional Audiovisual Features', 'hostpn')),
        'description' => esc_html(__('Add custom audiovisual features not listed above', 'hostpn')),
      ];

      // Accommodation features - Audiovisual Section End
      $hostpn_fields_meta['hostpn_audiovisual_section_end'] = [
        'id' => 'hostpn_audiovisual_section_end',
        'section' => 'end',
        'class' => 'hostpn-input hostpn-width-100-percent  hostpn-audiovisual-section',
        'label' => esc_html(__('Audiovisual Equipment', 'hostpn')),
      ];

    return $hostpn_fields_meta;
  }

  /**
   * Register accommodation.
   *
   * @since    1.0.0
   */
  public function hostpn_accommodation_register_post_type() {
    $labels = [
      'name'                => _x('Accommodation', 'Post Type general name', 'hostpn'),
      'singular_name'       => _x('Accommodation', 'Post Type singular name', 'hostpn'),
      'menu_name'           => esc_html(__('Accommodations', 'hostpn')),
      'parent_item_colon'   => esc_html(__('Parent accommodation', 'hostpn')),
      'all_items'           => esc_html(__('All accommodations', 'hostpn')),
      'view_item'           => esc_html(__('View accommodation', 'hostpn')),
      'add_new_item'        => esc_html(__('Add new accommodation', 'hostpn')),
      'add_new'             => esc_html(__('Add new accommodation', 'hostpn')),
      'edit_item'           => esc_html(__('Edit accommodation', 'hostpn')),
      'update_item'         => esc_html(__('Update accommodation', 'hostpn')),
      'search_items'        => esc_html(__('Search accommodations', 'hostpn')),
      'not_found'           => esc_html(__('Not accommodation found', 'hostpn')),
      'not_found_in_trash'  => esc_html(__('Not accommodation found in Trash', 'hostpn')),
    ];

    $args = [
      'labels'              => $labels,
      'rewrite'             => ['slug' => (!empty(get_option('hostpn')) ? get_option('hostpn') : 'hostpn'), 'with_front' => false],
      'label'               => esc_html(__('Accommodation', 'hostpn')),
      'description'         => esc_html(__('Accommodation description', 'hostpn')),
      'supports'            => ['title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'page-attributes', ],
      'hierarchical'        => true,
      'public'              => false,
      'show_ui'             => true,
      'show_in_menu'        => false,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 5,
      'can_export'          => true,
      'has_archive'         => false,
      'exclude_from_search' => true,
      'publicly_queryable'  => false,
      'capability_type'     => 'page',
      'taxonomies'          => HOSTPN_ROLE_CAPABILITIES,
      'show_in_rest'        => true, /* REST API */
    ];

    register_post_type('hostpn_accommodation', $args);
    add_theme_support('post-thumbnails', ['page', 'hostpn_accommodation']);
  }

  /**
   * Add accommodation dashboard metabox.
   *
   * @since    1.0.0
   */
  public function hostpn_accommodation_add_meta_box() {
    add_meta_box('hostpn_meta_box', esc_html(__('Accommodation details', 'hostpn')), [$this, 'hostpn_accommodation_meta_box_function'], 'hostpn_accommodation', 'normal', 'high', ['__block_editor_compatible_meta_box' => true,]);
  }

  /**
   * Defines accommodation dashboard contents.
   *
   * @since    1.0.0
   */
  public function hostpn_accommodation_meta_box_function($post) {
    foreach (self::hostpn_accommodation_get_fields_meta() as $hostpn_field) {
      if (!is_null(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $post->ID))) {
        echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $post->ID), HOSTPN_KSES);
      }
    }
  }

  /**
   * Defines single template for accommodation.
   *
   * @since    1.0.0
   */
  public function hostpn_accommodation_single_template($single) {
    if (get_post_type() == 'hostpn_accommodation') {
      if (file_exists(hostpn_DIR . 'templates/public/single-hostpn_accommodation.php')) {
        return hostpn_DIR . 'templates/public/single-hostpn_accommodation.php';
      }
    }

    return $single;
  }

  /**
   * Defines archive template for accommodation.
   *
   * @since    1.0.0
   */
  public function hostpn_accommodation_archive_template($archive) {
    if (get_post_type() == 'hostpn_accommodation') {
      if (file_exists(hostpn_DIR . 'templates/public/archive-hostpn_accommodation.php')) {
        return hostpn_DIR . 'templates/public/archive-hostpn_accommodation.php';
      }
    }

    return $archive;
  }

  public function hostpn_accommodation_save_post($post_id, $cpt, $update) {
    if($cpt->post_type == 'hostpn_accommodation' && array_key_exists('hostpn_accommodation_form', $_POST)){
      // Always require nonce verification
      if (!array_key_exists('hostpn_ajax_nonce', $_POST)) {
        echo wp_json_encode([
          'error_key' => 'hostpn_nonce_error_required',
          'error_content' => esc_html(__('Security check failed: Nonce is required.', 'hostpn')),
        ]);

        exit;
      }

      if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hostpn_ajax_nonce'])), 'hostpn-nonce')) {
        echo wp_json_encode([
          'error_key' => 'hostpn_nonce_error_invalid',
          'error_content' => esc_html(__('Security check failed: Invalid nonce.', 'hostpn')),
        ]);

        exit;
      }

      if (!array_key_exists('hostpn_duplicate', $_POST)) {
        foreach (array_merge(self::hostpn_accommodation_get_fields(), self::hostpn_accommodation_get_fields_meta()) as $hostpn_field) {
          $hostpn_input = array_key_exists('input', $hostpn_field) ? $hostpn_field['input'] : '';

          if (array_key_exists($hostpn_field['id'], $_POST) || $hostpn_input == 'html_multi') {
            $hostpn_value = array_key_exists($hostpn_field['id'], $_POST) ? 
              HOSTPN_Forms::hostpn_sanitizer(
                wp_unslash($_POST[$hostpn_field['id']]),
                $hostpn_field['input'], 
                !empty($hostpn_field['type']) ? $hostpn_field['type'] : '',
                $hostpn_field // Pass the entire field config
              ) : '';

            if (!empty($hostpn_input)) {
              switch ($hostpn_input) {
                case 'input':
                  if (array_key_exists('type', $hostpn_field) && $hostpn_field['type'] == 'checkbox') {
                    if (isset($_POST[$hostpn_field['id']])) {
                      update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                    } else {
                      update_post_meta($post_id, $hostpn_field['id'], '');
                    }
                  } else {
                    update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                  }

                  break;
                case 'select':
                  if (array_key_exists('multiple', $hostpn_field) && $hostpn_field['multiple']) {
                    $multi_array = [];
                    $empty = true;

                    foreach (wp_unslash($_POST[$hostpn_field['id']]) as $multi_value) {
                      $multi_array[] = HOSTPN_Forms::hostpn_sanitizer(
                        $multi_value, 
                        $hostpn_field['input'], 
                        !empty($hostpn_field['type']) ? $hostpn_field['type'] : '',
                        $hostpn_field // Pass the entire field config
                      );
                    }

                    update_post_meta($post_id, $hostpn_field['id'], $multi_array);
                  } else {
                    update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                  }
                  
                  break;
                case 'html_multi':
                  foreach ($hostpn_field['html_multi_fields'] as $hostpn_multi_field) {
                    if (array_key_exists($hostpn_multi_field['id'], $_POST)) {
                      $multi_array = [];
                      $empty = true;

                      // Sanitize the POST data before using it
                      $sanitized_post_data = isset($_POST[$hostpn_multi_field['id']]) ? 
                        array_map(function($value) {
                            return sanitize_text_field(wp_unslash($value));
                        }, (array)$_POST[$hostpn_multi_field['id']]) : [];
                      
                      foreach ($sanitized_post_data as $multi_value) {
                        if (!empty($multi_value)) {
                          $empty = false;
                        }

                        $multi_array[] = HOSTPN_Forms::hostpn_sanitizer(
                          $multi_value, 
                          $hostpn_multi_field['input'], 
                          !empty($hostpn_multi_field['type']) ? $hostpn_multi_field['type'] : '',
                          $hostpn_multi_field // Pass the entire field config
                        );
                      }

                      if (!$empty) {
                        update_post_meta($post_id, $hostpn_multi_field['id'], $multi_array);
                      } else {
                        update_post_meta($post_id, $hostpn_multi_field['id'], '');
                      }
                    }
                  }

                  break;
                default:
                  update_post_meta($post_id, $hostpn_field['id'], $hostpn_value);
                  break;
              }
            }
          } else {
            update_post_meta($post_id, $hostpn_field['id'], '');
          }
        }
      }
    }
  }

  public function hostpn_accommodation_form_save($element_id, $key_value, $hostpn_form_type, $hostpn_form_subtype) {
    $post_type = !empty(get_post_type($element_id)) ? get_post_type($element_id) : 'hostpn_accommodation';

    if ($post_type == 'hostpn_accommodation') {
      switch ($hostpn_form_type) {
        case 'post':
          switch ($hostpn_form_subtype) {
            case 'post_new':
              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  if (strpos($key, 'hostpn_') !== false) {
                    ${$key} = $value;
                    delete_post_meta($element_id, $key);
                  }
                }
              }

              $post_functions = new HOSTPN_Functions_Post();
              $accommodation_id = $post_functions->hostpn_insert_post(esc_html($hostpn_accommodation_title), $hostpn_accommodation_description, '', sanitize_title(esc_html($hostpn_accommodation_title)), 'hostpn_accommodation', 'publish', get_current_user_id());

              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_post_meta($accommodation_id, $key, $value);
                }
              }

              break;
            case 'post_edit':
              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  if (strpos($key, 'hostpn_') !== false) {
                    ${$key} = $value;
                    delete_post_meta($element_id, $key);
                  }
                }
              }

              $accommodation_id = $element_id;
              wp_update_post(['ID' => $accommodation_id, 'post_title' => $hostpn_accommodation_title, 'post_content' => $hostpn_accommodation_description,]);

              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  update_post_meta($accommodation_id, $key, $value);
                }
              }

              break;
            case 'post_check':
              self::hostpn_history_add($element_id);
              break;
            case 'post_uncheck':
              if (!empty($key_value)) {
                foreach ($key_value as $key => $value) {
                  if (strpos($key, 'hostpn_') !== false) {
                    ${$key} = $value;
                    delete_post_meta($element_id, $key);
                  }
                }
              }

              break;
          }
      }
    }
  }

  public function hostpn_accommodation_register_scripts() {
    if (!wp_script_is('hostpn-aux', 'registered')) {
      wp_register_script('hostpn-aux', HOSTPN_URL . 'assets/js/hostpn-aux.js', [], HOSTPN_VERSION, true);
    }

    if (!wp_script_is('hostpn-forms', 'registered')) {
      wp_register_script('hostpn-forms', HOSTPN_URL . 'assets/js/hostpn-forms.js', [], HOSTPN_VERSION, true);
    }

    if (!wp_script_is('hostpn-selector', 'registered')) {
      wp_register_script('hostpn-selector', HOSTPN_URL . 'assets/js/hostpn-selector.js', [], HOSTPN_VERSION, true);
    }
  }

  public function hostpn_accommodation_print_scripts() {
    wp_print_scripts(['hostpn-aux', 'hostpn-forms', 'hostpn-selector']);
  }

  public function hostpn_accommodation_list_wrapper() {
    ob_start();

    if(HOSTPN_Functions_User::is_user_admin(get_current_user_id())) {
      ?>
        <div class="hostpn-hostpn_accommodation-list hostpn-mb-50">
          <div class="hostpn-accommodation-search-container hostpn-mb-20 hostpn-text-align-right">
            <div class="hostpn-accommodation-search-wrapper">
              <input type="text" class="hostpn-accommodation-search-input hostpn-input hostpn-display-none" placeholder="<?php esc_attr_e('Filter...', 'hostpn'); ?>" />
              <i class="material-icons-outlined hostpn-accommodation-search-toggle hostpn-cursor-pointer hostpn-font-size-30 hostpn-vertical-align-middle hostpn-tooltip" title="<?php esc_attr_e('Search Accommodations', 'hostpn'); ?>">search</i>

              <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-add" data-hostpn-ajax-type="hostpn_accommodation_new">
                <i class="material-icons-outlined hostpn-cursor-pointer hostpn-font-size-30 hostpn-vertical-align-middle hostpn-tooltip" title="<?php esc_attr_e('Add new Accommodation', 'hostpn'); ?>">add</i>
              </a>
            </div>
          </div>

          <div class="hostpn-hostpn_accommodation-list-wrapper">
            <?php echo wp_kses(self::hostpn_accommodation_list(), HOSTPN_KSES); ?>
          </div>
        </div>
      <?php
    }else{
      echo do_shortcode('[hostpn-call-to-action hostpn_call_to_action_icon="account_circle" hostpn_call_to_action_title="' . __('Account needed', 'hostpn') . '" hostpn_call_to_action_content="' . __('You need a valid account to see this content. Please', 'hostpn') . ' ' . '<a href=\'#\' class=\'userspn-profile-popup-btn\'>' . __('login', 'hostpn') . '</a>' . ' ' . __('or', 'hostpn') . ' ' . '<a href=\'#\' class=\'userspn-profile-popup-btn\' data-userspn-action=\'register\'>' . __('register', 'hostpn') . '</a>' . ' ' . __('to go ahead', 'hostpn') . '" hostpn_call_to_action_button_link="#" hostpn_call_to_action_button_text="' . __('Login', 'hostpn') . '" hostpn_call_to_action_button_class="userspn-profile-popup-btn" hostpn_call_to_action_class="hostpn-mb-100"]');
    }
    
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_accommodation_list() {
    $accommodation_atts = [
      'fields' => 'ids',
      'numberposts' => -1,
      'post_type' => 'hostpn_accommodation',
      'post_status' => 'any', 
      'orderby' => 'date', 
      'order' => 'DESC', 
    ];
    
    if (class_exists('Polylang')) {
      $accommodation_atts['lang'] = pll_current_language('slug');
    }

    $accommodation = get_posts($accommodation_atts);

    ob_start();
    ?>
      <ul class="hostpn-accommodations hostpn-list-style-none hostpn-margin-auto">
        <?php if (!empty($accommodation)): ?>
          <?php foreach ($accommodation as $accommodation_id): ?>
            <?php
              $hostpn_accommodation_period = get_post_meta($accommodation_id, 'hostpn_accommodation_period', true);
              $hostpn_accommodation_timed_checkbox = get_post_meta($accommodation_id, 'hostpn_accommodation_timed_checkbox', true);
              $accommodation_title = get_post_meta($accommodation_id, 'hostpn_accommodation_title', true);
            ?>

            <li class="hostpn-accommodation hostpn-mb-10" data-hostpn_accommodation-id="<?php echo esc_attr($accommodation_id); ?>">
              <div class="hostpn-display-table hostpn-width-100-percent">
                <div class="hostpn-display-inline-table hostpn-width-60-percent">
                  <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-view" data-hostpn-ajax-type="hostpn_accommodation_view">
                    <span><?php echo esc_html($accommodation_title); ?></span>
                      
                    <?php if ($hostpn_accommodation_timed_checkbox == 'on'): ?>
                      <i class="material-icons-outlined hostpn-timed hostpn-cursor-pointer hostpn-vertical-align-super hostpn-p-5 hostpn-font-size-15 hostpn-tooltip" title="<?php esc_html_e('This Accommodation is timed', 'hostpn'); ?>">access_time</i>
                    <?php endif ?>

                    <?php if ($hostpn_accommodation_period == 'on'): ?>
                      <i class="material-icons-outlined hostpn-timed hostpn-cursor-pointer hostpn-vertical-align-super hostpn-p-5 hostpn-font-size-15 hostpn-tooltip" title="<?php esc_html_e('This Accommodation is periodic', 'hostpn'); ?>">replay</i>
                    <?php endif ?>
                  </a>
                </div>

                <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-text-align-right hostpn-position-relative">
                  <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-share" data-hostpn-ajax-type="hostpn_accommodation_share">
                    <i class="material-icons-outlined hostpn-share-btn hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-mr-10 hostpn-tooltip" title="<?php esc_html_e('Share link to allow guests fill their information out directly in the platform.', 'hostpn'); ?>">share</i>
                  </a>

                  <i class="material-icons-outlined hostpn-menu-more-btn hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30">more_vert</i>

                  <div class="hostpn-menu-more hostpn-z-index-99 hostpn-display-none-soft">
                    <ul class="hostpn-list-style-none">
                      <li>
                        <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-view" data-hostpn-ajax-type="hostpn_accommodation_view">
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('View Accommodation', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">visibility</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-edit" data-hostpn-ajax-type="hostpn_accommodation_edit"> 
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Edit Accommodation', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">edit</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-share" data-hostpn-ajax-type="hostpn_accommodation_share">
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Share accommodation', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">share</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-accommodation-duplicate-post">
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Duplicate Accommodation', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">copy</i>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="#" class="hostpn-popup-open" data-hostpn-popup-id="hostpn-popup-accommodation-remove">
                          <div class="hostpn-display-table hostpn-width-100-percent">
                            <div class="hostpn-display-inline-table hostpn-width-70-percent">
                              <p><?php esc_html_e('Remove Accommodation', 'hostpn'); ?></p>
                            </div>
                            <div class="hostpn-display-inline-table hostpn-width-20-percent  hostpn-text-align-right">
                              <i class="material-icons-outlined hostpn-vertical-align-middle hostpn-font-size-30 hostpn-ml-30">delete</i>
                            </div>
                          </div>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </li>
          <?php endforeach ?>
        <?php endif ?>

        <li class="hostpn-mt-50 hostpn-accommodation" data-hostpn_accommodation-id="0">
          <a href="#" class="hostpn-popup-open-ajax hostpn-text-decoration-none" data-hostpn-popup-id="hostpn-popup-accommodation-add" data-hostpn-ajax-type="hostpn_accommodation_new">
            <div class="hostpn-display-table hostpn-width-100-percent">
              <div class="hostpn-display-inline-table hostpn-width-20-percent hostpn-tablet-display-block hostpn-tablet-width-100-percent hostpn-text-align-center">
                <i class="material-icons-outlined hostpn-cursor-pointer hostpn-vertical-align-middle hostpn-font-size-30 hostpn-width-25">add</i>
              </div>
              <div class="hostpn-display-inline-table hostpn-width-80-percent hostpn-tablet-display-block hostpn-tablet-width-100-percent">
                <?php esc_html_e('Add new Accommodation', 'hostpn'); ?>
              </div>
            </div>
          </a>
        </li>
      </ul>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_accommodation_view($accommodation_id) {  
    ob_start();
    self::hostpn_accommodation_register_scripts();
    self::hostpn_accommodation_print_scripts();
    ?>
      <div class="accommodation-view hostpn-p-30" data-hostpn_accommodation-id="<?php echo esc_attr($accommodation_id); ?>">
        <h4 class="hostpn-text-align-center"><?php echo esc_html(get_the_title($accommodation_id)); ?></h4>
        
        <div class="hostpn-word-wrap-break-word">
          <p><?php echo wp_kses(str_replace(']]>', ']]&gt;', apply_filters('the_content', get_post($accommodation_id)->post_content)), HOSTPN_KSES); ?></p>
        </div>

        <div class="accommodation-view">
          <?php foreach (array_merge(self::hostpn_accommodation_get_fields(), self::hostpn_accommodation_get_fields_meta()) as $hostpn_field): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_display_wrapper($hostpn_field, 'post', $accommodation_id), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right hostpn-accommodation" data-hostpn_accommodation-id="<?php echo esc_attr($accommodation_id); ?>">
            <a href="#" class="hostpn-btn hostpn-btn-mini hostpn-popup-open-ajax" data-hostpn-popup-id="hostpn-popup-accommodation-edit" data-hostpn-ajax-type="hostpn_accommodation_edit"><?php esc_html_e('Edit Accommodation', 'hostpn'); ?></a>
          </div>
        </div>
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_accommodation_new() {
    ob_start();
    self::hostpn_accommodation_register_scripts();
    self::hostpn_accommodation_print_scripts();
    ?>
      <div class="accommodation-new hostpn-p-30">
        <h4 class="hostpn-mb-30"><?php esc_html_e('Add new Accommodation', 'hostpn'); ?></h4>

        <form action="" method="post" id="hostpn-form" class="hostpn-form">      
          <?php foreach (self::hostpn_accommodation_get_fields() as $hostpn_field): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post'), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <?php foreach (self::hostpn_accommodation_get_fields_meta() as $hostpn_field_meta): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field_meta, 'post'), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right">
            <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-subtype="post_new" data-hostpn-post-type="hostpn_accommodation" type="submit" value="<?php esc_attr_e('Create Accommodation', 'hostpn'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_accommodation_edit($accommodation_id) {
    ob_start();
    self::hostpn_accommodation_register_scripts();
    self::hostpn_accommodation_print_scripts();
    ?>
      <div class="accommodation-edit hostpn-p-30">
        <p class="hostpn-text-align-center hostpn-mb-0"><?php esc_html_e('Editing', 'hostpn'); ?></p>
        <h4 class="hostpn-text-align-center hostpn-mb-30"><?php echo esc_html(get_the_title($accommodation_id)); ?></h4>

        <form action="" method="post" id="hostpn-form" class="hostpn-form">      
          <?php foreach (self::hostpn_accommodation_get_fields($accommodation_id) as $hostpn_field): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field, 'post', $accommodation_id), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <?php foreach (self::hostpn_accommodation_get_fields_meta() as $hostpn_field_meta): ?>
            <?php echo wp_kses(HOSTPN_Forms::hostpn_input_wrapper_builder($hostpn_field_meta, 'post', $accommodation_id), HOSTPN_KSES); ?>
          <?php endforeach ?>

          <div class="hostpn-text-align-right">
            <input class="hostpn-btn" data-hostpn-type="post" data-hostpn-subtype="post_edit" data-hostpn-post-type="hostpn_accommodation" type="submit" data-hostpn-post-id="<?php echo esc_attr($accommodation_id); ?>" value="<?php esc_attr_e('Save Accommodation', 'hostpn'); ?>"/>
          </div>
        </form> 
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }
  
  public function hostpn_accommodation_share() {
    ob_start();
    ?>
      <div class="hostpn-p-30">
        <h3 class="hostpn-text-align-center"><?php esc_html_e('Accommodation share link', 'hostpn'); ?></h3>
        <p class="hostpn-text-align-center"><?php esc_html_e('You can share accommodation link to allow companions to fill out their guests forms directly in the platform.', 'hostpn'); ?></p>

        <?php if (class_exists('USERSPN')): ?>
          <div class="hostpn-display-table hostpn-width-100-percent">
            <div class="hostpn-display-inline-table hostpn-width-90-percent">
              <code id="hostpn-share-url"><?php 
                $share_url = add_query_arg([
                  'hostpn_action' => 'popup_open',
                  'hostpn_popup' => 'userspn-profile-popup',
                  'hostpn_tab' => 'register',
                  'hostpn_get_nonce' => wp_create_nonce('hostpn-get-nonce')
                ], home_url('guests'));
                echo esc_url($share_url); 
              ?></code>
            </div>
            <div class="hostpn-display-inline-table hostpn-width-10-percent hostpn-text-align-center hostpn-copy-disabled">
              <i class="material-icons-outlined hostpn-btn-copy hostpn-vertical-align-middle hostpn-cursor-pointer hostpn-tooltip" title="<?php esc_html_e('Copy url', 'hostpn'); ?>" data-hostpn-copy-content="#hostpn-share-url">content_copy</i>
            </div>
          </div>
        <?php else: ?>
          <p class="hostpn-text-align-center"><?php esc_html_e('Please install Users Manager - PN plugin to allow user creation and management.', 'hostpn'); ?></p>

          <div class="hostpn-text-align-center">
            <a href="<?php echo esc_url(self::hostpn_share_link()); ?>" class="hostpn-btn hostpn-btn-mini"><?php esc_html_e('Install plugin', 'hostpn'); ?></a>
          </div>
        <?php endif ?>
      </div>
    <?php
    $hostpn_return_string = ob_get_contents(); 
    ob_end_clean(); 
    return $hostpn_return_string;
  }

  public function hostpn_share_link() {
    return esc_url(admin_url('/plugin-install.php?s=userspn&tab=search&type=term'));
  }
}
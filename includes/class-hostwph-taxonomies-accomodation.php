<?php
/**
 * Accomodation taxonomies creator.
 *
 * This class defines Accomodation taxonomies.
 *
 * @link       wordpress-heroes.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@wordpress-heroes.com>
 */
class HOSTWPH_Taxonomies_Accomodation { 
	/**
	 * Register taxonomies.
	 *
	 * @since    1.0.0
	 */
	public static function register_taxonomies() {
		$taxonomies = [
			'hostwph_accomodation_category' => [
				'name'               	=> _x('Accomodation categories', 'Taxonomy general name', 'hostwph'),
				'singular_name'      	=> _x('Accomodation category', 'Taxonomy singular name', 'hostwph'),
				'search_items'      	=> esc_html(__('Search Accomodation categories', 'hostwph')),
        'all_items'         	=> esc_html(__('All Accomodation categories', 'hostwph')),
        'parent_item'       	=> esc_html(__('Parent Accomodation category', 'hostwph')),
        'parent_item_colon' 	=> esc_html(__('Parent Accomodation category:', 'hostwph')),
        'edit_item'         	=> esc_html(__('Edit Accomodation category', 'hostwph')),
        'update_item'       	=> esc_html(__('Update Accomodation category', 'hostwph')),
        'add_new_item'      	=> esc_html(__('Add New Accomodation category', 'hostwph')),
        'new_item_name'     	=> esc_html(__('New Accomodation category', 'hostwph')),
        'menu_name'         	=> esc_html(__('Accomodation categories', 'hostwph')),
				'manage_terms'      	=> 'manage_hostwph_accomodation_category',
	      'edit_terms'        	=> 'edit_hostwph_accomodation_category',
	      'delete_terms'      	=> 'delete_hostwph_accomodation_category',
	      'assign_terms'      	=> 'assign_hostwph_accomodation_category',
	      'archive'			      	=> false,
	      'slug'			      		=> 'hosts',
			],
		];;

	  foreach ($taxonomies as $taxonomy => $options) {
	  	$labels = [
				'name'          		=> $options['name'],
				'singular_name' 		=> $options['singular_name'],
			];

			$capabilities = [
				'manage_terms'      => $options['manage_terms'],
				'edit_terms'      	=> $options['edit_terms'],
				'delete_terms'      => $options['delete_terms'],
				'assign_terms'      => $options['assign_terms'],
	    ];

			$args = [
				'labels'            => $labels,
				'hierarchical'      => true,
				'public'            => false,
				'show_ui' 					=> false,
				'query_var'         => false,
				'rewrite'           => false,
				'show_in_rest'      => true,
	    	'capabilities'      => $capabilities,
			];

			if ($options['archive']) {
				$args['public'] = true;
				$args['publicly_queryable'] = true;
				$args['show_in_nav_menus'] = true;
				$args['query_var'] = $taxonomy;
				$args['show_ui'] = true;
				$args['rewrite'] = [
					'slug' => $options['slug'],
				];
			}

			register_taxonomy($taxonomy, 'hostwph_accomodation', $args);
			register_taxonomy_for_object_type($taxonomy, 'hostwph_accomodation');
		}
	}
}
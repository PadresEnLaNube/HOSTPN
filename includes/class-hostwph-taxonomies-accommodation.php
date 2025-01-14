<?php
/**
 * Accommodation taxonomies creator.
 *
 * This class defines Accommodation taxonomies.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@padresenlanube.com>
 */
class HOSTWPH_Taxonomies_Accommodation { 
	/**
	 * Register taxonomies.
	 *
	 * @since    1.0.0
	 */
	public static function register_taxonomies() {
		$taxonomies = [
			'hostwph_accommodation_category' => [
				'name'               	=> _x('Accommodation categories', 'Taxonomy general name', 'hostwph'),
				'singular_name'      	=> _x('Accommodation category', 'Taxonomy singular name', 'hostwph'),
				'search_items'      	=> esc_html(__('Search Accommodation categories', 'hostwph')),
        'all_items'         	=> esc_html(__('All Accommodation categories', 'hostwph')),
        'parent_item'       	=> esc_html(__('Parent Accommodation category', 'hostwph')),
        'parent_item_colon' 	=> esc_html(__('Parent Accommodation category:', 'hostwph')),
        'edit_item'         	=> esc_html(__('Edit Accommodation category', 'hostwph')),
        'update_item'       	=> esc_html(__('Update Accommodation category', 'hostwph')),
        'add_new_item'      	=> esc_html(__('Add New Accommodation category', 'hostwph')),
        'new_item_name'     	=> esc_html(__('New Accommodation category', 'hostwph')),
        'menu_name'         	=> esc_html(__('Accommodation categories', 'hostwph')),
				'manage_terms'      	=> 'manage_hostwph_accommodation_category',
	      'edit_terms'        	=> 'edit_hostwph_accommodation_category',
	      'delete_terms'      	=> 'delete_hostwph_accommodation_category',
	      'assign_terms'      	=> 'assign_hostwph_accommodation_category',
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

			register_taxonomy($taxonomy, 'hostwph_accomm', $args);
			register_taxonomy_for_object_type($taxonomy, 'hostwph_accomm');
		}
	}
}
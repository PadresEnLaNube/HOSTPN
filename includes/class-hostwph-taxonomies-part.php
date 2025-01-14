<?php
/**
 * Part of travelers taxonomies creator.
 *
 * This class defines Part of travelers taxonomies.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@padresenlanube.com>
 */
class HOSTWPH_Taxonomies_Part { 
	/**
	 * Register taxonomies.
	 *
	 * @since    1.0.0
	 */
	public static function register_taxonomies() {
		$taxonomies = [
			'hostwph_part_category' => [
				'name'               	=> _x('Part of travelers categories', 'Taxonomy general name', 'hostwph'),
				'singular_name'      	=> _x('Part of travelers category', 'Taxonomy singular name', 'hostwph'),
				'search_items'      	=> esc_html(__('Search Part of travelers categories', 'hostwph')),
        'all_items'         	=> esc_html(__('All Part of travelers categories', 'hostwph')),
        'parent_item'       	=> esc_html(__('Parent Part of travelers category', 'hostwph')),
        'parent_item_colon' 	=> esc_html(__('Parent Part of travelers category:', 'hostwph')),
        'edit_item'         	=> esc_html(__('Edit Part of travelers category', 'hostwph')),
        'update_item'       	=> esc_html(__('Update Part of travelers category', 'hostwph')),
        'add_new_item'      	=> esc_html(__('Add New Part of travelers category', 'hostwph')),
        'new_item_name'     	=> esc_html(__('New Part of travelers category', 'hostwph')),
        'menu_name'         	=> esc_html(__('Part of travelers categories', 'hostwph')),
				'manage_terms'      	=> 'manage_hostwph_part_category',
	      'edit_terms'        	=> 'edit_hostwph_part_category',
	      'delete_terms'      	=> 'delete_hostwph_part_category',
	      'assign_terms'      	=> 'assign_hostwph_part_category',
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

			register_taxonomy($taxonomy, 'hostwph_part', $args);
			register_taxonomy_for_object_type($taxonomy, 'hostwph_part');
		}
	}
}
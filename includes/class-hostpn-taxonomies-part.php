<?php
/**
 * Part of travelers taxonomies creator.
 *
 * This class defines Part of travelers taxonomies.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Taxonomies_Part { 
	/**
	 * Register taxonomies.
	 *
	 * @since    1.0.0
	 */
	public static function register_taxonomies() {
		$taxonomies = [
			'hostpn_part_category' => [
				'name'               	=> _x('Part of travelers categories', 'Taxonomy general name', 'hostpn'),
				'singular_name'      	=> _x('Part of travelers category', 'Taxonomy singular name', 'hostpn'),
				'search_items'      	=> esc_html(__('Search Part of travelers categories', 'hostpn')),
        'all_items'         	=> esc_html(__('All Part of travelers categories', 'hostpn')),
        'parent_item'       	=> esc_html(__('Parent Part of travelers category', 'hostpn')),
        'parent_item_colon' 	=> esc_html(__('Parent Part of travelers category:', 'hostpn')),
        'edit_item'         	=> esc_html(__('Edit Part of travelers category', 'hostpn')),
        'update_item'       	=> esc_html(__('Update Part of travelers category', 'hostpn')),
        'add_new_item'      	=> esc_html(__('Add New Part of travelers category', 'hostpn')),
        'new_item_name'     	=> esc_html(__('New Part of travelers category', 'hostpn')),
        'menu_name'         	=> esc_html(__('Part of travelers categories', 'hostpn')),
				'manage_terms'      	=> 'manage_hostpn_part_category',
	      'edit_terms'        	=> 'edit_hostpn_part_category',
	      'delete_terms'      	=> 'delete_hostpn_part_category',
	      'assign_terms'      	=> 'assign_hostpn_part_category',
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

			register_taxonomy($taxonomy, 'hostpn_part', $args);
			register_taxonomy_for_object_type($taxonomy, 'hostpn_part');
		}
	}
}
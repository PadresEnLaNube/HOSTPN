<?php
/**
 * Guest taxonomies creator.
 *
 * This class defines Guest taxonomies.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTWPH
 * @subpackage HOSTWPH/includes
 * @author     wordpress-heroes <info@padresenlanube.com>
 */
class HOSTWPH_Taxonomies_Host { 
	/**
	 * Register taxonomies.
	 *
	 * @since    1.0.0
	 */
	public static function register_taxonomies() {
		$taxonomies = [
			'hostwph_guest_category' => [
				'name'               	=> _x('Guest categories', 'Taxonomy general name', 'hostwph'),
				'singular_name'      	=> _x('Guest category', 'Taxonomy singular name', 'hostwph'),
				'search_items'      	=> esc_html(__('Search Guest categories', 'hostwph')),
        'all_items'         	=> esc_html(__('All Guest categories', 'hostwph')),
        'parent_item'       	=> esc_html(__('Parent Guest category', 'hostwph')),
        'parent_item_colon' 	=> esc_html(__('Parent Guest category:', 'hostwph')),
        'edit_item'         	=> esc_html(__('Edit Guest category', 'hostwph')),
        'update_item'       	=> esc_html(__('Update Guest category', 'hostwph')),
        'add_new_item'      	=> esc_html(__('Add New Guest category', 'hostwph')),
        'new_item_name'     	=> esc_html(__('New Guest category', 'hostwph')),
        'menu_name'         	=> esc_html(__('Guest categories', 'hostwph')),
				'manage_terms'      	=> 'manage_hostwph_guest_category',
	      'edit_terms'        	=> 'edit_hostwph_guest_category',
	      'delete_terms'      	=> 'delete_hostwph_guest_category',
	      'assign_terms'      	=> 'assign_hostwph_guest_category',
	      'archive'			      	=> false,
	      'slug'			      		=> 'guests',
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

			register_taxonomy($taxonomy, 'hostwph_guest', $args);
			register_taxonomy_for_object_type($taxonomy, 'hostwph_guest');
		}
	}
}
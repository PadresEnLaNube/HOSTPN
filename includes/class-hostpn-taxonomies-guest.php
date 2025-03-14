<?php
/**
 * Guest taxonomies creator.
 *
 * This class defines Guest taxonomies.
 *
 * @link       padresenlanube.com/
 * @since      1.0.0
 * @package    HOSTPN
 * @subpackage HOSTPN/includes
 * @author     Padres en la Nube <info@padresenlanube.com>
 */
class HOSTPN_Taxonomies_Host { 
	/**
	 * Register taxonomies.
	 *
	 * @since    1.0.0
	 */
	public static function register_taxonomies() {
		$taxonomies = [
			'hostpn_guest_category' => [
				'name'               	=> _x('Guest categories', 'Taxonomy general name', 'hostpn'),
				'singular_name'      	=> _x('Guest category', 'Taxonomy singular name', 'hostpn'),
				'search_items'      	=> esc_html(__('Search Guest categories', 'hostpn')),
        'all_items'         	=> esc_html(__('All Guest categories', 'hostpn')),
        'parent_item'       	=> esc_html(__('Parent Guest category', 'hostpn')),
        'parent_item_colon' 	=> esc_html(__('Parent Guest category:', 'hostpn')),
        'edit_item'         	=> esc_html(__('Edit Guest category', 'hostpn')),
        'update_item'       	=> esc_html(__('Update Guest category', 'hostpn')),
        'add_new_item'      	=> esc_html(__('Add New Guest category', 'hostpn')),
        'new_item_name'     	=> esc_html(__('New Guest category', 'hostpn')),
        'menu_name'         	=> esc_html(__('Guest categories', 'hostpn')),
				'manage_terms'      	=> 'manage_hostpn_guest_category',
	      'edit_terms'        	=> 'edit_hostpn_guest_category',
	      'delete_terms'      	=> 'delete_hostpn_guest_category',
	      'assign_terms'      	=> 'assign_hostpn_guest_category',
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

			register_taxonomy($taxonomy, 'hostpn_guest', $args);
			register_taxonomy_for_object_type($taxonomy, 'hostpn_guest');
		}
	}
}
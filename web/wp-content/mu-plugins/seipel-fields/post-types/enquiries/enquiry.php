<?php // Register Custom Post Type
function enquiry_post_type() {

	$labels = array(
		'name'                  => _x( 'Enquiries', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Enquiry', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Enquiries', 'text_domain' ),
		'name_admin_bar'        => __( 'Enquiries', 'text_domain' ),
		'archives'              => __( 'Enquiry Archives', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Enquiry:', 'text_domain' ),
		'all_items'             => __( 'All Enquiries', 'text_domain' ),
		'add_new_item'          => __( 'Add New Enquiry', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Enquiry', 'text_domain' ),
		'edit_item'             => __( 'Edit Enquiry', 'text_domain' ),
		'update_item'           => __( 'Update Enquiry', 'text_domain' ),
		'view_item'             => __( 'View Enquiry', 'text_domain' ),
		'search_items'          => __( 'Search Enquiry', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into Enquiry', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Enquiry', 'text_domain' ),
		'items_list'            => __( 'Enquiries list', 'text_domain' ),
		'items_list_navigation' => __( 'Enquiries list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter Enquiries list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Enquiry', 'text_domain' ),
		'description'           => __( 'Enquiry Post Type', 'text_domain'),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor' ),
		'taxonomies'            => array( ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 7,
		'menu_icon'             => 'dashicons-email',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'enquiry', $args );

}
add_action( 'init', 'enquiry_post_type', 0 );

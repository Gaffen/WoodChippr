<?php
// Register Custom Post Type
function glossary_post_type() {

	$labels = array(
		'name'                  => _x( 'Terms', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Term', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Visual Glossary', 'text_domain' ),
		'name_admin_bar'        => __( 'Glossary', 'text_domain' ),
		'archives'              => __( 'Term Archives', 'text_domain' ),
		'attributes'            => __( 'Term Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Term:', 'text_domain' ),
		'all_items'             => __( 'All Terms', 'text_domain' ),
		'add_new_item'          => __( 'Add New Term', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Term', 'text_domain' ),
		'edit_item'             => __( 'Edit Term', 'text_domain' ),
		'update_item'           => __( 'Update Term', 'text_domain' ),
		'view_item'             => __( 'View Term', 'text_domain' ),
		'view_items'            => __( 'View Terms', 'text_domain' ),
		'search_items'          => __( 'Search Term', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set image', 'text_domain' ),
		'remove_featured_image' => __( 'Remov image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into term', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this term', 'text_domain' ),
		'items_list'            => __( 'Terms list', 'text_domain' ),
		'items_list_navigation' => __( 'Terms list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter terms list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Item', 'text_domain' ),
		'description'           => __( 'Visual Glossary Terms', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
    'menu_icon'             => 'dashicons-editor-ul',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'glossary', $args );

}
add_action( 'init', 'glossary_post_type', 0 );

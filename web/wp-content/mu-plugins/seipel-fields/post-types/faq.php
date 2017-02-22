<?php // Register Custom Post Type
function faq_post_type() {

	$labels = array(
		'name'                  => 'FAQ',
		'singular_name'         => 'FAQ Item',
		'menu_name'             => 'FAQ',
		'name_admin_bar'        => 'FAQ',
		'parent_item_colon'     => 'Parent FAQ:',
		'all_items'             => 'All FAQ',
		'add_new_item'          => 'Add New FAQ',
		'add_new'               => 'Add New',
		'new_item'              => 'New FAQ',
		'edit_item'             => 'Edit FAQ',
		'update_item'           => 'Update FAQ',
		'view_item'             => 'View FAQ',
		'search_items'          => 'Search FAQ',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'items_list'            => 'FAQ list',
		'items_list_navigation' => 'FAQ list navigation',
		'filter_items_list'     => 'Filter FAQ list',
	);
	$args = array(
		'label'                 => 'FAQ Item',
		'description'           => 'FAQ post type',
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'revisions', ),
		'taxonomies'            => array( 'faq_category' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-info',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'faq', $args );

  $labels = array(
		'name'                       => 'FAQ Categories',
		'singular_name'              => 'FAQ Category',
		'menu_name'                  => 'FAQ Category',
		'all_items'                  => 'All FAQ Categories',
		'parent_item'                => 'Parent FAQ Categories',
		'parent_item_colon'          => 'Parent FAQ Categories:',
		'new_item_name'              => 'New FAQ Category Name',
		'add_new_item'               => 'Add New FAQ Category',
		'edit_item'                  => 'Edit FAQ Category',
		'update_item'                => 'Update FAQ Category',
		'view_item'                  => 'View FAQ Category',
		'separate_items_with_commas' => 'Separate FAQ Categories with commas',
		'add_or_remove_items'        => 'Add or remove FAQ Categories',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular FAQ Categories',
		'search_items'               => 'Search FAQ Categories',
		'not_found'                  => 'Not Found',
		'items_list'                 => 'FAQ Categories list',
		'items_list_navigation'      => 'FAQ Categories list navigation',
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'faq_category', array( 'faq' ), $args );

}
add_action( 'init', 'faq_post_type', 0 );

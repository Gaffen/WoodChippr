<?php
// require_once 'inc/acf.php';

require_once 'post-types/glossary.php';
require_once 'post-types/faq.php';
require_once 'post-types/enquiries/index.php';

function seipel_clean_wordpress_menu() {
    remove_menu_page( 'edit.php' );
}
add_action( 'admin_menu', 'seipel_clean_wordpress_menu' );

add_theme_support( 'post-thumbnails' );

add_filter('acf/settings/save_json', 'my_acf_json_save_point');

function my_acf_json_save_point( $path ) {

    // update path
    $path = plugin_dir_path( __FILE__ ) . 'local-json';

    // return
    return $path;

}

add_filter('acf/settings/load_json', 'my_acf_json_load_point');

function my_acf_json_load_point( $paths ) {

    // remove original path (optional)
    unset($paths[0]);


    // append path
    $paths[] = plugin_dir_path( __FILE__ ) . 'local-json';


    // return
    return $paths;

}

<?php

if (! function_exists('write_log')) {
    function write_log($log)
    {
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}

require_once("AutoLoader.php");

add_filter('acf/settings/save_json', 'my_acf_json_save_point');

function my_acf_json_save_point($path)
{

    // update path
    $path = plugin_dir_path(__FILE__) . 'local-json';

    // return
    return $path;
}

add_filter('acf/settings/load_json', 'my_acf_json_load_point');

function my_acf_json_load_point($paths)
{

    // remove original path (optional)
    unset($paths[0]);


    // append path
    $paths[] = plugin_dir_path(__FILE__) . 'local-json';


    // return
    return $paths;
}

// function my_acf_init() {
//
// 	acf_update_setting('google_api_key', get_field('gmaps_key', 'option'));
// }
//
// add_action('acf/init', 'my_acf_init');

// function cc_mime_types($mimes) {
//   $mimes['svg'] = 'image/svg+xml';
//   return $mimes;
// }
// add_filter('upload_mimes', 'cc_mime_types');
//
// function custom_admin_head() {
//   $css = '';
//
//   $css = 'td.media-icon img[src$=".svg"] { width: 100% !important; height: auto !important; }';
//
//   echo '<style type="text/css">'.$css.'</style>';
// }
// add_action('admin_head', 'custom_admin_head');

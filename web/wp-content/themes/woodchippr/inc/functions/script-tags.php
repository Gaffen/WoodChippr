<?php

/*********************
SCRIPT ENQUEUEING, taking
much code from
http://themble.com/bones/
*********************/

/*********************
SCRIPTS & ENQUEUEING
*********************/

// loading modernizr and jquery, and reply script
function chippr_scripts_and_styles()
{
    global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

    if (!is_admin()) {

        // wp_register_script( 'chippr-modernizr', get_stylesheet_directory_uri() . '/js/modernizr.js', array(), '0.0.0', false );

        // register main stylesheet
        wp_register_style('main-stylesheet', get_stylesheet_directory_uri() . '/css/style.css', array(), '0.0.0', 'all');

        // comment reply script for threaded comments
        if (is_singular() and comments_open() and (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }

        //adding scripts file in the footer
        wp_register_script('chippr-js', get_stylesheet_directory_uri() . '/js/main.bundle.js', array( 'jquery' ), '0.0.0', true);

        // enqueue styles and scripts
        wp_enqueue_script('chippr-modernizr');
        wp_enqueue_style('main-stylesheet');

        /*
        I recommend using a plugin to call jQuery
        using the google cdn. That way it stays cached
        and your site will load faster.
        */
        //wp_enqueue_script( 'jquery' );
        wp_enqueue_script('chippr-js');
    }
}

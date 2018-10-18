<?php

/* WOODCHIPPR: Timber and the Bones starter theme ground to a fine powder */
use WoodChippr\Utilities\ImageMethods;

if (! class_exists('Timber')) {
    add_action('admin_notices', function () {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
    });
    return;
}

add_filter('show_admin_bar', '__return_false');

function woodchippr_get_base_img($size, $image)
{
    // write_log(array($size, $image));
    return ImageMethods::getBaseSize($size, $image);
}

class WoodChippr extends TimberSite
{
    public function __construct()
    {
        add_theme_support('menus');
        add_theme_support('post-thumbnails', array( 'post', 'page' ));
        add_filter('timber_context', array( $this, 'add_to_context' ));
        add_filter('get_twig', array( $this, 'add_to_twig' ));
        add_action('init', array( $this, 'register_post_types' ));
        add_action('init', array( $this, 'register_taxonomies' ));
        register_nav_menu('main-menu', __('Main Menu'));

        add_image_size('prod-img', 460);
        add_image_size('prod-img-r', 920);

        /* CHIPPR functions: Bones's tidying functions */
        include_once 'inc/functions/wp-tidy.php';

        /* CHIPPR functions: Declare theme support options in dedicated file */
        include_once 'inc/functions/theme-support.php';

        /* CHIPPR functions: Custom gallery templating using Timber */
        include_once 'inc/functions/gallery.php';

        /* CHIPPR functions: Enqueue scripts and styles */
        include_once 'inc/functions/script-tags.php';

        /* CHIPPR functions: Custom gallery templating using Timber */
        include_once 'inc/functions/init-theme.php';

        parent::__construct();
    }

    public function register_post_types()
    {
        //this is where you can register custom post types
    }

    public function register_taxonomies()
    {
        //this is where you can register custom taxonomies
    }

    public function add_to_context($context)
    {
        // $context['option'] = get_fields('option');
        // $context['menu'] = new TimberMenu('Main Menu');
        // Until the active class functionality is working for Timber,
        // this is the best way of handling wordpress menus

        $context['menu'] = wp_nav_menu(array( 'theme_location' => 'main-menu', 'echo' => false ));

        $context["analyticscode"] = "MISSING_TRACKING_CODE";

        return $context;
    }

    public function add_to_twig($twig)
    {
        /* this is where you can add your own fuctions to twig */
        $twig->addExtension(new Twig_Extension_StringLoader());

        add_filter('timber/twig', function (\Twig_Environment $twig) {
            $twig->addFunction(new Timber\Twig_Function('woodchippr_get_base_img', 'woodchippr_get_base_img'));
            return $twig;
        });

        return $twig;
    }
}

new WoodChippr();

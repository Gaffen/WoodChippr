<?php
use WoodChippr\Utilities\RImg;

remove_shortcode('gallery');
add_shortcode('gallery', 'chippr_gallery');


/**
 * Builds the Gallery shortcode output.
 *
 * This implements the functionality of the Gallery Shortcode for displaying
 * WordPress images on a post.
 *
 * @since 2.5.0
 *
 * @staticvar int $instance
 *
 * @param array $attr {
 *     Attributes of the gallery shortcode.
 *
 *     @type string $order      Order of the images in the gallery. Default 'ASC'. Accepts 'ASC', 'DESC'.
 *     @type string $orderby    The field to use when ordering the images. Default 'menu_order ID'.
 *                              Accepts any valid SQL ORDERBY statement.
 *     @type int    $id         Post ID.
 *     @type string $itemtag    HTML tag to use for each image in the gallery.
 *                              Default 'dl', or 'figure' when the theme registers HTML5 gallery support.
 *     @type string $icontag    HTML tag to use for each image's icon.
 *                              Default 'dt', or 'div' when the theme registers HTML5 gallery support.
 *     @type string $captiontag HTML tag to use for each image's caption.
 *                              Default 'dd', or 'figcaption' when the theme registers HTML5 gallery support.
 *     @type int    $columns    Number of columns of images to display. Default 3.
 *     @type string $size       Size of the images to display. Default 'thumbnail'.
 *     @type string $ids        A comma-separated list of IDs of attachments to display. Default empty.
 *     @type string $include    A comma-separated list of IDs of attachments to include. Default empty.
 *     @type string $exclude    A comma-separated list of IDs of attachments to exclude. Default empty.
 *     @type string $link       What to link each image to. Default empty (links to the attachment page).
 *                              Accepts 'file', 'none'.
 * }
 * @return string HTML content to display gallery.
 */
function chippr_gallery($attr)
{
    $post = get_post();

    static $instance = 0;
    $instance++;

    if (! empty($attr['ids'])) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if (empty($attr['orderby'])) {
            $attr['orderby'] = 'post__in';
        }
        $attr['include'] = $attr['ids'];
    }

    /**
     * Filter the default gallery shortcode output.
     *
     * If the filtered output isn't empty, it will be used instead of generating
     * the default gallery template.
     *
     * @since 2.5.0
     * @since 4.2.0 The `$instance` parameter was added.
     *
     * @see gallery_shortcode()
     *
     * @param string $output   The gallery output. Default empty.
     * @param array  $attr     Attributes of the gallery shortcode.
     * @param int    $instance Unique numeric ID of this gallery shortcode instance.
     */
    $output = apply_filters('post_gallery', '', $attr, $instance);
    if ($output != '') {
        return $output;
    }

    $html5 = current_theme_supports('html5', 'gallery');
    $atts = shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post ? $post->ID : 0,
        'itemtag'    => $html5 ? 'figure'     : 'dl',
        'icontag'    => $html5 ? 'div'        : 'dt',
        'captiontag' => $html5 ? 'figcaption' : 'dd',
        'columns'    => 3,
        'size'       => 'thumbnail',
        'include'    => '',
        'exclude'    => '',
        'link'       => ''
    ), $attr, 'gallery');

    $id = intval($atts['id']);

    if (! empty($atts['include'])) {
        $_attachments = get_posts(array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ));

        $attachments = array();
        foreach ($_attachments as $key => $val) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif (! empty($atts['exclude'])) {
        $attachments = get_children(array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ));
    } else {
        $attachments = get_children(array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ));
    }

    if (empty($attachments)) {
        return '';
    }

    if (is_feed()) {
        $output = "\n";
        foreach ($attachments as $att_id => $attachment) {
            $output .= wp_get_attachment_link($att_id, $atts['size'], true) . "\n";
        }
        return $output;
    }

    /* A bug in timber currently makes it neccessary to get the individual image
         src's and pass them to the template. Replace these sizes with your own.

         START HACK */

    $images = array();

    foreach ($attachments as $attachment) {
        // $image = [
        //     'gall_thumb' => wp_get_attachment_image_src($attachment->ID, 'gall_thumb'),
        //     'gall_thumb-r' => wp_get_attachment_image_src($attachment->ID, 'gall_thumb-r'),
        //     'gall_thumb-l' => wp_get_attachment_image_src($attachment->ID, 'gall_thumb-l'),
        //     'gallimg' => wp_get_attachment_image_src($attachment->ID, 'gallimg'),
        //     'gallimg-r' => wp_get_attachment_image_src($attachment->ID, 'gallimg-r'),
        //     'full' => wp_get_attachment_image_src($attachment->ID, 'full')
        // ];
        $image = new TimberImage($attachment->ID);
        $images[]=$image;
    }

    /* END HACK */

    $itemtag = tag_escape($atts['itemtag']);
    $captiontag = tag_escape($atts['captiontag']);
    $icontag = tag_escape($atts['icontag']);
    $valid_tags = wp_kses_allowed_html('post');
    if (! isset($valid_tags[ $itemtag ])) {
        $itemtag = 'dl';
    }
    if (! isset($valid_tags[ $captiontag ])) {
        $captiontag = 'dd';
    }
    if (! isset($valid_tags[ $icontag ])) {
        $icontag = 'dt';
    }

    $data = [
        "post" => $post,
        "atts" => $atts,
        "attachments" => $attachments,
        "images" => $images,
        "base_url" => get_site_url(),
        "itemtag" => $itemtag,
        "captiontag" => $captiontag,
        "icontag" => $icontag,
        "rtl" => is_rtl()
    ];

    return Timber::compile('partials/gallery.twig', $data);
}

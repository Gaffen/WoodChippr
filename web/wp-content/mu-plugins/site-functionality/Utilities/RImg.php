<?php
namespace WoodChippr\Utilities;

class RImg
{
    public $landscape;
    public $portrait;
    public $id;

    protected $imgsizes;

    /**
     * Responsive image data object
     * @param Number $landscape ID for landscape image
     * @param Number $portrait  ID for portrait image
     * @param array  $options   Options array
     */
    public function __construct($landscape = null, $portrait = null, $options = array(), $sizes = "100vw")
    {
        if (isset($options["size"])) {
            if (is_array($options["size"])) {
                $this->imgsizes = array(
                    'land' => $options["size"]["land"],
                    'port' => $options["size"]["port"]
                );
            } else {
                $this->imgsizes = array(
                    'land' => $options["size"],
                    'port' => $options["size"]
                );
            }
        } else {
            $this->imgsizes = array('land' => 'full', 'port' => 'full');
        }


        if ($landscape) {
            $base_size = ImageMethods::getBaseSize($this->imgsizes["land"], $landscape);

            $base_img = wp_get_attachment_image_src($landscape, $base_size);
            $this->landscape = array(
                'main_img' => $base_img[0],
                'src_set' => wp_get_attachment_image_srcset($landscape, $base_size)
            );
        }

        if ($portrait) {
            $base_size = ImageMethods::getBaseSize($this->imgsizes["port"], $portrait);
            $base_img = wp_get_attachment_image_src($portrait, $base_size);
            $this->portrait = array(
                'src' => $base_img[0],
                'src_set' => wp_get_attachment_image_srcset($portrait, $base_size)
            );
        }
    }
}

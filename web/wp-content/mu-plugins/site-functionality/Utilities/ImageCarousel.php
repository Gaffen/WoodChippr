<?php
namespace WoodChippr\Utilities;

class ImageCarousel
{
    public $slides;

    public function __construct(array $data)
    {
        $this->slides = $data;

        foreach ($this->slides as $index => $slide) {
            $this->slides[$index]['img'] = new RImg($slide['image'], $slide['image_portrait']);
            $this->slides[$index]['video'] = preg_replace('/src="(.+?)"/', 'src="$1&autoplay=1&controls=0&modestbranding=1&showinfo=0&rel=0"', $this->slides[$index]['video']);
        }
    }
}

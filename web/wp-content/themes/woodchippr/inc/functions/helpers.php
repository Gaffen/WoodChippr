<?php

/*********************
  WOODCHIPPR: Helper Functions

  BREAK IT DOWN:
  - Get content without gallery shortcodes
 *********************/

function  strip_shortcode_gallery( $content ) {
    preg_match_all( '/'. get_shortcode_regex() .'/s', $content, $matches, PREG_SET_ORDER );
    if ( ! empty( $matches ) ) {
        foreach ( $matches as $shortcode ) {
            if ( 'gallery' === $shortcode[2] ) {
                $pos = strpos( $content, $shortcode[0] );
                if ($pos !== false)
                    return substr_replace( $content, '', $pos, strlen($shortcode[0]) );
            }
        }
    }
    return $content;
}

function chippr_create_image_sizes($name, $start, $sizes, $ratio=1, $crop=false){

  $height = ($ratio === null)? null : intval(round($start * $ratio));

  add_image_size($name, $start, $height, $crop);

  // var_dump($name, $start, $height, $crop);

  foreach ($sizes as $size) {
    $height = ($ratio === null)? null : intval(round($size * $ratio));
    // var_dump($name, $start, $height, $crop);
    if($size != $start){
      add_image_size($name.$size, $size, $height, $crop);
    }
  }
}

function get_image_sizes() {
	global $_wp_additional_image_sizes;

	$sizes = array();

	foreach ( get_intermediate_image_sizes() as $_size ) {
		if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
			);
		}
	}

	return $sizes;
}

/**
 * Get size information for a specific image size.
 *
 * @uses   get_image_sizes()
 * @param  string $size The image size for which to retrieve data.
 * @return bool|array $size Size data about an image size or false if the size doesn't exist.
 */
function get_image_size( $size ) {
	$sizes = get_image_sizes();

	if ( isset( $sizes[ $size ] ) ) {
		return $sizes[ $size ];
	}

	return false;
}

function get_base_size($size, $image){
  $sizedetail = get_image_size($size);
  if(!$sizedetail || !$sizedetail['crop']){
    return $size;
  }
  if($sizedetail['width'] === 0 || $sizedetail['height'] === 0){
    return $size;
  }
  if( $image['width'] != $sizedetail['width'] && $image['height'] != $sizedetail['height'] ){
    $largest = 0;
    foreach ($image['sizes'] as $name => $sizeitem) {
      if(strpos($name, '-width') == false && strpos($name, '-height') == false){
        $targheight = intval(round($image['sizes'][$name.'-width']/$sizedetail['width']*$sizedetail['height']));
        if($targheight === $image['sizes'][$name.'-height']){
          if($image['sizes'][$name.'-width'] > $largest){
            $largest = $image['sizes'][$name.'-width'];
            $size = $name;
          }
        }
      }
    }
  }
  return $size;
}

?>

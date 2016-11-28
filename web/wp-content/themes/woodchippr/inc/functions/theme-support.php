<?php

/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function chippr_theme_support() {

	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails' );

	// default thumb size
	set_post_thumbnail_size(125, 125, true);

	// rss thingy
	add_theme_support('automatic-feed-links');

	// Woocommerce
  add_theme_support( 'woocommerce' );

	// to add header image support go here: http://themble.com/support/adding-header-background-image-support/

} /* end bones theme support */

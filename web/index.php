<?php
define( 'WP_USE_THEMES', true );
require_once dirname(__DIR__).'/vendor/autoload.php' ;

if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}

require_once __DIR__.'/controlcentre/wp-blog-header.php' ;

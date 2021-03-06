<?php
// ===================================================
// Load database info and local development parameters
// ===================================================
// define( 'WP_LOCAL_DEV', false );

require_once dirname(__DIR__).'/vendor/autoload.php' ;

$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();
define('DB_NAME', $_ENV["DB_NAME"]);
define('DB_USER', $_ENV["DB_USER"]);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
define('DB_HOST', $_ENV['DB_HOST']); // Probably 'localhost'

// ========================
// Custom Content Directory
// ========================
define('WP_CONTENT_DIR', dirname(__FILE__) . $_ENV['WP_CONTENT_DIR']);
define('WP_CONTENT_URL', $_ENV['WP_CONTENT_URL']);

// ================================================
// You almost certainly do not want to change these
// ================================================
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
$table_prefix  = $_ENV['TABLE_PREFIX'];

// ================================
// Language
// Leave blank for American English
// ================================
define('WPLANG', '');

//define('UPLOADS', $_ENV['WP_CONTENT_REL_WP'].$_ENV['UPLOADS']);

// =================================================================
// Debug mode
// Debugging? Enable these. Can also enable them in local-config.php
// =================================================================
$debug = (bool) $_ENV['DEBUG'];
$display_errors = ((bool) $debug === true) ? 1 : 0 ;

define('SAVEQUERIES', $debug);
define('WP_DEBUG', $debug);
ini_set('display_errors', $display_errors);
define('WP_DEBUG_DISPLAY', $debug);
define('WP_DEBUG_LOG', $debug);

// ======================================
// Load a Memcached config if we have one
// ======================================
if (file_exists(dirname(__FILE__) . '/memcached.php')) {
    $memcached_servers = include(dirname(__FILE__) . '/memcached.php');
}

// ===========================================================================================
// This can be used to programatically set the stage when deploying (e.g. production, staging)
// ===========================================================================================
// define( 'WP_STAGE', '%%WP_STAGE%%' );
// define( 'STAGING_DOMAIN', '%%WP_STAGING_DOMAIN%%' ); // Does magic in WP Stack to handle staging domain rewriting

define('AUTH_KEY', $_ENV['AUTH_KEY']);
define('SECURE_AUTH_KEY', $_ENV['SECURE_AUTH_KEY']);
define('LOGGED_IN_KEY', $_ENV['LOGGED_IN_KEY']);
define('NONCE_KEY', $_ENV['NONCE_KEY']);
define('AUTH_SALT', $_ENV['AUTH_SALT']);
define('SECURE_AUTH_SALT', $_ENV['SECURE_AUTH_SALT']);
define('LOGGED_IN_SALT', $_ENV['LOGGED_IN_SALT']);
define('NONCE_SALT', $_ENV['NONCE_SALT']);

// ===================
// Bootstrap WordPress
// ===================
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/controlcentre/');
}
require_once(ABSPATH . 'wp-settings.php');

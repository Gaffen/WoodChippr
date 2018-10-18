<?php
require_once WPMU_PLUGIN_DIR.'/force-regenerate-thumbnails/force-regenerate-thumbnails.php';
require_once WPMU_PLUGIN_DIR.'/timber-library/timber.php';
require_once WPMU_PLUGIN_DIR.'/wordpress-seo/wp-seo.php';
require_once WPMU_PLUGIN_DIR.'/site-functionality/index.php';

if (isset($_ENV['ENABLE_ACF']) && ((bool) $_ENV['ENABLE_ACF'] === true)) {
    require_once WPMU_PLUGIN_DIR.'/advanced-custom-fields-pro/acf.php';
}

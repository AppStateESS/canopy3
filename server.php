<?php

/**
 *
 *
 * Loads sets root directory and autoloader.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
define('C3_DIR', __DIR__ . '/');
define('C3_THEMES_DIR', C3_DIR . 'resources/themes/');
define('C3_DASHBOARDS_DIR', C3_DIR . 'resources/dashboards/');
define('C3_PLUGINS_DIR', C3_DIR . 'resources/plugins/');

/**
 * Composer autoloader
 */
require_once C3_DIR . 'vendor/autoload.php';

/**
 * Canopy3 and resource autoloader
 */
require_once C3_DIR . 'src/AutoLoader.php';

$resourceConfig = C3_DIR . 'config/resourcesUrl.php';
if (is_file($resourceConfig)) {
    require_once $resourceConfig;
}
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

/**
 * Composer autoloader
 */
require_once C3_DIR . 'vendor/autoload.php';

/**
 * Canopy3 autoloader
 */
require_once C3_DIR . 'src/AutoLoader.php';

/**
 * Define the C3_URI variable
 */
$urlConfig = C3_DIR . 'config/url.php';
if (is_file($urlConfig)) {
    require_once $urlConfig;
} else {
    throw new \Canopy3\Exception\FileNotFound($urlConfig);
}


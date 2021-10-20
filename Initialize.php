<?php

/**
 * Loads sets root directory and autoloader.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Initialize;

function autoloader()
{
    // Composer autoloader
    require_once C3_DIR . 'vendor/autoload.php';

    // Canopy3 and resource autoloader
    require_once C3_DIR . 'src/AutoLoader.php';
    spl_autoload_register(fn($namespaceString) => \Canopy3\AutoLoader::run($namespaceString));

    require_once C3_DIR . 'src/GlobalFunctions.php';

    \Canopy3\requireConfigFile(C3_DIR . 'config/system');

    $resourceConfig = C3_DIR . 'config/resourcesUrl.php';
    if (is_file($resourceConfig)) {
        require_once $resourceConfig;
    }
}

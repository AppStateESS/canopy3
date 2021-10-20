<?php

/**
 * Prepares the Router to run the setup process.
 * 
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

use Canopy3\HTTP\Server;
use Canopy3\Setup\Prepare;

if (!Prepare::resourceUrlReady() || !Prepare::databaseReady()) {
    Prepare::createTemporaryResourceUrls();
    $router = Router::singleton();
    if (Server::getRequestUriOnly() === false) {
        Prepare::buildRouter($router);
    }
}
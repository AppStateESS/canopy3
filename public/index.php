<?php

/**
 * Public facing file for access. All admin access comes through this file.
 * All site queries include this file.
 * If the application has not been setup, it will be noticed and the router
 * will be directed to run the install.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
require_once '../DirectoryDefines.php';
require_once C3_DIR . 'src/GlobalFunctions.php';
require_once C3_DIR . 'src/AutoLoader.php';

use Canopy3\Router;
use Canopy3\Autoloader;
use Canopy3\HTTP\Response;

AutoLoader::initialize();
\Canopy3\requireConfigFile('config/system');

set_exception_handler(array('\Canopy3\ErrorHandler', 'catchError'));

$router = Router::singleton();
$response = $router->execute();
Response::execute($response);

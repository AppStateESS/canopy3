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
require_once '../server.php';
require_once '../src/Router.php';

use Canopy3\Router;
use Canopy3\Role;
use Canopy3\OutputError;
use Canopy3\HTTP\Response;

processRouter();

function processRouter()
{
    set_exception_handler(array('\Canopy3\ErrorHandler', 'catchError'));
    try {
        $router = Router::singleton();
        // Determines if setup is required
        if (defined('C3_TEST_SETUP') && C3_TEST_SETUP) {
            require C3_DIR . 'src/PrepareSetup.php';
        }
        $response = $router->execute();
    } catch (\Canopy3\Exception\CodedException $e) {
        $response = OutputError::codedException($e);
    } catch (\Throwable $e) {
        $response = OutputError::throwable($e);
    }
    Response::execute($response);
}

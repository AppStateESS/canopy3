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
use Canopy3\Theme;
use Canopy3\Role;
use Canopy3\ErrorPage;

try {
    $theme = Theme::singleton();
    $router = Router::singleton();

    $dbConfigFound = is_file(C3_DIR . 'config/db.php');

    if (!defined('C3_RESOURCES_URL') || !$dbConfigFound) {
        $controller = new \Dashboard\Setup\Controller\Setup;
        $router->setController($controller);
    } else {
        require_once C3_DIR . 'config/db.php';
        // determine router action from url
    }
    $router->execute();
} catch (\Canopy3\Exception\CodedException $e) {
    echo ErrorPage::codedView($e);
} catch (\Exception $e) {
    echo ErrorPage::view($e);
}
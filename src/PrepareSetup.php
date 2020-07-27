<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

if (!PrepareSetup::resourceUrlReady() || !PrepareSetup::databaseReady()) {
    PrepareSetup::createTemporaryResourceUrls();
    $router = \Canopy3\Router::singleton();
    if (HTTP\Server::getRequestUriOnly() === false) {
        PrepareSetup::buildRouter($router);
    }
}

class PrepareSetup
{

    /**
     * Determine is Canopy3 requires installation.
     * @return bool
     */
    public static function resourceUrlReady(): bool
    {
        return defined('C3_RESOURCES_URL');
    }

    public static function databaseReady(): bool
    {
        return is_file(C3_DIR . 'config/db.php');
    }

    public static function buildRouter($router)
    {
        $controller = new \Dashboard\Setup\Controller\Setup;

        $router->setControllerName('Setup');
        $router->setController($controller);
        $router->setCommand('view');
    }

    public static function createTemporaryResourceUrls()
    {
        if (defined('C3_RESOURCES_URL')) {
            return;
        }
        $url = preg_replace('@public/$@', '',
                \Canopy3\HTTP\Server::getCurrentUri());
        define('C3_RESOURCES_URL', $url . 'resources/');
        define('C3_DASHBOARDS_URL', C3_RESOURCES_URL . 'dashboards/');
        define('C3_PLUGINS_URL', C3_RESOURCES_URL . 'plugins/');
        define('C3_THEMES_URL', C3_RESOURCES_URL . 'themes/');
    }

}

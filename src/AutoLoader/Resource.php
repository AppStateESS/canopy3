<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
require_once C3_DIR . 'src/SystemFactory.php';

function DashboardLoader(string $filename, string $classDirectory)
{
    $dashboard = stristr($classDirectory, '/', true);
    $dashboardDirectory = \Canopy3\SystemFactory::getDashboardDirectoryByNamespace($dashboard) ?? $dashboard;
    $className = stristr($classDirectory, '/');

    return C3_DASHBOARDS_DIR . "$dashboardDirectory/src{$className}/{$filename}.php";
}

function PluginLoader(string $filename, string $classDirectory)
{
    $plugin = stristr($classDirectory, '/', true);
    $pluginDirectory = \Canopy3\SystemFactory::getDashboardDirectoryByNamespace($plugin) ?? $plugin;
    $className = stristr($classDirectory, '/');

    return C3_DASHBOARDS_DIR . "$pluginDirectory/src{$className}/{$filename}.php";
}

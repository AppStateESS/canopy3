<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
function DashboardLoader(string $filename, string $classDirectory)
{
    $dashboard = stristr($classDirectory, '/', true);
    if ($dashboard) {
        $dashboardDirectory = \Canopy3\Dashboard::singleton()->getDirectory($dashboard);
        $className = stristr($classDirectory, '/');
        return C3_DASHBOARDS_DIR . "$dashboardDirectory/src{$className}/{$filename}.php";
    } else {
        return C3_DASHBOARDS_DIR . "$classDirectory/src/{$filename}.php";
    }
}

function PluginLoader(string $filename, string $classDirectory)
{
    $plugin = stristr($classDirectory, '/', true);
    if ($plugin) {
        $pluginDirectory = \Canopy3\Plugin::singleton()->getDirectory($plugin);
        $className = stristr($classDirectory, '/');
        return C3_DASHBOARDS_DIR . "$pluginDirectory/src{$className}/{$filename}.php";
    } else {
        return C3_DASHBOARDS_DIR . "$classDirectory/src/{$filename}.php";
    }
}

<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
function DashboardLoader(string $filename, string $directory)
{
    $dashboard = stristr($directory, '/', true);
    if ($dashboard) {
        $className = stristr($directory, '/');
        return C3_DASHBOARDS_DIR . "$dashboard/src{$className}/{$filename}.php";
    } else {
        return C3_DASHBOARDS_DIR . "$directory/src/{$filename}.php";
    }
}

function PluginLoader(string $filename, string $directory)
{
    $plugin = stristr($directory, '/', true);
    if ($plugin) {
        $className = stristr($directory, '/');
        return C3_PLUGINS_DIR . "$plugin/src{$className}/{$filename}.php";
    } else {
        return C3_PLUGINS_DIR . "$directory/src/{$filename}.php";
    }
}

<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
function DashboardLoader(string $fileName, string $directory)
{
    require_once C3_DIR . 'resources/dashboards/src/' . $directory . '/' . $fileName . '.php';
}

function PluginLoader(string $fileName, string $directory)
{
    require_once C3_DIR . 'resources/plugins/src/' . $directory . '/' . $fileName . '.php';
}

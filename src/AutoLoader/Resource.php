<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
function DashboardLoader(string $fileName, string $directory)
{
    require_once C3_DIR . 'resources/dashboards/' . $directory . '/src/' . $fileName . '.php';
}

function PluginLoader(string $fileName, string $directory)
{
    require_once C3_DIR . 'resources/plugins/' . $directory . '/src/' . $fileName . '.php';
}

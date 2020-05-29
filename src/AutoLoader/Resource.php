<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
function DashboardLoader(string $filename, string $directory)
{
    require_once C3_DASHBOARDS_DIR . $directory . '/src/' . $filename . '.php';
}

function PluginLoader(string $filename, string $directory)
{
    require_once C3_PLUGINS_DIR . $directory . '/src/' . $filename . '.php';
}

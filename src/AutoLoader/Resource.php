<?php

/**
 * MIT License
 * Copyright (c) 2020 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
function DashboardLoader(string $fileName, string $directory)
{
    require_once C3_ROOT . 'resources/dashboards/src/' . $directory . '/' . $fileName . '.php';
}

function PluginLoader(string $fileName, string $directory)
{
    require_once C3_ROOT . 'resources/plugins/src/' . $directory . '/' . $fileName . '.php';
}

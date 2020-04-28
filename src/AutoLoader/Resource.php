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
function PluginLoader($fileName, $directory)
{
    require_once C3_ROOT . 'resources/plugins/src' . $directory . $fileName . '.php';
}

function ThemeLoader($fileName, $directory)
{
    require_once C3_ROOT . 'resources/themes/src' . $directory . $fileName . '.php';
}

function DashboardLoader($fileName, $directory)
{
    require_once C3_ROOT . 'resources/dashboards/src' . $directory . $fileName . '.php';
}

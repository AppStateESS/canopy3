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
function Canopy3Loader($fileName, $directory)
{
    $path = C3_ROOT . 'src/' . $directory . '/' . $fileName . '.php';
    if (!is_file($path)) {
        throw new \Exception("File not found for class [$fileName].");
    }
    require_once $path;
}

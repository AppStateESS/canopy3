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
spl_autoload_register(function($namespaceString) {
    $filePath = getRequireDirectory($namespaceString);
    if (is_file($filePath)) {
        require_once $filePath;
    } else {
        trigger_error('File not found:' . $filePath);
    }
});

/**
 * Returns a directory string based on the namespaceString
 * @param string $namespaceString
 * @return string
 */
function getRequireDirectory(string $namespaceString)
{
    $namespaceArray = explode('\\', $namespaceString);
    $fileName = array_pop($namespaceArray);
    $library = array_shift($namespaceArray);
    $directory = empty($namespaceArray) ? '/' : implode('/', $namespaceArray);
    if ($library == 'Canopy3') {
        $requireDirectory = C3_ROOT . 'src' . $directory . $fileName . '.php';
    } else {
        $requireDirectory = C3_ROOT . 'plugins/' . $library . '/src' . $directory . $fileName . '.php';
    }
    return $requireDirectory;
}

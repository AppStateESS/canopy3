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
    AutoLoader::run($namespaceString);
});

class AutoLoader
{

    public static function run($namespaceString)
    {
        $namespaceArray = explode('\\', $namespaceString);
        $libraryName = array_shift($namespaceArray); // Plugin
        $fileName = array_pop($namespaceArray); // Admin
        $directory = empty($namespaceArray) ? '/' : implode('/', $namespaceArray); // Blog/Controller
        $libraries = self::getLibraries();
        if (!isset($libraries[$libraryName])) {
            return;
        }
        $params = $libraries[$libraryName];
        $file = self::getFile($libraryName, $params);
        $call = self::getCall($libraryName, $params);
        require_once C3_ROOT . "src/AutoLoader/$file";
        $call($fileName, $directory);
    }

    private static function getCall($library, $params)
    {
        return $params['call'] ?? $library . 'Loader';
    }

    private static function getFile($libraryName, $params)
    {
        return $params['file'] ?? $libraryName . '.php';
    }

    private static function getLibraries()
    {
        include C3_ROOT . 'src/AutoLoader/Libraries.php';
        return $autoloadLibraries;
    }

}

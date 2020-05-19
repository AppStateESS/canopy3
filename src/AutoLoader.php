<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
spl_autoload_register(fn($namespaceString) => AutoLoader::run($namespaceString));

class AutoLoader
{

    public static function run($namespaceString)
    {
        $namespaceArray = explode('\\', $namespaceString);
        $libraryName = array_shift($namespaceArray);
        $fileName = array_pop($namespaceArray);
        $directory = empty($namespaceArray) ? '/' : implode('/', $namespaceArray);
        $libraries = self::getLibraries();
        if (!isset($libraries[$libraryName])) {
            return;
        }
        $params = $libraries[$libraryName];
        $file = self::getFile($libraryName, $params);
        $call = self::getCall($libraryName, $params);

        require_once C3_DIR . "src/AutoLoader/$file";
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
        include C3_DIR . 'src/AutoLoader/Libraries.php';
        return $autoloadLibraries;
    }

}

<?php

/**
 * Autoloader for Canopy and resource classes. The alternatives to Canopy
 * are defined in the Libraries file.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

class AutoLoader
{

    public static function initialize()
    {
        // Composer autoloader
        require_once C3_DIR . 'vendor/autoload.php';

        // Canopy3 and resource autoloader
        spl_autoload_register(fn($namespaceString) => self::run($namespaceString));
    }

    /**
     * Receives a namespace string from the autoload register and determines if
     * it is a Canopy class or a resource class.
     *
     * Depending on the type, the file path is returned and an attempted
     * require_once is performed on it.
     *
     * @param string $namespaceString
     * @return void
     * @throws \Canopy3\Exception\AutoLoadFailure
     */
    public static function run(string $namespaceString)
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
        $classFile = $call($fileName, $directory);
        if (!is_file($classFile)) {
            return;
        }
        require_once $classFile;
    }

    /**
     * Returns the name of the function specified by 'call' key in the
     * $params array. The string will always be {$libraryName}Loader
     *
     * @param string $library
     * @param array $params
     * @return string
     */
    private static function getCall(string $libraryName, array $params)
    {
        return $params['call'] ?? $libraryName . 'Loader';
    }

    /**
     * Returns the name of the file specified by the 'file' key in the
     * $params array. The string will be {$libraryName}.php.
     *
     * @param string $libraryName
     * @param array $params
     * @return string
     */
    private static function getFile(string $libraryName, array $params)
    {
        return $params['file'] ?? $libraryName . '.php';
    }

    /**
     * Returns the array of autoload libraries in the Libraries file. It assumes
     * there is an autoloadLibraries variable in this file.
     * @return array
     */
    private static function getLibraries()
    {
        include C3_DIR . 'src/AutoLoader/Libraries.php';
        return $autoloadLibraries;
    }

}

<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

use Canopy3\Template\ContentStack;
use Canopy3\Exception\ExpectedType;

if (!defined('C3_TEMPLATE_EMPTY_WARNING_DEFAULT')) {
    define('C3_TEMPLATE_EMPTY_WARNING_DEFAULT', false);
}

class Template
{

    private string $path;
    private array $registeredFunctions = [];

    public function __construct(string $path = null)
    {
        if (!is_null($path)) {
            $slash = preg_match('@/$@', $path) ? null : '/';
            $this->path = $path . $slash;
        }
    }

    /**
     * Returns an assumed template directory based on the name of the dashboard
     * @param string $library
     * @return string
     */
    public static function dashboardDirectory(string $library): string
    {
        return C3_DIR . "resources/dashboards/$library/templates/";
    }

    public function getPath()
    {
        return $this->path;
    }

    public static function htmlSuffix($fileName)
    {
        return preg_match('@\.(html|txt)$@', $fileName) ? $fileName : $fileName . '.html';
    }

    public function isRegistered(string $functionName)
    {
        return isset($this->registeredFunctions[$functionName]);
    }

    /**
     * Returns an assumed template directory based on the name of the plugin
     * @param string $library
     * @return string
     */
    public static function pluginDirectory(string $library): string
    {
        return C3_DIR . "resources/plugins/$library/templates/";
    }

    public function registerFunction(string $functionName,
            \Closure $functionCode)
    {
        $this->registeredFunctions[$functionName] = $functionCode;
    }

    public function render(string $fileName, array $values = null,
            bool $emptyWarning = C3_TEMPLATE_EMPTY_WARNING_DEFAULT): string
    {
        $fileName = self::htmlSuffix($fileName);
        $filePath = $this->path . $fileName;
        if (!is_file($filePath)) {
            throw new \Canopy3\Exception\FileNotFound($filePath);
        }
        if (is_null($values)) {
            $values = [];
        }
        $t = new ContentStack($this, $values, $emptyWarning);

        return self::captureContent($t, $filePath);
    }

    public function runRegistered(string $funcName, $value)
    {
        if (!is_callable($this->registeredFunctions[$funcName])) {
            throw new \Exception("Function [$funcName] was not registered");
        }
        return $this->registeredFunctions[$funcName]($value);
    }

    private static function captureContent(ContentStack $t, $filePath)
    {
        ob_start();
        include $filePath;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

}

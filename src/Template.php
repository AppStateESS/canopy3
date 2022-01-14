<?php

/**
 * MIT License
 * Copyright (c) 2021 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
/**
 * This class's purpose is to plug values into a PHP formatted HTML file and return the result.
 * Full instructions on its use are in the markdown file /docs/Template.md
 */

namespace Canopy3;

use Canopy3\Template\ContentStack;
use Canopy3\Exception\InvalidFileType;

if (!defined('C3_TEMPLATE_EMPTY_WARNING_DEFAULT')) {
    define('C3_TEMPLATE_EMPTY_WARNING_DEFAULT', false);
}

class Template
{

    /**
     * Path to the directory containing the template files. Does NOT include
     * in the file itself.
     * @var string
     */
    private string $path;

    /**
     * An array of functions that can be called in the template. New functions
     * are pushed on the array using the registerFunction method.
     * @var array
     */
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
        return C3_DIR . "systems/dashboards/$library/templates/";
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Checks $fileName parameter for
     * @param string $fileName
     * @return string
     */
    public static function htmlSuffix(string $fileName): string
    {
        if (!preg_match('@\.(\w+)\w$@i', $fileName)) {
            return $fileName . '.phtml';
        } else if (preg_match('@\.(phtml|txt|html)$@i', $fileName)) {
            return $fileName;
        } else {
            throw new InvalidFileType('html, txt, phtml', $fileName);
        }
    }

    public function isRegistered(string $functionName): bool
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

    /**
     * Calls a registered function by funcName using $value as the parameter.
     *
     * @param string $funcName
     * @param mixed $value
     * @return string
     * @throws \Exception
     */
    public function runRegistered(string $funcName, $value): string
    {
        if (!is_callable($this->registeredFunctions[$funcName])) {
            throw new \Exception("Function [$funcName] was not registered");
        }
        return $this->registeredFunctions[$funcName]($value);
    }

    /**
     * Receives the ContentStack object, applies it content values to the template file, outputs the content
     * to the captured buffer, and returns the result.
     * @param ContentStack $t
     * @param type $filePath
     * @return type
     */
    private static function captureContent(ContentStack $t, $filePath): string
    {
        ob_start();
        include $filePath;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

}

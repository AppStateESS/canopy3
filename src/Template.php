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

namespace Canopy3;

use Canopy3\Template\ContentStack;
use Canopy3\Exception\ExpectedType;

class Template
{

    private $path;
    private $values = [];
    private $registeredFunctions;

    public function __construct(string $path = null)
    {
        if (!is_null($path)) {
            $slash = preg_match('@/$@', $path) ? null : '/';
            $this->path = C3_ROOT . $path . $slash;
        }
    }

    public function render(string $fileName, array $values,
            $emptyWarning = false)
    {
        $fileName = self::htmlSuffix($fileName);
        $filePath = $this->path . $fileName;
        if (!is_file($filePath)) {
            throw new \Canopy3\Exception\FileNotFound($filePath);
        }

        $t = new ContentStack($this, $values, $emptyWarning);

        return self::captureContent($t, $filePath);
    }

    public function registerFunction(string $functionName,
            \Closure $functionCode)
    {
        $this->registeredFunctions[$functionName] = $functionCode;
    }

    public static function htmlSuffix($fileName)
    {
        return preg_match('@\.html@', $fileName) ? $fileName : $fileName . '.html';
    }

    public function runRegistered(string $funcName, $value)
    {
        if (!is_callable($this->registeredFunctions[$funcName])) {
            throw new \Exception("Function [$funcName] was not registered");
        }
        return $this->registeredFunctions[$funcName]($value);
    }

    public function isRegistered(string $functionName)
    {
        return isset($this->registeredFunctions[$functionName]);
    }

    private static function captureContent(ContentStack $t, $filePath)
    {
        ob_start();
        include $filePath;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    public function getPath()
    {
        return $this->path;
    }

}

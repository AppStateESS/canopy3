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

use Canopy3\Template\ArrayValueTemplate;
use Canopy3\Template\ObjectValueTemplate;
use Canopy3\Exception\ExpectedType;

class Template
{

    private $path;
    private $values = [];
    private $templateFile;

    public function __construct(string $path = null)
    {
        if (!is_null($path)) {
            $slash = preg_match('@/$@', $path) ? null : '/';
            $this->path = C3_ROOT . $path . $slash;
        }
    }

    public function render(string $fileName, $values, $emptyWarning = false)
    {
        $fileName = preg_match('@\.html@', $fileName) ? $fileName : $fileName . '.html';
        $filePath = $this->path . $fileName;
        if (!is_file($filePath)) {
            throw new \Canopy3\Exception\FileNotFound($filePath);
        }
        if (is_array($values)) {
            $t = new ArrayValueTemplate($values, $emptyWarning);
        } elseif (is_object($values)) {
            $t = new ObjectValueTemplate($values, $emptyWarning);
        } else {
            throw new ExpectedType('object/array', gettype($values));
        }

        ob_start();
        include $filePath;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

}

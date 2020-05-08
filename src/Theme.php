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

require_once C3_ROOT . 'config/theme.php';

if (!is_defined('C3_DEFAULT_THEME')) {
    define('C3_DEFAULT_THEME', 'Simple');
}

class Theme
{

    private $footer;
    private $header;
    private $sections;
    private $themeName;
    private $structure;

    public function __construct(string $themeName = C3_DEFAULT_THEME)
    {
        $this->themeName = trim($themeName);
        $this->load();
    }

    private function load()
    {
        $filePath = C3_ROOT . 'themes/' . $this->themeName . '/structure.json';
        if (!is_file($filePath)) {
            throw new \Exception("Theme {$this->themeName} structure file not found");
        }
        $structure = file_get_contents($filePath);
        $this->structure = json_decode($structure);
    }

    public function frontPage()
    {

    }

    public function internalPage()
    {

    }

    public function basicPage()
    {

    }

    private function loadSections()
    {

    }

}

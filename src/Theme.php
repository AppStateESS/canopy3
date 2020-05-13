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

use Canopy3\Exception\FileNotFound;
use Canopy3\Theme\Structure;
use Canopy3\HTTP\Header;
use Canopy3\HTTP\Footer;

require_once C3_ROOT . 'config/theme.php';


if (!defined('C3_DEFAULT_THEME')) {
    define('C3_DEFAULT_THEME', 'canopy3-theme-simple');
}

class Theme
{

    static \Canopy3\Theme $singleton;
    //private array $sections; ??
    public string $themeName;

    /**
     *
     * @var Canopy3\Theme\Structure
     */
    private Structure $structure;

//    private $frontPageTemplate;
//    private $internalTemplate;
//    private $errorTemplate;


    private function __construct(string $themeName = C3_DEFAULT_THEME,
            string $page = null)
    {
        $this->themeName = trim($themeName);
        $this->loadStructure($page);
    }

    public static function singleton()
    {
        if (empty(self::$singleton)) {
            self::$singleton = new self;
        }
        return self::$singleton;
    }

    private function loadStructure(string $page = null)
    {
        $this->structure = new Structure;
        $filePath = C3_ROOT . 'resources/themes/' . $this->themeName . '/structure.json';
        if (!is_file($filePath)) {
            throw new FileNotFound($filePath);
        }
        $structureJson = file_get_contents($filePath);
        $structureObj = json_decode($structureJson);

        $this->structure->setTitle($structureObj->title);
        $this->structure->directory = $structureObj->directory;
        $this->structure->setDescription($structureObj->description);
        $this->structure->setScreenshot($structureObj->screenshot);
        $this->structure->setPages($structureObj->pages);
        $this->structure->setDefaultPage($structureObj->defaultPage);
        if ($page && $this->structure->pageExists($page)) {
            $this->structure->setCurrentPage($page);
        }
    }

    public function addContent(string $content, string $section = null)
    {
        if (empty($section)) {
            $section = 'main';
        }
        $this->sectionContent[$section][] = $content;
    }

    public function getContent()
    {
        foreach ($this->sectionContent as $sectionName => $sectionContent) {
            $sections[$sectionName] = implode("\n", $sectionContent);
        }
        return $sections;
    }

    public function view()
    {
        $template = new Template($this->getThemePagesDirectory());
        $fileName = $this->structure->currentPage->filename;
        $header = Header::singleton();
        $footer = Footer::singleton();
        $values = $this->getContent();
        $values['header'] = $header->view();
        $values['footer'] = $footer->view();

        return $template->render($fileName, $values);
    }

    public function getThemeDirectory()
    {
        return 'resources/themes/' . $this->structure->directory;
    }

    public function getThemePagesDirectory()
    {
        return $this->getThemeDirectory() . '/pages/';
    }

}

<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

use Canopy3\Exception\FileNotFound;
use Canopy3\Theme\Structure;
use Canopy3\HTTP\Header;
use Canopy3\HTTP\Footer;

require_once C3_DIR . 'config/theme.php';


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
        $filePath = C3_DIR . 'resources/themes/' . $this->themeName . '/structure.json';
        if (!is_file($filePath)) {
            throw new FileNotFound($filePath);
        }
        $structureJson = file_get_contents($filePath);
        $structureObj = json_decode($structureJson);

        $this->structure->setTitle($structureObj->title);
        $this->structure->setDirectory($this->themeName);
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

    public function includeScript(string $scriptPath)
    {
        $scriptTag = new Tag\Script(['src' => $this->getThemeUrl() . $scriptPath]);
        return (string) $scriptTag;
    }

    public function includeStyle(string $stylePath)
    {
        $styleTag = new Tag\Style(['href' => $this->getThemeUrl() . $stylePath]);
        return (string) $styleTag;
    }

    public function view()
    {
        $template = $this->buildTemplate();
        $fileName = 'pages/' . $this->structure->currentPage->filename;
        $header = Header::singleton();
        $footer = Footer::singleton();
        $values = $this->getContent();

        $values['header'] = $header->view();
        $values['footer'] = $footer->view();
        $values['pageTitle'] = $header->pageTitle;
        $values['themeUrl'] = $this->getThemeUrl();
        $values['themeDirectory'] = $this->getThemeDirectory();
        return $template->render($fileName, $values);
    }

    public function getThemeDirectory()
    {
        return C3_DIR . 'resources/themes/' . $this->structure->directory . '/';
    }

    public function getThemeUrl()
    {
        return C3_URL . 'resources/themes/' . $this->structure->directory . '/';
    }

    private function buildTemplate()
    {
        $template = new Template($this->getThemeDirectory());
        $includeScript = function($args) {
            return $this->includeScript($args[0]);
        };
        $template->registerFunction('includeScript', $includeScript);
        $includeStyle = function($args) {
            return $this->includeStyle($args[0]);
        };
        $template->registerFunction('includeStyle', $includeStyle);
        return $template;
    }

}

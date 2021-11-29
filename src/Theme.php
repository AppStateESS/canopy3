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

    public string $themeName;

    /**
     * @var Canopy3\Theme\Structure
     */
    private Structure $structure;

    public function __construct(string $themeName = C3_DEFAULT_THEME,
        string $page = null)
    {
        $this->themeName = trim($themeName);
        $this->loadStructure($page);
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

    public function getThemeDirectory()
    {
        return C3_THEMES_DIR . $this->structure->directory . '/';
    }

    public function getThemeUrl()
    {
        if (!defined('C3_THEMES_URL')) {
            throw new \Exception('C3_THEMES_URL is not set');
        }
        return C3_THEMES_URL . $this->structure->directory . '/';
    }

    public function includeScript(string $scriptPath, bool $defer = true)
    {
        $scriptTag = new Tag\Script(['src' => $this->getThemeUrl() . $scriptPath, 'defer' => $defer]);
        return (string) $scriptTag . "\n";
    }

    public function includeStyle(string $stylePath)
    {
        $styleTag = new Tag\Style(['href' => $this->getThemeUrl() . $stylePath]);
        return (string) $styleTag . "\n";
    }

    public function pageExists(string $templateName): bool
    {
        return $this->structure->pageExists($templateName);
    }

    /**
     * Sets the current page in the structure.
     * @param string $pageName
     */
    public function setCurrentPage(string $pageName)
    {
        $this->structure->setCurrentPage($pageName);
    }

    public function view()
    {
        $template = $this->buildTemplate();
        $fileName = 'pages/' . $this->structure->currentPage->filename;
        $header = Header::singleton();
        $values = $this->getContent();

        $values['header'] = $header->view();
        $values['pageTitle'] = $header->getPageTitle();
        $values['themeUrl'] = $this->getThemeUrl();
        $values['themeDirectory'] = $this->getThemeDirectory();
        return $template->render($fileName, $values);
    }

    private function buildTemplate()
    {
        $template = new Template($this->getThemeDirectory());
        $includeScript = function($args) {
            return $this->includeScript($args[0], $args[1] ?? true);
        };
        $template->registerFunction('includeScript', $includeScript);
        $includeStyle = function($args) {
            return $this->includeStyle($args[0]);
        };
        $template->registerFunction('includeStyle', $includeStyle);
        return $template;
    }

    private function loadStructure(string $page = null)
    {
        $this->structure = new Structure;
        $filePath = C3_THEMES_DIR . $this->themeName . '/structure.json';
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

}

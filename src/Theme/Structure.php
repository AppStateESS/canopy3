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

namespace Canopy3\Theme;

use Canopy3\Theme\Page;

class Structure extends \Canopy3\AbstractConstruct
{

    /**
     * Title of theme
     * @var string
     */
    private string $title;

    /**
     * Directory of theme under theme directory.
     * @var string
     */
    private string $directory;

    /**
     * Description of theme
     * @var string
     */
    private string $description;

    /**
     * Page templates.
     * @var array
     */
    private array $pages = [];

    /**
     * Path to theme screenshot image file.
     * @var string
     */
    private string $screenshot;

    /**
     * Default page template to use if not selected.
     * @var string
     */
    private string $defaultPage;
    public Page $currentPage;

    public function __construct()
    {

    }

    public function setDirectory($directory)
    {
        $this->directory = preg_match('@/?@', $directory) ? $directory : $directory . '/';
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function setTitle(string $title)
    {
        $this->title = self::filterString($title);
    }

    public function setDescription(string $description)
    {
        $this->description = self::filterHTML($description);
    }

    public function getPage($pageName)
    {
        if (!$this->pageExists($pageName)) {
            throw new \Exception("Page $pageName does not exist");
        } else {
            return $this->pages[$pageName];
        }
    }

    public function setPages(object $pages)
    {
        foreach ((array) $pages as $pageName => $page) {
            $pageObj = new Page;
            $pageObj->filename = $page->filename;
            $pageObj->altSections = $page->altSections;
            $pageObj->title = $page->title;
            $pageObj->columns = $page->columns;
            $pageObj->description = $page->description;
            $this->pages[$pageName] = $pageObj;
        }
    }

    public function setScreenshot($screenshot)
    {
        $this->screenshot = self::filterString($screenshot);
    }

    public function setDefaultPage($defaultPage)
    {
        if (empty($this->pages)) {
            throw new \Exception('Can not set default before templates');
        }
        $defaultPage = self::filterString($defaultPage);
        if (!$this->pageExists($defaultPage)) {
            throw new \Exception("Default page template '$defaultPage' is not included in template listing");
        }
        $this->defaultPage = $defaultPage;
        if (empty($this->currentPage)) {
            $this->currentPage = $this->getPage($this->defaultPage);
        }
    }

    public function pageExists(string $page)
    {
        return isset($this->pages[$page]);
    }

    public function setCurrentPage($page)
    {
        $this->currentPage = $this->pages[$page];
    }

}

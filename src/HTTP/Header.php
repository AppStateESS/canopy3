<?php

/**
 * The Head class collects meta information for the <head> tag of an html page.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP;

use Canopy3\Variable\StringVar;

class Header extends AbstractMetaData
{

    private static $header;
    private StringVar $siteTitle;
    private StringVar $pageTitle;

    public function __construct()
    {
        $this->siteTitle = new StringVar;
        $this->pageTitle = new StringVar;
    }

    public static function singleton(): object
    {
        if (empty(self::$header)) {
            self::$header = new Header;
        }
        return self::$header;
    }

    public function setSiteTitle($title)
    {
        $this->sitetitle->set($title);
    }

    public function setPageTitle($title)
    {
        $this->pageTitle->set($title);
    }

    public function getFullTitle()
    {
        $tag = new \Canopy3\Tag(null, 'title');
        if ($this->pageTitle->isEmpty()) {
            $tag->child = $this->pageTitle->get();
        } else {
            $tag->child = $this->pagetitle->get . ' - ' . $this->siteTitle->get();
        }
        return $tag->print();
    }

    public function view()
    {
        $values[] = (string) Robots::singleton();
        $values[] = $this->getFullTitle();
        return implode("\n", $values);
    }

}

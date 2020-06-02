<?php

/**
 * The Head class collects meta information for the <head> tag of an html page.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP;

use Canopy3\Variable\StringVar;
use Canopy3\Exception\InaccessibleProperty;

class Header
{

    private static $header;
    private array $scripts = [];
    private array $scriptValues = [];
    private StringVar $siteTitle;
    private StringVar $pageTitle;

    public function __construct()
    {
        $this->siteTitle = new StringVar;
        $this->pageTitle = new StringVar;
    }

    public function __get($varName)
    {
        switch ($varName) {
            case 'pageTitle':
                return $this->pageTitle->get();
        }
        throw new InaccessibleProperty(__class__, $varName);
    }

    public function addScript(string $src, ?array $attributes = null)
    {
        $allAttributes = ['src' => $src];

        if (!is_null($attributes)) {
            $allAttributes = $allAttributes + $attributes;
        }
        $script = new \Canopy3\Tag\Script($allAttributes);
        $this->scripts[] = $script;
    }

    public function addScriptValue(string $varName, $value)
    {
        $this->scriptValues[$varName] = $value;
    }

    public function getScripts()
    {
        if (empty($this->scripts)) {
            return null;
        }
        return implode("\n", $this->scripts);
    }

    public function getScriptValues()
    {
        if (empty($this->scriptValues)) {
            return null;
        }

        foreach ($this->scriptValues as $varName => $value) {
            if (is_array($value) || is_object($value)) {
                $strValue = json_encode($value);
            } elseif (is_numeric($value)) {
                $strValue = $value;
            } else {
                $strValue = "'" . str_replace("'", "\'", $value) . "'";
            }
            $values[] = "const $varName = $strValue";
        }
        $tag = new \Canopy3\Tag\Script(null, null, implode("\n", $values));
        return (string) $tag;
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
        $this->siteTitle->set($title);
    }

    public function setPageTitle($title)
    {
        $this->pageTitle->set($title);
    }

    public function getFullTitle()
    {
        $tag = new \Canopy3\Tag(null, 'title');
        if ($this->pageTitle->isEmpty()) {
            $tag->child = $this->siteTitle->get();
        } else {
            $tag->child = $this->pageTitle->get() . ' - ' . $this->siteTitle->get();
        }
        return $tag->print();
    }

    public function view()
    {
        $values[] = (string) Robots::singleton();
        $values[] = $this->getFullTitle();
        $values[] = $this->getScriptValues();
        if ($scripts = $this->getScripts()) {
            $values[] = $scripts;
        }
        return implode("\n", $values) . "\n";
    }

}

<?php

/**
 * The Head class collects meta information for the <head> tag of an html page.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP;

use Canopy3\Exception\InaccessibleProperty;
use Canopy3\VariableType\TextOnly;

class Header
{

    private static $header;
    private string $contentType = 'Content-type:text/html; charset=UTF-8';
    private int $httpResponseCode = 200;
    private array $scripts = [];
    private array $scriptValues = [];
    private string $siteTitle = '';
    private string $pageTitle = '';

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

    public function getFullTitle()
    {
        $tag = new \Canopy3\Tag(null, 'title');
        if (empty($this->pageTitle)) {
            $tag->child = $this->siteTitle;
        } else {
            $tag->child = $this->pageTitle . ' - ' . $this->siteTitle;
        }
        return $tag->print();
    }

    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    public function getSiteTitle()
    {
        return $this->siteTitle;
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

    public function setContentType(string $contentType)
    {
        $contentType = preg_replace('/^content-type:\s?/i', '', $contentType);
        switch ($contentType) {
            case 'json':
                $this->contentType = 'application/json';
                break;

            case 'html':
                $this->contentType = 'text/html; charset=UTF-8';
                break;

            case 'text':
                $this->contentType = 'text/plain; charset=UTF-8';
                break;

            case 'download':
            case 'octet-stream':
                $this->contentType = 'application/octet-stream';
                break;

            case 'pdf':
                $this->contentType = 'application/pdf';
                break;

            default:
                $this->contentType = $contentType;
        }
    }

    public function setHttpResponseCode(int $code)
    {
        $this->httpResponseCode = $code;
    }

    public function setSiteTitle($title)
    {
        $this->siteTitle = \Canopy3\VariableType\TextOnly::filter($title);
    }

    public function setPageTitle($title)
    {
        $this->pageTitle = $title;
    }

    public function sendContentType(string $contentType = null)
    {
        if ($contentType !== null) {
            $this->setContentType($contentType);
        }
        header('Content-Type:' . $this->contentType);
    }

    public function sendHttpResponseCode()
    {
        http_response_code($this->httpResponseCode);
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

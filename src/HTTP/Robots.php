<?php

/**
 *
 * Stores and outputs robot meta tags for display in a page header. As of this writing,
 * Google supports all the below and Bing ignores the majority.
 *
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP;

use Canopy3\Tag\Meta;

class Robots
{

    static $robots;

    /**
     * An array of boolean meta conditions. Only displayed if false.
     * archive - allow caching
     * follow - allow link crawling
     * imageindex - allow images to be indexed
     * index - allows indexing page content
     * snippet - allow display of page snippets
     * translate - allows page text translation
     *
     * @var array
     */
    private $boolSwitches = [];

    /**
     * none - no image snippet
     * standard - default
     * large - full size shown
     * @var string
     */
    private $maxImagePreview = 'standard';

    /**
     * -1  - no limit
     *  0  - opt out
     * [n] - character limit
     * @var int
     */
    private $maxSnippet = -1;

    /**
     * Name of metatag. Defaults to "robots" but can be set to a service
     * name.
     * @var string
     */
    private $name = 'robots';

    /**
     * a no-index directive after a certain date
     * stored in unix time
     * @var int
     */
    private $unavailableAfter = 0;

    public static function singleton()
    {
        if (!self::$robots) {
            self::$robots = new self;
        }
        return self::$robots;
    }

    private function __construct()
    {
        $this->boolswitches = [
            'archive' => true,
            'follow' => true,
            'imageindex' => true,
            'index' => true,
            'snippet' => true,
            'translate' => true
        ];
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'archive':
            case 'follow':
            case 'imageIndex':
            case 'index':
            case 'snippet':
            case 'translate':
                $this->boolSwitches[$name] = (bool) $value;
        }
    }

    public function __toString()
    {
        return $this->print();
    }

    public function print()
    {
        $tagList = [];

        foreach ($this->boolSwitches as $name => $allow) {
            if (!$allow) {
                $meta = new Meta;
                $meta->name($this->name)->content("no$name");
                $tagList[] = $meta->print();
            }
        }
        if ($this->maxImagePreview != 'standard') {
            $meta = new Meta;
            $meta->name($this->name)->content("max-image-preview: {$this->maxImagePreview}");
            $tagList[] = $meta->print();
        }

        if ($this->maxSnippet != -1) {
            $meta = new Meta;
            $meta->name($this->name)->content("max-snippet: {$this->maxSnippet}");
            $tagList[] = $meta->print();
        }

        return implode("\n", $tagList);
    }

}

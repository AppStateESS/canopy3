<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Tag;

class Style extends Link
{

    public function __construct(array $attributes = null, string $tagName = null)
    {
        parent::__construct(['rel' => 'stylesheet', 'type' => 'text/css', 'href' => $attributes['href'] ?? null]);
        $this->tagName = 'link';
    }

    public function __set($varname, $value)
    {
        if ($varname === 'href') {
            $this->setParam('href', $value);
        }
        throw new \Exception("Unknown parameter '$varname'");
    }

}

<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Tag;

class Link extends \Canopy3\Tag
{

    public function __construct(array $attributes = null, string $tagName = null)
    {
        parent::__construct(['rel' => $attributes['rel'] ?? '', 'type' => $attributes['type'] ?? null, 'href' => $attributes['href'] ?? null]);
    }

}

<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Tag;

class Link extends \Canopy3\Tag
{

    public function __construct(array $params = null, string $tagName = null)
    {
        parent::__construct(['rel' => $params['rel'] ?? '', 'type' => $params['type'] ?? null, 'href' => $params['href'] ?? null]);
    }

}

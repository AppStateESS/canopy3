<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Tag;

class Script extends \Canopy3\Tag
{

    public function __construct(array $params = null, string $tagName = null)
    {
        parent::__construct(['src' => $params['src'] ?? '', 'type' => $params['type'] ?? null]);
    }

}

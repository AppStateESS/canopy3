<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Tag;

use Canopy3\Tag;

class Meta extends Tag
{

    public function __construct(array $params = null, string $tagName = null)
    {
        parent::__construct(['name' => '', 'content' => '']);
    }

}

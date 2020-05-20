<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

class Request
{

    static Canopy3\Request $singletonObj;

    public function singleton()
    {
        if (empty(self::$singletonObj)) {
            self::$singletonObj = new self;
        }
        return self::$singletonObj;
    }

}

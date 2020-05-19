<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP;

class Footer extends AbstractMetaData
{

    private static $footer;

    public static function singleton(): object
    {
        if (empty(self::$footer)) {
            self::$footer = new self;
        }
        return self::$footer;
    }

    public function view()
    {
        return 'footer';
    }

}

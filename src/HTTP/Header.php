<?php

/**
 *
 * The Head class collects meta information for the <head> tag of an html page.
 *
 * MIT License
 * Copyright (c) 2020 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP;

class Header extends AbstractMetaData
{

    private static $header;

    public static function singleton(): object
    {
        if (empty(self::$header)) {
            self::$header = new Header;
        }
        return self::$header;
    }

    public function view()
    {
        $robots = (string) Robots::singleton();

        return 'header';
    }

}

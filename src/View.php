<?php

/**
 * MIT License
 * Copyright (c) 2020 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

class View
{

    static $theme = C3_DEFAULT_THEME;

    public static function themed(array $sections, string $themeName)
    {
        $theme = new Theme;
        $theme->sections = $sections;
        return $theme->print();
    }

    public static function html(string $content)
    {

    }

    public static function json(array $jsonArray)
    {
        return json_encode($jsonArray);
    }

    public static function error(string $message, int $code)
    {

    }

}

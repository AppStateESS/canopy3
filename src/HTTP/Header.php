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

namespace Canopy3;

class Header
{

    private static object $header;
    private array $robots;

    private function __construct()
    {
        $this->robots->allowIndex('robots');
        $this->robots->allowFollow('robots');
    }

    public static function get(): object
    {
        if (empty(self::$header)) {
            self::$header = new Head;
        }
        return self::$header;
    }

    public function addRobot($name, $content)
    {

    }

    public function setNoIndex($name = 'robots')
    {

    }

    public function getRobots()
    {
        $robotArray = [];
        foreach ($this->robots as $name => $content) {
            $robotArray[] = <<<EOF
<meta name="$name" content="$content" />
EOF;
        }
        return implode("\n", $robotArray);
    }

}

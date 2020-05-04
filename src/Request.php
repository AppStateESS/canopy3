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

class Request
{

    static $singletonObj;

    public function singleton()
    {
        if (empty(self::$singletonObj)) {
            self::$singletonObj = new self;
        }
        return self::$singletonObj;
    }

}

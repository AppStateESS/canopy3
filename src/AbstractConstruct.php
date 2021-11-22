<?php

declare(strict_types=1);
/**
 * MIT License
 * Copyright (c) 2021 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

use Canopy3\Traits\MagicGetSetTrait;

class AbstractConstruct
{

    use MagicGetSetTrait;

    public function __get($valueName)
    {
        return $this->getByMethod($valueName);
    }

    public function __set($valueName, $value)
    {
        return $this->setByMethod($valueName, $value);
    }

    public function isEmpty($valueName)
    {
        return empty($this->$valueName);
    }

}

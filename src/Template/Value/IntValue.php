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

namespace Canopy3\Template\Value;

use Canopy3\Exception\WrongVariableType;

class IntValue extends Value
{

    protected function verify($value)
    {
        return is_int($value);
    }

    public function asDate($format = "%c")
    {
        return strftime('%c', $this->value);
    }

}

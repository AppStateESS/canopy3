<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Template\Value;

use Canopy3\Exception\WrongVariableType;

class IntValue extends Value
{

    public function asDate($format = "%c")
    {
        return strftime('%c', $this->value);
    }

    protected function verify($value)
    {
        return is_int($value);
    }

}

<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Template\Value;

class BoolValue extends Value
{

    protected function verify($value)
    {
        return is_bool($value);
    }

    public function __get(string $varName)
    {
        if ($varName === 'true' || $varName === 'is') {
            return $this->value;
        } elseif ($varName === 'false' || $varName === 'not') {
            return !$this->value;
        } else {
            throw new InaccessibleProperty(__class__, $varName);
        }
    }

}

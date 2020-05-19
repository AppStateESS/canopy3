<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Template\Value;

class StringValue extends Value
{

    protected function verify($value)
    {
        return is_string($value);
    }

}

<?php

/**
 * Exception that may be used to indicate a called method does not exist in a class.
 * Normally, php will happily handle this, but you may use this in a __call function.
 * 
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class UnknownClassMethod extends \Exception
{

    public function __construct(string $functionName)
    {
        parent::__construct("Unknown class method '$functionName'");
    }

}

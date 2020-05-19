<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class WrongVariableType extends ExceptionAbstract
{

    public function __construct(string $expectedType, string $wrongType)
    {
        $message = "Expected variable type $expectType but received $wrongType";
        parent::__construct($message);
    }

}

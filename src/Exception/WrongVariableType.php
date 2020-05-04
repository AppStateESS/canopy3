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

namespace Canopy3\Exception;

class WrongVariableType extends ExceptionAbstract
{

    public function __construct(string $expectedType, string $wrongType)
    {
        $message = "Expected variable type $expectType but received $wrongType";
        parent::__construct($message);
    }

}

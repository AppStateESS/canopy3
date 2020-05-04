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

namespace Canopy3\Template;

use Canopy3\Exception\WrongVariableType;

class ArrayValueTemplate extends ValueTemplate
{

    protected $values;

    public function __construct($values, $emptyWarning)
    {
        if (!is_array($values)) {
            throw new WrongVariableType('array', gettype($values));
        }
        parent::__construct($values, $emptyWarning);
    }

    public function get(string $valueName)
    {
        return $this->values[$valueName] ?? ($this->emptyWarning ? "<!-- Template variable [$valueName] missing -->" : null);
    }

}

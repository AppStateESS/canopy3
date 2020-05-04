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

class ObjectValueTemplate extends ValueTemplate
{

    protected $values;

    public function __construct($values, $emptyWarning)
    {
        if (!is_object($values)) {
            throw new WrongVariableType('array', gettype($values));
        }
        parent::__construct($values, $emptyWarning);
    }

    public function get(string $name)
    {
        $methodName = 'get' . ucwords($name);
        return $this->values->$methodName ?? ($this->values->$name ?? ($this->emptyWarning ? "<!-- $name template variable missing -->" : null));
    }

}

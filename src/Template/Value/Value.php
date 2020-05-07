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

use Canopy3\Template\Value\StringValue;
use Canopy3\Template\Value\IntValue;
use Canopy3\Template\Value\ArrayValue;

abstract class Value
{

    protected $value;
    protected $template;

    abstract protected function verify($value);

    public function __construct($value, $template)
    {
        $this->set($value);
        $this->template = $template;
    }

    public function __toString()
    {
        return $this->value;
    }

    public function get()
    {
        return $this->value;
    }

    public function set($value)
    {
        if (!$this->verify($value)) {
            throw new \Exception('Incorrect variable type: ' . gettype($value));
        }
        $this->value = $value;
    }

    public static function assign($value, $template)
    {
        switch (gettype($value)) {
            case 'array':
                return new ArrayValue($value, $template);

            case 'integer':
                return new IntValue($value, $template);

            case 'string':
                return new StringValue($value, $template);
        }
    }

}

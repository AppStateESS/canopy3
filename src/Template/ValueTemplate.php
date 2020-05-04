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

abstract class ValueTemplate
{

    protected $values;
    protected $emptyWarning = false;

    abstract public function get(string $valueName);

    public function __construct($values, $emptyWarning)
    {
        $this->emptyWarning = $emptyWarning;
        foreach ($values as $k => $v) {
            $this->values[$k] = new Content($v);
        }
    }

    public function import(string $filePath)
    {

    }

    public function asDate(string $valueName)
    {

    }

    public function showIfElse(string $valueName, string $elseContent = null)
    {

    }

    public function echo(string $valueName)
    {
        echo $this->get($valueName);
    }

    public function __get($valueName)
    {
        return $this->get($valueName);
    }

}

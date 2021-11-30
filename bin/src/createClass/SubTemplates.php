<?php

/**
 * MIT License
 * Copyright (c) 2021 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
function propertyTpl($value)
{
    return "    private \$$value;\n";
}

function getTpl(string $value): string
{
    $funcName = 'get' . ucwords($value);
    $funcStack[] = "    public function $funcName()";
    $funcStack[] = '    {';
    $funcStack[] = '        return $this->' . $value . ';';
    $funcStack[] = '    }';
    $funcStack[] = '';

    return implode("\n", $funcStack);
}

function setTpl(string $value): string
{
    $funcName = 'set' . ucwords($value);
    $funcStack[] = '    public function ' . $funcName . '($' . $value . ')';
    $funcStack[] = '    {';
    $funcStack[] = '        $this->' . $value . ' = $' . $value . ';';
    $funcStack[] = '    }';
    $funcStack[] = '';

    return implode("\n", $funcStack);
}

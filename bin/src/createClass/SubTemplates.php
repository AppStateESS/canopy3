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
function propertyTpl($property)
{
    extract(typeAndValue($property));
    return <<<EOF
    /**
     * @var $type
     */
    private $type \$$value;

EOF;
}

function getComment($type = null)
{
    return <<<EOF
    /**
     * @returns $type
     */
EOF;
}

function setComment($param, $type = null)
{
    return <<<EOF
    /**
     * @param $type \$$param
     */
EOF;
}

function typeAndValue($property)
{
    if (strpos($property, ':') !== -1) {
        list($value, $type) = explode(':', $property);
    } else {
        $value = $property;
        $type = null;
    }
    return ['value' => $value, 'type' => $type];
}

function getTpl(string $property): string
{
    extract(typeAndValue($property));
    $funcName = 'get' . ucwords($value);
    $funcStack[0] = getComment($type);
    $funcStack[1] = "    public function $funcName()";
    if ($type) {
        $funcStack[1] .= ' : ' . $type;
    }
    $funcStack[2] = '    {';
    $funcStack[3] = '        return $this->' . $value . ';';
    $funcStack[4] = '    }';
    $funcStack[5] = '';

    return implode("\n", $funcStack);
}

function setTpl(string $property): string
{
    extract(typeAndValue($property));
    $funcName = 'set' . ucwords($value);
    $funcStack[] = setComment($value, $type);
    $funcStack[] = '    public function ' . $funcName . '(' . $type . ' $' . $value . ')';
    $funcStack[] = '    {';
    $funcStack[] = '        $this->' . $value . ' = $' . $value . ';';
    $funcStack[] = '    }';
    $funcStack[] = '';

    return implode("\n", $funcStack);
}

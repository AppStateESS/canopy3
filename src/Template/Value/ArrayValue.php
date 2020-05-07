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

use Canopy3\Template;

class ArrayValue extends Value
{

    protected function verify($value)
    {
        return is_array($value);
    }

    public function loopFunction($functionName)
    {
        foreach ($this->value as $val) {
            $stack[] = $functionName($val);
        }
        return implode("\n", $stack);
    }

    public function asTableRows(array $options)
    {
        $rowClass = null;
        $order = null;

        extract($options);
        foreach ($this->value as $key => $row) {
            $table[] = "<tr class=\"$rowClass\">";
            if (!empty($order)) {
                $table[] = $this->orderedRow($order, $row);
            } else {
                foreach ($row as $column) {
                    $table[] = "<td>$column</td>";
                }
            }
            $table[] = '</tr>';
        }
        return implode("\n", $table);
    }

    private function orderedRow($order, $row)
    {
        foreach ($order as $colName) {
            $columnStack[] = "<td>{$row[$colName]}</td>";
        }
        return implode("", $columnStack);
    }

    public function loopInclude($fileName)
    {
        foreach ($this->value as $row) {
            $contentArray[] = $this->template->render($fileName, $row);
        }
        return implode("\n", $contentArray);
    }

}

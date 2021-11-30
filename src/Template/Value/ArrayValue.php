<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Template\Value;

use Canopy3\Template;

class ArrayValue extends Value
{

    /**
     * Creates a table with the array value rows.
     * Note the table, tbody, and header tags are not in the result.
     * @param array $options
     * @return string
     */
    public function asTableRows(array $options = []): string
    {
        $rowClass = null;
        $order = null;
        $emptyWarning = false;

        extract($options);
        if (empty($this->value)) {
            return $emptyWarning ? '<!-- Empty value array -->' : '';
        }
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

    /**
     * Calls the $functionName for each array value and implodes the result.
     * @param string $functionName
     * @return string
     */
    public function loopFunction(string $functionName): string
    {
        foreach ($this->value as $val) {
            $stack[] = $functionName($val);
        }
        return implode("\n", $stack);
    }

    /**
     * Includes another template to loop through with the values.
     * @param string $fileName
     * @return string
     */
    public function loopInclude(string $fileName): string
    {
        foreach ($this->value as $row) {
            $contentArray[] = $this->template->render($fileName, $row);
        }
        return implode("\n", $contentArray);
    }

    /**
     * Uses to the implode function to output the array value's elements.
     * @param string $character
     * @return string
     */
    public function implode(string $character): string
    {
        return implode($character, $this->value);
    }

    /**
     * Verifies the value is an array.
     * @param type $value
     * @return bool
     */
    protected function verify($value): bool
    {
        return is_array($value);
    }

    private function orderedRow($order, $row): string
    {
        foreach ($order as $colName) {
            $columnStack[] = "<td>{$row[$colName]}</td>";
        }
        return implode("", $columnStack);
    }

}

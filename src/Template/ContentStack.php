<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Template;

use Canopy3\Template\Value\Value;
use Canopy3\Template;

class ContentStack
{

    protected $emptyWarning = false;
    protected $filePath;
    public $template;
    protected $values;

    public function __construct($template, array $values, $emptyWarning = false)
    {
        $this->template = $template;
        $this->emptyWarning = $emptyWarning;

        foreach ($values as $k => $v) {
            if (is_a($v, 'Canopy3\Template\Value\Value')) {
                $this->values[$k] = $v;
            } else {
                $this->values[$k] = Value::assign($v, $this->template);
            }
        }
    }

    public function __call($functionName, $value)
    {
        if ($this->template->isRegistered($functionName)) {
            return $this->template->runRegistered($functionName, $value);
        } else {
            throw new \Exception("Unknown method: $functionName");
        }
    }

    public function __get($valueName)
    {
        return $this->get($valueName);
    }

    public function __isset($valueName)
    {
        return isset($this->values[$valueName]);
    }

    public function echo(string $valueName)
    {
        echo $this->get($valueName);
    }

    private function emptyResult(string $valueName): string
    {
        return ($this->emptyWarning ? "<!-- Template variable [$valueName] is missing -->" : '');
    }

    public function get(string $valueName)
    {
        return $this->values[$valueName] ?? $this->emptyResult($valueName);
    }

    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * Returns a rendering of the $fileName using the current values. This
     * allow embedding of smaller templates within a larger template.
     *
     * @param type $fileName
     * @return string
     */
    public function include(string $fileName): string
    {
        return $this->template->render($fileName, $this->values);
    }

    /**
     * If valueName has been set, it is returned surrounded by the text in the
     * $left and $right parameters.
     *
     * @param type $valueName
     * @param type $left
     * @param type $right
     * @return string
     */
    public function wrap($valueName, $left, $right): string
    {
        if (!isset($this->values[$valueName])) {
            return $this->emptyResult($valueName);
        }
        return $left . $this->values[$valueName] . $right;
    }

}

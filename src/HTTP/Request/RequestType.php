<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP\Request;

class RequestType
{

    const stringFilter = FILTER_SANITIZE_STRING;
    const stringFilterFlags = FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_ENCODE_AMP;

    private array $values = [];
    private string $method;

    public function __construct(string $method)
    {
        $this->method = $method;
    }

    public function __get(string $valueName)
    {
        $value = $this->values[$valueName] ?? null;
        if (!is_null($value) && is_string($value)) {
            return filter_var($value, self::stringFilter,
                    self::stringFilterFlags);
        }
        return $value;
    }

    public function getValues()
    {
        if (empty($this->values)) {
            return [];
        }
        foreach ($this->values as $key => $val) {
            $filtered[$key] = $this->__get($key);
        }
        return $filtered;
    }

    public function setValues(array $values)
    {
        if (empty($values)) {
            return;
        }

        foreach ($values as $key => $value) {
            $this->values[$key] = $this->parseValue($value);
        }
    }

    public function unfiltered(string $valueName)
    {
        $value = $this->values[$valueName] ?? null;
        if ($value === null) {
            throw new \Exception("Request variable '$valueName' not set in $this->method");
        }
        return is_string($value) ? filter_var($value, FILTER_UNSAFE_RAW,
                        self::stringFilterFlags) : $value;
    }

    public function __isset(string $valueName)
    {
        return isset($this->values[$valueName]);
    }

    private function parseValue($value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $subset) {
                $newValueArray[$key] = $this->parseValue($subset);
            }
            return $newValueArray;
        } elseif (is_numeric($value)) {
            if ((string) (int) $value === $value) {
                return (int) $value;
            } else {
                return (float) $value;
            }
        } else {
            return $value;
        }
    }

}

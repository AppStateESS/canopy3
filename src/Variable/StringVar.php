<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Variable;

class StringVar
{

    private string $value;
    public array $allowedTags = [];
    public bool $allowHtml = false;
    public int $limit = 0;

    public function __construct(string $str = '')
    {
        $this->set($str);
    }

    public function __set($valName, $value)
    {
        $this->set($value);
    }

    public function __toString()
    {
        return $this->get();
    }

    public function allowHtml(bool $allow)
    {
        $this->allowHtml = $allow;
        return $this;
    }

    public function allowedTags(array $tags)
    {
        $this->allowedTags = $tags;
        return $this;
    }

    public function isEmpty()
    {
        return empty($this->value);
    }

    public static function filter(string $value)
    {
        return trim(filter_var($value, FILTER_UNSAFE_RAW,
                        FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
    }

    public function get()
    {
        if (is_null($this->value)) {
            return false;
        }
        if ($this->allowHtml) {
            $str = self::filter($this->value);
        } else {
            $str = strip_tags(self::filter($this->value), $this->allowedTags);
        }

        if ($this->limit) {
            return substr($str, 0, $this->limit);
        } else {
            return $str;
        }
    }

    public function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function set(string $str)
    {
        $this->value = $str;
    }

}

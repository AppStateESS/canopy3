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

namespace Canopy3;

class Construct
{

    public function __get($valueName)
    {

        $getMethod = 'get' . ucwords($valueName);
        if (method_exists($this, $getMethod)) {
            return $this->$getMethod();
        } else {
            $className = get_class($this);
            throw new \Exception('Cannot access protected/private property ' . $className . '::$' . $valueName);
        }
    }

    public function __set($valueName, $value)
    {
        $setMethod = 'set' . ucwords($valueName);
        if (method_exists($this, $setMethod)) {
            $this->$setMethod($value);
        } else {
            $className = get_class($this);
            throw new \Exception('Cannot access protected/private property ' . $className . '::$' . $valueName);
        }
    }

    public static function filterString(string $value)
    {
        return trim(strip_tags(filter_var($value, FILTER_SANITIZE_STRING,
                                FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH)));
    }

    public static function filterHTML($value, $allowedTags = null)
    {
        return trim(strip_tags(filter_var($value, FILTER_UNSAFE_RAW,
                                FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH),
                        $allowedTags));
    }

}

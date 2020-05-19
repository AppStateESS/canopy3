<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

abstract class AbstractConstruct
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

}

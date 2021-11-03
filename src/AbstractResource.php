<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

use Canopy3\FieldGenerator;
use Canopy3\Traits\MagicGetSetTrait;

abstract class AbstractResource
{

    use MagicGetSetTrait;

    public function __get($valueName)
    {
        return self::getByMethod($valueName);
    }

    public function __set($valueName, $value)
    {
        return self::setByMethod($valueName, $value);
    }

    public function getTableProperties(): array
    {
        return FieldGenerator::deriveFields($this);
    }

}

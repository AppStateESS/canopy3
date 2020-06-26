<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class InaccessibleProperty extends \Exception
{

    public function __construct($className, $valueName)
    {
        parent::__construct('Cannot access protected/private property ' . $className . '::$' . $valueName);
    }

}

<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class AutoLoadFailure extends \Exception
{

    public function __construct(string $nameSpaceString, string $classFilePath)
    {
        parent::__construct("Could not autoload class [$nameSpaceString] by requiring [$classFilePath]");
    }

}

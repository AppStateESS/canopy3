<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class FileNotFound extends CodedException
{

    public function __construct(string $fileName)
    {
        parent::__construct("cannot access [$fileName]", 500);
    }

}

<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class FileNotFound extends ExceptionAbstract
{

    public function __construct(string $fileName)
    {
        parent::__construct("File not found: $fileName", 10);
    }

}

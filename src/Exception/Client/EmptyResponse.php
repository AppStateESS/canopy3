<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception\Client;

class EmptyResponse extends CodedException implements ClientException
{

    public function __construct(string $controllerClassName, string $method,
        string $command)
    {
        parent::__construct("Null response received from controller call $controllerClassName::$method($command)",
            404);
    }

}

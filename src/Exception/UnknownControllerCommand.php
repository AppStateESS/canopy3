<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class UnknownControllerCommand extends CodedException
{

    /**
     *
     * @param string $controllerName
     * @param string $command
     */
    public function __construct(string $restState, string $command)
    {
        $this->code = '404';

        $this->message = "Unknown " . ucwords($restState) . " command '$command' sent to controller";
    }

}

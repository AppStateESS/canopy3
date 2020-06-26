<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class RouterReassignException extends \Exception
{

    public function __construct()
    {
        parent::__construct('You cannot change the previously set Router::resourceType or Router::library');
    }

}

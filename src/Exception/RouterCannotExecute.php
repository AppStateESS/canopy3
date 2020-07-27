<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class RouterCannotExecute extends \Exception
{

    public function __construct()
    {
        parent::__construct('Incomplete Request information has prevented the Router from executing');
    }

}

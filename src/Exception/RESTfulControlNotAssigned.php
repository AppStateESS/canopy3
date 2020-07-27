<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class RESTfulControlNotAssigned extends CodedException
{

    public function __construct(string $method, string $className)
    {
        parent::__construct("Requested RESTful method '$method' not assigned for controller $className",
                404);
    }

}

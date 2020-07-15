<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class RESTfulControlNotAssigned extends CodedException
{

    public function __construct()
    {
        parent::__construct('Requested RESTful method not assigned for this controller',
                404);
    }

}

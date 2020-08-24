<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

class ErrorHandler
{

    public static function catchError(\Throwable $error)
    {
        $response = OutputError::throwable($error);
        HTTP\Response::execute($response);
    }

}

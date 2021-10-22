<?php

/**
 * The default ErrorHandler for exceptions. The behavior depends on the exception type.
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

class ErrorHandler
{

    public static function catchError(\Throwable $error)
    {
        switch (1) {
            case is_subclass_of($error, 'Canopy3\Exception\CodedException'):
                $response = OutputError::codedException($error);
                break;

            default:
                $response = OutputError::throwable($error);
                break;
        }
        HTTP\Response::execute($response);
    }

}

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
        switch (get_class($error)) {
            case 'Canopy3\Exception\CodedException':
                $response = OutputError::codedException($error);
                break;

            default:
                $response = OutputError::throwable($error);
                break;
        }
        HTTP\Response::execute($response);
    }

}

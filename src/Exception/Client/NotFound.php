<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception\Client;

use Canopy3\Exception\CodedException;

class NotFound extends CodedException implements ClientException
{

    public function __construct(string $message = null)
    {
        if (is_null($message)) {
            $message = 'Resource not found';
        }
        parent::__construct($message, 404);
    }

}

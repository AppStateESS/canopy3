<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class NotFound extends CodedException
{

    public function __construct(string $message = null)
    {
        if (is_null($message)) {
            $message = 'Page not found';
        }
        parent::__construct($message, 404);
    }

}

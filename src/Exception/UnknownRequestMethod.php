<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class UnknownRequestMethod extends \Exception
{

    public function __construct(string $method)
    {
        parent::__construct('The passed request method "$method" is not of type GET|POST|PUT|PATCH|DELETE');
    }

}

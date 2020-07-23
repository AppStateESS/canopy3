<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP\Response;

abstract class ResponseType
{

    protected int $httpResponseCode = 200;

    public abstract function execute();

    public function setHttpResponseCode(int $code)
    {
        $this->httpResponseCode = $httpResponseCode;
    }

}

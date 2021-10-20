<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP\Response;

/**
 * A ResponseType is the final result of a process. Typically, this response
 * is a communication string but it could a forward, an error, or a download prompt.
 * The extended class requires an execute function that will be called by the router.
 */
abstract class ResponseType
{

    /**
     * The HTTP response status code sent by the header.
     * https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
     * Default on successful response is 200.
     * @var int
     */
    protected int $httpResponseCode = 200;

    public abstract function execute();

    public function setHttpResponseCode(int $httpResponseCode)
    {
        $this->httpResponseCode = $httpResponseCode;
    }

}

<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP\Response;

class Redirect extends ResponseType
{

    private string $url;

    public function __construct(string $url)
    {
        $this->url = filter_var($url, FILTER_SANITIZE_URL);
        $this->setHttpResponseCode(303);
    }

    public function execute()
    {
        \Canopy3\HTTP\Header::singleton()->sendHttpResponseCode();
        $currentUri = \Canopy3\HTTP\Server::getCurrentUri();
        header("Location: {$currentUri}$this->url");
        die();
    }

}

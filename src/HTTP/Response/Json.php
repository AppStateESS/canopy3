<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP\Response;

class Json extends ResponseType
{

    private array $values = [];

    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    public function execute()
    {
        $header = \Canopy3\HTTP\Header::singleton();
        $header->sendHttpResponseCode();
        $header->sendContentType('json');

        echo json_encode($this->values);
    }

}

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
        \Canopy3\HTTP\Header::singleton()->sendHttpResponseCode();
        echo json_encode($this->values);
    }

}

<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception\Client;

use Canopy3\Exception\Client\NotFound;

class DashboardControllerNotFound extends NotFound implements ClientException
{

    public function __construct(string $dashboardName)
    {
        parent::__construct("unknown dashboard [$dashboardName]");
    }

}

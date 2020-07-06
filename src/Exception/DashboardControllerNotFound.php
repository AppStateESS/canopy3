<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class DashboardControllerNotFound extends NotFound
{

    public function __construct(string $dashboardName)
    {
        parent::__construct("Unknown dashboard: $dashboardName");
    }

}

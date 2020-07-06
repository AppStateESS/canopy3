<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

use Canopy3\Role\Admin;
use Canopy3\Role\Deity;
use Canopy3\Role\Guest;
use Canopy3\Role\Logged;

class Role
{

    public function isAdmin()
    {
        return false;
    }

    public function isDeity()
    {
        return false;
    }

    public function isGuest()
    {
        return false;
    }

    public function isLogged()
    {
        return false;
    }

    public function getCurrent()
    {
        if (!isset($_SESSION['User'])) {
            $_SESSION['User'] = new Guest;
        }
        return $_SESSION['User'];
    }

}

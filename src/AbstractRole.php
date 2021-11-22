<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

define('ROLE_GUEST', 0);
define('ROLE_LOGGED', 1);
define('ROLE_ADMIN', 2);

class AbstractRole
{

    protected int $type = ROLE_GUEST;

    public function isAdmin()
    {
        return $this->type === ROLE_ADMIN;
    }

    public function isGuest()
    {
        return $this->type === ROLE_GUEST;
    }

    public function isLogged()
    {
        return $this->type === ROLE_LOGGED;
    }

    public function setType(int $type)
    {
        $this->type = $type;
    }

    public static function getCurrent()
    {
        if (!isset($_SESSION['User'])) {
            $_SESSION['User'] = new Guest;
        }
        return $_SESSION['User'];
    }

}

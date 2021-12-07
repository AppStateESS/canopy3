<?php

/**
 * MIT License
 * Copyright (c) 2021 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
require_once '../DirectoryDefines.php';
require_once C3_DIR . 'src/GlobalFunctions.php';
require_once C3_DIR . 'src/AutoLoader.php';

use Canopy3\Router;
use Canopy3\Autoloader;
use Canopy3\HTTP\Response;
use Canopy3\Role;
use Canopy3\OutputError;
use Doctrine\DBAL\Types\Type;

\Canopy3\AutoLoader::initialize();
\Canopy3\requireConfigFile('config/system');

set_exception_handler(array('\Canopy3\ErrorHandler', 'catchError'));
/* * *********************************** */

//$user = new Dashboard\User\Resource\User;

/**
 * site      | system     | action
 * ----------|-------------------------------
 * appstate  | johnthomas | unlockbuilding
 *
 * permit('appstate', 'johnthomas:unlockbuilding');
 * permit('appstate', 'johnthomas:unlockoffice', 346);
 * permitCurrentSite('johnthomas:unlockoffice', 346);
 *
 * permitCurrentSite('blog:editTag')
 * permitCurrentSite('blog:editArticle', 1);
 *
 */

/**
 * $role->site($site)->system($system)->action($action)->resource($id)
 * $role->sites->system->action;
 */
function permit($site, $sysPermission, $resourceId = null)
{
    $role['appstate']['johnthomas']['unlockbuilding'] = true;
    $role['appstate']['johnthomas']['unlockoffice'][346] = true;
    list($system, $permission) = explode(':', $sysPermission);
    if ($resourceId !== null) {
        return $role[$site][$system][$permission][$resourceId];
    } else {
        $result = $role[$site][$system][$permission];
        return is_bool($result) ? $result : false;
    }
}

echo permit('appstate', 'johnthomas:unlockbuilding') ? 'true' : 'false';
echo '<br>';
echo permit('appstate', 'johnthomas:unlockbuilding', 251) ? 'true' : 'false';
echo '<br>';
echo permit('appstate', 'johnthomas:unlockoffice', 346) ? 'true' : 'false';
echo '<br>';
echo permit('appstate', 'johnthomas:unlockoffice') ? 'true' : 'false';

/**
 * role : name  : Appstate Staff
 *      : sites : appstate : p
 *
 */
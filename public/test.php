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

/** ----------------------------------------------------------------------* */
//$jsonFile = C3_RESOURCES_DIR . 'dashboards/canopy3-dashboard-user/setup/user_user.json';
//$schema = Canopy3\Database::buildTableFromJson($jsonFile);
//$connection = Canopy3\Database\Connection::getMain();
//$query = $schema->toSql($connection->getDatabasePlatform());
//echo $query[0];
//exit;
//$result = $connection->executeStatement(array_pop($query));
//var_dump($result);
//exit;
//require C3_DASHBOARDS_DIR . 'canopy3-dashboard-user/src/Resource/User.php';
//$fields = \Canopy3\Database\FieldGenerator::deriveFields('Dashboard\\User\\Resource\User', false);
//var_dump($fields);

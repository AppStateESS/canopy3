<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

use Canopy3\Exception\FileNotFound;

/**
 * Looks for and requires a file requested from the $filePath. If not found
 * a default version is checked before throwing an exception.
 * The directory is expected to be under the C3_DIR
 *
 * @param string $filePath
 * @throws \FileNotFound
 */
function requireConfigFile(string $filePath)
{
    if (preg_match('/\.\./', $filePath)) {
        throw new FileNotFound($filePath);
    }

    $fullPath = preg_replace('/\.php$/', '', C3_DIR . $filePath);
    $configFile = $fullPath . '.php';
    $defaultFile = $fullPath . '.default.php';

    if (is_file($configFile)) {
        require_once($configFile);
    } elseif (is_file($defaultFile)) {
        require_once($defaultFile);
    } else {
        throw new FileNotFound($configFile);
    }
}

function getDoctrineConnection()
{
    if (!is_file(C3_CONFIG_DIR . 'db.php')) {
        throw new FileNotFound('db.php');
    }
    include C3_CONFIG_DIR . 'db.php';
    return \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
}

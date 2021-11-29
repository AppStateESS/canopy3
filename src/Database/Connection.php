<?php

declare(strict_types=1);

namespace Canopy3\Database;

/**
 * MIT License
 * Copyright (c) 2021 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
class Connection
{

    public static function getConfigFileParams(string $configFile)
    {
        if (!is_file($configFile)) {
            throw new FileNotFound($configFile);
        }
        include $configFile;
        if (!isset($connectionParams)) {
            throw new \Exception('connectionParams variable missing from configFile');
        }
        return $connectionParams;
    }

    /**
     * Returns a Doctrine connection based on the db.php file.
     * @return Doctrine\DBAL\Connection
     * @throws FileNotFound
     */
    public static function getFromConfigFile(string $configFile)
    {
        return \Doctrine\DBAL\DriverManager::getConnection(self::getConfigFileParams($configFile));
    }

    public static function getMain()
    {
        $configFile = C3_CONFIG_DIR . 'db.php';
        return self::getFromConfigFile($configFile);
    }

}

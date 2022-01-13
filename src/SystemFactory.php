<?php

declare(strict_types=1);
/**
 * MIT License
 * Copyright (c) 2021 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
/**
 * Class helps with the creation and manipulation of Systems (i.e. dashboards and plugins).
 */

namespace Canopy3;

use Composer\Installer\PackageEvent;
use Canopy3\System\Dashboard;
use Canopy3\System\Plugin;
use Canopy3\System\Theme;
use Canopy3\System\AbstractSystem;

if (!defined('C3_DASHBOARDS_DIR')) {
    require_once './DirectoryDefines.php';
}

if (!defined('C3_SYSTEMS_CONFIG_DIR')) {
    define('C3_SYSTEMS_CONFIG_DIR', C3_DIR . 'config/');
}

class SystemFactory
{

    /**
     * Reads a system.json file and builds the appropriate dashboard|plugin object.
     * @param string $systemFile
     * @return Dashboard|Plugin
     */
    public static function getSystemObjectFromFile(string $systemFile)
    {
        $content = file_get_contents($systemFile);
        if (empty($content)) {
            return;
        }
        $rawObject = json_decode($content);
        if ($rawObject->type == 'canopy3-dashboard') {
            return self::buildDashboard($rawObject);
        } elseif ($rawObject->type == 'canopy3-dashboard') {
            return self::buildPlugin($rawObject);
        } elseif ($rawObject->type == 'canopy3-theme') {
            return self::buildTheme($rawObject);
        } else {
            throw new \Exception("Unknown system type [$rawObject->type]");
        }
    }

    public static function register(AbstractSystem $system)
    {
        $jsonFilePath = self::getSystemFilePath($system);
        if (!is_file($jsonFilePath)) {
            if (!is_writable(C3_SYSTEMS_CONFIG_DIR)) {
                throw new \Exception('Systems file missing. Restricted directory prevents a new file - ' . C3_SYSTEMS_CONFIG_DIR);
            }
            if (!self::createSystemFile($system->type)) {
                throw new \Exception('Could not create system file in ' . C3_SYSTEMS_CONFIG_DIR);
            }
        }
        $filePath = self::getSystemListFilePathByType($system->type);
        $json = file_get_contents($filePath);
        $systemList = json_decode($json, false, JSON_NUMERIC_CHECK);
        if (self::isSystemInList($system, $systemList)) {
            return false;
        }
        $systemList = self::addSystemToList($system, $systemList);
        self::saveSystemListFile($systemList, $filePath);
        return true;
    }

    /**
     * Helps composer register a system to Canopy 3.
     * @param PackageEvent $event
     * @return void
     */
    public static function registerFromComposer(PackageEvent $event)
    {
        $operation = $event->getOperation();
        $package = method_exists($operation, 'getPackage') ? $operation->getPackage() : $operation->getInitialPackage();
        list(, $packageName) = explode('/', $package->getName());
        $systemFile = self::getSystemFileByPackageName($packageName);
        if (empty($systemFile)) {
            echo "Package not a Canopy3 system.";
            return;
        }
        $systemObject = self::getSystemObjectFromFile($systemFile);
        if (empty($systemObject)) {
            echo "Package has a corrupt system.json file.";
            return;
        }
        if ($systemObject->type === 'dashboard') {
            self::buildDashboard($systemObject);
        } elseif ($systemObject->type === 'plugin') {
            self::buildPlugin($systemObject);
        } elseif ($systemObject->type === 'theme') {
            self::buildTheme($systemObject);
        } else {
            echo 'Unknown system package type.';
        }
    }

    /**
     * Pushes the system on the end of the systemList array.
     * @param AbstractSystem $system
     * @param array $systemList
     * @return array
     */
    private static function addSystemToList(AbstractSystem $system, array $systemList)
    {
        $systemList[] = $system->getParameters();
        return $systemList;
    }

    /**
     * Receives a basic PHP standard object and pipes it into a Dashboard object.
     *
     * @param \stdClass $system
     * @return Dashboard
     */
    private static function buildDashboard(\stdClass $system): Dashboard
    {
        return self::plugStandard($system, new Dashboard);
    }

    /**
     * Receives a basic PHP standard object and pipes it into a Plugin object.
     *
     * @param \stdClass $system
     * @return Plugin
     */
    private static function buildPlugin(\stdClass $system): Plugin
    {
        return self::plugStandard($system, new Plugin);
    }

    /**
     * Receives a basic PHP standard object and pipes it into a Theme object.
     *
     * @param \stdClass $system
     * @return Theme
     */
    private static function buildTheme(\stdClass $system): Theme
    {
        return self::plugStandard($system, new Theme);
    }

    /**
     * Creates and empty array, system, JSON file.
     * @param string $systemType
     * @throws \Exception
     */
    private static function createSystemFile(string $systemType)
    {
        $filename = self::getSystemListFilePathByType($systemType);
        $handle = fopen($filename, 'w');
        fwrite($handle, '[]');
        if (!fclose($handle)) {
            return false;
        } else {
            return $filename;
        }
    }

    /**
     * Returns a path to systems list file based on the $systemType param.
     * Throws a standard exception if the type is unknown.
     * @param string $systemType
     * @throws \Exception
     */
    private static function getSystemListFilePathByType(string $systemType)
    {
        switch ($systemType) {
            case 'canopy3-dashboard':
                return C3_SYSTEMS_CONFIG_DIR . 'dashboards.json';
                break;

            case 'canopy3-plugin':
                return C3_SYSTEMS_CONFIG_DIR . 'plugins.json';
                break;

            case 'canopy3-theme':
                return C3_SYSTEMS_CONFIG_DIR . 'themes.json';
                break;

            default:
                throw new \Exception('Unknown system type');
        }
    }

    /**
     * Returns the system.json path depending on the system type.
     * @param string $packageName
     * @return string
     */
    private static function getSystemFileByPackageName(string $packageName)
    {
        $dashboardPath = C3_DASHBOARDS_DIR . $packageName . '/system.json';
        $pluginPath = C3_PLUGINS_DIR . $packageName . '/system.json';
        $themePath = C3_THEMES_DIR . $packageName . '/system.json';
        if (is_file($dashboardPath)) {
            return $dashboardPath;
        } elseif (is_file($pluginPath)) {
            return $pluginPath;
        } elseif (is_file($themePath)) {
            return $themePath;
        } else {
            return null;
        }
    }

    /**
     * Returns the correct systems path depending on the system type.
     * Will be
     * systems/dashboard/
     * systems/plugin/
     * systems/theme/
     * @param AbstractSystem $system
     * @return string
     * @throws type
     */
    private static function getSystemFilePath(AbstractSystem $system): string
    {
        if ($system->isDashboard()) {
            return C3_SYSTEMS_CONFIG_DIR . 'dashboards.json';
        } elseif ($system->isPlugin()) {
            return C3_SYSTEMS_CONFIG_DIR . 'plugins.json';
        } elseif ($system->isTheme()) {
            return C3_SYSTEMS_CONFIG_DIR . 'themes.json';
        } else {
            throw \Exception('Unknown system type');
        }
    }

    /**
     *
     * @param string $name
     */
    private static function getSystemFromListByName(string $name, array $systemList)
    {
        return array_search($name, array_column($systemList, 'name'));
    }

    private static function isSystemInList(AbstractSystem $system, array $systemList)
    {
        return (bool) self::getSystemFromListByName($system->name, $systemList);
    }

    /**
     * Receives a basic PHP standard object and pipes it into a System object.
     * @param \stdClass $system
     * @return AbstractSystem
     */
    private static function plugStandard(\stdClass $stdObject, AbstractSystem $system)
    {
        foreach (get_object_vars($stdObject) as $key => $value) {
            $system->$key = $value;
        }
        return $system;
    }

    /**
     * Saves a
     * @param string $jsonString
     * @param string $filePath
     * @return type
     */
    private static function saveSystemListFile(array $systemList, string $filePath)
    {
        $jsonString = json_encode($systemList);
        return file_put_contents($filePath, $jsonString);
    }

}

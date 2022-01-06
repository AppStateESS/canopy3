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

namespace Canopy3;

use Composer\Installer\PackageEvent;

if (!defined('C3_DASHBOARDS_DIR')) {
    require_once './DirectoryDefines.php';
}

class System
{

    /**
     * @param PackageEvent $event
     *
     * @return null
     */
    public static function registerFromComposer(PackageEvent $event)
    {
        $operation = $event->getOperation();
        $package =
            method_exists($operation, 'getPackage')
            ? $operation->getPackage()
            : $operation->getInitialPackage();
        list(, $packageName) = explode('/', $package->getName());
        $systemFile = self::getSystemFileByPackageName($packageName);
        if (empty($systemFile)) {
            echo "Package not a Canopy3 system.";
            return;
        }
        $systemObject = self::registerSystemByFile($systemFile);
        if (empty($systemObject)) {
            echo "Package has a corrupt system.json file.";
            return;
        }
        if ($systemObject->type === 'dashboard') {
            self::buildDashboard($systemObject);
        } elseif ($systemObject->type === 'plugin') {
            self::buildPlugin($systemObject);
        } else {
            echo 'Unknown system package type.';
        }
    }

    public static function buildDashboard($system)
    {
        $dashboard = new System\Dashboard;

    }

    public static function buildPlugin($system)
    {
    }



    /**
     * @param string $systemFile
     *
     * @return object
     */
    public static function registerSystemByFile(string $systemFile)
    {
        $content = file_get_contents($systemFile);
        if (empty($content)) {
            return;
        }
        return json_decode($content);
    }

    /**
     *
     */
    private static function getSystemFileByPackageName(string $packageName)
    {
        $dashboardPath = C3_DASHBOARDS_DIR . $packageName . '/system.json';
        $pluginPath = C3_PLUGINS_DIR . $packageName . '/system.json';
        if (is_file($dashboardPath)) {
            return $dashboardPath;
        } elseif (is_file($pluginPath)) {
            return $pluginPath;
        } else {
            return null;
        }
    }
}

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
//Assuming in directory canopy3/bin/src/
require_once '../DirectoryDefines.php';

/**
 * Returns the object found in a dashboard JSON file.
 * @param string $dashboardName Name of the dashboard path in the
 *               systems/dashboards/ directory.
 * @return
 */
function getDashboardJSON(string $dashboardName): object
{
    if (preg_match('/[^\w\-\.]/', $dashboardName)) {
        throw new \Exception('Bad characters in dashboard name.');
    }
    $filePath = C3_DASHBOARDS_DIR . $dashboardName . '/dashboard.json';
    if (!is_file($filePath)) {
        throw new \Exception("File not found: $filePath");
    }
    $json = file_get_contents($filePath);
    if (empty($json)) {
        throw new \Exception("Dashboard JSON file contained no data.");
    }
    $dashboardName = json_decode($json);
    if (!is_object($dashboardName)) {
        throw new \Exception('Dashboard JSON did not resolve to an object.');
    }
    return $dashboardName;
}

function getDashboardPath(): string
{
    return C3_DASHBOARDS_DIR . 'dashboards.json';
}

/**
 * Returns an array of current dashboards from the dashboards.json file
 * or false if the file does not exist.
 * @return boolean | array
 */
function getDashboardData()
{
    $dashboardFilePath = getDashboardPath();

    if (!is_file($dashboardFilePath)) {
        return false;
    }
    $dashboardDataJson = file_get_contents($dashboardFilePath);
    if ($dashboardDataJson === false) {
        return false;
    }
    $dashboardData = json_decode($dashboardDataJson);
    if (empty($dashboardDataJson) || !is_object($dashboardData)) {
        throw new \Exception('The dashboards.json file is corrupt or empty. Delete and try again.');
    }
    return $dashboardData;
}

/**
 * Writes the dashboard JSON file.
 * @param array $dashboardData
 * @return bool  Bytes if successful, false if not
 */
function saveDashboardData(stdClass $dashboardData): bool
{
    $dashboardDataJson = json_encode($dashboardData);
    $dashboardPath = getDashboardPath();
    return (bool) file_put_contents($dashboardPath, $dashboardDataJson);
}

/**
 * Adds a dashboard object to the list (if not already in it) and save the
 * JSON file.
 * @param stdClass $dashboardData
 * @param object $dashboard
 * @return bool True if saved, false if already in list and not saved.
 */
function addDashboardData(stdClass $dashboardData, object $dashboard, bool $forceUpdate = false): bool
{
    $namespace = $dashboard->namespace ?? $dashboard->name;
    if (isset($dashboardData->$namespace) && !$forceUpdate) {
        throw new \Exception("A dashboard using the '$namespace' namespace already exists.");
    }
    $dashboardData->$namespace = $dashboard;
    return true;
}

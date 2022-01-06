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

class Plugin
{

    private static \Canopy3\Dashboard $singleton;
    private \stdClass $data;

    public static function singleton()
    {
        self::$singleton ??= new self;
        return self::$singleton;
    }

    private function __construct()
    {
        $this->load();
    }

    public function getData()
    {
        return $this->data;
    }

    public function getDirectory(string $dashboard)
    {
        return $this->data->$dashboard->directory ?? $dashboard;
    }

    /**
     * Loads the dashboards.json file in the resource/dashboards/ directory.
     *
     * @return boolean
     */
    private function load()
    {
        $dashboardPath = C3_DASHBOARDS_DIR . 'dashboards.json';
        if (is_file($dashboardPath)) {
            $this->data = \Canopy3\JSON::getFileData($dashboardPath);
        } else {
            $this->data = new \stdClass;
        }
    }
}

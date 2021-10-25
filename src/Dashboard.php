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

    private static \Canopy3\Plugin $singleton;
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

    public function getDirectory(string $plugin)
    {
        return $this->data->$plugin->directory ?? $plugin;
    }

    /**
     * Loads the plugins.json file in the resource/plugins/ directory.
     *
     * @return boolean
     */
    private function load()
    {
        $pluginPath = C3_PLUGINS_DIR . 'plugins.json';
        if (is_file($pluginPath)) {
            $this->data = \Canopy3\JSON::getFileData($pluginPath);
        } else {
            $this->data = new \stdClass;
        }
    }

}

<?php

/**
 * MIT License
 * Copyright (c) 2020 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * Loads sets root directory and autoloader.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
define('C3_ROOT', __DIR__ . '/');

/**
 *
 */
require_once C3_ROOT . '/vendor/autoload.php';
require_once C3_ROOT . '/src/AutoLoader.php';

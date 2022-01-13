<?php

/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * Boots up the autoloader.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
require_once dirname(__DIR__, 2) . '/DirectoryDefines.php';
require_once C3_DIR . 'src/GlobalFunctions.php';
require_once C3_DIR . 'src/AutoLoader.php';

use Canopy3\Autoloader;

\Canopy3\AutoLoader::initialize();

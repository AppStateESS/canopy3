<?php

/**
 * MIT License
 * Copyright (c) 2020 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
require_once '../server.php';

if (!is_file(C3_ROOT . 'config/db.php')) {

    echo 'no config';
} else {
    echo 'dashboard';
}
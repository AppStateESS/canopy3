<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
require_once '../server.php';

$dbConfigFound = is_file(C3_DIR . 'config/db.php');

if (!defined('C3_RESOURCE_URL') || !$dbConfigFound) {
    $setup = new \Dashboard\Setup\Setup;
    $setup->view();
} else {
    require_once C3_DIR . 'config/db.php';
    require_once C3_DIR . 'config/resource.php';
    echo 'Admin dashboard is a go!';
}
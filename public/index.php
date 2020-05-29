<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
$dbConfigFound = $urlConfigFound = true;

try {
    require_once '../server.php';
} catch (\Canopy3\Exception\FileNotFound $e) {
    $urlConfigFound = false;
}
$dbConfigFound = is_file(C3_DIR . 'config/db.php');

if (!$urlConfigFound || !$dbConfigFound) {
    $setup = new \Dashboard\Setup\Setup;
    $setup->view();
} else {
    echo 'Admin dashboard is a go!';
}
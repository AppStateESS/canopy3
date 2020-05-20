<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
try {
    require_once '../server.php';
} catch (\Canopy3\Exception\FileNotFound $e) {
    // The config/url.php is missing. Create one.
    echo \Dashboard\CreateUrl\Form::view();
}

if (!is_file(C3_DIR . 'config/db.php')) {

} else {
    echo 'dashboard';
}
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
if (!is_dir('../systems/dashboards/canopy3-dashboard-setup')) {
    echo <<<EOF
    <p>You are missing the canopy3-dashbaord-setup.</p>
    <p>You will need to run <code>composer install</code></p>
EOF;
} elseif (!is_file('../systems/dashboards/dashboards.json')) {
    echo <<<EOF
<p>You are missing a <code>dashboards.json</code> file. You will need to add your setup dashboard.</p>
    <p>Go to bin/ and <br /><code>addDashboard canopy3-dashboard-setup</code></p>
EOF;
} else {
    header("Location: ../public/d/Setup/Setup/view");
    exit();
}

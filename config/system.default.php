<?php

/**
 * This configuration determines some system-wide settings. You should not make
 * changes to this file. Instead make a copy named "system.php" and save it to
 * the same directory.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
/**
 * If true, then the program is set into development mode. Normally suppressed
 * errors will print.
 *
 * Default: false
 */
define('C3_DEVELOPMENT_MODE', false);

/**
 * If true, any empty template value will show an empty template warning. You
 * can use this to debug a template file.
 */
//define('C3_TEMPLATE_EMPTY_WARNING_DEFAULT', false);

/**
 * If true, each call will test if the installation needs to be run. Once you
 * have installed Canopy3, you may comment this out or change it to false in
 * your system.php file.
 */
define('C3_TEST_SETUP', true);

<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

use Canopy3\Exception\FileNotFound;

/**
 * An echo function for terminal bash scripts.
 * @param string $message
 * @param string $color
 */
function becho(string $message, string $color = null, bool $endWithNewline = false)
{
    $code = null;
    switch ($color) {
        case 'red':
            $code = "\033[31m";
            break;
        case 'green':
            $code = "\033[32m";
            break;
        case 'yellow':
            $code = "\033[33m";
            break;
        case 'blue':
            $code = "\033[34m";
            break;
        case 'cyan':
            $code = "\033[36m";
            break;
        case 'lgray':
            $code = "\033[37m";
            break;
        case 'dgray':
            $code = "\033[90m";
            break;
        case 'lgreen':
            $code = "\033[92m";
            break;
        case 'lcyan':
            $code = "\033[96m";
            break;
    }
    if ($code !== null) {
        $close = "\033[0m";
    }
    echo "$code$message$close";
    if ($endWithNewline) {
        echo "\n";
    }
}

function endWithSlash(string $directory): string
{
    return preg_match('@/?@', $directory) ? $directory : $directory . '/';
}

/**
 * Looks for and requires a file requested from the $filePath. If not found
 * a default version is checked before throwing an exception.
 * The directory is expected to be under the C3_DIR
 *
 * @param string $filePath
 * @throws \FileNotFound
 */
function requireConfigFile(string $filePath)
{
    if (preg_match('/\.\./', $filePath)) {
        throw new FileNotFound($filePath);
    }

    $fullPath = preg_replace('/\.php$/', '', C3_DIR . $filePath);
    $configFile = $fullPath . '.php';
    $defaultFile = $fullPath . '.default.php';

    if (is_file($configFile)) {
        require_once($configFile);
    } elseif (is_file($defaultFile)) {
        require_once($defaultFile);
    } else {
        throw new FileNotFound($configFile);
    }
}

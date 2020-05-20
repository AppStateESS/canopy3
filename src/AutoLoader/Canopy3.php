<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */
function Canopy3Loader(string $fileName, string $directory)
{
    $pathStack[] = C3_DIR . 'src/';
    if ($directory !== '/') {
        $pathStack[] = $directory . '/';
    }
    $pathStack[] = $fileName . '.php';
    $path = implode('', $pathStack);

    if (!is_file($path)) {
        throw new \Exception("File [$path] not found for class [$fileName]");
    }
    require_once $path;
}

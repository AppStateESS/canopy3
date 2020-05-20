<?php

/**
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP;

class Server
{

    public static function getCurrentUrl($with_directory = true)
    {
        if (!isset($_SERVER['HTTP_HOST'])) {
            throw new \Exception('$_SERVER[HTTP_HOST] superglobal does not exist');
        }
        $address[] = '//';
        $address[] = $_SERVER['HTTP_HOST'];
        if ($with_directory) {
            $address[] = dirname($_SERVER['PHP_SELF']);
        }

        $url = preg_replace('@\\\@', '/', implode('', $address)) . '/';
        return $url;
    }

}

<?php

/**
 * Returns filtered information from the _SERVER super global and additional
 * utilities.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP;

class Server
{

    public static function getCurrentUri($with_directory = true)
    {
        $httpHost = self::getHost();
        $address[] = '//';
        $address[] = $httpHost;
        if ($with_directory) {
            $address[] = dirname($_SERVER['PHP_SELF']);
        }

        $url = preg_replace('@\\\@', '/', implode('', $address)) . '/';
        return $url;
    }

    public static function __callStatic(string $methodName, array $arguments)
    {
        $result = preg_split('/([A-Z][a-z]+)/', $methodName, -1,
                PREG_SPLIT_DELIM_CAPTURE);

        foreach ($result as $val) {
            if ($val != 'get' && $val != '') {
                $server[] = strtoupper($val);
            }
        }

        $serverVal = implode('_', $server);
        return self::getRequestVar($serverVal);
    }

    /**
     * Returns request information following the site URI.
     * @return string
     */
    public static function getRequestUriOnly()
    {
        $phpSelf = self::getPhpSelf();
        $uri = self::getRequestUri();
        $rootUri = substr($self, 0, strrpos($phpSelf, '/'));
        $requestUri = preg_replace("@^$rootUri/@", '', $uri);
        return empty($requestUri) ? false : $requestUri;
    }

    public static function getHost()
    {
        $httpHost = filter_input(INPUT_SERVER, 'HTTP_HOST',
                FILTER_VALIDATE_DOMAIN);
        if (empty($httpHost)) {
            throw new \Exception('$_SERVER[HTTP_HOST] superglobal does not exist');
        }
        return $httpHost;
    }

    private static function getRequestVar(string $varName)
    {
        switch (strtoupper($varName)) {
            case 'PHP_SELF':
            case 'REQUEST_URI':
            case 'SCRIPT_FILENAME':
                return filter_input(INPUT_SERVER, $varName, FILTER_SANITIZE_URL);

            case 'REQUEST_TIME':
                return filter_input(INPUT_SERVER, $varName, FILTER_VALIDATE_INT);

            default:
                throw new \Exception("Unknown SERVER variable: $varname");
        }
    }

}

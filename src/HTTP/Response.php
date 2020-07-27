<?php

/**
 * Handles the response returned from processes within Canopy 3.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP;

use Canopy3\HTTP\Response\Html;
use Canopy3\HTTP\Response\Json;
use Canopy3\HTTP\Response\Redirect;
use Canopy3\HTTP\Response\Themed;
use Canopy3\HTTP\Response\ThemedError;
use Canopy3\HTTP\Header;
use Canopy3\Theme;
use Canopy3\HTTP\Response\ResponseType;

class Response
{

    public static function execute(ResponseType $response)
    {
        $response->execute();
    }

    public static function html(string $content)
    {
        $html = new Html($content);
        return $html;
    }

    public static function themed(string $content)
    {
        $themed = new Themed($content);
        return $themed;
    }

    public static function themedError(string $content)
    {
        $themed = new ThemedError($content);
        return $themed;
    }

    public static function redirect(string $url)
    {
        $redirect = new Redirect($url);
        return $redirect;
    }

}

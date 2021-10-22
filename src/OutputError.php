<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

use Canopy3\HTTP\Server;
use Canopy3\HTTP\Response;
use Canopy3\HTTP\Request;
use Canopy3\Exception\CodedException;

class OutputError
{

    static \Canopy3\Template $template;

    public static function codedException(CodedException $e)
    {
        if (Request::singleton()->isAjax()) {
            return self::codedJsonView($e);
        } else {
            return self::codedHtmlView($e);
        }
    }

    private static function codedHtmlView(CodedException $e)
    {
        $values = self::getDebugValues($e);
        $code = $e->getCode();
        $template = self::getTemplate();
        HTTP\Header::singleton()->setHttpResponseCode($code);
        return Response::themedError($template->render($code, $values));
    }

    private static function codedJsonView(CodedException $e)
    {
        $values['message'] = $e->getMessage();
        $values['file'] = $e->getFile();
        $values['line'] = $e->getLine();
        $values['trace'] = $e->getTrace();
        $code = $e->getCode();
        HTTP\Header::singleton()->setHttpResponseCode($code);
        return Response::json($values);
    }

    /**
     * This is an uncaught, unexpected throwable error. The assumption is something
     * went so wrong we had a 500 server error.
     * @param \Throwable $error
     * @return type
     */
    public static function throwable(\Throwable $error)
    {
        HTTP\Header::singleton()->setHttpResponseCode(500);
        if (Request::singleton()->isAjax()) {
            $values['message'] = $error->getMessage();
            $values['file'] = $error->getFile();
            $values['line'] = $error->getLine();
            $values['trace'] = $error->getTrace();
            return Response::json($values);
        } else {
            $values = self::getDebugValues($error);
            $template = self::getTemplate();
            return Response::themedError($template->render('500', $values));
        }
    }

    private static function getDebugValues(\Throwable $e)
    {
        $values = [];
        if (Role::getCurrent()->isDeity() || Server::isDevelopmentMode()) {
            $values['reason'] = Server::isDevelopmentMode() ? 'C3_DEVELOPMENT_MODE is TRUE' :
                'Current user is deity';
            if (isset($e->xdebug_message)) {
                $values['debug'] = self::xdebug($e);
            } else {
                $values['debug'] = self::debug($e);
            }
        }
        return $values;
    }

    private static function getTemplate()
    {
        return self::$template ?? new Template(C3_DIR . 'src/ErrorPage/templates/');
    }

    private static function xdebug(\Throwable $e): string
    {
        $template = self::getTemplate();
        $values['xdebugMessage'] = $e->xdebug_message;
        return $template->render('Xdebug', $values);
    }

    private static function debug(\Throwable $e): string
    {
        $template = self::getTemplate();
        return $template->render('Debug', $values);
    }

}

<?php

/**
 * Router decides what resource should be called based on the request.
 * Two factors determine which controller to call.
 * resourceType : dashboard, plugin, or view.
 * library: the dashboard or plugin sent the request
 *
 * The execute function will load the expected controller, call its execute method,
 * and respond the response.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

use Canopy3\HTTP\Request;

class Router
{

    private static $resourceType;
    private static $library;
    private static $controller;

    /**
     * If true, the developer is allowed to change a resourceType and libraryName
     * after
     * @var boolean
     */
    private static $forceAssign = false;

    public static function setResourceType(string $resourceType)
    {
        if (is_null(self::$resourceType) || self::$forceReassign) {
            self::$resourceType = $resourceType;
        } else {
            throw new \RouterReassignException;
        }
    }

    public static function setLibrary(string $library)
    {
        if (is_null(self::$library) || self::$forceReassign) {
            self::$library = $library;
        } else {
            throw new \RouterReassignException;
        }
    }

    public static function setController(string $controller)
    {
        if (is_null(self::$controller) || self::$forceReassign) {
            self::$controller = $controller;
        } else {
            throw new \RouterReassignException;
        }
    }

//(dashboard|plugin)/Library/Controller/Command|ID[/Command]

    /**
     * Gets the current request which determines the resource controller.
     *
     */
    public static function execute()
    {
        self::parseRequest();
//        if (is_null(self::$resourceType) || is_null(self::$library) || is_null(self::$controller)) {
//            throw new \Exception('ResourceType and/or Library missing');
//        }
        self::loadController();
    }

    private static function parseRequest()
    {
        $request = Request::singleton();
        $request->shift();
    }

    private static function loadController()
    {
        switch (self::$resourceType) {
            case 'dashboard':
                //require_once C3_DASHBOARDS_DIR . self::$library . '/';

                break;

            case 'plugin':
                self::requireLibrary();
                break;

            case 'file':
                break;

            case 'page':
                break;
        }
    }

}

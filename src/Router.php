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
use Canopy3\HTTP\Server;
use Canopy3\HTTP\Response;
use Canopy3\Exception\CodedException;
use Canopy3\Exception\Client\DashboardControllerNotFound;
use Canopy3\Exception\PluginControllerNotFound;
use Canopy3\Exception\RouterCannotExecute;
use Canopy3\Exception\RouterReassignException;
use Canopy3\Exception\Client\EmptyResponse;

class Router
{

    private const allowedMethods = ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'];

    private static \Canopy3\Router $singleton;

    /**
     * Instantiation of requested controller.
     * @var object
     */
    private object $controller;

    /**
     * Name of controller to execute a function upon
     * @var string
     */
    private ?string $controllerName;

    /**
     * The full namespace of the current requested controller.
     * @var string|null
     */
    private ?string $controllerClassName;

    /**
     *
     * @var string
     */
    private ?string $command = null;

    /**
     * Name of page, file, image viewed.
     * @var string
     */
    private ?string $dataTitle;

    /**
     * If true, the developer is allowed to change a resourceType and libraryName
     * after
     * @var boolean
     */
    private bool $forceReassign = false;

    /**
     * If true, the request was an ajax call.
     * @var bool
     */
    private bool $isAjax = false;

    /**
     * Name of the library within a dashboard or plugin.
     * @var string
     */
    private ?string $library;

    /**
     * The request method
     * @var string
     */
    private string $method = 'GET';

    /**
     * An object of plugin information
     * @var stdClass
     */
    private \stdClass $pluginData;

    /**
     * Requested element id for a view, list, put, etc.
     * @var int
     */
    private int $resourceId = 0;

    /**
     * The type of resource requested. Will be a dashboard, plugin, or data.
     * @var string
     */
    private ?string $resourceType;

    /**
     * Name of site requesting resource
     * @var string
     */
    private ?string $site;

    /**
     * @return \Canopy3\Router;
     */
    public static function singleton()
    {
        self::$singleton ??= new self;
        return self::$singleton;
    }

    public function __construct()
    {
        $this->parseRequest();
    }

    /**
     * Calls the current command on the current controller and outputs the
     * response.
     */
    public function execute()
    {
        $this->loadController();
        $response = $this->controller->{$this->method}(
            $this->command,
            $this->isAjax
        );

        if (is_null($response)) {
            throw new EmptyResponse(
                    $this->controllerClassName,
                    $this->method,
                    $this->command
            );
        }
        return is_a($response, 'Canopy3\HTTP\Response\ResponseType') ? $response : Response::themed($response);
    }

    public function getValues()
    {
        return [
            'method' => $this->method,
            'resourceType' => $this->resourceType,
            'controllerName' => $this->controllerName,
            'library' => $this->library,
            'command' => $this->command,
            'isAjax' => $this->isAjax ? 'True' : 'False',
            'dataTitle' => $this->dataTitle,
            'resourceId' => $this->resourceId,
        ];
    }

    public function methodAllowed(string $method): bool
    {
        return in_array(strtoupper($method), self::allowedMethods);
    }

    public function setCommand(string $command)
    {
        $this->command = $command;
        return $this;
    }

    public function setController(\Canopy3\Controller $controller)
    {
        $this->controller = $controller;
        $this->controllerClassName = get_class($controller);
        return $this;
    }

    public function setControllerClassName(string $controllerClassName)
    {
        $this->controllerClassName = $controllerClassName;
        return $this;
    }

    public function setControllerName(string $controllerName)
    {
        //        if (!isset($this->controllerName) || $this->forceReassign) {
        $this->controllerName = $controllerName;
        //        } else {
        //            throw new \RouterReassignException;
        //        }
    }

    public function setLibrary(string $library)
    {
        if (!isset($this->library) || $this->forceReassign) {
            $this->library = $library;
        } else {
            throw new RouterReassignException;
        }
    }

    public function setResourceType(string $resourceType)
    {
        if (!isset($this->resourceType) || $this->forceReassign) {
            $this->resourceType = $resourceType;
        } else {
            throw new RouterReassignException;
        }
    }

    /**
     * Returns an array from a slashed URI. Removes trailing and double slashes.
     * @return array
     */
    private function getRequestUriArray()
    {
        $uriOnly = Server::getRequestUriOnly();
        if ($uriOnly === false) {
            return false;
        }
        $requestUri = preg_replace('/\?.*$/', '', $uriOnly);
        if ($requestUri === 'index.php') {
            return false;
        }
        $cleanedUri = str_replace(
            '//',
            '/',
            preg_replace('@/$@', '', $requestUri)
        );
        return explode('/', $cleanedUri);
    }

    /**
     * Run on execute command.
     * Creates a new controller object based on the controllerClassName value.
     * This object becomes the controller for the router.
     * @throws RouterCannotExecute
     */
    private function loadController()
    {
        if (!isset($this->controllerClassName)) {
            throw new RouterCannotExecute();
        }
        $controller = new $this->controllerClassName;
        $this->setController($controller);
    }

    /**
     * Loads the a controller based on the results of parseRequest.
     * @throws CodedException
     */
    private function loadDataControllerClassName()
    {
        switch ($this->resourceType) {
            case 'file':
                $this->controllerClassName = 'Canopy3\\DataController\\File';
                break;

            case 'image':
                $this->controllerClassName = 'Canopy3\\DataController\\Image';
                break;

            case 'page':
                $this->controllerClassName = 'Canopy3\\DataController\\Page';
                break;
        }
        //                throw new CodedException('Router resource type missing or unknown',
        //                        404);
    }

    private function loadResourceCommand(array $requestUriArray)
    {
        $method = Request::singleton()->getMethod();
        $segment = array_shift($requestUriArray);
        if ($segment === null && $method === 'GET') {
            if ($this->resourceId > 0) {
                $this->command = 'view';
            } else {
                $this->command = 'list';
            }
        } elseif (is_numeric($segment) && (int) $segment > 0 && $this->resourceId > 0) {
            $this->setResourceId($segment);
            $this->loadResourceCommand($requestUriArray);
        } else {
            $this->command = $segment;
        }
    }

    /**
     * Forms the complete controller class name from the library and controllerName parameters.
     * @throws DashboardControllerNotFound
     * @throws PluginControllerNotFound
     */
    private function loadResourceControllerClassName()
    {
        switch ($this->resourceType) {
            case 'dashboard':
                $this->controllerClassName = "\\Dashboard\\{$this->library}\\Controller\\{$this->controllerName}";
                if (!class_exists($this->controllerClassName)) {
                    throw new DashboardControllerNotFound($this->controllerClassName);
                }
                break;

            case 'plugin':
                $this->controllerClassName = "\\Plugin\\{$this->library}\\Controller\\{$this->controllerName}";
                if (!class_exists($this->controllerClassName)) {
                    throw new PluginControllerNotFound($this->controllerClassName);
                }
                break;

            default:
                throw new CodedException(
                        "Unknown resource controller $controllerName",
                        404
                );
        }
    }

    /**
     * Looks at the request URI to determine what type of controller should be
     * returned. For plugins and dashboards, the Library is the name of package
     * (the second URI section). The controller name is pulled from the third
     * URI section.
     * If not a plugin or dashboard, a data resource is assumed and the
     * appropriate data type controller set.
     *
     * @return null
     */
    private function parseRequest()
    {
        $request = Request::singleton();
        $this->isAjax = $request->isAjax();
        $this->method = $request->getMethod();
        $requestUriArray = $this->getRequestUriArray();
        if ($requestUriArray == false) {
            return;
        }
        $resourceType = strtolower(array_shift($requestUriArray));
        $this->resourceType = $resourceType == 'p' ? 'plugin' : ($resourceType == 'd' ? 'dashboard' : $resourceType);
        if (in_array($this->resourceType, ['plugin', 'dashboard'])) {
            $this->parseResourceUri($requestUriArray);
            $this->loadResourceControllerClassName();
            $this->loadResourceCommand($requestUriArray);
        } else {
            $this->loadDataControllerClassName();
            $this->dataTitle = array_shift($requestUriArray);
            $this->command = $this->dataTitle === null ? 'list' : 'view';
        }
    }

    /**
     * Attempts to read the requested library from the URI.
     * Next, the controller name is popped off the end of the URI.
     * If it doesn't exist, "Controller" is used by default.
     * @param type $requestUriArray
     * @throws CodedException
     */
    private function parseResourceUri(&$requestUriArray)
    {
        if (empty($requestUriArray)) {
            throw new CodedException('Could not load resource library', 404);
        }
        $this->setLibrary(array_shift($requestUriArray));

        if (!empty($requestUriArray)) {
            $this->setControllerName(array_shift($requestUriArray));
        } else {
            $this->setControllerName('Controller');
        }
    }

    private function setResourceId(int $resourceId)
    {
        $this->resourceId = $resourceId;
    }

}

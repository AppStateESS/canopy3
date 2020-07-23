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
use Canopy3\Exception\DashboardControllerNotFound;
use Canopy3\Exception\PluginControllerNotFound;
use Canopy3\Exception\UnknownRequestMethod;
use Canopy3\Exception\EmptyResponse;

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
    private bool $forceAssign = false;

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
    private string $method = 'get';

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
        if (isset($this->controllerName)) {
            $this->loadController();
            //$this->loadCommand();
        }
    }

//(dashboard|plugin)/Library/Controller/Command|ID[/Command]
//file/document.pdf
//image/friend.jpg
//page/name-of-page

    /**
     * Calls the current command on the current controller and outputs the
     * response.
     */
    public function execute()
    {
        $response = $this->controller->{$this->method}($this->command,
                $this->isAjax);
        if (is_null($response)) {
            throw new EmptyResponse($this->controllerClassName, $this->method,
                    $this->command);
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

    public function loadController()
    {
        $controller = new $this->controllerName;
        $this->setController($controller);
    }

    public function methodAllowed(string $method): bool
    {
        return in_array(strtoupper($method), self::allowedMethods);
    }

    public function setCommand(string $command)
    {
        $this->command = $command;
    }

    public function setController(object $controller)
    {
        $this->controller = $controller;
        $this->controllerClassName = get_class($controller);
    }

    public function setControllerClassName(string $controllerClassName)
    {
        $this->controllerClassName = $controllerClassName;
    }

    public function setControllerName(string $controllerName)
    {
        if (!isset($this->controllerName) || $this->forceReassign) {
            $this->controllerName = $controllerName;
        } else {
            throw new \RouterReassignException;
        }
    }

    public function setLibrary(string $library)
    {
        if (!isset($this->library) || $this->forceReassign) {
            $this->library = $library;
        } else {
            throw new \RouterReassignException;
        }
    }

    public function setResourceType(string $resourceType)
    {
        if (!isset($this->resourceType) || $this->forceReassign) {
            $this->resourceType = $resourceType;
        } else {
            throw new \RouterReassignException;
        }
    }

    /**
     * Returns an array from a slashed uri. Removes trailing and double slashes.
     * @return array
     */
    private function getRequestUriArray()
    {
        $requestUri = preg_replace('/\?.*$/', '', Server::getRequestUriOnly());
        if ($requestUri === false || $requestUri === 'index.php') {
            return false;
        }
        $cleanedUri = str_replace('//', '/',
                preg_replace('@/$@', '', $requestUri));
        return explode('/', $cleanedUri);
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

            default:
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
     *
     * @throws DashboardControllerNotFound
     * @throws PluginControllerNotFound
     */
    private function loadResourceControllerClassName()
    {
        switch ($this->resourceType) {
            case 'dashboard':
                $this->controllerClassName = "\\Canopy3\\dashboard\\{$this->library}\\{$this->controllerName}";
                if (!class_exists($this->controllerClassName)) {
                    throw new DashboardControllerNotFound($this->controllerName);
                }
                break;

            case 'plugin':
                $this->controllerClassName = "\\Canopy3\\plugin\\{$this->library}\{$this->controllerName}";
                if (!class_exists($this->controllerClassName)) {
                    throw new PluginControllerNotFound($this->controllerClassName);
                }
                break;

            default:
                throw new CodedException("Uknown resource controller $controllerName",
                        404);
        }
    }

    /**
     * Looks at the request uri to determine what type of controller should be
     * returned. For plugins and dashboards, the Library is the name of package
     * (the second uri section). The controller name is pulled from the third
     * uri section.
     * If not a plugin or dashboard, a data resource is assumed and the
     * appropriate data type controller set.
     *
     * @return null
     */
    private function parseRequest()
    {
        $requestUriArray = $this->getRequestUriArray();
        $this->isAjax = Request::singleton()->isAjax();
        if ($requestUriArray == false) {
            return;
        }
        $resourceType = strtolower(array_shift($requestUriArray));
        $this->resourceType = $resourceType;
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

    private function parseResourceUri($requestUriArray)
    {
        if (empty($requestUriArray)) {
            throw new CodedException('Could not load resource library', 404);
        }
        $this->library = array_shift($requestUriArray);
        if (!empty($requestUriArray)) {
            $this->controllerName = array_shift($requestUriArray);
        } else {
            $this->controllerName = 'Controller';
        }
    }

}

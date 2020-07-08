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
use Canopy3\Exception\CodedException;
use Canopy3\Exception\DashboardControllerNotFound;
use Canopy3\Exception\PluginControllerNotFound;

class Router
{

    private static \Canopy3\Router $singleton;

    /**
     * If true, the request was an ajax call.
     * @var bool
     */
    private bool $isAjax;

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
    private string $command = 'view';

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
     * Name of the library within a dashboard or plugin.
     * @var string
     */
    private ?string $library;
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
            $this->loadCommand();
        }
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

    public function setResourceType(string $resourceType)
    {
        if (!isset($this->resourceType) || $this->forceReassign) {
            $this->resourceType = $resourceType;
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

    public function setControllerName(string $controllerName)
    {
        if (is_null($this->controllerName) || $this->forceReassign) {
            $this->controllerName = $controllerName;
        } else {
            throw new \RouterReassignException;
        }
    }

    public function setControllerClassName(string $controllerClassName)
    {
        $this->controllerClassName = $controllerClassName;
    }

    public function setController(object $controller)
    {
        $this->controller = $controller;
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
        $response = $this->controller->{$this->command}();
    }

    private function loadResourceCommand(array $requestUriArray)
    {
        $segment = array_shift($requestUriArray);
        if ($segment === null) {
            $this->command = 'list';
        } elseif (is_numeric($segment)) {
            $this->setResourceId($segment);
            $command = array_shift($requestUriArray);
            if ($command === null) {
                $this->command = 'view';
            }
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

    /**
     * Returns an array from a slashed uri. Removes trailing and double slashes.
     * @return array
     */
    private function getRequestUriArray()
    {
        $requestUri = Server::getRequestUriOnly();
        if ($requestUri === false) {
            return false;
        }
        $cleanedUri = str_replace('//', '/',
                preg_replace('@/$@', '', $requestUri));
        return explode('/', $cleanedUri);
    }

    private function parseResourceUri($requestUriArray)
    {
        if (empty($requireUriArray)) {
            throw new CodedException('Could not load resource library', 404);
        }
        $this->library = array_shift($requestUriArray);
        if (!empty($requestUriArray)) {
            $this->controllerName = array_shift($requestUriArray);
        } else {
            $this->controllerName = 'Controller';
        }
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

            case 'page':
                $this->controllerClassName = 'Canopy3\\DataController\\Page';
                break;

            case 'image':
                $this->controllerClassName = 'Canopy3\\DataController\\Image';
                break;

            default:
                throw new CodedException('Router resource type missing or unknown',
                        404);
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
                $this->controllerClassName = "dashboard\\{$this->library}\{$this->controllerName}";
                if (!class_exists($this->controllerClassName)) {
                    throw new DashboardControllerNotFound($this->controllerName);
                }
                break;

            case 'plugin':
                $this->controllerClassName = "plugin\\{$this->library}\{$this->controllerName}";
                if (!class_exists($this->controllerClassName)) {
                    throw new PluginControllerNotFound($this->controllerClassName);
                }
                break;

            default:
                throw new CodedException("Uknown resource controller $controllerName",
                        404);
        }
    }

    public function loadController()
    {
        $controller = new $this->controllerName;
        $this->setController($controller);
    }

}

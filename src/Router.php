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
     *
     * @var string
     */
    private ?string $command;
    private ?string $dataTitle;

    /**
     * If true, the developer is allowed to change a resourceType and libraryName
     * after
     * @var boolean
     */
    private bool $forceAssign = false;
    private ?string $library;
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

    public function setController(string $controller)
    {
        if (is_null($this->controllerName) || $this->forceReassign) {
            $this->controllerName = $controller;
        } else {
            throw new \RouterReassignException;
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
        $requestUri = Server::getRequestUriOnly();
        if ($requestUri === false) {
            return;
        }
        $requestUriArray = explode('/', $requestUri);
        $resourceType = strtolower(array_shift($requestUriArray));
        $this->resourceType = $resourceType;
        if (in_array($this->resourceType, ['plugin', 'dashboard'])) {
            $this->library = array_shift($requestUriArray);
            $controllerName = array_shift($requestUriArray);
            $this->loadResourceControllerName($controllerName);
        } else {
            $this->loadDataControllerName();
            $this->dataTitle = array_shift($requestUriArray);
        }
    }

    /**
     * Loads the a controller based on the results of parseRequest.
     * @throws CodedException
     */
    private function loadDataControllerName()
    {
        switch ($this->resourceType) {
            case 'file':
                $this->controllerName = 'Canopy3\\DataController\\File';
                break;

            case 'page':
                $this->controllerName = 'Canopy3\\DataController\\Page';
                break;

            case 'image':
                $this->controllerName = 'Canopy3\\DataController\\Image';
                break;

            default:
                throw new CodedException('Router resource type missing or unknown',
                        404);
        }
    }

    /**
     *
     * @param string $controllerName
     * @throws DashboardControllerNotFound
     * @throws PluginControllerNotFound
     */
    private function loadResourceControllerName(string $controllerName)
    {
        if (empty($controllerName)) {
            $this->controllerName = 'Controller';
        }
        switch ($this->resourceType) {
            case 'dashboard':
                $this->controllerName = "dashboard\\{$this->library}\{$controllerName}";
                if (!class_exists($this->controllerName)) {
                    throw new DashboardControllerNotFound($controllerName);
                }
                break;

            case 'plugin':
                $this->controllerName = "plugin\\{$this->library}\{$controllerName}";
                if (!class_exists($controllerName)) {
                    throw new PluginControllerNotFound($controllerName);
                }
                break;

            default:
                throw new CodedException("Uknown resource controller $controllerName",
                        404);
        }
    }

    private function loadController()
    {
        $this->controller = new $this->controllerName;
    }

}

<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP;

class Request
{

    private array $getVars;
    private array $postVars;
    private array $patchVars;
    private array $putVars;
    private array $deleteVars;
    private string $method;
    private string $requestUri;
    static private Request $singletonObj;

    private function __construct()
    {
        Server::getCurrentUri();
        $this->loadRequestUri();
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        $this->loadData();
    }

    /**
     * Checks to see the current require was an ajax request by reading the jquery
     * header. JQUERY required for this.
     * @return boolean
     */
    public static function isAjax()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }

    /**
     * @return boolean
     */
    public function isDelete()
    {
        return $this->method == 'DELETE';
    }

    /**
     * @return boolean
     */
    public function isGet()
    {
        return $this->method == 'GET';
    }

    /**
     * @return boolean
     */
    public function isPatch()
    {
        return $this->method == 'PATCH';
    }

    /**
     * @return boolean
     */
    public function isPost()
    {
        return $this->method == 'POST';
    }

    /**
     * @return boolean
     */
    public function isPut()
    {
        return $this->method == 'PUT';
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getRequestUri()
    {
        return $this->requestUri;
    }

    public function loadData()
    {
        $data = file_get_contents('php://input');
        $dataValues = array();
        parse_str($data, $dataValues);
        $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;

        switch ($this->method) {
            case 'PATCH':
                $this->setPatchVars($dataValues);
                break;

            case 'DELETE':
                $this->setDeleteVars($dataValues);
                break;

            case 'PUT':
                $this->setPutVars($dataValues);
                break;

            case 'POST':
                if (strpos($content_type, 'multipart/form-data') !== false) {
                    $this->setPostVars($_POST);
                } else {
                    $this->setPostVars($dataValues);
                }
                break;
        }
        $this->setGetVars($_GET);
    }

    /**
     * @param array $vars
     */
    public function setDeleteVars($vars)
    {
        $this->deleteVars = $vars;
    }

    /**
     * @param array $vars
     */
    public function setGetVars(array $vars)
    {
        $this->getVars = $vars;
    }

    /**
     * @param array $vars
     */
    public function setPatchVars(array $vars)
    {
        $this->patchVars = $vars;
    }

    /**
     * @param array $post
     */
    public function setPostVars(array $vars)
    {
        $this->postVars = $vars;
    }

    /**
     * @param array $vars
     */
    public function setPutVars(array $vars)
    {
        $this->putVars = $vars;
    }

    public function shift()
    {
        var_dump($this);
    }

    public function singleton()
    {
        if (empty(self::$singletonObj)) {
            self::$singletonObj = new self;
        }
        return self::$singletonObj;
    }

    private function loadRequestUri()
    {
        $this->requestUri = Server::getRequestUri();
    }

}

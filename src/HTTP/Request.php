<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP;

use Canopy3\HTTP\Request\RequestType;

class Request
{

    private RequestType $DELETE;
    private RequestType $GET;
    private RequestType $PATCH;
    private RequestType $POST;
    private RequestType $PUT;
    private string $method;
    private bool $isAjax;
    private string $requestUri;

    private const methodTypes = ['GET', 'DELETE', 'PATCH', 'POST', 'PUT'];

    static private Request $singleton;

    private function __construct()
    {
        $this->loadRequestUri();
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        if (!$this->allowedMethod($this->method)) {
            throw new InaccessibleProperty(__CLASS__, $requestVariable);
        }
        $this->DELETE = new RequestType('DELETE');
        $this->GET = new RequestType('GET');
        $this->PATCH = new RequestType('PATCH');
        $this->POST = new RequestType('POST');
        $this->PUT = new RequestType('PUT');
        $this->loadData();
        $this->loadIsAjax();
    }

    public function __get($varName)
    {
        $requestVariable = strtoupper($varName);
        if (!$this->allowedMethod($varName)) {
            throw new InaccessibleProperty(__CLASS__, $requestVariable);
        }
        return $this->$requestVariable;
    }

    /**
     * Checks to see the current require was an ajax request by reading the jquery
     * header. JQUERY required for this.
     * @return boolean
     */
    public function isAjax()
    {
        return $this->isAjax;
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

    private function allowedMethod(string $method)
    {
        return in_array($method, self::methodTypes);
    }

    /**
     * Pulls in data from the php input and applies the values to the current method object.
     * GET always is filled.
     */
    private function loadData()
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? null;
        $formData = file_get_contents('php://input');
        parse_str($formData, $dataValues);

        switch ($this->method) {
            case 'DELETE':
            case 'PATCH':
            case 'PUT':
                $this->{$this->method}->setValues($dataValues);
                break;

            case 'POST':
                if (strpos($contentType, 'multipart/form-data') !== false) {
                    $this->{$this->method}->setValues($_POST);
                } else {
                    $this->{$this->method}->setValues($dataValues);
                }
        }
        $this->GET->setValues($_GET);
    }

    private function loadIsAjax()
    {
        $this->isAjax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') || isset($this->GET->json);
    }

    /**
     *
     * @return Request
     */
    public static function singleton()
    {
        self::$singleton ??= new self;
        return self::$singleton;
    }

    private function loadRequestUri()
    {
        $this->requestUri = Server::getRequestUri();
    }

    public function debug()
    {
        $values['GET'] = $this->templify($this->GET->getValues());
        $values['DELETE'] = $this->templify($this->DELETE->getValues());
        $values['GET'] = $this->templify($this->GET->getValues());
        $values['PATCH'] = $this->templify($this->PATCH->getValues());
        $values['POST'] = $this->templify($this->POST->getValues());
        $values['PUT'] = $this->templify($this->PUT->getValues());
        $template = new \Canopy3\Template(C3_DIR . 'src/HTTP/Request/Templates/');
        return $template->render('Debug', $values);
    }

    private function templify($values)
    {
        if (empty($values)) {
            return [];
        }
        foreach ($values as $key => $val) {
            $row[] = ['key' => $key, 'value' => $val];
        }
        return $row;
    }

}

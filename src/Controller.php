<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

use Canopy3\HTTP\Request;
use Canopy3\Exception\UnknownRequestMethod;

abstract class Controller
{

    protected \Canopy3\HTTP\Request $request;

    public function __construct()
    {
        $this->request = Request::singleton();
    }

    public function execute()
    {
        $method = strtoupper($this->request->getMethod());
        switch ($method) {
            case 'GET':
                $response = $this->get();
                break;
            case 'HEAD':
                $response = $this->head();
                break;
            case 'POST':
                $response = $this->post();
                break;
            case 'PUT':
                $response = $this->put();
                break;
            case 'DELETE':
                $response = $this->delete();
                break;
            case 'OPTIONS':
                $response = $this->options();
                break;
            case 'PATCH':
                $response = $this->patch();
                break;
            default:
                throw new UnknownRequestMethod($method);
                break;
        }
    }

    public function get()
    {

    }

    public function head()
    {

    }

    public function post()
    {

    }

    public function put()
    {

    }

    public function delete()
    {

    }

    public function options()
    {

    }

    public function patch()
    {

    }

}

<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3;

use Canopy3\HTTP\Request;
use Canopy3\Exception\RESTfulControlNotAssigned;

abstract class Controller
{

    protected \Canopy3\HTTP\Request $request;

    public function __construct()
    {
        $this->request = Request::singleton();
    }

    public function execute()
    {

    }

    public function get(string $command, bool $isAjax)
    {
        throw new RESTfulControlNotAssigned('get', get_class($this));
    }

    public function head(string $command, bool $isAjax)
    {
        throw new RESTfulControlNotAssigned('head', get_class($this));
    }

    public function post(string $command, bool $isAjax)
    {
        throw new RESTfulControlNotAssigned('post', get_class($this));
    }

    public function put(string $command, bool $isAjax)
    {
        throw new RESTfulControlNotAssigned('put', get_class($this));
    }

    public function delete(string $command, bool $isAjax)
    {
        throw new RESTfulControlNotAssigned('delete', get_class($this));
    }

    public function options(string $command, bool $isAjax)
    {
        throw new RESTfulControlNotAssigned('options', get_class($this));
    }

    public function patch(string $command, bool $isAjax)
    {
        throw new RESTfulControlNotAssigned('patch', get_class($this));
    }

}

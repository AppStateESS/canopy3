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

    public function getHtml()
    {
        throw new RESTfulControlNotAssigned('getHtml');
    }

    public function head()
    {
        throw new RESTfulControlNotAssigned('head');
    }

    public function post()
    {
        throw new RESTfulControlNotAssigned('post');
    }

    public function put()
    {
        throw new RESTfulControlNotAssigned('put');
    }

    public function delete()
    {
        throw new RESTfulControlNotAssigned('delete');
    }

    public function options()
    {
        throw new RESTfulControlNotAssigned('options');
    }

    public function patch()
    {
        throw new RESTfulControlNotAssigned('patch');
    }

}

<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class PluginControllerNotFound extends NotFound
{

    public function __construct(string $pluginName)
    {
        parent::__construct("Unknown plugin: $pluginName");
    }

}

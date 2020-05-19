<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP;

abstract class AbstractMetaData
{

    private array $scripts;

    public function addScript(string $src)
    {
        $script = new \Canopy3\Tag\Script();
        $script->src = $src;
        $this->scripts[] = $script;
    }

    public function getScripts()
    {
        if (empty($this->scripts)) {
            return null;
        }

        return implode("", $this->scripts);
    }

}

<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP\Response;

use Canopy3\Theme;

class ThemedError extends Themed
{

    public function execute()
    {
        if (!defined('C3_THEMES_URL')) {
            return parent::execute();
        }
        \Canopy3\HTTP\Header::singleton()->sendHttpResponseCode();
        $theme = new Theme;

        if ($theme->pageExists('error')) {
            $theme->setCurrentPage('error');
        }

        $theme->addContent($this->content);
        echo $theme->view();
    }

}

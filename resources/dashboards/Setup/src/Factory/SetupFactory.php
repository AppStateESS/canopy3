<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Dashboard\Setup\Factory;

use Canopy3\Template;

class SetupFactory
{

    /**
     * Creates the resourcesUrl.php file in the config/ directory.
     *
     * @param \Canopy3\HTTP\Request $request
     * @return bool
     */
    public static function createResourceUrl(\Canopy3\HTTP\Request $request): bool
    {
        $urlConfigFilePath = C3_DIR . 'config/resourcesUrl.php';
        $values['resourcesUrl'] = $request->POST->resourcesUrl;
        $template = new \Canopy3\Template(Template::dashboardDirectory('Setup'));
        $content = "<?php\n" . $template->render('ResourcesUrl.txt', $values);
        $result = file_put_contents($urlConfigFilePath, $content);
        return (bool) $result;
    }

}

<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Dashboard\Setup\Factory;

use Canopy3\Template;
use Canopy3\HTTP\Request;
use Doctrine\DBAL\DriverManager;

class SetupFactory
{

    /**
     * Creates the resourcesUrl.php file in the config/ directory.
     *
     * @param \Canopy3\HTTP\Request $request
     * @return bool
     */
    public static function createResourceUrl(Request $request): bool
    {
        $urlConfigFilePath = C3_DIR . 'config/resourcesUrl.php';
        $values['resourcesUrl'] = $request->POST->resourcesUrl;
        $template = new \Canopy3\Template(Template::dashboardDirectory('Setup'));
        $content = "<?php\n" . $template->render('ResourcesUrl.txt', $values);
        $result = file_put_contents($urlConfigFilePath, $content);
        return (bool) $result;
    }

    public static function testDB(Request $request)
    {
        $connection = [
            'user' => $request->GET->username,
            'password' => $request->GET->password,
            'dbname' => $request->GET->dbname,
            'host' => $request->GET->host ?? 'localhost',
            'port' => $request->GET->port
        ];
        if ($connection['user'] == null) {
            $result['success'] = false;
            $result['error']['userNameEmpty'] = true;
        }
        if ($connection['dbname'] == null) {
            $result['success'] = false;
            $result['error']['databaseNameEmpty'] = true;
        }

        $result = ['success' => true];

        return $result;
    }

}

<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Dashboard\Setup\Controller;

use Canopy3\Template;
use Canopy3\HTTP\Header;
use Canopy3\HTTP\Request;

class Setup extends \Canopy3\Controller
{

    const templateDir = C3_DASHBOARDS_DIR . 'Setup/templates/';
    const setupFilePath = C3_DIR . 'config/setup.php';

    private static Template $template;
    private static string $javascriptUrl;
    private bool $setupFileExists = false;
    private bool $resourcesConfigExists = false;
    private bool $databaseConfigExists = false;

    public function __construct()
    {
        parent::__construct();
        $this->setupFileExists = is_file(C3_DIR . 'config/setup.php');
        $this->resourcesConfigExists = is_file(C3_DIR . 'config/resourcesUrl.php');
        $this->databaseConfigExists = is_file(C3_DIR . 'config/db.php');
        if (!$this->resourcesConfigExists) {
            $this->defineDefaultUrl();
        }
        Header::singleton()->setSiteTitle('Administration Setup');
        self::$template = new Template(self::templateDir);
    }

    private function defineDefaultUrl()
    {
        $url = preg_replace('@public/$@', '',
                \Canopy3\HTTP\Server::getCurrentUri());
        define('C3_RESOURCES_URL', $url . 'resources/');
        define('C3_DASHBOARDS_URL', C3_RESOURCES_URL . 'dashboards/');
        define('C3_PLUGINS_URL', C3_RESOURCES_URL . 'plugins/');
        define('C3_THEMES_URL', C3_RESOURCES_URL . 'themes/');
        self::$javascriptUrl = C3_DASHBOARDS_URL . 'Setup/javascript/';
    }

    private function setupAllowed()
    {
        $setupAllowed = false;
        if ($this->setupFileExists) {
            include self::setupFilePath;
            return $setupAllowed;
        } else {
            return false;
        }
    }

    private function displayStage()
    {
        switch (1) {
            case (!$this->setupAllowed()):
                return $this->setupFile();

            case (!$this->resourcesConfigExists):
                return $this->createResourcesConfig();
        }
        return 'stage busted';
    }

    private function setupFile()
    {
        $values['configFile'] = self::setupFilePath;
        $phpCode = <<<EOF
<&#63;php
\$setupAllowed = true;
EOF;
        $values['fileCode'] = self::$template->render('codeArea',
                ['code' => $phpCode]);

        $path = self::setupFilePath;
        $consoleCode = <<<EOF
echo '<&#63;php \$setupAllowed = true;' > $path
EOF;
        $values['consoleCode'] = self::$template->render('codeArea',
                ['code' => $consoleCode]);
        return $this->wrapper('Welcome to Canopy 3!',
                        self::$template->render('setupFileWarning', $values));
    }

    private function wrapper($title, $content)
    {
        $values['title'] = $title;
        $values['content'] = $content;
        return self::$template->render('Wrapper', $values);
    }

    private function createResourcesConfig()
    {
        $header = Header::singleton();
        $header->setPageTitle('Create Resources Config');
        $header->addScript(self::$javascriptUrl . 'updateResource.js',
                ['defer' => true]);
        $header->addScriptValue('resourcesUrl', C3_RESOURCES_URL);
        $values['resourcesUrl'] = C3_RESOURCES_URL;
        $values['c3Dir'] = C3_DIR;
        $values['configWritable'] = is_writable(C3_DIR . 'config/');
        return $this->wrapper('Create System File',
                        self::$template->render('CreateSystemConfig', $values));
        return self::$template->render('Wrapper', $values);
    }

    public function view()
    {
        $theme = \Canopy3\Theme::singleton();
        $theme->addContent($this->displayStage());
        echo $theme->view();
    }

}

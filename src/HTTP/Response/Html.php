<?php

/**
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\HTTP\Response;

class Html extends ResponseType
{

    protected ?string $content;

    public function __construct(?string $content = 'No content')
    {
        $this->setContent($content);
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function execute()
    {
        \Canopy3\HTTP\Header::singleton()->sendHttpResponseCode();
        echo $this->content;
    }

}

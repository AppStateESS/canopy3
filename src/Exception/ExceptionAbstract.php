<?php

/**
 *
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class ExceptionAbstract extends \Exception
{

    private int $severity;

    public function __construct(string $fileName, int $severity = 10)
    {
        parent::__construct($fileName);
        $this->setSeverity($severity);
    }

    public function setSeverity(int $severity = 10)
    {
        $this->severity = $severity > 10 ? 10 : $severity < 0 ? 0 : $severity;
        return $this;
    }

}

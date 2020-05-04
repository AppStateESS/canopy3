<?php

/**
 * MIT License
 * Copyright (c) 2020 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class ExceptionAbstract extends \Exception
{

    private $severity;

    public function setSeverity(int $severity = 10)
    {
        $this->severity = $severity > 10 ? 10 : $severity < 0 ? 0 : $severity;
        return $this;
    }

}

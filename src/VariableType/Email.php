<?php

declare(strict_types=1);

/**
 * MIT License
 * Copyright (c) 2021 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\VariableType;

class Email implements \Canopy3\VariableTypeInterface
{

    /**
     * This is a extremely basic verification. Why isn't more complex? Well,
     * because the allowed characters, domain names, and domain exceptions
     * seem, at this point, limitless. Therefore, the check is just this:
     * are there characters before and after an @ character.
     * @param type $value
     * @return type
     */
    public static function verify($value)
    {
        return preg_match("/.+@.+/", $value);
    }

}

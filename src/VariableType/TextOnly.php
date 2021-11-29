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
 *
 * VariableType class that prohibits tags and unsafe characters.
 */

namespace Canopy3\VariableType;

class TextOnly implements \Canopy3\VariableTypeInterface
{

    use \Canopy3\Traits\TextTrait;

    public static function filter(string $value)
    {
        return self::stripTags(self::filterVar($value));
    }

}

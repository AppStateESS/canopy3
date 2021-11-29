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

class Html implements \Canopy3\VariableTypeInterface
{

    const basicTags = ['b', 'strong', 'em', 'i', 'p', 'div'];

    use \Canopy3\Traits\TextTrait;

    /**
     * Does a quick strip tags on a string. Removes tag attributes as well.
     * This is not a replacement for purify.
     * @param string $content
     * @param type $allowedTags
     */
    public static function filter(string $html, $allowedTags = self::basicTags)
    {
        return self::stripTags($html, $allowedTags);
    }

    /**
     * Uses HTML Purifier to clean up all outgoing, user-submitted code.
     *
     * @param string $content
     * @return type
     */
    public static function purify(string $content)
    {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($content);
    }

}

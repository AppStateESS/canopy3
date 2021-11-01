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

namespace Canopy3;

use Canopy3\Exception\CodedException;

class JSON
{

    const jsonDepth = 512;
    const encodeFlags = 0;
    const decodeFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK;

    public static function getFileData(string $filePath, int $decodeFlags = 0)
    {
        if (!is_file($filePath)) {
            return false;
        }
        $dataJson = file_get_contents($filePath);
        $data = self::decode($dataJson, $decodeFlags);
        if (!is_object($data) && !is_array($data)) {
            throw new CodedException("JSON file [$filePath] is corrupted", 500);
        }
        return $data;
    }

    public static function decode(string $value, int $flags = 0)
    {
        return json_decode($value, null, self::jsonDepth, $flags ?? self::decodeFlags);
    }

    public static function encode($value, int $flags = 0)
    {
        return json_encode($value, $flags ?? self::encodeFlags);
    }

}

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

    const decodeConstants = false;
    const encodeConstants = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK;

    public static function getFileData(string $filePath, int $decodeConstants = null)
    {
        if (!is_file($filePath)) {
            return false;
        }
        $dataJson = file_get_contents($filePath);
        $data = self::decode($dataJson, $decodeConstants);
        if (!is_object($data) && !is_array($data)) {
            throw new CodedException("JSON file [$filePath] is corrupted", 500);
        }
        return $data;
    }

    public static function decode($value, bool $constants = null)
    {
        return json_decode($value, $constants ?? self::decodeConstants);
    }

    public static function encode(string $json, int $constants = null)
    {
        return json_encode($value, $constants ?? self::encodeConstants);
    }

}

<?php

declare(strict_types=1);
/**
 * MIT License
 * Copyright (c) 2021 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * This is an exception for when a server process accesses a file that is not allowed.
 * Different from the Client\InvalidFileType exception.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Exception;

class InvalidFileType extends CodedException implements ClientException
{

    /**
     *
     * @param string $expected The expected file type (e.g. txt, pdf, html)
     * @param string $receivedFile The file with the incorrect file type.
     *                             The extension will be derived.
     */
    public function __construct(string $expected = null, string $receivedFile = null)
    {
        $message[] = 'invalid file type received.';
        if ($expected) {
            $message[] = "Expected file type: [$expected].";
        }
        if ($receivedFile) {
            $period = strrpos($receivedFile, '.');
            if ($period !== false) {
                $message[] = 'Received file type: [' . substr($receivedFile, $period + 1) . ']';
            }
        }
        parent::__construct(implode(' ', $message), 500);
    }

}

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

use Canopy3\Exception\FileNotFound;
use Canopy3\JSON;

class Database
{

    /**
     *
     * @param string $jsonFile
     * @return \Doctrine\DBAL\Schema\Schema
     */
    public static function buildTableFromJson(string $jsonFile): \Doctrine\DBAL\Schema\Schema
    {
        $jsonTable = JSON::getFileData($jsonFile);
        $primaryKeys = [];
        $unique = [];
        $schema = new \Doctrine\DBAL\Schema\Schema();
        $table = $schema->createTable($jsonTable->tableName);
        foreach ($jsonTable->fields as $field) {
            $options = self::tableFieldOptions($field);
            if ($field->isPrimary) {
                $primaryKeys[] = $field->name;
            }
            if ($field->isUnique) {
                $uniqueIndex[] = $field->name;
            }
            $table->addColumn($field->name, $field->datatype, $options);
        }
        if (!empty($primaryKeys)) {
            $table->setPrimaryKey($primaryKeys);
        }
        return $schema;
    }

    /**
     * Sets the options for table columns based on Doctrine defaults.
     * @param object $field
     */
    private static function tableFieldOptions(object $field)
    {
        $options = [];
        $options['notnull'] = $field->notNull ?? true;
        $options['default'] = $field->default ?? null;

        switch ($field->datatype) {
            case 'string':
            case 'binary':
                if (!empty($field->length)) {
                    $options['length'] = (int) $field->length;
                }
                $options['fixed'] = $field->fixed ?? false;
                break;

            case 'integer':
            case 'bigint':
            case 'smallint':
                $options['autoincrement'] = $field->autoincrement ?? false;
                $options['unsigned'] = $field->unsigned ?? false;
                break;

            case 'decimal':
            case 'float':
                $options['precision'] = $field->precision ?? 10;
                $options['scale'] = $field->precision ?? 0;
                break;
        }
        return $options;
    }

}

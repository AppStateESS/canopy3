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

class Resource
{

    public static function describeAsJson($resource)
    {
        return \Canopy3\JSON::encode(self::describe($resource), JSON_PRETTY_PRINT);
    }

    /**
     * Returns an associative array describing the class values.
     *
     * @param mixed $resource Object or class name string
     * @return array
     */
    public static function describe($resource)
    {
        if (is_string($resource)) {
            $reflection = new \ReflectionClass($resource);
            $className = $resource;
        } else {
            $reflection = new \ReflectionObject($resource);
            $className = get_class($resource);
        }
        $shortName = $reflection->getShortName();
        $className = $reflection->getName();
        $tableName = preg_replace('@^(dashboard|plugin)\\\(\w+)\\\.*\w+@i', '\\2', $className) . '_' . $shortName;
        $structure['tableName'] = strtolower($tableName);
        $structure['className'] = $className;
        $properties = $reflection->getProperties();

        $objectInfo = [];
        foreach ($properties as $property) {
            $name = $property->name;
            $type = $property->getType()->getName();
            $defaultProperties = $property->getDeclaringClass()->getDefaultProperties();
            $default = $defaultProperties[$name] ?? null;

            $objectInfo[$name]['type'] = $type;
            $objectInfo[$name]['default'] = $default;
            $objectInfo[$name]['field'] = self::fieldDefaults($type, $default);
            if ($name === 'id' && $type === 'int') {
                $objectInfo[$name]['field']['primary'] = true;
            }
            $structure['properties'] = $objectInfo;
        }

        return $structure;
    }

    public static function createDescribeFile($resource, string $filePath)
    {
        $json = self::describeAsJson($resource);
        file_put_contents($filePath, $json);
    }

    public static function fieldDefaults($type, $default)
    {
        switch ($type) {
            case 'int':
                $field['datatype'] = 'int';
                $field['size'] = 11;
                break;
            case 'string':
                $field['datatype'] = 'varchar';
                $field['size'] = 255;
                break;
            case 'bool':
                $field['datatype'] = 'boolean';
                break;
            case 'DateTime':
                $field['datatype'] = ['mysql' => 'datetime', 'pgsql' => 'timestamp'];
                break;
        }
        $field['isNull'] = $default === null;
        return $field;
    }

}

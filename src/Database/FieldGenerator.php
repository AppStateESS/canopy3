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

class FieldGenerator
{

    public static function deriveFieldsAsJson($resource)
    {
        $fields = self::deriveFields($resource, true);
        return \Canopy3\JSON::encode($fields, JSON_PRETTY_PRINT);
    }

    /**
     * Returns an associative array describing the class values.
     *
     * @param mixed $resource Object or class name string
     * @return array
     */
    public static function deriveFields($resource, bool $asArray = false)
    {

        if (is_string($resource)) {
            $reflection = new \ReflectionClass($resource);
            $className = $resource;
        } elseif (is_object($resource)) {
            $reflection = new \ReflectionObject($resource);
            $className = get_class($resource);
        } else {
            throw new \Exception('wrong resource type [' . gettype($resource) . ']');
        }
        $shortName = $reflection->getShortName();
        $className = $reflection->getName();
        $tableName = preg_replace('@^(dashboard|plugin)\\\(\w+)\\\.*\w+@i', '\\2', $className) . '_' . $shortName;
        $structure['tableName'] = strtolower($tableName);
        $properties = $reflection->getProperties();

        $objectInfo = [];
        foreach ($properties as $property) {
            $options = [];
            $name = $property->name;
            $propType = $property->getType();
            if ($propType === null) {
                throw new \Exception("The type for property [$name] was not found.");
            }
            $type = $propType->getName();

            $defaultProperties = $property->getDeclaringClass()->getDefaultProperties();
            $options['default'] = $defaultProperties[$name] ?? null;
            $datatype = self::fieldDefault($type);
            $options['notNull'] = $options['default'] !== null;
            if ($name === 'id' && $type === 'int') {
                $options['isPrimary'] = true;
            }
            $field = new FieldType($name, $datatype, $options);
            if ($asArray) {
                $structure['fields'][] = $field->asArray();
            } else {
                $structure['fields'][] = $field;
            }
        }

        return $structure;
    }

    public static function createFieldFile($resource, string $filePath)
    {
        $json = self::deriveFieldsAsJson($resource);

        file_put_contents($filePath, $json);
    }

    public static function fieldDefault($type)
    {
        switch ($type) {
            case 'int':
                return 'integer';
            case 'string':
                return 'string';
            case 'float':
                return 'float';
            case 'double':
                return 'double';
            case 'bool':
                return 'boolean';
            case 'DateTime':
                return 'datetimetz';
        }
    }

}

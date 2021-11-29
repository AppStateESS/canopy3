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

namespace Canopy3\Database;

use Doctrine\DBAL\Types\Type;

class FieldGenerator
{

    static $allowFieldTypes = [];

    public static function createFieldFile($resource, string $filePath, bool $overwrite = false)
    {
        $json = self::deriveFieldsAsJson($resource);
        if ($overwrite && is_file($filePath)) {
            unlink($filePath);
        }
        file_put_contents($filePath, $json);
    }

    /**
     * Returns an associative array describing the class values.
     *
     * @param mixed $resource Object value or class name string
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

        if (empty($properties)) {
            throw new \Exception("No properties found in table $tableName");
        }

        return self::fillStructureWithProperties($structure, $properties, $asArray);
    }

    public static function deriveFieldsAsJson($resource)
    {
        $fields = self::deriveFields($resource, true);
        return \Canopy3\JSON::encode($fields, JSON_PRETTY_PRINT);
    }

    public static function fieldDefault(string $type): string
    {
        switch ($type) {
            case 'int':
                return 'integer';

            case 'bool':
                return 'boolean';

            case 'DateTime':
                return 'datetimetz';

            case 'string':
            case 'float':
            case 'double':
                return $type;
        }
    }

    private static function fillStructureWithProperties(array $structure, array $properties, $asArray = false): array
    {

        foreach ($properties as $property) {
            $options = self::parsePropertyComment($property);

            $name = $property->name;
            $propType = $property->getType();
            if ($propType === null) {
                throw new \Exception("The type for property [$name] was not found.");
            }
            $type = $propType->getName();

            $defaultProperties = $property->getDeclaringClass()->getDefaultProperties();
            if (isset($defaultProperties[$name])) {
                $options['default'] = $defaultProperties[$name];
            }

            $datatype = self::fieldDefault($type);
            $options['notNull'] = $options['notNull'] ?? true;
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

    private static function parsePropertyComment(\ReflectionProperty $property)
    {
        $propertyComment = $property->getDocComment();
        $propertyComment = preg_replace('@[/*]+@', '', $propertyComment);
        $commentRows = explode("\n", $propertyComment);
        $options = [];
        foreach ($commentRows as $row) {
            $row = trim($row);
            if (strpos($row, '@') === 0) {
                preg_match('/^@(\w+) (\w+)/', $row, $matches);
                if (empty($matches)) {
                    continue;
                }
                list($comment, $name, $value) = $matches;
                switch ($name) {
                    case 'length':
                    case 'scale':
                        $value = (int) $value;
                        break;

                    case 'unsigned':
                        $value = (bool) $value;
                        break;

                    case 'datatype':
                        if (!Type::hasType($value)) {
                            continue 2;
                        }
                        break;

                    default:
                        continue 2;
                }
                $options[$name] = $value;
            }
        }
        return $options;
    }

}

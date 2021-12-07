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
use Canopy3\Exception\DirectoryPermissionDenied;

class FieldGenerator
{

    static $allowFieldTypes = [];

    public static function createFieldFile($resource, string $filePath, bool $overwrite = false)
    {
        $json = self::deriveFieldsAsJson($resource);
        if ($overwrite && is_file($filePath)) {
            if (!is_writable($filePath)) {
                throw new DirectoryPermissionDenied($filePath);
            }
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

        $tableName = self::getTableName($reflection);

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
        if (Type::hasType($type)) {
            return $type;
        }
        switch ($type) {
            case 'int':
                return 'integer';

            case 'bool':
                return 'boolean';

            case 'double':
                return 'decimal';

            case 'DateTime':
                return 'datetimetz';

            default:
                return 'string';
        }
    }

    private static function fillStructureWithProperties(array $structure, array $properties, $asArray = false): array
    {
        foreach ($properties as $property) {
            $options = self::parsePropertyComment($property);
            if ($options['noField']) {
                continue;
            }
            $name = $property->name;
            $propType = $property->getType();
            if ($propType === null) {
                if (isset($options['datatype'])) {
                    $type = $options['datatype'];
                } else {
                    $type = 'string';
                }
            } else {
                $type = $propType->getName();
            }

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

    /**
     * Reads a reflections comments for phpdoc settings
     * @param \Reflector $reflection
     * @return null | array
     */
    private static function getDocValues(\Reflector $reflection)
    {
        $comment = preg_replace('@[/*]+@', '', $reflection->getDocComment());
        if (empty($comment)) {
            return null;
        }
        $commentRows = explode("\n", $comment);
        if (empty($commentRows)) {
            return null;
        }
        $values = [];
        foreach ($commentRows as $row) {
            $row = trim($row);
            if (strpos($row, '@') !== 0) {
                continue;
            }
            preg_match('/^@(\w+) (\w+)/', $row, $matches);
            if (empty($matches)) {
                continue;
            }
            list(, $name, $value) = $matches;
            $values[$name] = $value;
        }

        return $values;
    }

    /**
     * Returns a class's @table value or a best guess from the class name.
     * @param \Reflector $reflection
     * @return string
     */
    private static function getTableName(\Reflector $reflection): string
    {
        $values = self::getDocValues($reflection);
        if ($values['table']) {
            return $values['table'];
        } else {
            $shortName = $reflection->getShortName();
            $className = $reflection->getName();
            $tableName = preg_replace('@^(dashboard|plugin)\\\(\w+)\\\.*\w+@i', '\\2', $className) . '_' . $shortName;
            return strtolower($tableName);
        }
    }

    /**
     * Examines a property's comment line for field information.
     * @param \ReflectionProperty $property
     * @return array
     */
    private static function parsePropertyComment(\ReflectionProperty $property): array
    {
        $propertyValues = self::getDocValues($property);
        if (empty($propertyValues)) {
            return [];
        }
        $options = [];
        foreach ($propertyValues as $name => $value) {
            switch ($name) {
                case 'length':
                case 'scale':
                    $value = (int) $value;
                    break;

                case 'unsigned':
                case 'noField':
                case 'notNull':
                case 'isPrimary':
                    $value = is_string($value) ? strtolower($value) === 'true' : boolval($value);
                    break;

                case 'var':
                    $value = self::fieldDefault($value);
                    if (Type::hasType($value) && !isset($options['datatype'])) {
                        $name = 'datatype';
                    } else {
                        continue 2;
                    }
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
        return $options;
    }

}

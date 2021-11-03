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

use Doctrine\DBAL\Types\Type;

class FieldType
{

    /**
     * The field type. This is type MUST match the expected Doctrine/DBAL types:
     * https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/types.html
     * @var string
     */
    private string $datatype;

    /**
     * Default value for field. Can be left unset.
     * @var mixed
     */
    private $default;

    /**
     * The length of the field. Works for int, varchar, etc.
     * @var int
     */
    private int $length;

    /**
     * Field's name in the table.
     * @var string
     */
    private string $name;

    /**
     * Determines if field can be saved as null.
     * @var bool
     */
    private bool $notNull = true;

    /**
     * If true, the field is the primary key.
     * @var bool
     */
    private bool $isPrimary = false;

    /**
     * For numeric fields, determines if unsigned. Default true indicates
     * this will always a positive number.
     * @var bool
     */
    private bool $unsigned = true;

    public function __construct($name, $datatype, array $options = [])
    {
        $this->name = $name;
        $this->setDataType($datatype);
        foreach ($options as $varName => $value) {
            $this->__set($varName, $value);
        }
    }

    public function __set($varName, $value): void
    {
        switch ($varName) {
            case 'type':
                $this->setDataType($value);
                break;

            default:
                if (array_key_exists($varName, get_class_vars(__CLASS__))) {
                    $this->$varName = $value;
                } else {
                    throw new \Exception("unknown object property [$varName]");
                }
        }
    }

    public function __get($varName)
    {
        return $this->$varName;
    }

    public function asArray()
    {
        return get_object_vars($this);
    }

    public static function getAllowedDataTypes()
    {
        return array_keys(Type::getTypesMap());
    }

    public function setDataType(string $datatype): void
    {
        if (!Type::hasType($datatype)) {
            throw new \Exception("[$datatype] field type does not match the allowed Doctrine DBAL types");
        }
        $this->datatype = $datatype;
    }

}

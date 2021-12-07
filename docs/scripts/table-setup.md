# Table Setup

To assist you in installing a dashboard or plugin, you can create database JSON snapshots of your object. This snapshot can then inform Doctrine DBAL class to create a new table.

## createTableJson

In the canopy3/bin directory is a PHP console script named ```createTableJson```.
It uses Canopy3's Database\FieldGenerator to create a JSON file based on your class.

To begin, change to the ```bin/``` directory.

```
$ cd canopy3/bin/
```

You can point it to the class file:

```
$ ./createTableJson -f "c3-plugin/myPlugIn/src/Resource/Widget.php" -d widget.json
```

Alternately, you can use the class name:
```
$ ./createTableJson -c "plugin\\myPlugIn\\Resource\\Widget" -d widget.json
```

The script will parse your class and create defaults based on the @var phpdoc. Here is an example class.

```
class MyClass {

    /**
     * @var int $id
     */
    private int $id;

    /**
     * @var string
     * @length 50
     */
    private string $name;

    /**
     * @scale 2
     * @var double
     */
    private double $price;

}
```

The Doctrine datatype (i.e. the field type created in the database table) is determined by one of three things.

1. The type assigned to the property
```
private string $name
```
2. The @var type in the property comment
```
/**
 * @var string
 * @length 50
 */
```
3. The @datatype label in the property comment.
```
/**
 * @datatype text
 * @var string
 */
```
The datatype must be types as defined by Doctrine.

If the FieldGenerator class can't figure out the datatype, it defaults to ```string```.

## phpDoc class and property options

The ```@datatype``` label above is an example of options you may add to your class.

```@datatype [string]``` - overrides the property type in field determination

```@length [integer]``` - for strings, gives the length assigned to ```varchar``` datatype.

```@scale [integer]``` - for decimals or floats, number of decimal digits.

```@default [mixed]``` - the default value assigned to the field

```@noField [boolean]``` - if true, this property will not be used in table creation.

```@unsigned [boolean]``` - if true, an integer field will be unsigned. This works in MySQL only.

```@notNull [boolean]``` - if true, the field will not allow null values.

```@isPrimary [boolean]``` - if true, this field will be set as the table's primary key. The ```id``` property will be used by default.

## Doctrine types

The list below contains all the allowed field types. You can get more information on the Doctrine DBAL documentation page: https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/types.html#types

* smallint
* integer
* bigint
* decimal
* float
* string
* ascii_string
* text
* guid
* binary
* blob
* boolean
* date
* date_immutable
* datetime
* datetime_immutable
* datetimetz
* datetime_immutable
* time
* time_immutable
* dateinterval
* array
* simple_array
* json
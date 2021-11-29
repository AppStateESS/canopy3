# Table Setup

To assist you in installing a dashboard or plugin, you can create database JSON snapshots of your object. This snapshot can then inform Doctrine DBAL class to create a new table.

## createTableJson
In the canopy3/bin directory is a PHP console script named ```createTableJson```. Using it, you can create a JSON file for your class.

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
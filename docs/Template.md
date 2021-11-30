# Template

Instantiate the object using the directory the template files sit in.

```
$template = new \Canopy3\Template('path/to/templates/directory/');
```

The values added to the template should be in an associative array.

```
$values['title'] = 'Hello World';
$values['timestamp'] = time();
$values['rows'] = [
 ['name' => 'Joe', 'job' => 'Fireman'],
 ['name' => 'Sally', 'job' => 'Astronaut']
];
```

To show the text content, call the ```render``` function:
```
// Jobs will resolve to Jobs.phtml
echo $template->render('Jobs', $values, true);
```

The ```render``` function gets the name of the template file (in the directory you entered earlier) and the values to pipe in. The template file should have an ``.phtml``, ``.html``, or ``.txt`` extension. ``phtml`` is the default extension. Any other file type will throw an ```InvalidFileType``` exception.

The third Boolean variable, ``emptyWarning`` determines how empty or unset values are displayed. If true, an html comment will be inserted. For example, if you misspelled "timestamp" in the template:
```
<p>Time of access: <!-- Template variable [tiemstamp] is missing --></p>

```
If ```emptyWarning``` is false, then the result would be:
```
<p>Time of access: </p>
```


```
# jobs.phtml
/**
 * String and int values can be called directly.
 * If the value is not set, either nothing is printed
 * or, if the third parameter is true, the following html
 * comment is added:
 * <!-- Template variable [$valueName] is missing -->
 */
<h1><?= $t->title ?></h1>

/**
 * Some values can be called with a function for formatting
 * For example, an unixtime integer value can be formatted
 * as a date string.
 */
<p>It is currently <?= $t->timestamp->asDate('%c') ?></p>
```

If you want to conditionally show a value with surrounding content, there are two methods.

The first method is to test the value's existence.

```
<?php if (isset($t->greeting)):?>
  <p class="special-greeting"><?=$t->greeting?></p>
<?php endif;?>
```
or
```
<?=$t->greeting ? <<<EOF
<p class="special-greeting">{$t->greeting}</p>
EOF;
: ''?>
```


Alternatively, you may use the wrap() function.

```
<?=$t->wrap('greeting', '<p class="special-greeting">', '</p>')?>
```

The first parameter is the name of the variable. The second and third parameter are what will print on the left and right respectively. If the value is blank and ``emptyWarning`` is true, just the warning will print, not the wrapper.

If you want conditional text within your template, you can set a value a boolean and then test it.

```
// set your conditional boolean with other values
$values['showJobs'] = true;

```

```
<!-- then in your html file, test the value with a call to "true" or "is" -->
<?php if ($t->showJobs->true):?>
  <p>Only show this content if showJobs is true<p>
<?php endif;?>

// Alternate format
<?= $t->showJobs->true ? <<<EOF
<p>Only show this content if showJobs is true</p>
EOF : null;
?>
```

## Working with array values

Array values can be looped through in a couple of ways.

If you want the values to fill out a table, you can use the `asTableRows` function.

The function accepts an option array.


```
<?php
$options = [
  'rowClass'=> 'striped',
  'order'=> ['job', 'name']
];
?>
<table>
  <tbody>
  <?= $t->rows->asTableRows($options) ?>
  </tbody>
</table>
```
The rows would be in this format.

```
<tr class="striped">
 <td>Fireman</td>
 <td>Joe</td>
</tr>
<tr class="striped">
  <td>Astronaut</td>
  <td>Sally</td>
</tr>
```

Alternately, you loop the rows within another file using `loopInclude`.

```
<div><?= $t->rows->loopInclude('Row'); ?></div>
```

This will load the `Row.html` file from the same directory.

`Row.html`

```
<p> <?= $t->name ?> is employed as the <?= $t->job ?> in this city.</p>
```

The result:

```
<div>
  <p>Joe is employed as the Fireman in this city.</p>
  <p>Sally is employed as the Astronaut in this city.</p>
</div>
```

If you want to run the array through a custom function, you can use `loopFunction`.

```
function employ($values) {
  return <<<EOF
<p>
  {$values['name']} is employed as the {$values['job']}
  in this city.
</p>
EOF;
}
```

```
// Jobs.html
<div><?= $t->rows->loopFunction('employ')?></div>
```

Template also allows you to register a custom function.

```
$template->registerFunction('showRows',
  function ($args) {
    // the get() function returns the array itself
    $rows = $args[0]->get();
    foreach ($rows as $info) {
      $content[] = <<<EOF
<p>
  {$info['name']} likes their job as a {$info['job']}.
</p>
EOF;
    }
    return implode("\n", $content);
  }
);
```

Once the function is registered, you can call it in the template itself.

```
<div><?= $t->showRows($t->rows)?></div>
```

## Home URL
If you need the URL to the home directory, use the permanent value ```homeUrl```.
```
<form method="post" action="<?=$t->homeUrl?>p/MyPlugin/">
...
</form>
```

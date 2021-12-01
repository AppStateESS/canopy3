# Theming

## Installing

Themes reside in the ```canopy3/resources/themes/``` directory.
You can install them using composer:
```
composer require name-of-library-or-dev/name-of-theme
```
Note that only stable themes will be downloaded. If you want to experiment, you will need to change the ```minimum-stability```
variable in your composer.json.

## Formatting

Your theme page template must echo three variables: header, footer, and main. See the example below for more information.

### Page.html

```
<html>
  <head>
    <!--
      - Pulls in any script (see footer below), meta, link,
      - or title tags set by the system. It should be placed
      - in the <head> tag
      -->
    <?= $t->header ?>
    <!--
      - Includes a script in the theme directory. See more information
      - below.
      -->
    <?= $t->includeScript('dist/js/bootstrap.js', false)?>
  </head>

  <body>
    <!--
      - Although you can have several sections in your theme, you
      - must have a "main" section. This is the expected or default
      - destination for content. It is placed in the <body>.
      -
      -->
      <?= $t->main ?>

      <div class="custom-tag-area">
        <!--
          - Any custom tags added to the theme can be added anywhere
          - in the body.
          -->
        <?= $t->yourCustomTag ?>
      </div>
  </body>

</html>
```


Here is an example of the structure.json file included with canopy3-theme-simple.

```
{
  "title": "Simple",
  "description": "A basic page for testing and setup",
  "defaultPage": "basic",
  "screenshot": "",
  "pages": {
    "basic": {
      "filename": "basic.html",
      "title": "Basic",
      "columns": 1,
      "altSections": ["top", "bottom"],
      "description": "Basic one column page display"
    },
    "frontPage": {
      "filename": "front.html",
      "title": "Front page",
      "altSections": ["top", "bottom", "rightSide"],
      "columns": 2,
      "description": "Front page with large left column, smaller right side"
    }
  }
}
```


## Structure file

Your theme should have a structure.json file in the root directory. The JSON should describe an object with the following parameters.

**title** - the fancy name you have given your theme. It is used for admin information only.

**description** - descriptive information about your theme and what it contains.

**defaultPage** - normally, which page template is used is determined by admin settings. If the Theme class doesn't get a page specification, it will default to this file.

**screenshot** - the image file name of your theme in action. It is not required.

**pages** - an object containing the pages used in your theme. Each page entry should have a simple name (e.g. basic, frontPage).


### Pages have the following parameters:

**filename** - name of the template file in the pages directory.

**title** - proper name of the template.

**columns** - number of columns the user should expect

**description** - a verbose summary of the style, capabilities, layout, etc. of the page template.

## Functions

You may call some functions from the ```$t``` variable to help your theme.

### includeScript

```includeScript``` accepts two parameters.
```
<?= $t->includeScript('directory/to/the/file.js', $async);?>
```

The ```$async``` value determines whether the script file will be executed asynchronously (while the page is loading) or deferred (when the page loading is complete).

In Bootstrap, for example, the Javascript file needs to be deferred, so the async parameter is ```false```.

### includeStyle

```includeStyle``` only needs the relative link to a style sheet.

```
<?= $t->includeStyle('dist/css/bootstrap.css')?>
```

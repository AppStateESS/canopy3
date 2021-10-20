# Autoloade and Namespace

Canopy 3 adheres to the PSR-4 Autoloader standard.

## PSR-4 Autoloader

[PHP FIG page](https://www.php-fig.org/psr/psr-4/)

Class namespaces should begin with their source. For all Canopy 3 classes the source directory is`Canopy3\`. The class file should exist in the `src/` directory or a subdirectory of `src/`.

For example, the Router class for Canopy.

```
use Canopy3\Router;
# Autoloads /src/Router.php
```

For the Html response class, we include the subdirectory:

```
use Canopy3\HTTP\Response\Html
# Autoloads /src/HTTP/Response/Html.php
```

## Resources
There are two resources that may require namespacing: plugins and dashboards. The ```Plugin``` and ```Dashboard``` labels are a required in the namespace.

### Dashboards
Dashboards are administrative interfaces to manage the Canopy 3 installation. While
they may work with plugins, they are not dependant upon them. Dashboards are only run from the main installation and distinct from sites. A dashboad is loaded using the ```Dashboard``` namespace.

Here is an example of instantiating the Controller for the setup process.
```
$controller = new \Dashboard\Setup\Controller\Setup
# Autoloads file /resources/dashboards/Setup/src/Controller/Setup.php
```


### Plugins
Plugins are applications that add content to the system. They are independent from Dashboards. A plugin might have a administrative UI named "dashboard" but there should not be any intersection. Plugins may activated for and admininstrated from sites.

Here is an example of instantiating the WatchThis factory for a plugin called Slapchop.
```
return \Plugin\Slapchop\Factory\WatchThis::breakfastToGo();

# Autoloads file /resources/plugins/Slapchop/src/Factory/WatchThis.php
```

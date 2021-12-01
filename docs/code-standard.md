# Code Standard

Canopy 3 has much tighter coding standard expectations compared to previous versions.

Developers should familarize themselves with PHP 7.4 typed properties, the PSR-1 Basic Coding Standard, and the PSR-12 Coding Style.

## Typed properties
Variables should be typed to their expected purpose.

```
class Sample {
  public int $id;
  public ?string $title;
  private OtherClass $otherVar;

  public function noNumbersInTitle(string $title) : bool
  {
      return !preg_match('/\d/', $title);
  }
}
```

Strict type declarations are recommended:
```
<?php
    declare(strict_types=1);
```




## PSR-1 Basic Coding Standard
[PHP-FIG page](https://www.php-fig.org/psr/psr-1/)

### Overview (from PHP-FIG page)
- Files MUST use only <?php and <?= tags.
- Files MUST use only UTF-8 without BOM for PHP code.
- Files SHOULD either declare symbols (classes, functions, constants, etc.) or cause side-effects (e.g. generate output, change .ini settings, etc.) but SHOULD NOT do both.
- Namespaces and classes MUST follow an "autoloading" PSR: [PSR-0, PSR-4].
- Class names MUST be declared in StudlyCaps.
- Class constants MUST be declared in all upper case with underscore separators.
- Method names MUST be declared in camelCase.

## PSR-12 Extended Coding Style
[PHP-FIG page](https://www.php-fig.org/psr/psr-12/)

Please review the PSR-12 coding standards. I suggest investing in an editor with PSR-12 formatting rules. Make sure it complies, format on save, and forget about it.
